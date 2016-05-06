#!/bin/bash

if [ -z "$1" ] || [ -z "$2" ]; then
        echo "usage $0 <base-url> <client-id>"
        exit 1
fi

BASE_URL=$1
CLIENT_ID=$2
CMD="curl -iv $BASE_URL/authorization.php?state=2&response_type=code&client_id=$CLIENT_ID&scope=openid -d authorized=yes&__csrf_token=1 -b __csrf_token=1"

>&2 echo "invoking $CMD"

CURL_RESULT=$($CMD 2>/dev/null) 

CURL_ERR=$?

>&2 echo "curl returned $CURL_ERR"

if [ "$CURL_ERR" -gt 0 ]; then
        echo "error invoking curl"
        exit 1
fi

grep -q "^HTTP.*302" <<< "$CURL_RESULT"

if [ $? -ne 0 ]; then
        echo "Redirect not found in result"
        echo "$CURL_RESULT"
        exit 1
fi

echo "$CURL_RESULT" | sed -n 's/^Location.*code=\([^&]*\)&.*$/\1/p'
