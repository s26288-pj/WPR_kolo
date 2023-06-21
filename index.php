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
    header("Refresh:0");
}

if (isset($_COOKIE['username'])) {
    echo "<header>Witaj, {$_COOKIE['username']}!";
    echo "<form method='POST' action='cart.php'>
        <input type='submit' name='cart' value='Koszyk'>
        </form>";
    if($_COOKIE['user_type'] == "seller") {
        echo "<form method='GET' action='sell_item.php'>
        <input type='submit' name='sell' value='Sprzedaj'>
        </form>";
    }
    if($_COOKIE['user_type'] == "admin") {
        echo "<form method='POST' action='admin_panel.php'>
        <input type='submit' name='panel' value='Panel'>
        </form>";
    }
    echo "<form method='POST' action=''>
        <input type='submit' name='logout' value='Wyloguj'>
        </form></header>";
}
else {
    echo "<header> <form method='POST' action='register.php'>
        <input type='submit' name='register' value='Zarejestruj się!'>
        </form>";
    echo "<form method='POST' action='login.php'>
        <input type='submit' name='register' value='Logowanie'>
        </form></header>";
}

// Pobranie przedmiotów będących na promocji
$query = "SELECT id, name, price FROM items WHERE status=1";
$result = mysqli_query($connection, $query);

// Pobranie wszystkich przedmiotów
$queryAll = "SELECT id, name, price FROM items";
$allItems = mysqli_query($connection, $queryAll);

// Wyświetlanie przedmiotów promocyjnych
echo "<h1 id='sign'>Promocje:</h1>";
echo "<div class='box_wrapper'>";
while ($items = mysqli_fetch_assoc($result)) {
    echo "<a href='item.php?id={$items['id']}'><div class='box'>{$items['name']}<br>";
    echo "<img src='img/{$items['name']}.png'>";
    echo "<p>{$items['price']}</p></div></a>";
}
echo "</div>";

// Wyświetlanie wszystkich przedmiotów
echo "<h2>Wszystkie przedmioty:</h2>";
echo "<div class='box_wrapper'>";
foreach ($allItems as $item) {
    echo "<a href='item.php?id={$item['id']}'><div class='box'>{$item['name']} <br>";
    echo "<img src='img/{$item['name']}.png'>";
    echo "<center><p>{$item['price']}</p></a></center></div>";
}
echo "</div>";

mysqli_close($connection);
?>

