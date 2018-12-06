<?php
session_start();
$PostFormat = '<div class="article-box"><div class="inner-box"><h1><a href="viewpost.php?ID=%s">%s</a></h1><p>%s</p></div></div>';
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
            <h1>My Blog</h1>
            <p>This is my blog made for college</p>
            <?php
            if (isset($_SESSION['username'])) {
                echo '<p>Welcome back ' . $_SESSION['username'] . '</p>';
                if($_SESSION["auth"] == "JOURNOLIST" || $_SESSION["auth"] == "ADMIN")
                echo '<p><a href="search.php">Search</a> <a href="account.php">Account</a> <a href="createpost.php">Create post</a> <a href="logout.php">Logout</a></p>';
                else
                echo '<p><a href="search.php">Search</a> <a href="account.php">Account</a> <a href="logout.php">Logout</a></p>';
            } else {
                echo '<p><a href="search.php">Search</a> <a href="login.php">Login</a> <a href="register.php">Register</a></p>';
            }
            ?>
        </header>
    </div>

    <div class="wrapper">
        <div class="container">
        <?php
            $db = new mysqli('Address','Username','Password', 'DB Name');
            $query = mysqli_query($db, "SELECT * FROM `Posts` ORDER BY `_id` DESC");
            while($row = $query->fetch_assoc()) {
                $PostID = $row["_id"];
                $PostTitle = $row["PostTitle"];
                $PostContent = $row["PostContent"];
                if(strlen($PostContent) > 750)
                echo sprintf($PostFormat,$PostID, $PostTitle, substr($PostContent, 0, strpos(wordwrap($PostContent, 750), "\n")));
                else
                echo sprintf($PostFormat,$PostID, $PostTitle, $PostContent);
        }
        ?>
        </div>
    </div>
</body>

</html>