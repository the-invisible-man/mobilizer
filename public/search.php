<?php

// Include the connection.php file, this defined as variable called
// $connection which holds our connection to the database.
require_once 'connection.php';

// Check that 'email' is present in the request data. Because we are doing a
// 'get' request then the data for the request will be found inside $_GET.
if (array_key_exists('email', $_GET))
{
    $sql        = "SELECT * FROM `users` WHERE `email` = \"{$_GET['email']}\"";
    $results    = mysqli_query($connection, $sql);
    $totalRes   = mysqli_num_rows($results);

    if ($totalRes === 0) {
        echo "We could not find any matches for email: {$_GET['email']}";
    } else {
        $data = mysqli_fetch_assoc($results);

        echo "Found match for {$_GET['email']}: <br><br>";
        echo "First Name: " . $data['first_name'] . "<br>";
        echo "Last Name:  " . $data['last_name'] . "<br><br>";
    }
}

?>

<!DOCTYPE html>
<head>
    <title>My Website</title>
</head>

<body>
    <form method="GET" action="/test.php">
        <label for="email">Search a user by email: </label>
        <input type="text" name="email"/>
        <button type="submit">Search User</button>
    </form>
</body>
