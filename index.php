<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

session_start();
$message = "";
$otpForm = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST['send_otp'])) {
        $userEmail = htmlspecialchars($_POST['email']);

        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $userEmail;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ajay.upadhay007@gmail.com';     
            $mail->Password   = 'hkio vrjn uqdt tppf';          
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('ajayu381@gmail.com', 'OTP Sender');
            $mail->addAddress($userEmail);

            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your One-Time Password (OTP) is: $otp";

            $mail->send();
            $message = "<span style='color:green;'>OTP sent to $userEmail!</span>";
            $otpForm = true;
        } catch (Exception $e) {
            $message = "<span style='color:red;'>Mailer Error: {$mail->ErrorInfo}</span>";
        }
    }

if (isset($_POST['verify_otp'])) {
    $otpForm = true; 
    $enteredOtp = trim($_POST['otp']);
    if ($enteredOtp == $_SESSION['otp']) {
        unset($_SESSION['otp']);
        header("Location: success.php");
        exit();
    } else {
        $message = "<span style='color:red;'>Invalid OTP. Please try again.</span>";
    }
}


    }

?>



<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Email OTP Verification</h2>
    <?= $message ?>

    <?php if (!isset($_SESSION['otp']) || !$otpForm): ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>
    <?php else: ?>
        
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" pattern="\d{6}" maxlength="6" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
