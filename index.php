<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Web Application</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1 class="logo">My App</h1>
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="container">
                <h1>Welcome to My Web Application</h1>
                <p>This is a test application built with HTML, CSS, JavaScript, and PHP.</p>
                <button id="dynamicBtn" class="btn">Click Me!</button>
            </div>
        </section>

        <section id="about" class="content-section">
            <div class="container">
                <h2>About Section</h2>
                <div class="info-box">
                    <h3>Server Information</h3>
                    <p><strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                    <p><strong>Server:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
                    <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
                </div>
            </div>
        </section>

        <section id="contact" class="content-section">
            <div class="container">
                <h2>Contact Form</h2>
                <form id="contactForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn">Send Message</button>
                </form>
                
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = htmlspecialchars($_POST['name']);
                    $email = htmlspecialchars($_POST['email']);
                    $message = htmlspecialchars($_POST['message']);
                    
                    echo "<div class='success-message'>";
                    echo "<h3>Thank you, $name!</h3>";
                    echo "<p>Your message has been received. We'll get back to you at $email.</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 My Web Application. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>