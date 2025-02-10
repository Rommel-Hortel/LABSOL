<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Oswald', sans-serif;
            background: linear-gradient(to bottom, #1f95dd, #92d6fd, #e4f4fd);
        }
        .carousel-inner img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-success d-flex flex-column min-vh-100">

    <!-- Header -->
    <header class="bg-info text-black py-5">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="pic/pic1.jpg" alt="Logo" class="me-2" style="width: 50px; height: 50px;">
                <h2 class="m-0">LabSolution Inc.</h2>
            </div>
            <div class="d-flex align-items-center">
                  <h4 class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h4>
                    <form action="logout.php" method="post" class="m-0">
                        <button type="submit" class="btn btn-danger" id="logout-btn">Logout</button>
                    </form>
            </div>
        </div>
    </header>

    <!-- Carousel Section -->
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 offset-md-2 d-flex justify-content-center">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="max-width: 600px;">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="pic/pictures.png" class="d-block w-100 img-fluid" alt="Slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="pic/back2.jpg" class="d-block w-100 img-fluid" alt="Slide 2">
                        </div>
                        <div class="carousel-item">
                            <img src="pic/back3.jpg" class="d-block w-100 img-fluid" alt="Slide 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="container text-center font-monospace mb-4">
        <a href="FORM/checklistPT-install.php" class="btn btn-light fw-bolder mx-2">FORM</a>
        <a href="MANUAL/usermanual.php" class="btn btn-light fw-bolder mx-2">MANUAL</a>
        <a href="INSTALLER/softwares.php" class="btn btn-light fw-bolder mx-2">INSTALLER</a>
    </div>

    <!-- Footer -->
    <footer class="footer bg-info text-white text-center py-4 mt-auto">
        <p class="mb-0">LABSOLUTION INC. &copy; 2025</p>
        <div class="social-icons">
            <a href="https://www.facebook.com/labsolutiontechnologiesinc" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Fix Logout Button Click Issue -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#logout-btn").addEventListener("click", function(event) {
                event.stopPropagation(); // Prevents the click from affecting the carousel
            });
        });
    </script>
</body>
</html>
