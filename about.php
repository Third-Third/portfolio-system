<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About | MyPortfolio</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Custom CSS -->
    <link href="assets/css/style.css?v=2" rel="stylesheet">

    <style>
    /* ✅ FIX ABOUT IMAGE */
    .about-img {
        width: 100%;
        max-height: 350px;   /* ✅ control size */

        object-fit: contain; /* ✅ no crop/zoom */

        display: block;
        margin: 0 auto;

        border-radius: 16px;
        background: #f8fafc;
        padding: 10px;
    }

    /* ✅ OPTIONAL NICE CARD LOOK */
    .about-card {
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;

        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    </style>

</head>
<body>

<div class="page-wrapper">

    <?php include 'navbar.php'; ?>

<main class="content">
    <section class="py-5">
        <div class="container">

            <h1 class="mb-4 fw-bold">About Me</h1>

            <div class="about-card">
                <div class="row align-items-center">

                    <!-- ✅ IMAGE -->
                    <div class="col-md-5 mb-4 mb-md-0 text-center">
                        <img src="assets/images/about.png"
                             alt="About illustration"
                             class="about-img">
                    </div>

                    <!-- ✅ TEXT -->
                    <div class="col-md-7">

                        <p class="lead">
                            I am a Software Engineering student with a strong interest
                            in building modern web applications, solving problems, and
                            designing efficient systems.
                        </p>

                        <!-- ✅ SKILLS -->
                        <div class="row mt-4">

                            <div class="col-md-6">
                                <h5>Technical Skills</h5>
                                <ul>
                                    <li>HTML & CSS</li>
                                    <li>JavaScript</li>
                                    <li>PHP & MySQL</li>
                                    <li>CRUD Operations</li>
                                    <li>SDLC Principles</li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <h5>Soft Skills</h5>
                                <ul>
                                    <li>Problem-Solving</li>
                                    <li>Critical Thinking</li>
                                    <li>Teamwork</li>
                                    <li>Communication</li>
                                </ul>
                            </div>

                        </div>

                        <!-- ✅ EDUCATION -->
                        <div class="mt-4">
                            <h5>Education</h5>
                            <p>
                                <strong>Bachelor of Science in Software Engineering</strong><br>
                                University Name<br>
                                2020 – 2024
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>
</main>

<!-- ✅ FOOTER -->
<footer class="site-footer">
    <div class="container text-center">
        <p class="footer-title mb-1">
            © <?php echo date("Y"); ?> VINN TITHRITHY
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