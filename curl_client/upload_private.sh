#!/bin/bash

FILE_PATH="$1"

UPLOAD_URL="https://ty-b-c.com/SpXFSS/file_system.php"

echo "Uploading..."

curl -b cookies.txt -F "fileUpload=@$FILE_PATH" -F "submit=Upload" -F "upload_type=private" $UPLOAD_URL

echo "Done, please check with your upload!"
