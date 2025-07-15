<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figma to Web Conversion</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-content">
        <div class="container">
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">Welcome to Your Figma Design</h1>
                    <p class="hero-description">This is a template structure for converting Figma designs to web</p>
                    <button class="btn btn-primary" id="cta-button">Get Started</button>
                </div>
                <div class="hero-image">
                    <img src="assets/images/hero-placeholder.jpg" alt="Hero Image" class="responsive-image">
                </div>
            </section>
            
            <section class="features-section">
                <div class="section-header">
                    <h2 class="section-title">Features</h2>
                    <p class="section-description">Key features of your design</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-responsive"></i>
                        </div>
                        <h3 class="feature-title">Responsive Design</h3>
                        <p class="feature-description">Fully responsive across all devices</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-fast"></i>
                        </div>
                        <h3 class="feature-title">Fast Loading</h3>
                        <p class="feature-description">Optimized for speed and performance</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icon-modern"></i>
                        </div>
                        <h3 class="feature-title">Modern UI</h3>
                        <p class="feature-description">Clean and modern user interface</p>
                    </div>
                </div>
            </section>
            
            <section class="contact-section">
                <div class="contact-form-container">
                    <h2 class="section-title">Contact Us</h2>
                    <form class="contact-form" id="contact-form" method="POST" action="includes/contact-handler.php">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
</body>
</html>