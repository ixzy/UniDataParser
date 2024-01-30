<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_term = isset($_GET["url"]) ? $_GET["url"] : "";
    $root_folder = '../records';

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

                        if (!empty($user) && (stripos($user, 'Username:') !== false || stripos($user, 'Login:') !== false || stripos($user, 'Host:') !== false|| stripos($user, 'HOSTNAME:') !== false)) {
                            $folder_link = $folder;
                            $formattedUser = substr($user, 9);
                            $formattedPass = substr($pass, 9);
                            $results[] = [
                                "url" => $url,
                                "user" => $formattedUser,
                                "password" => $formattedPass,
                                "folder" => $folder_link
                            ];
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
        echo json_encode(["message" => "No matching records found."]);
    } else {
        echo json_encode(["message" => "Search Results:", "results" => $results]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
