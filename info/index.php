
<?php // index.php
include "GameInfo.php";

//original implementation

$strategies= array("Smart", "Random");
$info = array(
    "size" => 15,
    "strategies" => array("Smart", "Random")
);
echo json_encode($info);


//new implementation, yields good practice
// $strategies= array('Smart' => 'SmartStrategy', 'Random' => 'RandomStrategy');
// define('SIZE', 15);
// $info = new GameInfo(SIZE, array_keys($strategies));
// echo json_encode($info); 



?>