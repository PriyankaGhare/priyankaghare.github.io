<?php

if (isset($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] =  trim(addslashes($value));
    }
}
    $current_user = $_POST["login_username"];
    
    $password = $_POST["login_password"];

    $file_content = file_get_contents("user_db.txt");
    $pattern = preg_quote($current_user, '/');
    $pattern_pwd = preg_quote($password, '/');

    $pattern = "/^.*$pattern.*\$/m";
    $pattern_pwd = "/^.*$pattern_pwd.*\$/m";

    $current_user_profile = array();

    if(preg_match_all($pattern, $file_content, $matches))
    {
        $found_match = implode("\n", $matches[0]);
        $found_match = explode(",", $found_match);
        $login = false;
        for($i = 0; $i < count($found_match); $i++)
        {
            $current_user_profile[$i] = $found_match[$i];
            if(($current_user_profile[$i] == $found_match[$i]))
            {
                $login = true;
                break;
            }
            else
            {
                $login = false;
            }
        }
        if($login)
        {
            session_start();
            $_SESSION['username'] = $current_user;
            $_SESSION['password'] = $password;
            header('Location: main.php?username='.$current_user);
        }
        else
        {
            echo "<h3 style='color: red;'>Username or password in incorrect!</h3>";
            echo "<a href='index.php'>Try Again!</a>";
            return;
        }
    }
    else
    {
        echo "<h3 style='color: red;'>No user found!</h3>";
        echo "<a href='index.php'>Try Again!</a>";
        return;
    }
?>