<?php
// contact.php
session_start();

// Initialize variables
$name = $email = $phone = $message = "";
$nameErr = $emailErr = $phoneErr = $messageErr = "";
$successMsg = "";

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true;

    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $valid = false;
    } else {
        $name = sanitize_input($_POST["name"]);
        if (strlen($name) > 100) {
            $nameErr = "Name must be less than 100 characters";
            $valid = false;
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $valid = false;
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $valid = false;
        }
    }

    // Validate phone
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone is required";
        $valid = false;
    } else {
        $phone = sanitize_input($_POST["phone"]);
        if (strlen($phone) > 20) {
            $phoneErr = "Phone must be less than 20 characters";
            $valid = false;
        }
    }

    // Validate message
    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
        $valid = false;
    } else {
        $message = sanitize_input($_POST["message"]);
        if (strlen($message) > 1000) {
            $messageErr = "Message must be less than 1000 characters";
            $valid = false;
        }
    }

    // If valid, send email
    if ($valid) {
        $to = "info@gourmetdelights.co.za"; // Change to your email
        $subject = "New Contact Form Submission from " . $name;
        $body = "Name: " . $name . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Phone: " . $phone . "\n\n";
        $body .= "Message:\n" . $message;
        
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";

        if (mail($to, $subject, $body, $headers)) {
            $successMsg = "Thank you for your message! We will get back to you soon.";
            // Clear form
            $name = $email = $phone = $message = "";
        } else {
            $messageErr = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Gourmet Delights</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .error { color: #d32f2f; font-size: 0.875rem; margin-top: 4px; }
        .success { background: #4caf50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <!-- ==================== NAVBAR ==================== -->
    <header>
        <nav class="navbar">
            <div class="container nav-container">
                <a href="index.html" class="logo">Gourmet Delights</a>
                <ul class="nav-links">
                    <li><a href="index.html" class="nav-link">Home</a></li>
                    <li><a href="about.html" class="nav-link">About</a></li>
                    <li><a href="contact.php" class="nav-link active">Contact</a></li>
                </ul>

                <!-- Bucket List Button with Label -->
                <button id="bucket-btn" class="bucket-btn" aria-label="Open Bucket List">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="bucket-icon">
                        <path d="M9 2h6a2 2 0 0 1 2 2v2h-2V4H9v2H7V4a2 2 0 0 1 2-2z"></path>
                        <path d="M5 8h14l-1 12H6L5 8z"></path>
                    </svg>
                    <span class="bucket-label">Bucket List</span>
                    <span id="bucket-count" class="bucket-count">0</span>
                </button>
            </div>
        </nav>
    </header>

    <!-- ==================== HERO ==================== -->
    <section class="hero contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="fade-in">Contact Us</h1>
                <p class="fade-in">We'd love to hear from you! Reach out with any questions or feedback.</p>
            </div>
        </div>
    </section>

    <!-- ==================== CONTACT SECTION ==================== -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <h2>Send us a Message</h2>
                    
                    <?php if ($successMsg): ?>
                        <div class="success"><?php echo $successMsg; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact-form">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder="John Doe">
                            <span class="error"><?php echo $nameErr; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="john@example.com">
                            <span class="error"><?php echo $emailErr; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="(016) 123-4567">
                            <span class="error"><?php echo $phoneErr; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="6" placeholder="Tell us what's on your mind..."><?php echo $message; ?></textarea>
                            <span class="error"><?php echo $messageErr; ?></span>
                        </div>

                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    
                    <div class="info-card">
                        <div class="info-item">
                            <div class="info-icon">📍</div>
                            <div>
                                <h3>Address</h3>
                                <p>123 Main Street<br>Vanderbijlpark, 1911<br>South Africa</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">📞</div>
                            <div>
                                <h3>Phone</h3>
                                <p>(016) 123-4567<br>(016) 987-6543</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">✉️</div>
                            <div>
                                <h3>Email</h3>
                                <p>info@gourmetdelights.co.za<br>orders@gourmetdelights.co.za</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">🕐</div>
                            <div>
                                <h3>Hours</h3>
                                <p>Monday - Friday: 9:00 AM - 9:00 PM<br>
                                Saturday: 10:00 AM - 10:00 PM<br>
                                Sunday: 10:00 AM - 8:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3580.8547!2d27.8375!3d-26.7114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjbCsDQyJzQxLjAiUyAyN8KwNTAnMTUuMCJF!5e0!3m2!1sen!2sza!4v1234567890"
                            width="100%" 
                            height="300" 
                            style="border:0; border-radius: 8px;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h3>Gourmet Delights</h3>
                <p>Passion, flavor, and community – served fresh in the heart of Vanderbijlpark.</p>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>123 Main Street, Vanderbijlpark, 1911<br>Phone: (016) 123-4567<br>Email: info@gourmetdelights.co.za</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#">Facebook</a>
                    <a href="#">Instagram</a>
                    <a href="#">Twitter</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Gourmet Delights. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include bucket modal and script -->
    <div id="bucket-modal" class="bucket-modal">
        <div class="bucket-modal-content">
            <span class="bucket-close">&times;</span>
            <h2>Your Bucket List</h2>
            <ul id="bucket-items"></ul>
            
            <div class="delivery-options">
                <h3>Delivery Options</h3>
                <label><input type="radio" name="delivery" value="pickup" checked> Pickup (Free)</label>
                <label><input type="radio" name="delivery" value="standard"> Standard Delivery (R35.00)</label>
                <label><input type="radio" name="delivery" value="express"> Express Delivery (R70.00)</label>
            </div>

            <div class="bucket-totals">
                <p>Subtotal: R<span id="bucket-subtotal">0.00</span></p>
                <p>Delivery: R<span id="bucket-delivery">0.00</span></p>
                <p><strong>Total: R<span id="bucket-total">0.00</span></strong></p>
            </div>

            <div class="bucket-actions">
                <button id="clear-bucket" class="clear-btn">Clear All</button>
                <button id="checkout-btn" class="checkout-btn">Checkout</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
