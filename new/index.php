<?php 
//URL FORMAT: http://localhost:3000/OmokWebService/src/new/index.php?strategy=Smart
define('STRATEGY', 'strategy'); // constant
$strategies = ["Smart", "Random"]; // supported strategies



if (!array_key_exists(STRATEGY, $_GET)) { 
    //strategy not specified
    $response = array("response" => false, "reason" => "Strategy not specified");
    echo json_encode($response);
    exit; 
}
else{
    //strategy was found
    $strategy = $_GET[STRATEGY];
    switch ($strategy) {

        //user has chosen to play smart strategy
        case $strategies[0]:
            //declare variables stored in gamestate file
            $humanWon=false;
            $computerWon=false;
            $isDraw=false;
            $winningRow=[];
            $humanPlayerStones=[];
            $computerPlayerStones=[];
            $rowType = "";
            $pid=uniqid();
            
            //encode successful response
            $response = array("response" => true, "pid" => $pid);
            echo json_encode($response);


            //NOTE FOR FER: comment out my path in initial declaration of $gameStateFile and replace it with your path
            //store pid and game strategy in data/pid file
            $gameStateFile='../writable/';
            //$gameStateFile='/Users/fernandomunoz/Documents/Omok_Web/OmokWebService/src/data/';
            $gameStateFile.=$pid;
            $gameStateFile .= '.txt';
            //encode gamestate file variables into json object
            $fileContent = json_encode(array('pid' => $pid, 'strategy' => $strategy, 'humanPlayerStones' => $humanPlayerStones, 'computerPlayerStones' =>  $computerPlayerStones, 
                'isDraw'=>$isDraw, 'humanWon'=>$humanWon, 'computerWon'=>$computerWon, 'winningRow'=>$winningRow, 'rowType' => $rowType));
            //put content into gamestate file
            file_put_contents($gameStateFile, $fileContent);

          
           
            break;
         
        //  
        //user has chosen to play random strategy
        case $strategies[1]:
            //declare variables stored in gamestate file
            $humanWon=false;
            $computerWon=false;
            $isDraw=false;
            $winningRow=array();
            $humanPlayerStones=array();
            $computerPlayerStones=array();
            $rowType = "";
            $pid=uniqid();

            //encode successful response
            $response = array("response" => true, "pid" => $pid);
            echo json_encode($response);


            //NOTE FOR FER: comment out my path in initial declaration of $gameStateFile and replace it with your path
            //store pid and game strategy in data/pid file
            $gameStateFile='../writable/';
            //$gameStateFile='/Users/andre/Programming Languages/OmokWebService/src/data/';
            //$gameStateFile='/Users/fernandomunoz/Documents/Omok_Web/OmokWebService/src/data/';
            $gameStateFile.=$pid;
            $gameStateFile .= '.txt';
            echo $gameStateFile;
            //encode gamestate file variables into json object
            $fileContent = json_encode(array('pid' => $pid, 'strategy' => $strategy, 'humanPlayerStones' => $humanPlayerStones, 'computerPlayerStones' =>  $computerPlayerStones, 
                'isDraw'=>$isDraw, 'humanWon'=>$humanWon, 'computerWon'=>$computerWon, 'winningRow'=>$winningRow, 'rowType' => $rowType));
            //put content into gamestate file
            file_put_contents($gameStateFile, $fileContent);
         
            break;

        //strategy not specified
        default:
            $response = array("response" => false, "reason" => "Uknown strategy");
            echo json_encode($response);
            break;
    }
}
?>

