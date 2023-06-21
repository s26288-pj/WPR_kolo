<?php

if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit;
}

session_start();

global $connection;
require 'connection.php';

if (isset($_POST['cart'])) {
    $itemId = $_POST['cart'];

    $check = "SELECT * FROM cart WHERE item_id=$itemId";
    $check_result = mysqli_query($connection, $check);

    if(mysqli_num_rows($check_result) > 0) {
        $item = mysqli_fetch_assoc($check_result);
        $quantity = $item['quantity']+1;
        $query = "UPDATE cart SET quantity=$quantity WHERE item_id=$itemId";
        $result = mysqli_query($connection, $query);
    }
    else {
        $query = "INSERT INTO cart (user_id, item_id, quantity) VALUES ($_COOKIE[user_id], $itemId, 1)";
        $result = mysqli_query($connection, $query);
    }

    if($result)  {
        $_SESSION['cart_success'] = "Przedmiot dodany do koszyka.";
        header('Location: item.php?id='.$_POST['cart']);
    } else {
        $_SESSION['cart_error'] = "Wystąpił problem. Spróbuj ponownie.";
        header('Location: item.php?id='.$_POST['cart']);
    }
}

mysqli_close($connection);
?>
