<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "info@cyblytics.com"; // your lab's email
    $subject = "New Message from Cyblytics Contact Form";

    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    $fullMessage = "You have received a new message from Cyblytics contact form:\n\n";
    $fullMessage .= "Name: $name\n";
    $fullMessage .= "Email: $email\n\n";
    $fullMessage .= "Message:\n$message";

    if (mail($to, $subject, $fullMessage, $headers)) {
        echo "<h2>Thanks, your message has been sent!</h2>";
    } else {
        echo "<h2>Sorry, there was an error sending your message.</h2>";
    }
} else {
    http_response_code(403);
    echo "Access denied.";
}
?>`1