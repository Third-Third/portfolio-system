<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/* ✅ FORCE RESET ON REFRESH */
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && !isset($_GET['search_action'])
    && !isset($_GET['from_update'])
    && (isset($_GET['search']) || isset($_GET['year']))) {

    header("Location: dashboard.php");
    exit();
}

/* ===== YEAR FILTER ===== */
$yearFilter = "";
$selectedYear = "";

if (!empty($_GET['year'])) {
    $selectedYear = intval($_GET['year']);
    $yearFilter = "WHERE YEAR(created_at) = $selectedYear";
}

/* ===== SEARCH ===== */
$search = "";
$whereSearch = "";

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereSearch = "WHERE title LIKE '%$search%' OR id LIKE '%$search%'";
}

/* DATA */
$totalProjects = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM projects")
)['total'];

$row = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT MAX(updated_at) AS last_update FROM projects")
);

$lastUpdate = $row['last_update']
    ? date("Y-m-d H:i", strtotime($row['last_update']))
    : '—';

/* PROJECTS */
$projectsResult = mysqli_query($conn,
    "SELECT * FROM projects $whereSearch ORDER BY created_at DESC"
);

/* CHART */
$chartResult = mysqli_query($conn,
    "SELECT MONTH(created_at) as month, COUNT(*) as total
     FROM projects
     $yearFilter
     GROUP BY MONTH(created_at)
     ORDER BY MONTH(created_at)"
);

$allMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$dataMap = array_fill(0, 12, 0);

