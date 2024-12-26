<?php
define("DEVELOP_MOD", False);
session_start();
if (!isset($_SESSION['SpXFSS']["username"])){
	echo "<h3 style=\"color: red;\">Please login first!</h3>";
	#header('Location: index.php');
}
else{
	$username = $_SESSION['SpXFSS']["username"];
	echo "<h1>Signed in as <b style=\"color:blue;\">$username</b></h1>";
	echo "<br><p>Login time: " .date('c', strtotime("+ 6 hours"))."</p>";
 	echo "<br>Note: This file system allows uploading files in two types of modes: <b>Private</b> (default): only you can view this uploaded file; <b>Public</b>: anyone can access this uploaded file without loging in. Stay sharp!";	
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
		if ($_POST['upload_type'] == 'private'):
			$upload_folder = 'uploads';	#only the user can get access ('uploads/')
		else:
			$upload_folder = 'disk';	#anyone can get access ('disk/')
		endif;
		$dst_file_path = handle_upload($_FILES["fileUpload"]["tmp_name"], $_FILES["fileUpload"]['name'], $username, $upload_folder);
		archieve_file($username, $pdo, $file_name = $_FILES["fileUpload"]['name'], $file_path = $dst_file_path);	
		header("Location: file_system.php");
	}
	
	if (isset($_POST['delete_file_name'])){
		delete_record(basename($_POST['delete_file_name']), $pdo, $username);
		header("Location: file_system.php");
	}

	if (isset($_POST['sign_out'])){	
		session_destroy();	
		header('Location: file_system.php');
	}
}
?>
<!DOCTYPE>
<html>
<head>
<title>Your File System SpXFSS</title>
</head>
<body>
<?php
	if (isset($_SESSION['SpXFSS']['username'])):
		echo <<<HTMLParagraph
<hr><hr>
<form actoin="" method="POST" enctype="multipart/form-data">
<h4>Upload File: </h4>
<input type="file" name="fileUpload" id="fileUpload">
Accessibility: 
<input type="radio" name="upload_type" value="private" checked="checked">Private
<input type="radio" name="upload_type" value="public">Public
<br>
<input type="submit" value="Upload" name="submit">
<br>
There is only one file allowed to upload at one time.
</form>
HTMLParagraph;
		echo <<<HTMLParagraph
<hr>
<form action="" method="POST">
<h4>Delete File: </h4>
The file's name you want to delete must match exactly with file name displayed!
<br><input type="text" name="delete_file_name">
</form>
<hr><hr>

HTMLParagraph;
		if (isset($system_files)):
			disk_display($system_files);
		endif;
	endif;
?>
<br>
<a href='index.php'>Go back</a>
<?php
	if (isset($_SESSION['SpXFSS']["username"])):
		#sign out button:	
		echo "<br><br><form action='' method='POST'><input type='submit' name='sign_out' value='Sign out'></form>";
	endif;
	include "inform.php";
	caveat_0();
	copyright();
?>
</body>
</html>
