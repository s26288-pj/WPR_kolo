<style>
    <?php include 'style.css'; ?>
</style>

<?php
session_start();

if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit;
}

global $connection;
require 'connection.php';

echo "<header><a href='index.php'>< Strona Główna</a>";
if (isset($_COOKIE['username'])) {
    echo "Witaj, {$_COOKIE['username']}!";
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

if (isset($_POST['add_to_cart'])) {
    $itemId = $_POST['item_id'];

    $query = "INSERT INTO cart (user_id, item_id) VALUES ($_COOKIE[user_id], $itemId)";
    mysqli_query($connection, $query);
}

if (isset($_POST['remove_from_cart'])) {
    $itemId = $_POST['item_id'];

    $query = "DELETE FROM cart WHERE user_id = $_COOKIE[user_id] AND item_id = $itemId";
    mysqli_query($connection, $query);
}

$query = "SELECT items.id, items.name, items.price, quantity FROM cart
          INNER JOIN items ON cart.item_id = items.id
          WHERE cart.user_id = $_COOKIE[user_id]";
$result = mysqli_query($connection, $query);
$itemsInCart = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sum = 0.0;
foreach ($itemsInCart as $item) {
    $sum = $sum + ($item['price']*$item['quantity']);
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Koszyk</title>
</head>
<body>
<h2>Koszyk</h2>
<?php if (empty($itemsInCart)) : ?>
    <p>Koszyk jest pusty.</p>
<?php else : ?>
    <table>
        <tr>
            <th>Tytuł</th>
            <th>Cena</th>
            <th>Ilość</th>
            <th></th>
        </tr>
        <?php foreach ($itemsInCart as $item) : ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
<!--                <td><img src="--><?php //echo $item['image']; ?><!--" alt="--><?php //echo $item['title']; ?><!--" width="100"></td>-->
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <input type="submit" name="remove_from_cart" value="Usuń z koszyka">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th></th>
            <th></th>
            <th>Suma: <?php echo $sum; ?></th>
        </tr>
    </table>
<?php endif; ?>
</body>
</html>
