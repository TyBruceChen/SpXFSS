<?php	
	include "utilizations.php";
	$pdo = database_connection();
        echo "Database connected"."<br>";
	if (isset($_POST['username']) && isset($_POST['password']) != False):
                echo 'Logining in ...'.'<br>';  
                $post_username = htmlspecialchars($_POST['username']);
                $post_pw = htmlspecialchars($_POST['password']);

                $pwd_fetch = "SELECT password FROM test_login WHERE username=:username"; 
                $preparedNamed = $pdo->prepare($pwd_fetch);
                $preparedNamed->bindValue(':username',$post_username);
        
                $preparedNamed->execute();
                $fetchPW = $preparedNamed->fetchall(PDO::FETCH_NUM);
                if (($fetchPW[0][0] == $_POST['password'])&&($fetchPW)):
                        $POST_user = $_POST['username'];
			echo "Successfully signed in as $POST_user";
			session_start();
			$_SESSION['username'] = $POST_user;
			echo "SESSION: ".$_SESSION['username']."<BR>";
			header("Location: file_system.php");
			exit();
                else:
                        echo "<p style=\"color:red;\">Incorrect Account/Password, Pleae try again!</p>";                
		endif;
        endif;
?>
<!doctype>
<html>
<head>
<title>Sign in SpXFSS</title>
</head>
<body>
<h1>SpXFSS</h1>
	<form action="", method="POST">
		<input type='text', name="username", value="">	
		<input type='text', name="password", value="">
		<input type='submit', value='Sign In'>
	</form>
<h5>If you do not have account, <a href="sign_up.php">sign up</a> for one.</h5>
<h5>If you have already signed in, <a href="file_system.php">click here</a> to go back to your file system</h5>
<?php
	include "inform.php";
	caveat_0();
?>

<p>See others' disk without uploading: <a href='disk/'>click here</a>.</p>

<?php 
	copyright();
?>
</body>
</html>
