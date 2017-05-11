<?php

// Include the connection.php file, this defined as variable called
// $connection which holds our connection to the database.
require_once 'connection.php';

// Check if first_name is in the request, we will assume that if first_name is present
// then everything is present. In a real world scenario one would validate that all the
// required fields are present.
if (array_key_exists('first_name', $_POST)) {

    // Hash the user entered password with the md5 digest algorithm
    $password = md5($_POST['password']);

    // Construct query using the user entered data
    $sql = "INSERT INTO `users` (first_name, last_name, email, password)
            VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '{$password}')";

    // Execute query in database
    $result = mysqli_query($connection, $sql);

    // If the result is true the query executed with no problem.
    if ($result) {
        echo "User created successfully!";
    } else {
        // Failure otherwise.
        echo "Failed to create user";
    }

    die();
}

?>



<!DOCTYPE html>
    <head>
        <title>Sign Up</title>
    </head>
    <body>
        <h1>Sign Up For Our Website</h1>

        <form method="POST" action="signup.php">
            <label for="name">First Name:</label>
            <input type="text" name="first_name"><br><br>

            <label for="name">Last Name:</label>
            <input type="text" name="last_name"><br><br>

            <label for="email">Email:</label>
            <input type="text" name="email"><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password"><br>

            <input type="submit" value="Sign Up!">
        </form>
    </body>