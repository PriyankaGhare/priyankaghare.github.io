<?php

    include('header.php');
    

?>

<div class="form-container">
    <div class="login-form-container">
        <p class="heading-disp-text">Login</p>
        <p class="normal-disp-text">Start Playing Again...</p>
        <!-- Login form -->
        <form action="login.php" method="post">
            <input class="input-field" type="text" name="login_username" placeholder="Username" required><br/><br/>
            <input class="input-field" type="password" name="login_password" placeholder="Password" required><br/><br/>
            
            <button type="submit" name="login" class="form-button">Login</button>

        </form>
    </div>
    <div>
        <img class="divider-line" src="imgs\icons\Line 1.png">
    </div>
    <div class="signup-form-container">
        <p class="heading-disp-text">Sign Up</p>
        <p class="normal-disp-text">Give it a shot, It’s not that bad.</p>
        <!-- Signup form -->
        <form action="register.php" method="post">
            <input class="input-field" type="text" name="sgn_username" placeholder="Username" required><br/><br/>
            <input class="input-field" type="password" name="sgn_password" placeholder="Password" required><br/><br/>
            <input type="submit" class="form-button" value="Register" name="send">
        </form>
    </div>
</div>

<?php
    include('footer.php');
?>
