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

if (isset($_POST['email'], $_POST['username'], $_POST['password'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $stmt = $con->prepare('SELECT id FROM accounts WHERE email = ? OR username = ?');
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Email or username already in use.';
    } else {
   
        $stmt = $con->prepare('INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $email, $username, $password);
        if ($stmt->execute()) {
            echo 'Registration successful. <a href="index.html">Login</a>';
        } else {
            echo 'Registration failed. Please try again later.';
        }
    }

    $stmt->close();
} else {
    echo 'Please fill in all the fields.';
}
?>