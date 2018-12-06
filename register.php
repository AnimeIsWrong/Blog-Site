<?php
session_start();
if(isset($_SESSION['username'])){
    header('refresh:1;url=index.php');
    echo "you are already logged in redirecting...";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $isinputvalid = true;

    if ($username == "" || $password == "" || $email == "") {
        $isinputvalid = false;
    } else {

        $db = new mysqli('Address','Username','Password', 'DB Name');

        if (mysqli_connect_errno($db)) {
            echo '<p>Failed to connect to Server</p>';
            echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
            echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
            exit;
        }
    }
    if ($isinputvalid)
        if (mysqli_query($db, "SELECT 1 FROM `Users` WHERE `username` = `" . $username . "`;")) {
        echo "username already taken";
        $isinputvalid = false;
    }
    if (mysqli_query($db, "SELECT 1 FROM `Users` WHERE `email` = `" . $email . "`;")) {
        echo "email already taken";
        $isinputvalid = false;
    }

    if ($isinputvalid) {
        $sql = sprintf("INSERT INTO `Users`(`username`, `email`, `password`) VALUES ('%s','%s','%s');", $username, $email, hash("sha256", $password));
        $query = mysqli_query($db, $sql);
        if ($query) {
            $query = mysqli_query($db, sprintf("SELECT * FROM `Users` WHERE `_id` = %s", $db->insert_id));
            $row = $query->fetch_assoc();
            header('refresh:1;url=index.php');
            $_SESSION['username'] = $row["username"];
            $_SESSION['email'] = $row["email"];
            $_SESSION['_id'] = $row["_id"];
            $_SESSION["auth"] = $row["Authority"];;
            echo "account created";
        }
    }
}
if (isset($_SESSION['username'])) {
    echo '<p><a href="index.php">home</a></p>';
    echo '<p>You are already logged in as ' . $_SESSION['username'];
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="My Blog">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="Matthew Belbin">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Blog</title>

    <link rel="stylesheet" href="css/Style.css">

</head>
<body>
<div class="container">
    <header>
        <h1>Register</h1>
        <p><a href="index.php">home</a></p>
    </header>
</div>
<div class="container">
<form action="register.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['username'] == "") {
        ?>
    <div class="error">* Required Field</div>
    <?php
}
?>
    <br>
    <input type="password" name="password" placeholder="Password">
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] == "") {
        ?>
    <div class="error">* Required Field</div>
    <?php
}
?>
    <br>
    <input type="email" name="email" placeholder="Email">
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['email'] == "") {
        ?>
    <div class="error">* Required Field</div>
    <?php
}
?>
    <br>
    <input type="submit" value="Sumbit">
</form>
</div>
</body>
</html>

<?php
$db->close();
?>