<?php
$filename = 'newfile.txt';
$content = 'This is the content of the new file.';

if (file_put_contents($filename, $content) !== false) {
    echo "File created successfully.";
} else {
    echo "Failed to create the file.";
}