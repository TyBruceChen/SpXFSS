# SpXFSS (Simple php XAMPP File System Server)
A lightweight and easy-deployed file system server based on php and XAMPP server for multiplatform file upload/downloading

## Why this project?
When training AI models on Linux (Ubuntu) servers, it is often found hard to transmit the fine-tuned model back to other PCs which usually do not install with the ssh server (or other file server) for inference. Thus I decided to develop a file system that can enable cross-platform file uploading and downloading.

## Current Stage
<b>v0_3 features</b>: login/signup, upload/download enabled for both Ubuntu and Windows. However, everyone can download your uploaded files without login. Thus do not upload your sensitive files! The login is for authorizing uploading.

## Get Started (Ubuntu 20.04)

Install XAMPP to ```/opt/lampp``` from https://www.apachefriends.org/download.html (my PHP Version 8.2.12).

Optional: Modify your server configurations (e.g.: HTTP service port, SQL port, development settings) in ```/opt/lampp/etc```.

Start XAMPP (lampp) by executing ```./lampp start``` under ```/opt/lampp``` directory (the following tutorial is also in this folder).

Initialize SQL: <BR> 
1. ./bin/mysql -u root -p (default password is empty, and here we use the root account to log in)
2. create a database (```CREATE database_name;```) or use an existing one (in this case, ```test```)
3. ```USE database``` (test)
4. create tables: ```CREATE TABLE test_login (username VARCHAR(8) PRIMARY KEY UNIQUE NOT NULL, password VARCHAR(16) NOT NULL, date_created TIMESTAMP DEFAULT NOW() NOT NULL);```

## References:
* https://www.w3schools.com/php/php_ref_filesystem.asp
* https://www.apachefriends.org/faq_linux.html
* https://learncodingfast.com/php/
