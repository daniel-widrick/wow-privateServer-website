<?php

include "cMangosHelper.php";
include "config.php";


$characterDB = new lvlint67\cMangosHelper\databaseCharacter($dbHost, $dbPort, $dbUsername, $dbPassword, "characters");

$order = Array();
$order[] = "level desc";
$order[] = "xp desc";

$ranking = $characterDB->getCharacterRankings($order);

var_dump($ranking);
