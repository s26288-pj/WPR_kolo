<style>
    <?php include 'style.css'; ?>
</style>

<?php
session_start();
global $connection;
require 'connection.php';

if (isset($_POST['logout'])) {
    setcookie('username', "", time()-3600);
    setcookie('user_id', "", time()-3600);
    setcookie('user_type', "", time()-3600);
    session_unset();
    session_destroy();
    header('Location: index.php');
}

if (isset($_COOKIE['username'])) {
    if ($_COOKIE['user_type'] == "admin") {
        echo "<header><a href='index.php'>< Strona Główna</a>";
        echo "Witaj, {$_COOKIE['username']}!";
        echo "<form method='POST' action='cart.php'>
            <input type='submit' name='cart' value='Koszyk'>
            </form>";
        echo "<form method='POST' action=''>
            <input type='submit' name='logout' value='Wyloguj'>
            </form></header>";
    } else {
        header('Location: index.php');
    }
}

// Pobranie wszystkich przedmiotów
$queryAll = "SELECT id, name, price FROM items";
$allItems = mysqli_query($connection, $queryAll);

// Wyświetlanie wszystkich przedmiotów
echo "<div class='box_wrapper'>";
foreach ($allItems as $item) {
    echo "<a href='item.php?id={$item['id']}'><div class='box'>{$item['name']} <br>";
    echo "<img src='img/{$item['name']}.png'>";
    echo "<center><p>{$item['price']}</p></a></center>";
    echo "<form action='remove_item.php' method='POST'>
            <input type='submit' value='Usuń'>
            <input type='hidden' value='{$item['id']}' name='item_id'>
          </form></div>";
}
echo "</div>";

mysqli_close($connection);
?>