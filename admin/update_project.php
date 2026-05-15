<?php
require_once __DIR__ . '/../config/db.php';

if ($_POST) {

    $id = intval($_POST['project_id']);
    $title = $_POST['title'];
    $short = $_POST['short_description'];
    $long = $_POST['long_description'];

    $tech = isset($_POST['technologies'])
        ? implode(',', $_POST['technologies'])
        : '';

    $is_hot = isset($_POST['is_hot']) ? 1 : 0;

    /* ✅ UPDATE TEXT */
    mysqli_query($conn, "
        UPDATE projects 
        SET title='$title',
            short_description='$short',
            long_description='$long',
            technologies='$tech',
            is_hot=$is_hot,
            updated_at = NOW()
        WHERE id=$id
    ");

    /* ✅ UPDATE THUMBNAIL */
    if (!empty($_FILES['thumbnail']['name'])) {

        $file = time().'_'.$_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], "../uploads/projects/".$file);

        mysqli_query($conn, "
            UPDATE projects SET image='$file' WHERE id=$id
        ");
    }

    /* ✅ ADD GALLERY */
    if (!empty($_FILES['gallery_images']['name'][0])) {

        foreach ($_FILES['gallery_images']['name'] as $key => $name) {

            $tmp = $_FILES['gallery_images']['tmp_name'][$key];
            $file = time().'_'.$name;

            move_uploaded_file($tmp, "../uploads/projects/".$file);

            mysqli_query($conn, "
                INSERT INTO project_images (project_id, image)
                VALUES ($id, '$file')
            ");
        }
    }

    $redirect = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
header("Location: " . $redirect);
exit();
}