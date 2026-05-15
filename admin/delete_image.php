<?php
require_once __DIR__ . '/../config/db.php';

$id = intval($_GET['id']);

mysqli_query($conn, "DELETE FROM project_images WHERE id=$id");

header("Location: dashboard.php");