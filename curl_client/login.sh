#!/bin/bash

USERNAME="$1"
PWD="$2"

LOGIN_URL="https://ty-b-c.com/SpXFSS/index.php"

curl -c cookies.txt -d "username=$USERNAME&password=$PWD" $LOGIN_URL

echo "Please check the output to see it's correctly login:"

cat cookies.txt
