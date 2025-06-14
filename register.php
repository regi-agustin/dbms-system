<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "cinema";

$message = "";
$messageType = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = trim($_POST['email']);
    $confirmEmail = trim($_POST['confirm-email']);
    $firstName = trim($_POST['first-name']);
    $lastName = trim($_POST['last-name']);
    $mobile = trim($_POST['mobile']);
    
    // Basic validation
    if (empty($email) || empty($confirmEmail) || empty($firstName) || empty($lastName) || empty($mobile)) {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    } elseif ($email !== $confirmEmail) {
        $message = "Email addresses do not match.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } else {
        try {
            // Create connection
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Prepare and execute insert statement
            $stmt = $conn->prepare("INSERT INTO customer (Firstname, Lastname, mobile_number, email_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $mobile, $email]);
            
            $message = "Registration successful! Customer ID: " . $conn->lastInsertId();
            $messageType = "success";
            
            // Redirect to seats page after successful registration
            $movie = isset($_GET['movie']) ? $_GET['movie'] : 'ballerina';
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'seats_$movie.php';
                }, 2000);
            </script>";
            
        } catch(PDOException $e) {
            $message = "Registration failed: " . $e->getMessage();
            $messageType = "error";
        }
        
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registration Form</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    /* Reset and base */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      background: #f5f7fa;
      font-family: 'Inter', sans-serif;
      color: #2e3440;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    /* Form container */
    .form-container {
      background: #f5f2e8;
      padding: 40px 36px;
      border: 2px solid #333;
      border-radius: 0;
      box-shadow: none;
      max-width: 420px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    /* Header styling */
    .form-header {
      text-align: center;
      margin-bottom: 8px;
    }

    .form-title {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      margin: 0;
      letter-spacing: -0.5px;
    }

    /* Message styling */
    .message {
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 16px;
      text-align: center;
      font-weight: 500;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    /* Form group styling */
    .form-group {
      position: relative;
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: 400;
      font-size: 14px;
      color: #333;
      margin-bottom: 6px;
      user-select: none;
    }

    input[type="text"],
    input[type="email"] {
      padding: 8px 12px;
      font-size: 14px;
      border: 1px solid #999;
      border-radius: 0;
      background-color: #fff;
      transition: border-color 0.3s ease;
      outline-offset: 2px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
      border-color: #666;
      box-shadow: none;
      outline: none;
      background-color: #fff;
    }

    /* Buttons container */
    .button-row {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      margin-top: 10px;
    }

    button.btn {
      flex: 1;
      padding: 10px 20px;
      font-size: 14px;
      font-weight: 600;
      border: 1px solid #999;
      cursor: pointer;
      border-radius: 0;
      transition: background-color 0.3s ease;
      box-shadow: none;
    }

    button.btn-back {
      background-color: #e8e8e8;
      color: #333;
    }

    button.btn-back:hover,
    button.btn-back:focus-visible {
      background-color: #d8d8d8;
      outline: none;
      box-shadow: none;
    }

    button.btn-next {
      background: #ff8c00;
      color: white;
      border: 1px solid #ff8c00;
    }

    button.btn-next:hover,
    button.btn-next:focus-visible {
      background: #e67e00;
      outline: none;
      box-shadow: none;
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
      .form-container {
        padding: 32px 24px;
      }
      .form-title {
        font-size: 24px;
      }
      button.btn {
        font-size: 14px;
        padding: 12px 0;
      }
    }
  </style>
</head>
<body>
  <form class="form-container" method="POST" action="">
    <div class="form-header">
      <h1 class="form-title">Register</h1>
    </div>

    <?php if (!empty($message)): ?>
      <div class="message <?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
    </div>

    <div class="form-group">
      <label for="confirm-email">Re-type Email Address:</label>
      <input type="email" id="confirm-email" name="confirm-email" value="<?php echo isset($_POST['confirm-email']) ? htmlspecialchars($_POST['confirm-email']) : ''; ?>" required />
    </div>

    <div class="form-group">
      <label for="first-name">First Name:</label>
      <input type="text" id="first-name" name="first-name" value="<?php echo isset($_POST['first-name']) ? htmlspecialchars($_POST['first-name']) : ''; ?>" required />
    </div>

    <div class="form-group">
      <label for="last-name">Last Name:</label>
      <input type="text" id="last-name" name="last-name" value="<?php echo isset($_POST['last-name']) ? htmlspecialchars($_POST['last-name']) : ''; ?>" required />
    </div>

    <div class="form-group">
      <label for="mobile">Mobile Number:</label>
      <input type="text" id="mobile" name="mobile" value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>" required />
    </div>

    <div class="button-row">
      <button type="button" class="btn btn-back" onclick="window.location.href='homepage.php'">BACK</button>
      <button type="submit" class="btn btn-next">NEXT</button>
    </div>
  </form>

  <script>
    // Client-side validation for better user experience
    document.querySelector('form').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const confirmEmail = document.getElementById('confirm-email').value.trim();
      const firstName = document.getElementById('first-name').value.trim();
      const lastName = document.getElementById('last-name').value.trim();
      const mobile = document.getElementById('mobile').value.trim();

      if (!email || !confirmEmail || !firstName || !lastName || !mobile) {
        alert('Please fill in all required fields.');
        e.preventDefault();
        return false;
      }

      if (email !== confirmEmail) {
        alert('Email addresses do not match.');
        e.preventDefault();
        return false;
      }

      // Additional mobile number validation
      if (!/^\d{11}$/.test(mobile.replace(/[\s\-\(\)]/g, ''))) {
        alert('Please enter a valid mobile number (11 digits).');
        e.preventDefault();
        return false;
      }
    });
  </script>
</body>
</html>

<?php
/*
SQL to create the customer table in the cinema database:

CREATE DATABASE IF NOT EXISTS cinema;
USE cinema;

CREATE TABLE IF NOT EXISTS customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    Firstname VARCHAR(50) NOT NULL,
    Lastname VARCHAR(50) NOT NULL,
    mobile_number VARCHAR(20) NOT NULL,
    email_address VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/
?>