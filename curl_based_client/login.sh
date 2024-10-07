#!/bin/bash

USERNAME="$1"
PWD="$2"

LOGIN_URL="http://47.109.36.149:9080/SpXFSS/index.php"

curl -c cookies.txt -d "username=$USERNAME&password=$PWD" $LOGIN_URL

echo "Please check the output to see it's correctly login:"

cat cookies.txt
