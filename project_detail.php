<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_GET['id'])) {
    header("Location: projects.php");
    exit();
}

$id = intval($_GET['id']);

/* ✅ GET PROJECT */
$result = mysqli_query($conn, "SELECT * FROM projects WHERE id = $id");

if (mysqli_num_rows($result) == 0) {
    echo "Project not found";
    exit();
}

$row = mysqli_fetch_assoc($result);

/* ✅ GET GALLERY */
$galleryResult = mysqli_query($conn, "
    SELECT * FROM project_images WHERE project_id = $id
");

$imgPath = !empty($row['image'])
    ? "uploads/projects/" . $row['image']
    : "assets/images/default.png";

$techs = !empty($row['technologies'])
    ? explode(',', $row['technologies'])
    : [];

/* ✅ COLLECT IMAGES */
$images = [];
while ($img = mysqli_fetch_assoc($galleryResult)) {
    $images[] = "uploads/projects/" . $img['image'];
}

if (empty($images)) {
    $images[] = $imgPath;
}

$chunks = array_chunk($images, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($row['title']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

/* ✅ IMAGE CLICK */
.clickable-img { cursor: pointer; }

/* ✅ GALLERY BOX */
.gallery-box {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;

    background: #f8fafc;
    border-radius: 10px;
}

/* ✅ FIX ZOOM ISSUE */
.gallery-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;   /* ✅ FIX */
}

/* ✅ DOT STYLE */
.carousel-indicators [data-bs-target] {
    background-color: #2563eb;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

/* ✅ MODAL IMAGE */
.modal-img {
    width: 100%;
    max-height: 80vh;
    object-fit: contain;
}

</style>
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">

<!-- BACK BUTTON -->
<a href="projects.php" class="back-btn-modern">
    <i class="bi bi-arrow-left"></i> Back to Projects
</a>

<!-- ✅ TOP -->
<div class="row mb-5">

    <div class="col-md-6">
        <div class="detail-image-box">
            <img src="<?= $imgPath ?>" class="clickable-img"
                 data-bs-toggle="modal"
                 data-bs-target="#imageModal">
        </div>
    </div>

    <div class="col-md-6">

        <h2 class="fw-bold mb-3">
            <?= htmlspecialchars($row['title']) ?>
        </h2>

        <p class="text-muted">
            <?= htmlspecialchars($row['short_description']) ?>
        </p>

        <div class="mt-3">
            <?php foreach ($techs as $t): ?>
                <span class="badge bg-primary me-1"><?= $t ?></span>
            <?php endforeach; ?>
        </div>

    </div>

</div>

<!-- ✅ DESCRIPTION -->
<div class="mb-5">
    <h4 class="fw-bold mb-3">Project Description</h4>
    <p class="text-muted">
        <?= nl2br(htmlspecialchars($row['long_description'])) ?>
    </p>
</div>

<!-- ✅ ✅ GALLERY -->
<div>
<h4 class="fw-bold mb-3">Project Gallery</h4>

<div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

    <!-- ✅ DOT INDICATORS -->
    <div class="carousel-indicators">
        <?php foreach ($chunks as $index => $group): ?>
            <button type="button"
                data-bs-target="#galleryCarousel"
                data-bs-slide-to="<?= $index ?>"
                class="<?= $index == 0 ? 'active' : '' ?>">
            </button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner">

        <?php foreach ($chunks as $index => $group): ?>
        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
            <div class="row g-3">

                <?php foreach ($group as $img): ?>
                <div class="col-md-4">
                    <div class="gallery-box">
                        <img src="<?= $img ?>" class="clickable-img"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal">
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- CONTROLS -->
    <button class="carousel-control-prev" data-bs-target="#galleryCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" data-bs-target="#galleryCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>
</div>

</div>

<!-- ✅ MODAL -->
<div class="modal fade" id="imageModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark border-0">

        <div class="modal-body text-center position-relative">

            <img id="modalImage" class="modal-img">

            <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y"
                onclick="prevImage()">‹</button>

            <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y"
                onclick="nextImage()">›</button>

        </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let images = [];
let currentIndex = 0;

document.querySelectorAll('.clickable-img').forEach((img, index) => {
    images.push(img.src);

    img.addEventListener('click', function(){
        currentIndex = index;
        document.getElementById('modalImage').src = this.src;
    });
});

function nextImage(){
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}

function prevImage(){
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById('modalImage').src = images[currentIndex];
}
</script>

</body>
</html>