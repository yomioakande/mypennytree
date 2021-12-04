<?php
$host = "localhost";
$db_name = "ezragene_mypennytree";
$username = "ezragene_Yomzee";
$password = "oLuwadamilare@1";

$conn = null;
try {
  $conn = new PDO(
    "mysql:host=" . $host . ";dbname=" . $db_name,
    $username,
    $password
  );
} catch (PDOException $exception) {
  echo "Connection error: " . $exception->getMessage();
}

if (isset($_POST["contact"])) {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $message = $_POST["message"];
  $created = date("Y-m-d H:i:s");
  if (empty($name) || empty($email) || empty($message)) {
    echo "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo $email . " is an invalid email format.";
  } else {
    $query =
      "INSERT INTO contacts SET name = :name, email = :email, message = :message, created =:created";

    $stmt = $conn->prepare($query);
    $name = htmlspecialchars(strip_tags($name));
    $email = htmlspecialchars(strip_tags($email));
    $message = htmlspecialchars(strip_tags($message));

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":message", $message);
    $stmt->bindParam(":created", $created);

    if ($stmt->execute()) {
      echo "Message successfully sent. We will be in touch!";

      $to = "hello@mypennytree.com";
      $subject = "PennyTree Website Contact Form";
      $mailContent =
        '<!DOCTYPE html>
    <html>
  <head>
    <link href="http://fonts.cdnfonts.com/css/rubik" rel="stylesheet" />
  </head>
  <body
    style="
      background: #e8e9e993;
      padding: 30px 0;
      font-family: "Rubik", sans-serif;
    "
  >
  <div
      style="
        max-width: 600px;
        padding: 15px 30px;
        margin: 0 auto;
        font-size: 14px;
        line-height: 1.5;
        background: #ffffff;
        border-radius: 10px;
        border-top: 10px solid #14b8a6;
      "
    >
    <table style="width: 100%">
          <tr>
            <th colspan="2">Contact Message</th>
          </tr>
          <tr>
            <td>Name</td>
            <td>' .
        $name .
        '</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>' .
        $email .
        '</td>
          </tr>
          <tr>
            <td>Message</td>
            <td>' .
        $message .
        '</td>
          </tr>
          <tr>
            <td>Sent</td>
            <td>' .
        $created .
        '</td>
          </tr>
        </table>
    </div>
    
  </body>
</html>';

      //set content-type header for sending HTML email
      $headers[] = "MIME-Version: 1.0";
      $headers[] = "Content-type: text/html; charset=UTF-8";

      //additional headers
      $headers[] = "From: " . $name . "<" . $email . ">";
      //send email
      mail($to, $subject, $mailContent, implode("\r\n", $headers));
    } else {
      echo "Unable to send your message. Please try again.";
    }
  }
}

if (isset($_POST["token"])) {
  $token = $_POST["token"];
  $sql =
    "SELECT * FROM user_email_verification WHERE email_token = ? LIMIT 0,1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(1, $token);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if (empty($result["pennyTag"])) {
    echo "Wrong email token.";
  } else {
    $query =
      "UPDATE user_email_verification SET status = 'verified' WHERE email_token = ?";

    $stmt = $conn->prepare($query);
    $token = htmlspecialchars(strip_tags($token));

    $stmt->bindParam(1, $token);

    if ($stmt->execute()) {
      echo "Email successfully verified.";
    } else {
      echo "Unable to verify email. Please try again.";
    }
  }
}
?>
