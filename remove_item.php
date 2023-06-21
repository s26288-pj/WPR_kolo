<?php
session_start();

global $connection;
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];

    $query = "DELETE FROM items WHERE id = $itemId";
    $result = mysqli_query($connection, $query);

    header('Location: admin_panel.php');
}
else {
    header('Location: index.php');
}

mysqli_close($connection);
?>