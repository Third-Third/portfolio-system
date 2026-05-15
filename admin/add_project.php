<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save_project'])) {

    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $long_desc = trim($_POST['long_description']);

    // ✅ Technologies
    $technologies = isset($_POST['technologies'])
        ? implode(',', $_POST['technologies'])
        : '';

    // ✅ HOT PROJECT (FIXED ✅)
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;

    // ✅ Thumbnail Upload
    $thumbnail = "";
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = time() . "_" . $_FILES['thumbnail']['name'];

        move_uploaded_file(
            $_FILES['thumbnail']['tmp_name'],
            "../uploads/projects/" . $thumbnail
        );
    }

    // ✅ INSERT WITH is_hot ✅
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO projects (title, short_description, long_description, technologies, image, is_hot, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())"
    );

    // ✅ IMPORTANT: "sssssi"
    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $title,
        $short_desc,
        $long_desc,
        $technologies,
        $thumbnail,
        $is_hot   // ✅ THIS IS THE FIX
    );

    mysqli_stmt_execute($stmt);
    // ✅ GET PROJECT ID
$project_id = mysqli_insert_id($conn);

/* ✅ SAVE MULTIPLE IMAGES */
if (!empty($_FILES['gallery_images']['name'][0])) {

    foreach ($_FILES['gallery_images']['name'] as $key => $imageName) {

        $tmpName = $_FILES['gallery_images']['tmp_name'][$key];

        // ✅ unique filename
        $fileName = time() . "_" . $imageName;

        move_uploaded_file($tmpName, "../uploads/projects/" . $fileName);

        // ✅ INSERT INTO project_images TABLE
        mysqli_query($conn, "
            INSERT INTO project_images (project_id, image)
            VALUES ($project_id, '$fileName')
        ");
    }
}


    header("Location: dashboard.php?from_update=1");

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Project</title>

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ✅ Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- ✅ CSS -->
<link href="../assets/css/style.css" rel="stylesheet">

<style>
.admin-logo {
    height: 55px;
    object-fit: contain;
    display:block;
    margin:auto;
}
</style>

</head>

<body>

<div class="container-fluid">
<div class="row min-vh-100">

<!-- SIDEBAR -->
<div class="col-md-2 admin-sidebar p-4">

    <div class="text-center mb-4">
        <img src="../assets/images/logo.png" class="admin-logo">
    </div>

    <a href="dashboard.php" class="admin-nav-link active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="logout.php" class="admin-nav-link logout"
       onclick="return confirm('Are you sure to logout?')">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

</div>

<!-- CONTENT -->
<div class="col-md-10 d-flex justify-content-center align-items-center">

<div class="card shadow-sm p-4" style="width:100%; max-width:700px;">

<h3 class="fw-bold mb-4 text-center">Add New Project</h3>

<form method="post" enctype="multipart/form-data">

<!-- ✅ TITLE -->
<div class="mb-3">
    <label class="form-label">Project Title</label>
    <input type="text" name="title" class="form-control" required>
</div>

<!-- ✅ SHORT -->
<div class="mb-3">
    <label class="form-label">Short Description</label>
    <textarea name="short_description" class="form-control" rows="2"></textarea>
</div>

<!-- ✅ LONG -->
<div class="mb-3">
    <label class="form-label">Long Description</label>
    <textarea name="long_description" class="form-control" rows="4"></textarea>
</div>

<!-- ✅ TECHNOLOGIES -->
<div class="mb-3">
<label class="form-label">Technologies</label>
<br>

<?php
$techs = ["HTML","CSS","JavaScript","PHP","Python","MySQL","Bootstrap"];
foreach ($techs as $t) {
    echo "<label class='me-2'>
            <input type='checkbox' name='technologies[]' value='$t'> $t
          </label>";
}
?>
</div>

<!-- ✅ IMAGE -->
<div class="mb-4">
    <label class="form-label">Project Thumbnail</label>
    <input type="file" name="thumbnail" class="form-control">
</div>

<!-- ✅ MULTIPLE GALLERY IMAGES -->
<div class="mb-4">
    <label class="form-label">Project Gallery Images</label>
    <input type="file" name="gallery_images[]" class="form-control" multiple>

    <small class="text-muted">
        You can select multiple images for gallery.
    </small>
</div>

<!-- ✅ HOT PROJECT (FIXED UI ✅) -->
<div class="mb-3">
    <label class="form-label fw-semibold">Feature Project</label>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_hot" value="1" id="is_hot">
        <label class="form-check-label" for="is_hot">
            Mark as Hot Project 🔥
        </label>
    </div>

    <small class="text-muted">
        This project will appear on homepage as featured.
    </small>
</div>

<!-- ✅ BUTTON -->
<button type="submit" name="save_project" class="btn btn-primary w-100">
    <i class="bi bi-save"></i> Save Project
</button>

</form>

</div>
</div>

</div>
</div>

</body>
</html>