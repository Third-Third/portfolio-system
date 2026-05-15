<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg glass-navbar">
    <div class="container">

        <!-- ✅ LOGO IMAGE BRAND -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.png"
                 alt="MyPortfolio Logo"
                 class="nav-logo">
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- ✅ HOME -->
                <li class="nav-item">
                    <a class="nav-link nav-underline <?= ($currentPage == 'index.php') ? 'active' : '' ?>" href="index.php">
                        Home
                    </a>
                </li>

                <!-- ✅ ABOUT -->
                <li class="nav-item">
                    <a class="nav-link nav-underline <?= ($currentPage == 'about.php') ? 'active' : '' ?>" href="about.php">
                        About
                    </a>
                </li>

                <!-- ✅ PROJECTS -->
                <li class="nav-item">
                    <a class="nav-link nav-underline <?= ($currentPage == 'projects.php') ? 'active' : '' ?>" href="projects.php">
                        Projects
                    </a>
                </li>

                <!-- ✅ CONTACT BUTTON (optional active if you want) -->
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-primary rounded-pill px-4 <?= ($currentPage == 'contact.php') ? 'active' : '' ?>"
                       href="contact.php">
                        Contact
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>
