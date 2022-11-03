<?php
if(isset($_POST['sgn_username']) && isset($_POST['sgn_password']))
{
    $username = $_POST['sgn_username'];
    $password = $_POST['sgn_password'];

    //regex to check username doen't contain any numbers
    if(preg_match('/[0-9]/', $username))
    {
        echo "Username cannot contain numbers";
        echo "<a href='index.php'>Try Again!</a>";
    }
    else
    {

            //check if username already exists in user_db.txt
            $file = fopen("user_db.txt", "r");
            $user_exists = false;
            while(!feof($file))
            {
                $line = fgets($file);
                $line = trim($line);
                $line = explode(",", $line);
                if($line[0] == $username)
                {
                    $user_exists = true;
                    echo "Username already exists";
                    echo "<a href='index.php'>Try Again!</a>";
                    break;
                }
            }
            
            if(!$user_exists)
            {
                $_SESSION['current_username'] = $username;
                //append username and password to file
                $file = fopen("user_db.txt", "a");
                echo "<h3>You are registered successfully!</h3>";
                echo "<a href='index.php'>Login here!</a>";
                fwrite($file, $username . "," . $password . "\n");
                fclose($file);
            }

    }
    
}
else
{
    echo "Username or password is not set";
    echo "<a href='index.php'>Try Again!</a>";
}

?>