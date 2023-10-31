<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin2';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_POST['email_or_username'], $_POST['password'])) {
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];

    // Check if the input matches either email or username
    $stmt = $con->prepare('SELECT id, username, password FROM accounts WHERE email = ? OR username = ?');
    $stmt->bind_param('ss', $email_or_username, $email_or_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
            echo 'Incorrect email/username or password.';
        }
    } else {
        echo 'Incorrect email/username or password.';
    }

    $stmt->close();
} else {
    echo 'Please fill in all the fields.';
}
?>