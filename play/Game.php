<?php
include_once "RandomStrategy.php"; 
include_once "SmartStrategy.php";
include_once "Board.php"; 
class Game{
    public Board $board;//respresents game board
    public $strategy;//represents games strategy
    private $gameStateFile;//game state file
    private $gameData;//stores game state data array
    public $computerPlayerStones;
    public $validMove;
    public $gameOver;
    protected $cpuMove;

    //PSEUDOCODE
    //instantiate board with player and computer intersections
    //check for draw
    //check for win
    //update file
    //update json response


    //constructor that takes pid & both player and computer rows
    public function __construct($gameStateFile, $gameData, $x, $y, $player) {

        //$this->strategy = $strategy;
        $this->gameOver=false;

        $this->validMove=false;

        //represents this games gameStateFile
        $this->gameStateFile=$gameStateFile;

        //read the contents of game state file
        $fileContent = file_get_contents($gameStateFile);

        //decode the json content into an array
        $this->gameData = json_decode($fileContent, true);


        //store human players placed stones
        $humanPlayerStones=$this->gameData['humanPlayerStones'];
        //store computer players placed stones
        $computerPlayerStones=$this->gameData['computerPlayerStones'];

        

        //append new coordinates to players stones
        $appendedCoordinates=[$x,$y];
      
       
        if($player=="HUMAN"){
          
            // $humanPlayerStones=$humanPlayerStones+$appendedCoordinates;
            //echo json_encode($humanPlayerStones);
            //echo json_encode($appendedCoordinates);
            $humanPlayerStones=array_merge($humanPlayerStones, $appendedCoordinates);
           
        
            $this->gameData['humanPlayerStones']=$humanPlayerStones;
        }
        else {
            $computerPlayerStones+=$appendedCoordinates;
            $this->gameData['computerPlayerStones']=$computerPlayerStones;
        }
        
        //instantiate the Board class
        $this->board = new Board($humanPlayerStones, $computerPlayerStones, $gameData, $gameStateFile);

        //if player is human, place stone on board and set it to humanPlayers stone
        if($player=="HUMAN"){
            if($this->board->placeStone($x,$y,"HUMAN")){
                $this->validMove=true;
            }
            else{
                $this->validMove=false;
            }
            // $this->gameData = $this->board->getGameData();
            // $newFileContent = json_encode($this->gameData);
            // file_put_contents($this->gameStateFile, $newFileContent);
        }
        //if player is computer, place stone on board and set it to computerPlayers stone
        else{
            // $this->gameData = $this->board->getGameData();
            // $newFileContent = json_encode($this->gameData);
            // file_put_contents($this->gameStateFile, $newFileContent);
            $this->board->placeStone($x,$y,"COMPUTER");
        }

        
       


        //checking for a draw
        if ($this->board->isDraw()) {
            //if theres a draw, update game data
            $this->gameData['isDraw']=true;
        }
        //check if human player won game
        else if ($this->board->checkForWin($x, $y, "HUMAN", 5, $gameData)) {
            //update game state to showcase win, winning row
            $this->gameData = $this->board->getGameData();
            $this->gameData['humanWon']=true;
            $this->gameOver=true;
        }
        //check if computer player won game
        else if ($this->board->checkForWin($x, $y, "COMPUTER", 5, $gameData)) {
            //update game state to showcase win, winning row
            $this->gameData['computerWon']=true;
            $this->gameOver=true;
            
        }
        //convert the array to JSON
        $newFileContent = json_encode($this->gameData);
        //update game state file
        file_put_contents($this->gameStateFile, $newFileContent);
        // $this->gameData = $this->board->getGameData(); // This should include the latest winningRow information if a win was detected
        // $newFileContent = json_encode($this->gameData);
        // file_put_contents($this->gameStateFile, $newFileContent);
        

    }

    function CPUMove($x, $y){
        // Need to read strat here 
        if($this->gameData['strategy']=='Smart') {
     
            $smart = new SmartStrategy($this->board, $this->gameData, $this->gameStateFile, intval($x), intval($y));
            //$this->board->checkForWin($x, $y, "HUMAN", 3, $this->gameData);
            $computerMove = $smart->pickPlace($this->board);
        } else {
            // Default to RandomStrategy if not Smart
       
            $random = new RandomStrategy($this->board);
            $computerMove = $random->pickPlace($this->board); // Get the computer's move
        }
        // $random = new RandomStrategy($this->board);
        // $computerMove = $random->pickPlace($this->board); // Get the computer's move
    
        // Check if the move is valid and update the board and game state
        if (isset($computerMove['x']) && isset($computerMove['y'])) {
            $this->board->placeStone($computerMove['x'], $computerMove['y'], "COMPUTER");
            $this->gameData['computerPlayerStones'][] = $computerMove['x'];
            $this->gameData['computerPlayerStones'][] = $computerMove['y'];
    
            // Save the updated game state
            $newFileContent = json_encode($this->gameData);
            file_put_contents($this->gameStateFile, $newFileContent);
        }
    
        return $computerMove;
    }

    public function validMove(){
        return $this->validMove;
    }
    public function gameOver(){
        return $this->gameOver;
    }
    
    public function setCpuMove($cpuMove){
        $this->cpuMove=$cpuMove;
    }
    public function getCpuMove(){
        return $this->cpuMove;
    }
    public function board(){
        return $this->board;
    }
}
?>