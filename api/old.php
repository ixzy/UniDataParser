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

    if (empty($search_term)) {
       
        $total_records = 0;

        $password_files = search_password_files($root_folder, '');

        foreach ($password_files as $password_file) {
            $file_content = file_get_contents($password_file);
            $lines = explode("\n", $file_content);
            $total_records += count($lines);
        }

        $format = isset($_GET['format']) ? $_GET['format'] : 'JSON';

        if (strtoupper($format) === 'HTML' && isset($_GET['url'])) {
            header('Content-Type: text/html');
            echo "<h2>Total Records: $total_records</h2>";
        } else {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Total Records:", "total_records" => $total_records]);
        }
    } else {
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

                            if (!empty($user) && !empty($pass)) {
                                $folder_link = $folder;
                                $results[] = "$url | $user | $pass |";
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

        $format = isset($_GET['format']) ? $_GET['format'] : 'JSON';

        if (strtoupper($format) === 'HTML' && isset($_GET['url'])) {
            header('Content-Type: text/html');
            echo "<h2>Search Results:</h2>";
            foreach ($results as $result) {
                echo "<pre>$result</pre>";
            }
        } else {
            header('Content-Type: application/json');
            if (empty($results)) {
                echo json_encode(["message" => "No matching records found."]);
            } else {
                echo json_encode(["message" => "Search Results:", "results" => $results]);
            }
        }
    }
} else {
   
    echo json_encode(["message" => "Invalid request method"]);
}
?>
