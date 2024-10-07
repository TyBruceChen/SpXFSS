<?php
define("DEVELOP_MOD", False);
session_start();
if (!isset($_SESSION["username"])){
	echo "Please login first!";
	#header('Location: index.php');
}
else{
	$username = $_SESSION["username"];
	echo "<h1>Signed in as <b style=\"color:blue;\">$username</b></h1>";
	echo "<br><p>Login time: " .date('c', strtotime("+ 6 hours"))."</p>";
 	echo "<br>Note: If you're uploading your file for your account for the first time, please refresh the page to see your uploaded files.";	
	include "utilizations.php";
	$pdo = database_connection();
	$system_files = select_fileInfo($username,$pdo);
	if (DEVELOP_MOD == True):
		var_dump($system_files);		
	endif;

	if (isset($_POST['submit'])){	
		if (DEVELOP_MOD == True):
			var_dump($_FILES);
		endif;

		$dst_file_path = handle_upload($_FILES["fileUpload"]["tmp_name"], $_FILES["fileUpload"]['name'], $username);
		archieve_file($username, $pdo, $file_name = $_FILES["fileUpload"]['name'], $file_path = $dst_file_path);
		header("file_system.php");
	}
	
	if (isset($_POST['delete_file_name'])){
		delete_record($_POST['delete_file_name'], $pdo, $username);
	}

	if (isset($_POST['sign_out'])){	
		session_destroy();	
	}
}
?>
<!DOCTYPE>
<html>
<head>
<title>Your File System SpXFSS</title>
</head>
<body>
<hr><hr>
<form actoin="" method="POST" enctype="multipart/form-data">
<h4>Upload File: </h4>
<input type="file" name="fileUpload" id="fileUpload">
<input type="submit" value="Upload" name="submit">
</form>
<hr>
<form action="" method="POST">
<h4>Delete File: </h4>
The file's name you want to delete must match exactly with file name displayed!
<br><input type="text" name="delete_file_name">
</form>
<hr><hr>
<?php
	if (isset($system_files)):
		disk_display($system_files);
	endif;
?>
<br>
<a href='index.php'>Go back</a>
<?php
	if (isset($_SESSION["username"])):
		#sign out button:	
		echo "<br><br><form action='' method='POST'><input type='submit' name='sign_out' value='Sign out'></form>";
	endif;
	include "inform.php";
	caveat_0();
	copyright();
?>
</body>
</html>
