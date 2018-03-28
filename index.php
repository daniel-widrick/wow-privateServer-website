<?php

if( !isset($_POST['username']) || !isset($_POST['psw']) || !isset($_POST['psw-repeat']) || ( $_POST['psw'] !== $_POST['psw-repeat']) )
{
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<img src="https://d1u5p3l4wpay3k.cloudfront.net/wowpedia/0/08/Horde_Crest.png" />
		<form action="index.php" method="post" style="border:1px solid #ccc">
		  <div class="container">
			<h1>Sign Up</h1>
			<p>Please fill in this form to create an account.</p>
			<hr>

			<p><label for="username"><strong>Username</strong></label>
			<br /><input type="text" placeholder="Username" name="username" required></p>

			<p><label for="psw"><strong>Password</strong></label>
			<br /><input type="password" placeholder="Enter Password" name="psw" required></p>

			<p><label for="psw-repeat"><strong>Repeat Password</strong></label>
			<br /><input type="password" placeholder="Repeat Password" name="psw-repeat" required></p>

			<div class="clearfix">
			  <button type="submit" class="signupbtn">Sign Up</button>
			</div>
		  </div>
		</form>
		<p><a href="/315-mayday-gaming_1121_Pack.zip">Please download the 1.12.1 WoW client</a></p>
	</body>
</html>
<?php

}
else {

	try {
		include 'resource/config.php';

		$dbh = new PDO("mysql:host=127.0.0.1;dbname=realmd",$dbUsername,$dbPassword);
		$query = "SELECT count(username) from account where username = ':username'";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':username',$_POST['username']);
		$stmt->execute();

		if( $stmt->fetch()[0] != 0 ) { die('Username taken. Please Try again.'); }
		else {
			/*
			$query = "INSERT INTO account (`username`, `sha_pass_hash`, `gmlevel`, `sessionkey`, `email`, `joindate`, `last_ip`, `failed_logins`, `locked`, `last_login`, `active_realm_id`, `expansion`, `mutetime`, `locale`, `token`) VALUES(
				:username,
				:password,
				0,
				null,
				null,
				sysdate(),
				0,
				0,
				0,
				null,
				0,
				0,
				0,
				0,
				0)";
			 */
			$query = "INSERT INTO account (`username`,`sha_pass_hash`,`expansion`) VALUES(:username, :password, 0)";
			$stmt = $dbh->prepare($query);
			$stmt->bindParam(":username",$_POST['username']);
			$sha = sha1( strtoupper($_POST['username'] . ':' . $_POST['psw']) );
			$stmt->bindParam(":password",$sha);
			if($stmt->execute() === true) {
				echo "Registration of ".$_POST['username']." suceeded!";
			}
			else{ echo "registration failed"; }
		}



	} catch (PDOException $e) {
		print "Storage Error on Server";
		var_dump($e);
		die();
	}

}

?>
