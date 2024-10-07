#!/bin/bash

FILE_PATH="$1"

UPLOAD_URL="http://47.109.36.149:9080/SpXFSS/file_system.php"

echo "Uploading..."

curl -b cookies.txt -F "fileUpload=@$FILE_PATH" -F "submit=Upload" $UPLOAD_URL

echo "Done, please check with your upload!"
