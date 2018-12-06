<?php
    session_start();
    $PostFormat = '<div class="article-box"><div class="inner-box"><h1>%s</h1><p><a href="account.php?ID=%s">By %s</a></p><p>%s</p></div></div>';
    $CommentFormat= '<div class="article-box"><div class="inner-box"><h1><a href="account.php?ID=%s">%s</a> Said:</h1><p>%s</p></div></div>';
    $db = new mysqli('Address','Username','Password', 'DB Name');
    if (mysqli_connect_errno($db)) {
        echo '<p>Failed to connect to Server</p>';
        echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
        echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
        exit;
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST["type"] == "deletecomment"){
            $query = mysqli_query($db, "DELETE FROM `Comments` WHERE `Comment_ID` = '" . $_POST["commentID"] . "';");
            header('refresh:1;url=viewpost.php?ID=' . $_GET["ID"]);
            echo "comment deleted";
            exit;
        }
        if($_POST["type"] == "comment"){
            $Comment = $_POST["comment"];
            $query = mysqli_query($db,
            sprintf("INSERT INTO `Comments`(`CommentText`, `Commentor_ID`, `Article_ID`) VALUES ('%s','%s','%s');",
            $Comment,
            $_SESSION["_id"],
            $_GET["ID"]));
            if($query){
                header('refresh:1;url=viewpost.php?ID=' . $_GET["ID"]);
                echo "comment posted succesfully";
                exit;
            }
        } else {
            $ID = $_GET["ID"];
            $Action = $_POST["action"];

            if($Action == "delete"){
                $sql .= sprintf("DELETE FROM `Posts` WHERE `Posts`.`_id` = '%s';",$ID);
                $sql .= sprintf("DELETE FROM `Comments` WHERE `Article_ID` = '%s';",$ID);
                $query = mysqli_multi_query($db,$sql);
                if($query){
                    header('refresh:5;url=index.php');
                    echo "page deleted";
                    exit;
                }
            }else if($Action == "edit"){
                header('refresh:1;url=editpost.php?ID=' . $ID);
                exit;
            }
    }
    } else if(!isset($_GET["ID"])){
        header('refresh:1;url=index.php');
    } else{
        $query = mysqli_query($db, "SELECT * FROM `Posts` WHERE `_id` = '" . $_GET["ID"] . "';");
        $count = mysqli_num_rows($query);
        $row = $query->fetch_assoc();
        $PostTitle = $row["PostTitle"];
        $PostContent = $row["PostContent"];
        $query = mysqli_query($db, "SELECT * FROM `Users` WHERE `_id` = " . $row["Author_ID"] . ";");
        $row = $query->fetch_assoc();
        $PostAuthorID = $row["_id"];
        $PostAuthorName = $row["username"];
        $ID = $_GET["ID"];
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
            <h1>My Blog</h1>
            <p>This is my blog made for college</p>
            <?php
                if(isset($_SESSION['username'])){
                    echo '<p>Welcome back ' . $_SESSION['username'] . '</p>';
                    echo '<p><a href="index.php">Home</a> <a href="account.php">Account</a> <a href="logout.php">Logout</a></p>';
                } else {
                    echo '<p><a href="login.php">Login</a> <a href="register.php">Register</a></p>';
                }
                if($_SESSION["auth"] == "ADMIN" || $_SESSION["_id"] == $PostAuthorID){?>
                    <form action="viewpost.php?ID=<?php echo $ID; ?>" method="post">
                        <select name="action">
                            <option value="delete">Delete</option>
                            <option value="edit">Edit</option>
                        </select>
                        <br>
                        <input type="submit" value="Submit">
                </form>
                <?php
                }
            ?>
        </header>
    </div>
    <?php
            if($count == 1){
    ?>
    <div class="wrapper">
        <div class="container">
        <?php
            echo sprintf($PostFormat, $PostTitle, $PostAuthorID, $PostAuthorName, $PostContent);
        ?>
        </div>
    </div>
    <?php if(isset($_SESSION["username"])){?>
        <div class="container">
            <header>
            <form action="viewpost.php?ID=<?php echo $ID; ?>" method="post">
                <input type="hidden" name="type" value="comment">
                <input type="text" name="comment">
                <input type="submit" value="Submit">
            </form>
            </header>
        </div>
    <?php }?>
        <div class="container">
            <header>
                <h1>Comments:</h1>
            </header>
        </div>
    <div class="wrapper">
        <div class="container">
        <?php
            $query = mysqli_query($db, "SELECT * FROM `Comments` WHERE `Article_ID` = '" . $ID . "' ORDER BY `Comment_ID` DESC;");
            while($row = $query->fetch_assoc()){
                $namequery = mysqli_query($db, "SELECT `username` FROM `Users` WHERE `_id` = '" . $row["Commentor_ID"] . "';");
                ?>
                <div class="article-box">
                    <div class="inner-box">
                        <h1><a href="account.php?ID=<?php echo $row["Commentor_ID"]; ?>"><?php echo $namequery->fetch_assoc()["username"]; ?></a> Said:</h1>
                        <p><?php echo $row["CommentText"]; ?></p>
                        <?php
                        if($_SESSION["auth"] == "ADMIN"){
                        ?>
                        <form action="viewpost.php?ID=<?php echo $ID; ?>" method="post">
                        <input type="hidden" name="type" value="deletecomment">
                        <input type="hidden" name="commentID" value="<?php echo $row["Comment_ID"];?>">
                        <input type="submit" value="Delete Comment">
                    </form>
                    <?php
                        }
                    ?>
                    </div>
                </div>
            <?php
                //echo sprintf($CommentFormat, $row["Commentor_ID"], $namequery->fetch_assoc()["username"], $row["CommentText"]);
            }
        ?>
        </div>
    </div>
    <?php
        } else {
            ?>
    <div class="container">
        <header>
            <h1>ERROR 404: Article not found.</h1>
        </header>
        </div>
    <?php
        }
    ?>
</body>

</html>
<?php
    $db->close();
?>