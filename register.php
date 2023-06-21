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
        $email = $_POST['email'];
        $account_type = $_POST['account_type'];
    }

    if (empty($username) || empty($password) || empty($email)) {
        $error = "Wszystkie pola formularza są wymagane.";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Użytkownik o podanej nazwie już istnieje.";
        } else {
            $query = "INSERT INTO users (username, password, email, type) VALUES ('$username', '$password', '$email', '$account_type')";
            $result = mysqli_query($connection, $query);

            if ($result) {
                $success = "Rejestracja zakończona sukcesem. Możesz teraz się zalogować.";
                header('Location: login.php');
            } else {
                $error = "Wystąpił problem podczas rejestracji. Spróbuj ponownie.";
            }
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formularz rejestracji</title>
</head>
<body>
<h2>Rejestracja</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
<div class="input">
    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="email">Adres email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="account_type">Typ konta:</label>
        <select name="account_type" id="account_type">
            <option value="user">Kupujący</option>
            <option value="seller">Sprzedawca</option>
        </select><br><br>

        <input type="submit" value="Zarejestruj" class="input_button">
    </form>
</div>
</body>
</html>
