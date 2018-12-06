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
                echo '<p><a href="index.php">Home</a> <a href="account.php">Account</a> <a href="logout.php">Logout</a></p>';
            } else {
                echo '<p> <a href="index.php">Home</a> <a href="login.php">Login</a> <a href="register.php">Register</a></p>';
            }
            ?>
        </header>
    </div>
    <div class="wrapper">
        <div class="container">
        <form action="search.php" method="get">
            <table>
                <tr>
                    <td>
                    <p>Article Title:</p>
                    </td>
                    <td>
                    <p>Article Content:</p>
                    </td>
                    <td>
                    <p>Earliest Date:</p>
                    </td>
                    <td>
                    <p>Latest Date:</p>
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="text" name="TitleSearch">
                    </td>
                    <td>
                    <input type="text" name="ContentSearch">
                    </td>
                    <td>
                    <input type="date" name="FirstDateSearch">
                    </td>
                    <td>
                    <input type="date" name="SecondDateSearch">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if(!empty($_GET)){
            $TitleSearch = $_GET["TitleSearch"];
            $ContentSearch = $_GET["ContentSearch"];
            $FirstDateSearch = $_GET["FirstDateSearch"];
            $SearchDateSearch = $_GET["SecondDateSearch"];
            $sql = "SELECT * FROM `Posts` WHERE ";
            if($TitleSearch != ""){
                $sql.="`PostTitle` LIKE '%" . $TitleSearch . "%' AND ";
            }
            if($ContentSearch != ""){
                $sql.="`PostContent` LIKE '%" . $ContentSearch . "%' AND ";
            }
            if($FirstDateSearch != "" && $SearchDateSearch != ""){
                $sql.="`PostDate` BETWEEN '" . $FirstDateSearch . "' AND '" . $SearchDateSearch . "' ";
            }else if($FirstDateSearch != ""){
                $sql.="`PostDate` >= '" . $FirstDateSearch . "' ";
            }else if($SearchDateSearch != ""){
                $sql.="`PostDate` <= '" . $SearchDateSearch . "' ";
            }
            $sql.=";";
            $sql = str_replace(" AND ;",";",$sql);
            $db = new mysqli('Address','Username','Password', 'DB Name');
            $query = mysqli_query($db, $sql);
            $ResultCount = 0;
            while($row = $query->fetch_assoc()) {
                $PostID = $row["_id"];
                $PostTitle = $row["PostTitle"];
                $PostContent = $row["PostContent"];
                if(strlen($PostContent) > 750)
                echo sprintf($PostFormat,$PostID, $PostTitle, substr($PostContent, 0, strpos(wordwrap($PostContent, 750), "\n")));
                else
                echo sprintf($PostFormat,$PostID, $PostTitle, $PostContent);
                ++$ResultCount;
            }
            if($ResultCount == 0){
                ?>
                <p>No Results found</p>
                <?php
            }
    }
        ?>
        </div>
    </div>
</body>

</html>