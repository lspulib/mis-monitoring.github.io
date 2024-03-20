<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS count FROM replacements WHERE date_replaced IS NULL";

$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];

    // Send email
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'raffy.hular@gmail.com';
    $mail->Password = 'purrvgpbwpdezymw';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('raffy.hular@gmail.com');
    $mail->addAddress('rjhular30@gmail.com'); 
    $mail->isHTML(true);
    $mail->Subject = "Current Unreplaced Items";
    $mail->Body = "The inventory for replacement items \n have a total " . $count. " unreplaced item/s. \nPlease check the system to know the information \n for each item. Have a Good Day!"; 

    if ($mail->send()) {
        echo "Email sent successfully";
    } else {
        echo "Error: Email could not be sent";
    }

    $conn->close();
} else {
    echo "Error in executing SQL query";
}
?>