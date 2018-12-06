<?php
session_start();
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
            <h1>Edit Post.</h1>
            <p>This is my blog made for college</p>
    <?php
    if (!isset($_SESSION['username'])) {
        ?>
        <div class="container">
            <header>
                <h1>You need to be logged in to edit a post</h1>
                <br>
                <p>click
                    <a href="login.php">here</a> to login</p>
            </header>
        </div>
        <?php
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $db = new mysqli('Address','Username','Password', 'DB Name');

        if (mysqli_connect_errno($db)) {
            echo '<p>Failed to connect to Server</p>';
            echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
            echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
            exit;
        }

        $query = mysqli_query($db,
        sprintf("UPDATE `Posts` SET `PostTitle`='%s',`PostContent`='%s' WHERE `_id`= '%s';",
        $_POST["title"],
        $_POST["message"],
        $_GET["ID"]));

        if(!isset($_POST["ID"])){
        $posttitle = $_POST["title"];
        $postcontent = $_POST["message"];
        $postAuthorID = $_SESSION["_id"];

        if ($query) {

            ?>
            <p><a href="index.php">Home</a> <a href="viewpost.php?ID=<?php echo $_GET["ID"]?>">View Post</a></p>
            </header>
        </div>
        <div class="container">
            <table>
                <tr>
                    <td colspawn="2">
                        <h1>Editted post with following content</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        Title:
                    </td>
                    <td>
                        <?php
                        echo $posttitle
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Content:
                    </td>
                    <td>
                        <?php
                        echo $postcontent
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Your ID:
                    </td>
                    <td>
                        <?php
                        echo $postAuthorID
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Post ID:
                    </td>
                    <td>
                        <?php

                        echo $_GET["ID"];
                        ?>
                    </td>
                </tr>
            </table>
        </div>

    <?php

} else {
    ?>
    <div class="container">
        <h1>Failed to create post</h1>
    </div>
    <?php
}
        }
} else {
    $db = new mysqli('Address','Username','Password', 'DB Name');

    if (mysqli_connect_errno($db)) {
        echo '<p>Failed to connect to Server</p>';
        echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
        echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
        exit;
    }

    $query = mysqli_query($db, sprintf("SELECT * FROM `Posts` WHERE `_id`= '%s';", $_GET["ID"]));
    $row = $query->fetch_assoc();
    $posttitle = $row["PostTitle"];
    $postcontent = $row["PostContent"];
    ?>
            </header>
        </div>
                <div class="wrapper">
                    <div class="container">
                        <form action="editpost.php?ID=<?php echo $_GET["ID"];?>" method="post">
                            <input type="text" name="title" placeholder="Post title..." value="<?php echo $posttitle ?>" maxlength="255">
                            <textarea name="message" rows="12" cols="120"><?php echo $postcontent ?></textarea>
                            <br>
                            <input type="submit" value="Submit">
                        </form>
                    </div>
                </div>
                <?php

            }
            ?>
</body>


</html>
<?php
$db->close();
?>