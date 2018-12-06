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
    <?php
        if (!is_null($_GET["ID"])) {
            $db = new mysqli('Address','Username','Password', 'DB Name');
            $sql = sprintf("SELECT * FROM `Posts` WHERE `Author_ID` = %s;", $_GET["ID"]);
            $sql2 = sprintf("SELECT * FROM `Users` WHERE `_id` = %s;", $_GET["ID"]);
            $query = mysqli_query($db, $sql);
            $query2 = mysqli_query($db, $sql2);
            $row = $query2->fetch_assoc();
            ?>
    <div class="container">
        <header>
            <h1><a href="index.php">My Blog</a></h1>
            <p>This is my blog made for college</p>
            <p>Your are viewing the profile of <?php echo $row["username"] ?></p>
        </header>
    </div>
        <div class="wrapper">
        <div class="container">
            <?php
            while ($row = $query->fetch_assoc()) {
                echo sprintf($PostFormat, $row["_id"], $row["PostTitle"], $row["PostDate"]);
            }
            ?>
            </div>
        </div>
            <?php
    } else if (isset($_SESSION['username'])) {
        $db = new mysqli('Address','Username','Password', 'DB Name');
        $sql = sprintf("SELECT * FROM `Posts` WHERE `Author_ID` = %s;", $_SESSION["_id"]);
        $query = mysqli_query($db, $sql);
        ?>
        <div class="container">
        <header>
            <h1>My Blog</h1>
            <p>This is my blog made for college</p>
            <p>This is your profile</p>
        </header>
    </div>
        <div class="wrapper">
        <div class="container">
    <?php
        while ($row = $query->fetch_assoc()) {
            echo sprintf($PostFormat, $row["_id"], $row["PostTitle"], $row["PostDate"]);
        }
        ?>
        </div>
        </div>
        <?php
    } else {
        echo "log in idiot";
    }
    ?>
</body>

</html>

<?php
$db->close();
?>