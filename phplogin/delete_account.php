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

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    $stmt = $con->prepare('DELETE FROM accounts WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        session_destroy(); 
        header('Location: index.html');
    } else {
        echo 'Account deletion failed. Please try again later.';
    }

    $stmt->close();
} else {
    header('Location: index.html');
}
?>