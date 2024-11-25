# SpXFSS (Simple php XAMPP File System Server)
A lightweight and easy-deployed file system server based on php and XAMPP server for multiplatform file upload/downloading<br>
To access the latest updates, browse the branches

## Why this project?
When training AI models on Linux (Ubuntu) servers, it is often found hard to transmit the fine-tuned model back to other PCs which usually do not install with the ssh server (or other file server) for inference. Thus I decided to develop a file system that can enable cross-platform file uploading and downloading. Most importantly, easy-to-deploy and free.

![SpXFSS](https://github.com/user-attachments/assets/2319d13b-8264-46c3-a295-6ead07d609d5)
<img width="620" alt="image" src="https://github.com/user-attachments/assets/0d1a948b-4578-482b-be53-2d0054945230">

## Category:
* [Current State](#current-stage)
* [Get Started (Server) (Ubuntu 20.04)](#get-started-ubuntu-20-04)
   * [Client Usage](#unix-client-with-curl)
* [Maintenace](#maintenance)
* [Reference](#reference)

## Current Stage
<b>v0_3 features</b>: login/signup, upload/download enabled for both Ubuntu and Windows. However, everyone can download your uploaded files without login. Thus do not upload your sensitive files! The login is for authorizing uploading.

## Get Started (Ubuntu 20.04)

Install XAMPP to ```/opt/lampp``` from https://www.apachefriends.org/download.html (my PHP Version 8.2.12).

Optional: Modify your server configurations (e.g.: HTTP service port, SQL port, development settings) in ```/opt/lampp/etc```.

Start XAMPP (lampp) by executing ```./lampp start``` under ```/opt/lampp``` directory (the following tutorial is also in this folder).

Initialize SQL: <BR> 
1. ```./bin/mysql -u root -p``` (default password is empty, and here we use the root account to log in)
2. create a database (```CREATE database_name;```) or use an existing one (in this case, ```test```)
3. ```USE test```
4. create tables: <br>
   create test_login table for storing account information: ```CREATE TABLE test_login (username VARCHAR(8) PRIMARY KEY UNIQUE NOT NULL, password VARCHAR(16) NOT NULL, date_created TIMESTAMP DEFAULT NOW() NOT NULL);``` <br>
   create test_user_data for storing files' information: ```CREATE TABLE test_user_data (username VARCHAR(8) NOT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, upload_time TIMESTAMP DEFAULT NOW() NOT NULL, PRIMARY KEY(username, file_name), FOREIGN KEY (username) REFERENCES test_login(username) ON DELETE CASCADE ON UPDATE RESTRICT);```
5. copy this repository content end with ```*.php``` under ```./htdocs``` folder as ```SpXFSS```, and create a folder called ```disk``` under ```./htdocs/SpXFSS/``` (user uploadings will be stored here), change the privilege of ```disk``` as 0777.

#### Unix client with curl: 
1. For your own server, remember to modify ```*URL``` value in ```*.sh``` files to your URL.
2. make ```*.sh``` executable by ```chmod +x *.sh``` in your client folder.
3. ```./login.sh your_username your_password``` there should be a ```cookies.txt``` file saved.
4. ```./upload.sh your_file_path``` the upload should be finished moments later. (one file upload at one time)

Maintenance:
1. Delete a user account: Close the XAMPP server first, then use the ```test``` database as mentioned above, ```DELETE FROM test_login WHERE username='xxx';```, delete the user's folder under ```disk```.

Caveat: This project is still in beta version, thus the security might be vulnerable. Welcome to pull contribution!
   
## References:
* https://www.w3schools.com/php/php_ref_filesystem.asp
* https://www.apachefriends.org/faq_linux.html
* https://learncodingfast.com/php/
