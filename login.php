<?php
    session_start();
    if(isset($_SESSION['username'])){
        header('refresh:1;url=index.php');
        echo "you are already logged in redirecting...";
        exit;
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Username = $_POST['username'];
        $Password = $_POST['password'];

        
        $db = new mysqli('Address','Username','Password', 'DB Name');

        if(mysqli_connect_errno($db)){
            echo '<p>Failed to connect to Server</p>';
            echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
            echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
            exit;
        }

        

        $query = mysqli_query($db,"SELECT * FROM `Users` WHERE `username` = '" . $Username . "' AND `password` = '" . hash("sha256",$Password) . "';");
        $count = mysqli_num_rows($query);
        if(!is_null($query)){
            if($count == 1){
                $row = $query->fetch_assoc();
                $_SESSION['username'] = $row["username"];
                $_SESSION['email'] = $row["email"];
                $_SESSION['_id'] = $row["_id"];
                $_SESSION["auth"] = $row["Authority"];
                header('refresh:1;url=index.php');
                echo "you logged in successfully as " . $_SESSION['username'];
                exit;
            } else {
                echo "you didn't log in";
            }
    }
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
        <h1>Login</h1>
        <p><a href="index.php">home</a></p>
    </header>
</div>
<div class="container">
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="submit" value="Sumbit">
    </form>
</div>
</body>

</html>
<?php
    $db->close();
?>