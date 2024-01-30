<?php
function findScreenshots($directory) {
    $screenshots = [];

    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $path = $directory . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $screenshots = array_merge($screenshots, findScreenshots($path));
            } else {
                if (strtolower($file) == 'screenshot.jpg') {
                    $folder = str_replace('records/', '', $directory);
                    $screenshots[] = [
                        'path' => $path,
                        'folder' => $folder
                    ];
                }
            }
        }
    }

    return $screenshots;
}

$recordsDirectory = 'records'; \
$screenshots = findScreenshots($recordsDirectory);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Screenshots Slideshow</title>
    <style>
        #slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
            text-align: center;
        }

        .mySlides {
            display: none;
        }

        .slide-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 400px; 
        }

        .slide-img {
            max-height: 100%;
            max-width: 100%;
            cursor: pointer;
        }

        .folder-link {
            text-align: center;
            font-size: 18px;
        }

        .screenshot-number {
            font-size: 16px;
            margin-top: 5px;
        }

        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px;
            background-color: #000;
            color: #fff;
            cursor: pointer;
            user-select: none;
        }

        .prev {
            left: 0;
        }

        .next {
            right: 0;
        }

        .prev:hover, .next:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Screenshots Slideshow</h1>
    <div id="slideshow-container">
        <?php
        foreach ($screenshots as $index => $screenshot) {
            echo '<div class="mySlides">';
            echo '<div class="slide-container"><img src="' . $screenshot['path'] . '" class="slide-img" onclick="openModal(this)"></div>';
            echo '<div class="folder-link"><a href="' . $screenshot['folder'] . '">' . $screenshot['folder'] . '</a></div>';
            echo '<div class="screenshot-number">Screenshot: #' . ($index + 1) . '</div>';
            echo '</div>';
        }
        ?>
        <a class="prev" onclick="plusSlides(-1)">?</a>
        <a class="next" onclick="plusSlides(1)">?</a>
        <div id="out-of-screenshots" style="display: none;">
            Out of screenshots, come back when we have more.
        </div>
    </div>

    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");

            if (n > slides.length) {
                slideIndex = slides.length;
                document.getElementById("out-of-screenshots").style.display = "block";
            }

            if (n < 1) {
                slideIndex = 1;
            }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            if (slideIndex <= slides.length) {
                slides[slideIndex - 1].style.display = "block";
            }
        }

        function openModal(img) {
            var modal = document.createElement('div');
            modal.style.display = 'block';
            modal.style.position = 'fixed';
            modal.style.zIndex = '1';
            modal.style.padding = '10px';
            modal.style.textAlign = 'center';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
            modal.onclick = function () {
                modal.style.display = 'none';
                modal.innerHTML = '';
            };

            var modalImg = document.createElement('img');
            modalImg.src = img.src;
            modalImg.style.maxWidth = '100%';
            modalImg.style.maxHeight = '100%';
            modalImg.style.display = 'block';
            modalImg.style.margin = '0 auto';

            modal.appendChild(modalImg);

            document.body.appendChild(modal);
        }
    </script>
</body>
</html>
