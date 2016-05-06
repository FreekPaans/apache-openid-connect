#!/bin/bash

if [ -z "$1" ] || [ -z "$2" ]; then
        echo "usage $0 <base-url> <client-credentials> <code>"
        exit 1
fi

>&2 echo "running $@"

BASE_URL=$1
CLIENT_CREDENTIALS=$2
AUTHORIZATION_CODE=$3

CMD="curl -u $CLIENT_CREDENTIALS $BASE_URL/token.php -d grant_type=authorization_code&code=$AUTHORIZATION_CODE"

>&2 echo "$CMD"

$CMD
