<?php
include "utilizations.php";

session_start();
#var_dump($_SESSION['SpXFSS']);
if (isset($_SESSION['SpXFSS']['username'])){
	if (isset($_GET['file'])):
	$username = $_SESSION['SpXFSS']['username'];
	$file_path = $_GET['file'];
	
	$pdo = database_connection();	
	match_file_download($pdo, $username, $file_path);
	
	else:
	echo "<p>Empty file download request!</p>";
	endif;	
}else{
	echo "Please Login First!";
}
?>