while ($r = mysqli_fetch_assoc($chartResult)) {
    $dataMap[$r['month'] - 1] = $r['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css?v=20" rel="stylesheet">

<style>
.admin-logo{height:55px;display:block;margin:auto;}
.project-thumb{width:55px;height:55px;object-fit:contain;background:#f1f5f9;padding:4px;border-radius:8px;}
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
onclick="return confirm('Are you sure you want to logout?')">
<i class="bi bi-box-arrow-right"></i> Logout
</a>

</div>

<!-- MAIN -->
<div class="col-md-10 p-5">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h2 class="fw-bold">Admin Dashboard</h2>
<p class="text-muted">Welcome, <?= $_SESSION['admin']; ?></p>
</div>

<button class="btn btn-primary btn-sm px-3"
data-bs-toggle="modal"
data-bs-target="#addProjectModal">
<i class="bi bi-plus-circle"></i> Add Project

</button>

</div>

<!-- SUMMARY -->
<div class="row mb-4">
<div class="col-md-4"><div class="card p-3 shadow-sm"><small>Total</small><h3><?= $totalProjects ?></h3></div></div>
<div class="col-md-4"><div class="card p-3 shadow-sm"><small>Last Update</small><h5><?= $lastUpdate ?></h5></div></div>
<div class="col-md-4"><div class="card p-3 shadow-sm"><small>Admin</small><h5><?= $_SESSION['admin'] ?></h5></div></div>
</div>

<!-- FILTER -->
<form method="GET" class="mb-3">
<select name="year" class="form-select w-auto d-inline" onchange="this.form.submit()">
<option value="">All Years</option>
<?php
$years = mysqli_query($conn, "SELECT DISTINCT YEAR(created_at) as y FROM projects");
while($y=mysqli_fetch_assoc($years)){
$sel=$selectedYear==$y['y']?'selected':'';
echo "<option value='{$y['y']}' $sel>{$y['y']}</option>";
}
?>
</select>
</form>

<!-- CHART -->
<div class="card shadow-sm mb-4">
<div class="card-body">
<h5>Projects Added by Month</h5>
<div style="height:300px;">
<canvas id="projectChart"></canvas>
</div>
</div>
</div>

<!-- TABLE -->
<div id="projectsSection" class="card shadow-sm">
<div class="card-body">

<h5>Manage Projects</h5>

<!-- ✅ SEARCH -->
<div class="d-flex justify-content-end mb-3">
<form method="GET" class="d-flex" style="max-width:350px;">
<input type="hidden" name="search_action" value="1">
<input type="text" name="search" class="form-control me-2" value="<?= $search ?>">
<button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
</form>
</div>

<div class="table-responsive">
<table class="table table-bordered text-center align-middle">

<thead class="table-light">
<tr>
<th>ID</th><th>Image</th><th>Name</th><th>Created</th><th>Action</th>
</tr>
</thead>

<tbody>

<?php $no = 1; ?>
<?php while($p=mysqli_fetch_assoc($projectsResult)):
$img = $p['image']
? "../uploads/projects/".$p['image']
: "../assets/images/default.png";
?>

<tr>
<td><?= $no++ ?></td>
<td><img src="<?= $img ?>" class="project-thumb"></td>
<td><?= htmlspecialchars($p['title']) ?></td>
<td><?= date("Y-m-d",strtotime($p['created_at'])) ?></td>

<td>
<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editModal<?= $p['id'] ?>">
Edit
</button>

<a href="delete_project.php?id=<?= $p['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this project?')">
Delete
</a>
</td>
</tr>

<!-- ✅ ✅ ✅ ADD THIS (DO NOT REMOVE ANYTHING ABOVE) -->
<div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form method="POST" action="update_project.php" enctype="multipart/form-data">

<div class="modal-header">
<h5 class="modal-title">Edit Project</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="project_id" value="<?= $p['id'] ?>">

<!-- ✅ PROJECT NAME -->
<div class="mb-3">
<label>Project Name</label>
<input type="text" name="title" class="form-control"
value="<?= htmlspecialchars($p['title']) ?>">
</div>

<!-- ✅ SHORT DESCRIPTION -->
<div class="mb-3">
<label>Short Description</label>
<textarea name="short_description" class="form-control"><?= $p['short_description'] ?></textarea>
</div>

<!-- ✅ LONG DESCRIPTION -->
<div class="mb-3">
<label>Long Description</label>
<textarea name="long_description" class="form-control"><?= $p['long_description'] ?></textarea>
</div>

<!-- ✅ TECHNOLOGIES -->
<div class="mb-3">
<label>Technologies</label><br>

<?php
$techList = ['HTML','CSS','JavaScript','PHP','Python','MySQL','Bootstrap','React','Laravel','NodeJs','VueJs','D3Js','ChartJs','WordPress'];
$currentTech = explode(',', $p['technologies'] ?? '');

foreach($techList as $t):
?>
<label class="me-2">
<input type="checkbox" name="technologies[]"
value="<?= $t ?>"
<?= in_array($t,$currentTech) ? 'checked' : '' ?>>
<?= $t ?>
</label>
<?php endforeach; ?>

</div>

<!-- ✅ THUMBNAIL -->
<div class="mb-3">
<label>Thumbnail</label><br>

<div style="position:relative; display:inline-block;">
<img src="<?= $img ?>" style="width:100px;">

<a href="delete_thumbnail.php?id=<?= $p['id'] ?>"
class="btn btn-danger btn-sm"
style="position:absolute;top:0;right:0;"
onclick="return confirm('Delete thumbnail?')">
×
</a>
</div>

<input type="file" name="thumbnail" class="form-control mt-2">
</div>

<!-- ✅ GALLERY -->
<div class="mb-3">
<label>Gallery</label>

<div class="d-flex flex-wrap gap-2 mb-2">

<?php
$gallery = mysqli_query($conn, "SELECT * FROM project_images WHERE project_id=".$p['id']);

while($g = mysqli_fetch_assoc($gallery)):
$gImg = "../uploads/projects/".$g['image'];
?>

<div style="position:relative;">
<img src="<?= $gImg ?>" style="width:80px;height:80px;object-fit:cover;">

<a href="delete_image.php?id=<?= $g['id'] ?>"
class="btn btn-danger btn-sm"
style="position:absolute;top:0;right:0;padding:2px 6px;"
onclick="return confirm('Delete image?')">
×
</a>
</div>

<?php endwhile; ?>

</div>

<!-- ✅ ADD NEW -->
<input type="file" name="gallery_images[]" multiple class="form-control">
</div>

<!-- ✅ HOT PROJECT -->
<div class="mb-3">
<label>
<input type="checkbox" name="is_hot" value="1"
<?= $p['is_hot'] == 1 ? 'checked' : '' ?>>
Hot Project 🔥
</label>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-primary">Update</button>
</div>

</form>

</div>
</div>
</div>
<!-- ✅ ✅ ✅ END MODAL -->

<?php endwhile; ?>

</tbody>
</table>
</div>

</div>
</div>

</div>
</div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ✅ CHART (UNCHANGED)
new Chart(document.getElementById('projectChart'), {
type:'line',
data:{
labels:<?= json_encode($allMonths) ?>,
datasets:[{
label:'Projects',
data:<?= json_encode($dataMap) ?>,
borderWidth:2,
tension:0.4,
pointRadius:4
}]
},
options:{
responsive:true,
maintainAspectRatio:false,
scales:{
y:{beginAtZero:true}
}
}
});

// ✅ SCROLL CONTROL
window.onload = function () {

const params = new URLSearchParams(window.location.search);

if (params.has('search_action') || params.has('from_update')) {
    document.getElementById('projectsSection').scrollIntoView({
        behavior: 'smooth'
    });
} else {
    window.scrollTo(0, 0);
}
};
</script>







<!-- ###############✅ ADD PROJECT MODAL ##########################-->
<div class="modal fade" id="addProjectModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- ✅ THIS IS THE FIX (FORM START) -->
<form method="POST" action="add_project.php" enctype="multipart/form-data">

<div class="modal-header">
<h5 class="modal-title">Add New Project</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<!-- TITLE -->
<div class="mb-3">
<label class="fw-bold">Project Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<!-- SHORT -->
<div class="mb-3">
<label class="fw-bold">Short Description</label>
<textarea name="short_description" class="form-control"></textarea>
</div>

<!-- LONG -->
<div class="mb-3">
<label class="fw-bold">Long Description</label>
<textarea name="long_description" class="form-control"></textarea>
</div>

<!-- TECHNOLOGIES -->
<div class="mb-3">
<label class="fw-bold">Technologies</label><br>
<label><input type="checkbox" name="technologies[]" value="HTML"> HTML</label>
<label><input type="checkbox" name="technologies[]" value="CSS"> CSS</label>
<label><input type="checkbox" name="technologies[]" value="JavaScript"> JavaScript</label>
<label><input type="checkbox" name="technologies[]" value="PHP"> PHP</label>
<label><input type="checkbox" name="technologies[]" value="Python"> Python</label>
<label><input type="checkbox" name="technologies[]" value="MySQL"> MySQL</label>
<label><input type="checkbox" name="technologies[]" value="Bootstrap"> Bootstrap</label>
<label><input type="checkbox" name="technologies[]" value="Python"> React</label>
<label><input type="checkbox" name="technologies[]" value="MySQL"> Laravel</label>
<label><input type="checkbox" name="technologies[]" value="Bootstrap"> NodeJs</label>
<label><input type="checkbox" name="technologies[]" value="Bootstrap"> VueJs</label>
<label><input type="checkbox" name="technologies[]" value="Python"> D3Js</label>
<label><input type="checkbox" name="technologies[]" value="MySQL"> ChartJs</label>
<label><input type="checkbox" name="technologies[]" value="Bootstrap"> Wordpress</label>
</div>


<!-- THUMBNAIL -->
<div class="mb-3">
<label class="fw-bold">Project Thumbnail</label>
<input type="file" name="thumbnail" class="form-control">
</div>

<!-- GALLERY -->
<div class="mb-3">
<label class="fw-bold">Project Gallery Images</label>
<input type="file" name="gallery_images[]" class="form-control" multiple>
</div>

<!-- HOT -->
<div class="mb-3">
<label>
<input type="checkbox" name="is_hot" value="1">
Hot Project 🔥
</label>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

<!-- ✅ MUST HAVE NAME -->
<button type="submit" name="save_project" class="btn btn-primary">
Save Project
</button>
</div>

</form> <!-- ✅ THIS IS VERY IMPORTANT -->

</div>
</div>
</div>


</body>
</html>