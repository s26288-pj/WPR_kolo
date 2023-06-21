<style>
    <?php include 'style.css'; ?>
</style>

<?php
session_start();
global $connection;
require 'connection.php';

echo "<header><a href='index.php'>< Strona Główna</a></header>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }

    // Sprawdzenie danych logowania
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        setcookie("username", $user['username'], time() + 3600);
        setcookie("user_type", $user['type'], time() + 3600);
        setcookie("user_id", $user['id'], time() + 3600);

        header('Location: index.php');
        exit;
    } else {
        // Błędne dane logowanie
        $error = "Błędne dane logowania. Spróbuj ponownie.";
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formularz logowania</title>
</head>
<body>
<h2>Logowanie</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<div class="input">
    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Zaloguj" class="input_button">
    </form>
</div>
</body>
</html>

