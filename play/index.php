<?php
include_once "Game.php";
include_once "RandomStrategy.php";
include_once "SmartStrategy.php"; 
// include other strategy files if necessary

//http://localhost:3000/src/play/index.php?pid=65daa65356166&x=1&y=4
//missing pid
if (!array_key_exists('pid', $_GET)) { 
    $response = array("response" => false, "reason" => "Pid not specified");
    echo json_encode($response);
    exit; 
}
//missing x coordinate
else if (!array_key_exists('x', $_GET)) { 
    $response = array("response" => false, "reason" => "X coordinate not specified");
    echo json_encode($response);
    exit; 
}
//missing y coordinate
else if (!array_key_exists('y', $_GET)) { 
    $response = array("response" => false, "reason" => "Y coordinate not specified");
    echo json_encode($response);
    exit; 
}
//invalid x coordinate
else if($_GET['x']<0||$_GET['x']>14){
    $response = array("response" => false, "reason" => "Invalid x coordinate");
    echo json_encode($response);
    exit; 
}
//invalid y coordinate
else if($_GET['y']<0||$_GET['y']>14){
    $response = array("response" => false, "reason" => "Invalid y coordinate");
    echo json_encode($response);
    exit; 
}
//check if game exists by search for data/pid file
else{
    //create gamestate file format using pid
    //NOTE FOR FER: comment out my path in initial declaration of $gameStateFile and replace it with your path
    // $gameStateFile='/Users/andre/Programming Languages/OmokWebService/src/data/';
    $gameStateFile='../writable/';
    //$gameStateFile='/Users/fernandomunoz/Documents/Omok_Web/OmokWebService/src/data/';
    $gameStateFile.=$_GET['pid'].'.txt';

    //search if the file already exists, if so we can proceed with game
    if(file_exists(($gameStateFile))){
        $x=$_GET['x'];
        $y=$_GET['y'];
        $fileContent = file_get_contents($gameStateFile);
        $gameData = json_decode($fileContent, true);

        //instantiate new Game and pass in x/y coordinate of placed stone
        $newGame = new Game($gameStateFile, $gameData, intval($x), intval($y),"HUMAN");
        // CPU Move
        if($cpuMove = $newGame->CPUMove(intval($x), intval($y))){
            $newGame->setCpuMove($cpuMove);
            $newGame->board->placeStone($cpuMove['x'],$cpuMove['y'],"COMPUTER");
            $fileContent = file_get_contents($gameStateFile);
            $gameData = json_decode($fileContent, true);
            $response=array(
                "response"=>true,
                "ack_move"=>array(
                    "x"=>$x,
                    "y"=>$y,
                    "isWin"=>$gameData['humanWon'],
                    "isDraw"=>$gameData['isDraw'],
                    "row"=>$gameData['winningRow'] //row will be winning row
                ),
                "move"=>array(
                    "x"=>$cpuMove['x'],
                    "y"=>$cpuMove['y'],
                    "isWin"=>$gameData['computerWon'],
                    "isDraw"=>$gameData['isDraw'],
                    "row"=>$gameData['winningRow']
                )
            );
        }
        if($newGame->validMove()){
            $response = array("response" => false, "reason" => "Coordinate is not empty");
            echo json_encode($response);
        }
        else{
            echo json_encode($response);
        }
        if($newGame->gameOver==true){
            unlink($gameStateFile);
        }
        exit;

    }
    //gamestate file with given pid does not exist, this means a game has not been started with this pid
    else {
        $response = array("response" => false, "reason" => "Invalid Pid");
        echo json_encode($response);
        exit;
    }

}
?>