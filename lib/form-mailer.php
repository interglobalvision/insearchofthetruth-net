<?php
// Only process POST reqeusts.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $support_page = get_page_by_path('support');
  $support_page_id = $support_page->ID;

  // Get the form fields and remove whitespace.
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $message = trim($_POST['message']);
  $recipient = get_post_meta($support_page_id, '_igv_support_form_recipient', true);
  $message = trim($_POST['message']);

  // Check that data was sent to the mailer.
  if (empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Set a 400 (bad request) response code and exit.
    http_response_code(400);
    echo 'Oops! There was a problem with your submission. Please complete the form and try again.';
    exit;
  }

  // Get custom success response message
  $success = get_post_meta($support_page_id, '_igv_support_form_success', true);

  if (empty($success)) {
    $success = 'Thank You! Your message has been sent.';
  }

  // Set the email subject.
  $subject = 'Support Form Inquiry';

  // Build the email content.
  $email_content .= 'Email: $email\n\n';
  $email_content .= 'I want to $message';

  // Build the email headers.
  $email_headers = 'From: <$email>';

  // Send the email.
  if (mail($recipient, $subject, $email_content, $email_headers)) {
    // Set a 200 (okay) response code.
    http_response_code(200);
    echo $success;
  } else {
    // Set a 500 (internal server error) response code.
    http_response_code(500);
    echo 'Oops! Something went wrong and we couldn\'t send your message.';
  }

} else {
  // Not a POST request, set a 403 (forbidden) response code.
  http_response_code(403);
  echo 'There was a problem with your submission, please try again.';
}
?>
