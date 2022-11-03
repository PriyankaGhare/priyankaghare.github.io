<?php

    // if($_SESSION['current_username'] == null)
    // {
    //     header("Location: index.php");
    // }
    //header file, this is header of the website and it remains constant.
    include('header.php');
    //start the current session
    session_start();
    //check for difficulty level
    if(isset($_GET['difficulty']))
    {
        $_SESSION['difficulty'] = $_GET['difficulty'];
    }
    else
    {
        $_SESSION['difficulty'] = 1;
    }
    //all session,global variables and superglobal variables are declared here
    $GLOBALS['score'] = 0;
    $total_chances = 5;
    $chances = 0;
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $WON = false;
    $diffLevel = "";
    if(empty($_SESSION['hint']))
        $current_hint = "";
    else
        $current_hint = $_SESSION['hint'];
    static $hint = "";
    if(!empty($_GET['username']))
        $_SESSION['username'] = $_GET['username'];
    

    // temp variables for testing
    
    $guess = "HANGMAN";
    $maxLetters = strlen($guess) - 1;
    $responses = ["H","G","A"];
    
    
    // Live variables here
    
    
    // ALl the body parts
    $bodyParts = ["all","four","three","two","one","zero"];
    
    
    // Random words for the game and you to guess
    switch($_SESSION['difficulty'])
    {
        //difficulty switch statement 
        // case 1-newbie collection
        // case 2-rookie collection
        // case 3-normal collection
        // case 4-hard collection
        //words[] and hints[] should be always same length and in same order
        //difficulty level text is also assigned here
        case 1:
            $words = [
                "HAND", "BANANA" , "BEACH", "PARIS", "SANDWICH", 
                 "HANGMAN", "GYM", "TIGER", "SPRING", "NEWYORK", "TENNIS", "DEER",
                 "WINTER","DOCTOR","CAKE"
             ];
             $hints = [
                "BODY PART", "FRUIT" , "ALL ABOUT SEA", "PLACES", "EAT AND DRINK",
                 "GAME YOU ARE PLAYING", "HEALTH", "ANIMALS", "SEASON", "CITY", "SPORTS", "ANIMALS",
                 "SEASON","PROFESSION","BAKING"
             ];
             $diffLevel = "Newbie";
            break;
        case 2:
            $words = [
                "MANHANG", "FLYBUTTER" , "LAPPLE", "SERIOUSLY", "COPYCAT",
                 "TYPICAL", "BEAUTIFUL"
             ];
             $hints = [
                "MANHANG", "FLYBUTTER" , "LAPPLE", "SERIOUSLY", "COPYCAT",
                 "TYPICAL", "BEAUTIFUL"
             ];
             $diffLevel = "Rookie";
            break;
        case 3:
            $words = [
                "MEDIUM", "REGISTRAR" , "BANANA", "ORANGE", "KINGDOM",
                 "TALENT", "HANDSOME"
             ];
             $hints = [
                "MEDIUM", "REGISTRAR" , "BANANA", "ORANGE", "KINGDOM",
                 "TALENT", "HANDSOME"
             ];
             $diffLevel = "Normal";
            break;
        case 4:
            $words = [
                "BATMAN", "PENGUIN" , "APPLE", "XING", "FINALLY",
                 "METICULOUS", "MERICFUL"
             ];
             $hints = [
                "BATMAN", "PENGUIN" , "APPLE", "XING", "FINALLY",
                 "METICULOUS", "MERICFUL"
             ];
             $diffLevel = "Hard";
            break;

    }
    
    
    
    //get current picture from storage
    function getCurrentPicture($part){
        return "./images/". $part. ".png";
    }
    
    
    function startGame(){
       
    }
    
    // restart the game. Clear the session variables
    function restartGame(){
        header('Location: main.php?username='.$_SESSION['username']);
        session_destroy();
        session_start();
        
    }
    
    // Get all the hangman Parts
    function getParts(){
        global $bodyParts;
        return isset($_SESSION["parts"]) ? $_SESSION["parts"] : $bodyParts;
    }
    
    // add part to the Hangman
    function addPart(){
        $parts = getParts();
        array_shift($parts);
        $_SESSION["parts"] = $parts;
    }
    
    // get Current Hangman Body part
    function getCurrentPart(){
        $parts = getParts();
        return $parts[0];
    }
    
    // get the current words
    function getCurrentWord(){
      //  return "HANGMAN"; // for now testing
        global $words;
        global $hints;
        global $current_hint;
        if(!isset($_SESSION["word"]) && empty($_SESSION["word"])){
            $key = array_rand($words);
            
            $_SESSION["word"] = $words[$key];
            $_SESSION["hint"] = $hints[$key];
            $current_hint = $hints[$key];
        }
        return $_SESSION["word"];
    }
    
    
    // user responses logic
    
    // get user response
    function getCurrentResponses(){
        return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
    }
    
    function addResponse($letter){
        $responses = getCurrentResponses();
        array_push($responses, $letter);
        $_SESSION["responses"] = $responses;
    }
    
    // check if pressed letter is correct
    function isLetterCorrect($letter){
        $word = getCurrentWord();
        $max = strlen($word) - 1;
        for($i=0; $i<= $max; $i++){
            if($letter == $word[$i]){
                //increment player score by 1
                $GLOBALS['score'] = increment_score();
                return true;
            }
        }
        return false;
    }

    function increment_score()
    {
        static $score = 0;
        $score++;
        return $score;
    }
    
    // is the word (guess) correct
    
    function isWordCorrect(){
        $guess = getCurrentWord();
        $responses = getCurrentResponses();
        $max = strlen($guess) - 1;
        for($i=0; $i<= $max; $i++){
            if(!in_array($guess[$i],  $responses)){
                return false;
            }
        }
        return true;
    }
    
    // check if the body is ready to hang
    
    function isBodyComplete(){
        $parts = getParts();
        // is the current parts less than or equal to one
        if(count($parts) <= 1){
            return true;
        }
        return false;
    }
    
    // manage game session
    
    // is game complete
    function gameComplete(){
        return isset($_SESSION["gamecomplete"]) ? $_SESSION["gamecomplete"] :false;
    }
    
    
    // set game as complete
    function markGameAsComplete(){
        $_SESSION["gamecomplete"] = true;
    }
    
    // start a new game
    function markGameAsNew(){
        $_SESSION["gamecomplete"] = false;
    }
    
    
    
    /* Detect when the game is to restart. From the restart button press*/
    if(isset($_GET['start'])){
        restartGame();
    }
    
    
    /* Detect when Key is pressed */
    if(isset($_GET['kp'])){
        $currentPressedKey = isset($_GET['kp']) ? $_GET['kp'] : null;
        // if the key press is correct
        if($currentPressedKey 
        && isLetterCorrect($currentPressedKey)
        && !isBodyComplete()
        && !gameComplete()){
            
            addResponse($currentPressedKey);
            if(isWordCorrect()){
                $WON = true; // game complete
                markGameAsComplete();
            }
        }else{
            // start hanging the man :)
            if(!isBodyComplete()){
               addPart(); 
               if(isBodyComplete()){
                   markGameAsComplete(); // lost condition
               }
            }else{
                markGameAsComplete(); // lost condition
            }
        }
    }

