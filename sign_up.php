<?php
	if (isset($_POST['username'])&&isset($_POST['password'])){
		include "utilizations.php";
		$pdo = database_connection();
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);

		$add_user = "INSERT INTO test_login (username, password) VALUES (:username, :password);";
		$preparedNamed = $pdo->prepare($add_user);
		$preparedNamed->bindValue(":username", $username);
		$preparedNamed->bindValue(":password", $password);
		try{
			$preparedNamed->execute();
			echo "<p>Account <b style=\"color:green;\">".$username."</b> has created.</p>";
                echo "<p>Click <a href=\"index.php\">here</a> to go back to continue sign-in.</p>";
		mkdir_user($username);
		}catch(PDOException $e){
			echo var_dump($e).'<br><br>';
			if (str_contains($e->getMessage(),"Data too long for column 'username'")){
				echo "Username is too long!";
			}elseif (str_contains($e->getMessage(),'Duplicate entry')){
				echo "Account already existed!/Username Used!";
			}
			echo "<br> Error!";
		}	
	}
?>
<!DOCTYPE>
<html>
<head>
<title>Sign Up SpXFSS</title>
</head>
<body>
<h4>Sign Up Information</h4>
<form action="" method="POST">
Your Username:
<input type="text", name="username"><br>
Setup Your Password:
<input type="text", name="password"><br>
<input type="submit", value="Sign Up">
</form>
<?php
	include "inform.php";
	copyright();
?>
</body>
</html>
