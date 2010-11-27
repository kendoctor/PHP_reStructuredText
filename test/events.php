<?php
require __DIR__ . "/common.php";

$s = new Text\Restructured\Loader\FileLoader($_SERVER['argv'][1]);
$r = new Text\Restructured\Restructured($s, new Text\Restructured\StateMachine());
$r->registerState($states);

use Text\Restructured\Event;

$r->parse(function($event){
  printf("[%s]:\r\n",$event->type);
});