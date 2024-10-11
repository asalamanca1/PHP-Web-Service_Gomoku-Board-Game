<?php
include_once "MoveStrategy.php";

class SmartStrategy extends MoveStrategy{
    protected $board;
    private $gameStateFile;//game state file
    private $gameData;//stores game state data array
    private $x;
    private $y;
    // function pickPlace($board){
    //     while (true) {
    //         $x = mt_rand(0, 9);
    //         $y = mt_rand(0, 9);

    //         if ($board->board[$x][$y] == 0) {
    //             $board->board[$x][$y]=1;
    //             return array('x' => $x, 'y' => $y);
    //         }
    //     }
    //     return;
    // }
    public function __construct($board, $gameData, $gameStateFile, $x, $y) {
        $this->x = $x;
        $this->y = $y;
        $this->gameData = $gameData;
        $this->board = $board;
        $this->gameStateFile = $gameStateFile;
    }

    function pickPlace(){
        $freeSpaces = $this->pickSmart($this->x, $this->y);
        return $freeSpaces;
    }
    //uses boolean and while loop to look for an empty spot
    function pickSmart($x, $y){
        $takenTile = true;

        if($this->board->checkForWin($x, $y, "HUMAN", 3, $this->gameData)) {
            $this->gameData = $this->board->getGameData();
            if($this->gameData['rowType'] == "Vertical") {
                $x = $x;
                $y = $y + 1;
                if(!$this->board->isEmpty($x, $y)) {
                    $takenTile = true;
                    while($takenTile){
                        $x = rand(0,14);
                        $y = rand(0,14);
                        // echo "CPU MOVE: Ln 30, RandomStrat"; 
                        //$coordinate[0] = $x;
                        //$coordinate[1] = $y;
                        if($this->board->isEmpty($x, $y) == true){
                            $takenTile = false;
                        }
                    }
                }
            } else if ($this->gameData['rowType'] == "Horizontal") {
                $x = $x + 1;
                $y = $y;
                if(!$this->board->isEmpty($x, $y)) {
                    $takenTile = true;
                    while($takenTile){
                        $x = rand(0,14);
                        $y = rand(0,14);
                        // echo "CPU MOVE: Ln 30, RandomStrat"; 
                        //$coordinate[0] = $x;
                        //$coordinate[1] = $y;
                        if($this->board->isEmpty($x, $y) == true){
                            $takenTile = false;
                        }
                    }
                }
            } else if ($this->gameData['rowType'] == "DiagonalRL") {
                $x = $x + 1;
                $y = $y + 1;
                if(!$this->board->isEmpty($x, $y)) {
                    $takenTile = true;
                    while($takenTile){
                        $x = rand(0,14);
                        $y = rand(0,14);
                        // echo "CPU MOVE: Ln 30, RandomStrat"; 
                        //$coordinate[0] = $x;
                        //$coordinate[1] = $y;
                        if($this->board->isEmpty($x, $y) == true){
                            $takenTile = false;
                        }
                    }
                }
            } else if ($this->gameData['rowType'] == "DiagonalLR") {
                $x = $x - 1;
                $y = $y + 1;
                if(!$this->board->isEmpty($x, $y)) {
                    $takenTile = true;
                    while($takenTile){
                        $x = rand(0,14);
                        $y = rand(0,14);
                        // echo "CPU MOVE: Ln 30, RandomStrat"; 
                        //$coordinate[0] = $x;
                        //$coordinate[1] = $y;
                        if($this->board->isEmpty($x, $y) == true){
                            $takenTile = false;
                        }
                    }
                }
            }
        } else {
            $takenTile = true;
                while($takenTile){
                    $x = rand(0,14);
                    $y = rand(0,14);
                    // echo "CPU MOVE: Ln 30, RandomStrat"; 
                    //$coordinate[0] = $x;
                    //$coordinate[1] = $y;
                    if($this->board->isEmpty($x, $y) == true){
                        $takenTile = false;
                    }
                }
        }
        return array('x' => $x, 'y' => $y);
        // echo "CPU MOVE: Ln 30, RandomStrat"; 
            //$coordinate[0] = $x;
            //$coordinate[1] = $y;
        
        if($this->board->isEmpty($x, $y) == true){
            $takenTile = false;
        }
        
    }
}

// $random = new RandomStrategy();
// $result = $random->pickPlace($board);
// if ($result['value'] == 0) {
//     echo $result['value'] . " Empty Place at coordinates (" . $result['x'] . ", " . $result['y'] . ")";
// }