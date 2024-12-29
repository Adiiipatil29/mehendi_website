<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $message = $_POST['message'];

    // You can perform validation here before sending the email

    // Send email
    $to = "your_email@example.com";
    $subject = "New Message from MehndiMagicWorld";
    $email_body = "Name: $name\nEmail: $email\nNumber: $number\nMessage:\n$message";
    mail($to, $subject, $email_body);

    // Redirect back to the contact page
    header("Location: contact.php?status=success");
    exit();
}
?>
