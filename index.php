<?php
require_once __DIR__ . '/config/db.php';

/* ✅ GET HOT PROJECT FIRST */
$result = mysqli_query($conn, "
    SELECT * FROM projects
    WHERE is_hot = 1
    ORDER BY created_at DESC
");

/* ✅ FALLBACK IF NO HOT PROJECT */
$isHotMode = true;

if (mysqli_num_rows($result) == 0) {
    $isHotMode = false;

    $result = mysqli_query($conn, "
        SELECT * FROM projects
        ORDER BY created_at DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Software Engineering Portfolio</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="page-wrapper">

    <?php include 'navbar.php'; ?>

    <main class="content">

        <!-- ===== HERO ===== -->
        <section class="hero text-white">
            <div class="container">
                <h1>Software Engineering<br>Portfolio</h1>

                <p>
                    I am a Software Engineering student passionate about building
                    modern, scalable, and efficient web applications.
                </p>

                <a href="#projects" class="btn btn-light btn-lg">
                    View Projects
                </a>
            </div>
        </section>

        <!-- ===== PROJECTS ===== -->
        <section id="projects" class="py-5">
            <div class="container">

                <!-- ✅ HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <!-- ✅ DYNAMIC TITLE -->
                    <h2 class="section-title mb-0">
                        <?= $isHotMode ? '🔥 Hot Projects' : 'Projects' ?>
                    </h2>

                    <a href="projects.php" class="btn btn-outline-primary btn-sm">
                        View All
                    </a>
                </div>

                <div class="row g-4">

                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <?php
                            $imgPath = !empty($row['image'])
                                ? "uploads/projects/" . $row['image']
                                : "assets/images/default.png";

                            $techs = !empty($row['technologies'])
                                ? explode(',', $row['technologies'])
                                : [];
                            ?>

                            <div class="col-md-4">

                                <!-- ✅ CLICKABLE CARD -->
                                <a href="project_detail.php?id=<?= $row['id'] ?>" class="card-link">

                                    <div class="card project-card h-100 shadow-sm position-relative">

                                        <!-- ✅ HOT BADGE -->
                                        <?php if ($isHotMode && $row['is_hot'] == 1): ?>
                                            <span class="badge bg-danger position-absolute m-2">
                                                🔥 Hot
                                            </span>
                                        <?php endif; ?>

                                        <!-- ✅ IMAGE -->
                                        <div class="project-img-wrapper">
                                            <img src="<?= $imgPath ?>" alt="project">
                                        </div>

                                        <div class="card-body">

                                            <h5 class="fw-bold mb-2">
                                                <?= htmlspecialchars($row['title']) ?>
                                            </h5>

                                            <p class="text-muted small mb-3">
                                                <?= htmlspecialchars($row['short_description']) ?>
                                            </p>

                                            <div>
                                                <?php foreach ($techs as $t): ?>
                                                    <span class="badge bg-primary me-1"><?= $t ?></span>
                                                <?php endforeach; ?>
                                            </div>

                                        </div>

                                    </div>

                                </a>

                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>

                        <div class="text-center text-muted">
                            No projects available.
                        </div>

                    <?php endif; ?>

                </div>

            </div>
        </section>

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <div class="container text-center">
            <p class="footer-title mb-1">
                © <?= date("Y"); ?> VINN TITHRITHY
            </p>
            <p class="footer-subtitle mb-0">
                Designed & developed for academic and professional showcase
            </p>
        </div>
    </footer>

</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
