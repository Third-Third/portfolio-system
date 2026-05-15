<?php
require_once __DIR__ . '/../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

/* ✅ STEP 1: DELETE THUMBNAIL */
$result = mysqli_query($conn, "SELECT image FROM projects WHERE id=$id");
$project = mysqli_fetch_assoc($result);

if ($project && !empty($project['image'])) {
    $thumbPath = "../uploads/projects/" . $project['image'];

    if (file_exists($thumbPath)) {
        unlink($thumbPath);
    }
}

/* ✅ STEP 2: DELETE GALLERY IMAGES (FILES + DB) */
$gallery = mysqli_query($conn, "SELECT image FROM project_images WHERE project_id=$id");

while ($g = mysqli_fetch_assoc($gallery)) {
    $filePath = "../uploads/projects/" . $g['image'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* ✅ DELETE GALLERY RECORDS */
mysqli_query($conn, "DELETE FROM project_images WHERE project_id=$id");

/* ✅ STEP 3: DELETE PROJECT */
mysqli_query($conn, "DELETE FROM projects WHERE id=$id");

/* ✅ STEP 4: REDIRECT BACK */
header("Location: dashboard.php");
exit();