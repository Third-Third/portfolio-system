<?php
require_once __DIR__ . '/config/db.php';

/* ✅ FETCH PROJECTS */
$result = mysqli_query($conn, "SELECT * FROM projects ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects | MyPortfolio</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="page-wrapper">

    <!-- ✅ NAVBAR -->
    <?php include 'navbar.php'; ?>

    <main class="content">

        <!-- ✅ HEADER -->
        <section class="py-5 text-center">
            <div class="container">
                <h1 class="fw-bold mb-3">Projects</h1>
                <p class="text-muted">
                    This section displays projects managed dynamically through the system backend.
                </p>
            </div>
        </section>

        <!-- ✅ PROJECT LIST -->
        <section class="pb-5">
            <div class="container">

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

                                    <div class="card project-card h-100 shadow-sm">

                                        <!-- ✅ IMAGE BOX -->
                                        <div class="project-img-wrapper">
                                            <img src="<?= $imgPath ?>" alt="project">
                                        </div>

                                        <div class="card-body">

                                            <!-- ✅ TITLE -->
                                            <h5 class="fw-bold mb-2">
                                                <?= htmlspecialchars($row['title']) ?>
                                            </h5>

                                            <!-- ✅ DESCRIPTION -->
                                            <p class="text-muted small mb-3">
                                                <?= htmlspecialchars($row['short_description']) ?>
                                            </p>

                                            <!-- ✅ TECHNOLOGY BADGES -->
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

    <!-- ✅ FOOTER -->
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