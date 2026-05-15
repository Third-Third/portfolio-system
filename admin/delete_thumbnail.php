<?php
require_once __DIR__ . '/../config/db.php';

$id = intval($_GET['id']);

/* GET IMAGE */
$result = mysqli_query($conn, "SELECT image FROM projects WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if ($row && $row['image']) {

    $filePath = "../uploads/projects/" . $row['image'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    /* SET IMAGE NULL */
    mysqli_query($conn, "UPDATE projects SET image=NULL WHERE id=$id");
}

header("Location: dashboard.php");