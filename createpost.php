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
            <h1>Create a Post.</h1>
            <p>This is my blog made for college</p>
    <?php
    if (!isset($_SESSION['username'])) {
        ?>
        <div class="container">
            <header>
                <h1>You need to be logged in to create a post</h1>
                <br>
                <p>click
                    <a href="login.php">here</a> to login</p>
            </header>
        </div>
        <?php
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $posttitle = $_POST["title"];
        $postcontent = $_POST["message"];
        $postdate = date("Y-m-d");
        $postAuthorID = $_SESSION["_id"];

        $db = new mysqli('Address','Username','Password', 'DB Name');

        if (mysqli_connect_errno($db)) {
            echo '<p>Failed to connect to Server</p>';
            echo '<p>Error code: ' . mysqli_connect_errno() . '</p>';
            echo '<p>Error Message: ' . mysqli_connect_error() . '</p>';
            exit;
        }

        $sql = sprintf(
            "INSERT INTO `Posts`(`PostTitle`, `PostContent`, `PostDate`, `Author_ID`) VALUES ('%s','%s','%s','%s');",
            $posttitle,
            $postcontent,
            $postdate,
            $postAuthorID
        );

        $query = mysqli_query($db, $sql);

        if ($query) {

            ?>
            <p><a href="index.php">Home</a> <a href="viewpost.php?ID=<?php echo $db->insert_id?>">View Post</a></p>
            </header>
        </div>
        <div class="container">
            <table>
                <tr>
                    <td colspawn="2">
                        <h1>Created post with following content</h1>
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
                        Date:
                    </td>
                    <td>
                        <?php
                        echo $postdate
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

                        echo $db->insert_id;
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
} else {
    ?>
                </header>
        </div>
                <div class="wrapper">
                    <div class="container">
                        <form action="createpost.php" method="post">
                            <input type="text" name="title" placeholder="Post title..." maxlength="255">
                            <textarea name="message" rows="12" cols="120"></textarea>
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