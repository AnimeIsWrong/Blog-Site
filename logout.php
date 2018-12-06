<?php
    header('refresh:1;url=index.php');
    session_start();
    if(session_destroy()){
        echo '<p>You have been logged out redirecting...</p>';

    } else {
        echo 'Failed to log out please try again later';
    }

?>