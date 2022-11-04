<?php

    include('header.php');
    session_start();
    if($_SESSION['username'] == null)
    {
        header('Location: index.php');
    }

    $current_user = $_GET['username'];

    if(isset($_POST['logout']))
    {
        logoutGame();
    }

    function logoutGame(){
        header('Location: index.php');
        session_destroy();
        session_start();
    }
?>
<form method="post">
<button class="logout-btn" type="submit" name="logout"><img class="logout-btn-icn" src="imgs/icons/logout.png"/></button>
</form>
<div class="form-container">
    
    <p class="another">Choose Difficulty</p>
    
    <table cellpadding="20" cellspacing="20" class="grid-diff-btns">
        <tr>
            <td ><a href="updated_game.php?difficulty=1&username=<?php echo $current_user;?>"><img class="cell-btn" src="imgs/icons/difficulty_buttons/diff1.png" alt="Easy" /></a></td>
            <td ><a href="updated_game.php?difficulty=2&username=<?php echo $current_user;?>"><img class="cell-btn" src="imgs/icons/difficulty_buttons/diff2.png" alt="Medium" /></a></td>
            <td ><a href="updated_game.php?difficulty=3&username=<?php echo $current_user;?>"><img class="cell-btn" src="imgs/icons/difficulty_buttons/diff3.png" alt="Hard" /></a></td>
            <td ><a href="updated_game.php?difficulty=4&username=<?php echo $current_user;?>"><img class="cell-btn" src="imgs/icons/difficulty_buttons/diff4.png" alt="Insane" /></a></td>
        </tr>
    </table>
</div>
<div class="game-instruction">
    <!-- Hangman game instruction -->
    <p class="instruction-heading"><u>How to play:</u></p>
    <ul class="instruction-list">
        <li>Guess the letters in the secret word to solve the puzzle.
        <li>You can guess a letter by clicking on in-game keypad. 
        <li>You have 6 chances to guess the word. If you guess the word correctly, you win. 
        <li>If you run out of chances, you lose.
    </ul>
</div>
<?php
    include('footer.php');

?>