<style>
    <?php include 'style.css'; ?>
</style>

<?php
session_start();
global $connection;
require 'connection.php';

if (isset($_COOKIE['username'])) {
    echo "<header><a href='index.php'>< Strona Główna</a>";
    echo "Witaj, {$_COOKIE['username']}!";
    echo "<form method='POST' action='cart.php'>
        <input type='submit' name='cart' value='Koszyk'>
        </form>";
    echo "<form method='POST' action=''>
        <input type='submit' name='logout' value='Wyloguj'>
        </form></header>";
}
else {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $add_query = "INSERT INTO items(name, price, description, amount, category, status) VALUES ('$name', $price, '$description', $amount, $category, $status)";
    $exec = mysqli_query($connection, $add_query);
    $check = "SELECT * FROM items WHERE name='$name'";
    $item = mysqli_query($connection, $check);

    if ($item) {
        $success = "Poprawnie dodano przedmiot.";
        $item_id = mysqli_fetch_assoc($item);
        header('Location: item.php?id='.$item_id['id']);
    } else {
        $error = "Wystąpił problem podczas rejestracji. Spróbuj ponownie.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sprzedaj przedmiot</title>
</head>
<body>
<h2>Sprzedaj</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

<div class="input">
    <form method="POST" action="">
        <label for="name">Nazwa przedmiotu:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="price">Cena:</label>
        <input type="text" id="price" name="price" required><br>

        <label for="description">Opis:</label>
        <input type="textarea" id="description" name="description" required><br>

        <label for="amount">Ilość:</label>
        <input type="number" id="amount" name="amount" required><br>

        <label for="category">Kategoria:</label>
        <select name="category" id="category">
            <option value="1">Laptopy i komputery</option>
            <option value="2">Smartfony</option>
            <option value="3">Inne</option>
        </select><br>

        <label for="status">Promocja:</label>
        <select name="status" id="status">
            <option value="1">Tak</option>
            <option value="0">Nie</option>
        </select><br>

        <input type="submit" value="Sprzedaj" class="input_button">
    </form>
</div>
</body>
</html>