?>

<div class="game-panel">
    <div class="information-panel">
        <!-- <p class="score-text"> <b>Score:</b> <?php echo $GLOBALS['score']; ?> </p>
        <p class="chances-text"> <b>Chances Left:</b> <?php echo $chances ."/".$total_chances; ?> </p> -->
        <p class="name-text"> <b>User:</b> <?php echo $_SESSION['username']; ?> </p>
        <p class="score-text"> <b>Hint:</b> <?php echo $current_hint; ?> </p>
        <p class="chances-text"> <b>Selected Difficulty:</b> <?php echo $diffLevel; ?> </p>
        
    </div>
    <div class="game-area-rect">
        <?php
            switch(getCurrentPart())
            {
                case "all":?>
                    <img class="hangman-img1" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
                case "four":?>
                    <img class="hangman-img2" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
                case "three":?>
                    <img class="hangman-img3" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
                case "two":?>
                    <img class="hangman-img4" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
                case "one": ?>
                    <img class="hangman-img5"src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
                case "zero": ?>
                    <img class="hangman-img6" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
                    <?php break;
            }
        ?>
        
    </div>
    
    <div class="keypad-rect">
    <div class="inner-keypad-block">
                 <!-- <img style="width:80%; display:inline-block;" src="<?php echo getCurrentPicture(getCurrentPart());?>"/> -->
          
                <!-- Indicate game status -->
               <?Php if(gameComplete()):?>
                    <h1 class="title-game-comp">GAME COMPLETE</h1>
                <?php endif;?>
                <?php if($WON  && gameComplete()):?>
                    <img class="crown-icn" src="imgs/icons/crown.png">
                    <p class="winning-text">You Won! HURRAY! :)</p>
                <?php elseif(!$WON  && gameComplete()): ?>
                    <p class="losing-text">You Lost! Try Again</p>
                <?php endif;?>
            </div>
        <div class="keypad-box">
            <form method="get">
                    <?php
                        $max = strlen($letters) - 1;
                        for($i=0; $i<= $max; $i++){

                            echo "<button type='submit' name='kp' class='keypad-btn' value='". $letters[$i] . "'>".
                            $letters[$i] . "</button>";
                            if ($i == 8 || $i == 17) {
                               echo '<br>';
                            }
                            
                        }
                    ?>
            <br><br>
                    <!-- Restart game button -->
                    <br><br>
                    <!-- Restart game button -->
                    <button type="submit" name="start" class="refresh-btn">Restart</button>
            </form>        
        </div>
    </div>
    <div class="guesses-rect">
                <!-- Display the current guesses -->
                <?php 
                 $guess = getCurrentWord();
                 if(gameComplete())
                 {
                    for ($i = 0; $i< strlen($guess); $i++)
                    {
                        $l = getCurrentWord()[$i];
                        ?>
                        <span class="block-letter-filled"><?php echo $l;?></span>
                    <?php }
                 }
                 else
                 {
                 $maxLetters = strlen($guess) - 1;

                for($j=0; $j<= $maxLetters; $j++): $l = getCurrentWord()[$j]; ?>
                    <?php if(in_array($l, getCurrentResponses())):?>
                        <span class="block-letter-filled"><?php echo $l;?></span>
                    <?php else: ?>
                        <span class="block-letter">&nbsp;&nbsp;&nbsp;</span>
                    <?php endif;?>
                <?php endfor;
                 } 
                        
                        
                    
                ?>
    </div>
    <div >
        
    </div>
</div>