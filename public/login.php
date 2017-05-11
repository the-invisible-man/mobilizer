<?php

// Include the connection.php file, this defined as variable called
// $connection which holds our connection to the database.
require_once 'connection.php';

// The $_POST array will contain the form data that comes in the request
if (array_key_exists('email', $_POST)) {

    // Hash the user entered password with the md5 digest algorithm
    $userEnteredPassword = md5($_POST['password']);

    // Escape any special characters, protect from sql injections.
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // It's not safe to user the $email variable in the query
    $sql    = "SELECT * FROM `users`
               WHERE email = '{$email}'
               ";

    // Query database by passing the connection and the query.
    $result = mysqli_query($connection, $sql);

    // Plugin the $result resource into this function to check how rows the query returned
    $total  = mysqli_num_rows($result);

    if ($total === 0) {
        die("No such user exists");
    }

    // Pop the first column out of the $result and into $user.
    $user   = mysqli_fetch_assoc($result);

    // Check if the md5 of the user entered password is the same as the
    // md5 of the real password.
    if ($user['password'] != $userEnteredPassword) {
        die("That password is incorrect");
    }

    // Proceed
    die("Welcome back {$user['first_name']}");
}


?>

<!DOCTYPE html>
    <head>
        <title>Login</title>
    </head>

    <body>

        <h1>Login:</h1>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="text" name="email"><br>

            <label for="password">Password:</label>
            <input type="password" name="password"><br>

            <input type="submit" value="Login">
        </form>

    </body>