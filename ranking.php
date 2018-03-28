<html>
   <head>
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>

<br />
<h1>Character PVE Rankings</h2>
<table>
 <tr>
  <th>#</th>
  <th>Name</th>
  <th>Level</th>
  <th>Gear</th>
  <th>Race</th>
  <th>Class</th>
  <th>Gender</th>
 </tr>

<?php
include "resource/cMangosHelper.php";
include "resource/config.php";


$characterDB = new lvlint67\cMangosHelper\databaseCharacter($dbHost, $dbPort, $dbUsername, $dbPassword, "characters");

$order = Array();
$order[] = "level desc";
$order[] = "a.ilvl desc";
$order[] = "xp desc";

$ranking = $characterDB->getCharacterRankings($order);

foreach($ranking as $rank=> $data)
{
	echo "<tr>";
	echo "<td>". ($rank+1) ."</td>";
	echo "<td>".$data["name"]."</td>";
	echo "<td>".$data["level"]."</td>";
	echo "<td>".floor($data["ilvl"])."</td>";
	echo "<td>".$data["raceDescription"]."</td>";
	echo "<td>".$data["classDescription"]."</td>";
	echo "<td>".$data["genderDescription"]."</td>";
	echo "</tr>";
}

?>
</table>

<p><a href="/index.php" alt="Sign Up">Sign up to join the adventure!</a></p>
