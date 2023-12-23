<?php
$videoFiles = scandir('../video');
$videoList = [];

foreach ($videoFiles as $file) {
    if ($file !== '.' && $file !== '..') {
        $videoList[] = ['id' => $file, 'name' => $file];
    }
}

header('Content-Type: application/json');
echo json_encode($videoList);
?>
