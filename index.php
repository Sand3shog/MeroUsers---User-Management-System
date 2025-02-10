<?php 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HamroUsers - Digital Experience Platform</title>
    
 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>


    <header class="fixed-top">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">MeroUsers</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                            <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="btn btn-primary px-4" href="register.php">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <section class="hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-text">
                    <h1 class="display-4 mb-4">Transform Your Digital Experience</h1>
                    <p class="lead mb-4">Your all-in-one platform for seamless user management and enhanced online presence.</p>
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="hero-buttons">
                            <a href="register.php" class="btn btn-primary btn-lg mr-3">Get Started</a>
                            <a href="#features" class="btn btn-outline-light btn-lg">Learn More</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="./images/boy.svg" alt="Hero Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>


    <section id="features" class="features">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose HamroUsers?</h2>
            <div class="row">
                <div class="col-md-4 feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Secure & Reliable</h3>
                    <p>Advanced security protocols to protect your personal data</p>
                </div>
                <div class="col-md-4 feature-item">
                    <i class="fas fa-bolt"></i>
                    <h3>Lightning Fast</h3>
                    <p>Optimized performance for seamless user experiences</p>
                </div>
                <div class="col-md-4 feature-item">
                    <i class="fas fa-clock"></i>
                    <h3>24/7 Support</h3>
                    <p>Dedicated customer support available round-the-clock</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container text-center">
            <h2>Ready to Get Started?</h2>
            <p class="lead">Join our platform and revolutionize your digital journey!</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-primary btn-lg">Sign Up Now</a>
            <?php endif; ?>
        </div>
    </section>


    <footer class="footer bg-dark text-white">
        <div class="container">
            <div class="row py-4">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>HamroUsers</h5>
                    <p>Transforming digital experiences through innovative solutions.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="profile.php">Profile</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                    <a href="https://www.facebook.com/sandesh.freak"><i class="fab fa-facebook"></i></a>
                    <a href="https://github.com/Sand3shog"><i class="fab fa-github"></i></a>
                    <a href="https://www.instagram.com/sand3shog_/"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/sandesh-maharjan-4633342a0/"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center py-3 border-top border-secondary">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> Sandesh Maharjan. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
