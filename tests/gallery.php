<?php
// Directory where images are stored
$uploadDir = 'upload/';

// Open the directory and read image files
$images = array_diff(scandir($uploadDir), array('.', '..'));

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
        /* Basic styling for the gallery */
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .gallery img {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h1>Image Gallery</h1>
    <div class="gallery">
        <?php
        // Loop through each file in the uploads directory
        foreach ($images as $image) {
            // Ensure only image files are displayed
            $filePath = $uploadDir . $image;
            $fileType = mime_content_type($filePath);
            if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                echo "<a href='$filePath' target='_blank'><img src='$filePath' alt='$image'></a>";
            }
        }
        ?>
    </div>
</body>

</html>