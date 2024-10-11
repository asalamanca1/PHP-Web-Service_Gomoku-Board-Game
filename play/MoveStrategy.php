<?php
abstract class MoveStrategy {
   protected $board;

   function __construct(Board $board = null) {
      $this->board = $board;
   }

   abstract function pickPlace();

   function toJson() {
      return array("name" => get_class($this));
      return;
   }

   static function fromJson() {
       return new static();
   }
}
?>
