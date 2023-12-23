<?php
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $videoId = $_GET['id'];
    $videoPath = './../video/' . $videoId;
    if (file_exists($videoPath) && unlink($videoPath)) {
        echo json_encode(['status' => 'success', 'msg' => 'Video deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Failed to delete video']);
    }
} else {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
}
?>
