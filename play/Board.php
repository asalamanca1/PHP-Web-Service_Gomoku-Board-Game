<?php
include_once "Game.php"; 

define('ROWTYPE', 'rowtype'); // constant
$rowtypes = ["Horizontal", "Vertical", "Diagonal_/", "Diagonal"];

class Board{

    private $intersections=array();//represents 2d array of 15x15 intersections on board
    private $size =15;//represents board size of 15x15
    private $winningRow;//represents winning row
    private $computerPlayerStones=array();//represents computer players placed stones of format [x1,y1,x2,y2,...,xn,yn]
    private $humanPlayerStones=array();//represents human players placed stones of format [x1,y1,x2,y2,...,xn,yn]
    private $gameData;
    private $gameStateFile;


    //Default constructor
    public function __construct($humanPlayerStones,$computerPlayerStones, $gameData, $gameStateFile) {
        //initializes 2d array of 15x15 intersections on board
        $this->gameStateFile=$gameStateFile;
        $this->gameData = $gameData;

        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                $this->intersections[$i][$j] = "EMPTY";
            }
        }

        //set both players stones to class properties
        $this->humanPlayerStones=$humanPlayerStones;
        $this->computerPlayerStones=$computerPlayerStones;


        //place human players stones on board
        $count = count($this->humanPlayerStones);
        for ($i = 0; $i < $count; $i += 2) {
            $x = $this->humanPlayerStones[$i];
            $y = $this->humanPlayerStones[$i + 1];
            $this->placeStone($x, $y, "HUMAN");
        }



        //place computer players stones on board
        $count = count($this->computerPlayerStones);
        for ($i = 0; $i < $count; $i += 2) {
            $x = $this->computerPlayerStones[$i];
            $y = $this->computerPlayerStones[$i + 1];
            $this->placeStone($x, $y, "COMPUTER");
        }
    }

    //NOTE: MIGHT DELETE LATER, SERVES NO USE
    //returns 2d array of intersections on board
    public function intersections(){
        return $this->intersections;
    }

    //assigns player to intersection
    public function placeStone($x,$y,$player){
        if($this->isEmpty($x,$y)){
            $this->intersections[$x][$y]=$player;
            return true;
        }
        return false;
    }

    //checks for draw by checking if all intersections on board are full
    public function isDraw(){
        for ($i = 0; $i < 15; $i++) {
            for ($j = 0; $j < 15; $j++) {
                if($this->intersections[$i][$j]==="EMPTY"){
                    return false;
                }
            }
        }
        return true;
    }


    //check for a winning row of 5 or a potential winning row >= 3
    public function checkForWin($x, $y, $player, $n,$gameData) {
        $this->gameData=$gameData;
        $count=0;
        $this->winningRow = [];
        if($this->intersections[$x][$y] == $player) {
            // Vertical check
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countUp($x, $y - 1, $player, $tempWinningRow) + $this->countDown($x, $y + 1, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                //$newFileContent = json_encode($this->gameData);
                //file_put_contents($this->gameStateFile, $newFileContent);
                $this->gameData['rowType'] = "Vertical";
                return true;
            }
            $count=0;
    
            // Horizontal check
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countLeft($x - 1, $y, $player, $tempWinningRow) + $this->countRight($x + 1, $y, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                $this->gameData['rowType'] = "Horizontal";
                return true;
            }
            $count=0;
    
            //Check diagonally (top-left to bottom-right & bottom-right to top-left)
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countDiagonal_TL_BR($x + 1, $y - 1, $player,$tempWinningRow) + $this->countDiagonal_BR_TL($x - 1, $y + 1, $player,$tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                $this->gameData['rowType'] = "DiagonalLR";
                return true;
            }
            $count=0;
    
            //Check diagonally (top-right to bottom-left & bottom-left to top-right)
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countDiagonal_TR_BL($x - 1, $y - 1, $player,$tempWinningRow) + $this->countDiagonal_BL_TR($x + 1, $y + 1, $player,$tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                $this->gameData['rowType'] = "DiagonalRL";
                return true;
            }
            $count=0;
        }
    
        // No win condition met
        return false;
    }
    
    // Upwards count
    public function countUp($x, $y, $player, &$tempWinningRow) {
        if ($y >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countUp($x, $y - 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Downwards count
    public function countDown($x, $y, $player, &$tempWinningRow) {
        if ($y < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDown($x, $y + 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Leftwards count
    public function countLeft($x, $y, $player, &$tempWinningRow) {
        if ($x >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countLeft($x - 1, $y, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Rightwards count
    public function countRight($x, $y, $player, &$tempWinningRow) {
        if ($x < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countRight($x + 1, $y, $player, $tempWinningRow);
        }
        return 0;
    }
    
    //Count diagonally (top-right to bottom-left)
    public function countDiagonal_TR_BL($x, $y, $player,&$tempWinningRow) {
        if ($x != 0 && $y != 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonal_TR_BL($x - 1, $y - 1, $player,$tempWinningRow);
        }
        return 0;
    }
    //Count diagonally (top-right to bottom-left)
    public function countDiagonal_BL_TR($x, $y, $player,&$tempWinningRow) {
        if ($x != 14 && $y != 14 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonal_BL_TR($x + 1, $y + 1, $player,$tempWinningRow);
        }
        return 0;
    }
    //Count diagonally (top-left to bottom-right)
    public function countDiagonal_TL_BR($x, $y, $player,&$tempWinningRow) {
        if ($x != 14 && $y != 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonal_TL_BR($x + 1, $y - 1, $player,$tempWinningRow);
        }
        return 0;
    }
    //Count diagonally from bottom right to top left
    public function countDiagonal_BR_TL($x, $y, $player,&$tempWinningRow) {
        if ($x != 0 && $y != 14 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonal_BR_TL($x - 1, $y + 1, $player,$tempWinningRow);
        }
        return 0;
    }

    public function getGameData() {
        return $this->gameData;
    }

    public function isEmpty($x, $y) {
        // Assuming $this->intersections is the 2D array representing the board
        // Check if the position is within the board bounds and is empty
        return ($this->intersections[$x][$y] == "EMPTY");
    }
}

?>
