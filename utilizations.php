<?php
function database_connection(string $db_info = "mysql:host=localhost:3306;dbname=test", string $db_user = "lampp", string $db_password = "lampp3224"){
        $pdo = new PDO($db_info,$db_user,$db_password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $pdo;
}

function mkdir_user(string $username){
	$public_file_path = "disk/".$username;
	$private_file_path = "uploads/".$username;

	if (!(mkdir($public_file_path, 0755, true)&&mkdir($private_file_path, 0755, true))){
		echo "<p style=\"color:red;\">Failed to create directory for this account, Please Contact with administrater!</p>";
		exit();
	}
}

function handle_upload(string $src_file_path, string $file_name, string $username, $upload_type_folder="uploads"){
	$dst_file_path = $upload_type_folder.'/'.$username.'/'.$file_name;
	if (!move_uploaded_file($src_file_path, $dst_file_path)){
		echo "<p style=\"color:red;\">Failed to upload this file, please contact with administrater!</p>";
		exit();
	}else{
		echo "<br>".$file_name." has been successfully uploaded.".'<BR>';
		return $dst_file_path;
	}
}

function archieve_file(string $username, $pdo, string $file_name, string $file_path, string $table_name = 'test_user_data'){
	$insert_st = "INSERT INTO $table_name (username, file_name, file_path) VALUES (:username, :file_name, :file_path);";
	$preparedNamed = $pdo->prepare($insert_st);	
	$preparedNamed->bindValue(":username", $username);
	$preparedNamed->bindValue(":file_name", $file_name);
	$preparedNamed->bindValue(":file_path", $file_path);
	$preparedNamed->execute();
}

function select_fileInfo(string $username,$pdo, string $table_name = 'test_user_data'){
	$select_statement = "SELECT * FROM $table_name WHERE username=:username;";
	$preparedNamed = $pdo->prepare($select_statement);	
	$preparedNamed->bindValue(':username',$username);
	$preparedNamed->execute();
	return $preparedNamed->fetchAll(PDO::FETCH_ASSOC);
}

function disk_display(array $files_info, string $disk_link = 'disk/'){
	echo "<h4>Your Files:</h4>";	
	if ($files_info):
	foreach ($files_info as $file_info){
		$upload_time = $file_info['upload_time'];
		$username = $file_info['username'];
		$file_name = $file_info['file_name'];
		$file_path = $file_info['file_path'];
		echo "<a target='_blank' href='download.php?file=".urlencode($file_path)."'>".htmlspecialchars($file_name)."</a><br>";	
		#call function at download.php when the download link is clicked
	}
	echo "<br>click the link below to jump to your public files";
	echo "<br><b><a href=\"$disk_link$username/\">Your Public Disk</a></b>";
	endif;
	echo "<hr><hr>";
}

function match_file_download($pdo, string $username, string $file_path, string $table_name = 'test_user_data'){
	$search_request = "SELECT * FROM $table_name WHERE username=:username AND file_path=:file_path";
	$prepareNamed = $pdo->prepare($search_request);
	$prepareNamed->execute(['username'=>$username, 'file_path'=>$file_path]);
	$file = $prepareNamed->fetchAll();
	if($file){
		header('Content-Description: File Transfer');
	      	header('Content-Type: application/octet-stream');
    		header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    		header('Content-Length: ' . filesize($file_path));
    		readfile($file_path);
	}else{
		echo "Unauthorized File Download Request";
	}	
}

function delete_record(string $file_name, $pdo, string $username, string $table_name = 'test_user_data'){
	$files_info = select_fileInfo($username, $pdo);
	$file_exist = False;
	#check for existence
	foreach ($files_info as $file_info){
		if ($file_name == $file_info['file_name']):
			$file_exist = True;
			$file_path = $file_info['file_path'];
		endif;
	}

	if ($file_exist == True){
		$delete_st = "DELETE FROM $table_name WHERE file_name=:file_name and username=:username;";
		$preparedNamed = $pdo->prepare($delete_st);
		$preparedNamed->bindValue(":file_name", $file_name);
		$preparedNamed->bindValue(":username", $username);
		$preparedNamed->execute();	
		if (!unlink($file_path)):
			echo "<p style=\"color:red;\">Failded clear content. (Maybe need manually clear). </p>";
		else:
			echo "<br> Record removed";
		endif;
	}else{
		echo "<br>Failed to find the specified file, please input valid file name!";
	}	
}
?>
