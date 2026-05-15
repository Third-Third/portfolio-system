<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact | MyPortfolio</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (for phone) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome (for brand icons: Telegram & Facebook) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <?php include 'navbar.php'; ?>

    <main class="content">

        <section class="py-5">
            <div class="container">

                <h1 class="fw-bold mb-3">Contact Me</h1>
                <p class="text-muted mb-5">
                    You can reach me through the platforms below. I am open to discussions,
                    collaboration, and opportunities.
                </p>

                <div class="row align-items-center">

                    <!-- LEFT: CONTACT LINKS -->
                    <div class="col-md-6">

                        <!-- Telegram -->
                        <div class="about-card mb-4 d-flex align-items-center">
                            <i class="fa-brands fa-telegram fs-2 me-3" style="color:#229ED9;"></i>
                            <div>
                                <h5 class="mb-1">Telegram</h5>
                                <a href="https://t.me/call_me_tii_tii" target="_blank" class="text-decoration-none">
                                    Chat with me
                                </a>
                            </div>
                        </div>

                        <!-- Facebook -->
                        <div class="about-card mb-4 d-flex align-items-center">
                            <i class="fa-brands fa-facebook fs-2 me-3" style="color:#1877F2;"></i>
                            <div>
                                <h5 class="mb-1">Facebook</h5>
                                <a href="https://www.facebook.com/vinn.tithrithy/" target="_blank" class="text-decoration-none">
                                    View my profile
                                </a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="about-card d-flex align-items-center">
                            <i class="bi bi-telephone fs-2 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-1">Phone</h5>
                                <span>096 76 78 197</span>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: IMAGE -->
                    <div class="col-md-6 text-center mt-4 mt-md-0">
                        <img src="assets/images/contact.png" class="img-fluid" alt="Contact illustration">
                    </div>

                </div>

            </div>
        </section>

    </main>

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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>