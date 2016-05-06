#!/bin/bash

if [ -e "oauth2.key" ] || [ -e "pub.pem" ]; then
		echo "oauth2.key and/or pub.pem already exist, not overwriting"
		exit 1
fi

openssl genrsa 4096 > oauth2.key

if [ $? -ne 0 ]; then
		echo "failed generating key"
		exit 1
fi

openssl rsa -in oauth2.key -pubout -out pub.pem
