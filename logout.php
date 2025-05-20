<?php
    session_start();
    $_SESSION = array();
    print_r($_SESSION);
    if(isset($_COOKIE[session_name()]) == TRUE) {
        setcookie(session_name(),"", time()-0,"/");
    }
    session_destroy();
    header('Location:index.php');
?>