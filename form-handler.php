<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars($_POST["name"] ?? '');
    $email   = htmlspecialchars($_POST["email"] ?? '');
    $subject = htmlspecialchars($_POST["subject"] ?? '');
    $message = htmlspecialchars($_POST["message"] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Please fill all the fields."]);
        exit;
    }

    // Email 1: Send to Cyblytics admin
    $to_admin = "info@cyblytics.com";
    $admin_subject = "New Contact Form Submission: $subject";
    $admin_headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
    $admin_message = "You received a new message from the Cyblytics contact form:\n\n"
                   . "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

    $admin_sent = mail($to_admin, $admin_subject, $admin_message, $admin_headers);

    // Email 2: Send confirmation to visitor
    $to_user = $email;
    $user_subject = "Cyblytics Lab — We Received Your Message";
    $user_headers = "From: info@cyblytics.com\r\nReply-To: info@cyblytics.com\r\nX-Mailer: PHP/" . phpversion();
    $user_message = "Dear $name,\n\nThank you for contacting Cyblytics Lab at FAST NUCES.\n\n"
                  . "We’ve received your message and will get back to you as soon as possible.\n\n"
                  . "This is an automated email, if you are recioeving it by mistake, we are sorry.\n\n"
                  . "Here’s a copy of what you sent:\n\n"
                  . "Subject: $subject\n\n$message\n\n"
                  . "Warm regards,\nCyblytics Lab Team";

    $user_sent = mail($to_user, $user_subject, $user_message, $user_headers);

    if ($admin_sent && $user_sent) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "One or both emails failed to send. Please try again later."
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Method not allowed."
    ]);
}
?>
