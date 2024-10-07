<?php
function database_connection(string $db_info = "mysql:host=localhost:3306;dbname=test", string $db_user = "lampp", string $db_password = "lampp3224"){
        $pdo = new PDO($db_info,$db_user,$db_password); #connect to a specific database
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	#setup development mode for databse
	return $pdo;
}

function mkdir_user(string $username){
	$file_path = "disk/".$username;
	if (!mkdir($file_path)){	#create a directory when a new account is created.
		echo "<p style=\"color:red;\">Failed to create directory for this account, Please Contact with administrator!</p>";
		exit();
	}
}

function handle_upload(string $src_file_path, string $file_name, string $username){
	$dst_file_path = 'disk/'.$username.'/'.$file_name;
	if (!move_uploaded_file($src_file_path, $dst_file_path)){	#move the received file from the server's temporary buffer to corresponding user's directory
		echo "<p style=\"color:red;\">Failed to upload this file, please contact with administrator!</p>";
		exit();
	}else{
		echo "<br>".$file_name."has been successfully uploaded.".'<BR>';
		return $dst_file_path;
	}
}

function archieve_file(string $username, $pdo, string $file_name, string $file_path, string $table_name = 'test_user_data'){
	$insert_st = "INSERT INTO $table_name (username, file_name, file_path) VALUES (:username, :file_name, :file_path);";
	$preparedNamed = $pdo->prepare($insert_st);	#use prpared statement to avoid XSS
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
	foreach ($files_info as $file_info){
		$upload_time = $file_info['upload_time'];

		$file_name = $file_info['file_name'];
		$file_path = $file_info['file_path'];
		$username = $file_info['username'];

		echo "click below link to jump to your uploaded files";
		echo "<br><b><a href=\"$disk_link$username/\">Your Disk</a></b>";
		break;
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
		echo "<br>Record removed";
		if (!unlink($file_path)):
			echo "Failed clear content. (Maybe manually cleared).";
		endif;
	}else{
		echo "<br>Failed to find the specified file, please input valid file name!";
	}	
}
?>
