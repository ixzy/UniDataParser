<?php
header('Content-Type: text/html');
# dataleak.us beta search parser
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = isset($_POST["search_term"]) ? $_POST["search_term"] : "";
    $root_folder = 'records';

    function search_password_files($dir, $search_url) {
        $files = scandir($dir);
        $password_files = [];

        foreach ($files as $file) {
            if (is_dir($dir . '/' . $file) && $file != "." && $file != "..") {
                $subdir = $dir . '/' . $file;
                $password_files = array_merge($password_files, search_password_files($subdir, $search_url));
            } elseif (is_file($dir . '/' . $file) && (strtolower($file) == "passwords.txt")) {
                $password_files[] = $dir . '/' . $file;
            }
        }

        return $password_files;
    }

    $password_files = search_password_files($root_folder, $search_term);

    $results = [];
    foreach ($password_files as $password_file) {
        $folder_name = dirname($password_file);
        $file = fopen($password_file, 'r');
        if ($file) {
            $url = '';
            $user = '';
            $pass = '';
            $folder = $folder_name;

            while (($line = fgets($file)) !== false) {
                if (stripos($line, 'URL:') !== false) {
                    $url = trim(substr($line, 4));

                    if (stripos($url, $search_term) !== false) {
                        $user = trim(fgets($file));
                        $pass = trim(fgets($file));

                        if (!empty($user) && (stripos($user, 'Username:') !== false || stripos($user, 'Login:') !== false || stripos($user, 'Host:') !== false || stripos($user, 'HOSTNAME:') !== false)) {
                            $folder_link = $folder;
                            $formattedUser = '<span class="username">' . substr($user, 9) . '</span>';
                            $formattedPass = '<span class="password">' . substr($pass, 9) . '</span>';
                            $results[] = "$url | <span class='gray-label'>User:</span> $formattedUser | <span class='gray-label'>Password:</span> $formattedPass | Folder: $folder_link";
                            $url = '';
                            $user = '';
                            $pass = '';
                        }
                    }
                }
            }

            fclose($file);
        }
    }

    if (empty($results)) {
        echo "No matching records found.";
    } else {
        echo "<h2>Search Results:</h2>";
        foreach ($results as $result) {
            echo "<pre class='search-result'>$result</pre>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>search - dataleak.us beta search parser</title>
</head>
<body>
    <form action="" method="POST">
        <label for="search_term">Search (URL):</label>
        <input type="text" name="search_term" id="search_term" required>
        <br>
        <input type="submit" value="Search">
    </form>
</body>
</html>
