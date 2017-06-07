<?php 

error_reporting (E_ALL|E_STRICT);
require_once ("../MysqliDb.php");
require_once ("../dbObject.php");

$db = new Mysqlidb('localhost', 'root', '', 'testdb');

dbObject::autoload ("models");




?>