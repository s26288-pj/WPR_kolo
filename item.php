<style>
    <?php include 'style.css'; ?>
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
session_start();
$itemId = $_GET['id'];

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

$query = "SELECT * FROM items WHERE id=".$itemId;
$result = mysqli_query($connection, $query);
$item = mysqli_fetch_assoc($result);

echo "<header><a href='index.php'>< Strona Główna</a>";
if (isset($_COOKIE['username'])) {
    echo "Witaj, {$_COOKIE['username']}!";
    echo "<form method='POST' action='cart.php'>
        <input type='submit' name='cart' value='Koszyk'>
        </form>";
    echo "<form method='POST' action=''>
        <input type='submit' name='logout' value='Wyloguj'>
        </form></header>";
}
else {
    echo "<form method='POST' action='register.php'>
        <input type='submit' name='register' value='Zarejestruj się!'>
        </form>";
    echo "<form method='POST' action='login.php'>
        <input type='submit' name='register' value='Logowanie'>
        </form></header>";
}

echo "<div id='wrapper'>";
echo "<div id='img_wrapper'><img src='img/{$item['name']}.png'></div>";

echo "<div id='info_container'>";
echo "<h1>{$item['name']}</h1>";
echo "<p id='description'>{$item['description']}</p>";
echo "<p>Cena: {$item['price']}</p>";
echo "<p>Dostępna ilość: {$item['amount']}</p>";
echo "<form method='post' action='add_to_cart.php'>
        <button type='submit' name='addToCart'>Do koszyka</button>
        <input type='hidden' name='cart' value='{$item['id']}'>
      </form>";
echo "</div>";
if(isset($_SESSION['cart_success'])) {
    echo "<div class='info' style='color: green;'>".$_SESSION['cart_success']."</div>";
    unset($_SESSION['cart_success']);
}
if(isset($_SESSION['cart_error'])) {
    echo "<div class='info' style='color: green;'>".$_SESSION['cart_error']."</div>";
    unset($_SESSION['cart_error']);
}
echo "</div>";

// Wyświetlanie ocen i komentarzy
echo "<div id='rating_container'><h2>Oceny i komentarze:</h2>";
$ratingQuery = "SELECT rating, comment FROM rating WHERE item_id=".$itemId;
$rating = mysqli_query($connection, $ratingQuery);
foreach ($rating as $rate) {
    echo "<hr>";
    echo "<p>Ocena: ";
    for ($i=1; $i<=$rate['rating']; $i++) echo "<span class='fa fa-star checked'></span>";
    for ($i=5; $i>$rate['rating']; $i--) echo "<span class='fa fa-star'></span>";
    echo "<p>Komentarz: {$rate['comment']}</p>";
}

if (isset($_COOKIE['username'])) {
    echo "<h2>Dodaj ocenę i komentarz:</h2>";
    if(isset($_SESSION['error'])) {
        echo "<div class='info' style='color: red;'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success'])) {
        echo "<div class='info' style='color: green;'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
    }
    echo "<form method='POST' action='add_rating.php'>";
    echo "<input type='hidden' name='item_id' value='$itemId' />";
    echo "<input type='number' name='rating' min='1' max='5' required placeholder='Ocena'/><br><br>";
    echo "<textarea name='comment' required placeholder='Komentarz'></textarea><br><br>";
    echo "<button type='submit'>Dodaj</button>";
    echo "</form></div>";
}
else {
    echo "</div>";
}

mysqli_close($connection);
?>
