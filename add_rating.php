<?php
session_start();

global $connection;
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if (empty($itemId) || empty($rating)) {
        $error = "Wszystkie pola formularza są wymagane.";
        header('Location: item.php?id='.$_POST['item_id']);
    } else {
        $userId = $_COOKIE["user_id"];

        $query = "SELECT * FROM rating WHERE item_id = $itemId AND user_id = $userId";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Już oceniłeś ten przedmiot.";
            header('Location: item.php?id='.$_POST['item_id']);

        } else {
            $query = "INSERT INTO rating (item_id, user_id, rating, comment) VALUES ($itemId, $userId, $rating, '$comment')";
            $result = mysqli_query($connection, $query);

            if ($result) {
                $_SESSION['success'] = "Ocena została dodana.";
                header('Location: item.php?id='.$_POST['item_id']);
            } else {
                $_SESSION['error'] = "Wystąpił problem podczas dodawania oceny. Spróbuj ponownie.";
                header('Location: item.php?id='.$_POST['item_id']);
            }
        }
    }
}

mysqli_close($connection);
?>

