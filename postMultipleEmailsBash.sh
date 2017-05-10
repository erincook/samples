URL="http://localhost:51010/content/dist"
#==========================================================

echo "Sending $PRODUCT from $URL to $CS_ADDR"
for ADDR in "${EMAIL_LIST[@]}"
do
  echo "$ADDR"
done
echo
read -r -p "Continue? [Y/n] " response
case $response in
    [nN])
        exit 0
        ;;
esac

echo "====================================================="

SUB_TR=""
if [ $PRODUCT = "snow" ] || [ $PRODUCT = "rain" ] || [ $PRODUCT = "icyprcp" ] ; then
  SUB_TR=", \"subscription_threshold\": \"n\""
fi

if [ $PRODUCT = "excold" ] ; then
  SUB_TR=", \"subscription_threshold\": \"100\""
fi

if [ $PRODUCT = "exheat" ] ; then
  SUB_TR=", \"subscription_threshold\": \"1\""
fi

for ADDR in "${EMAIL_LIST[@]}"
do
  echo
  echo "To $ADDR"
  sleep 1
  curl -0 -X POST $URL \
  -H "Expect:" \
  -H 'Content-Type: text/json; charset=utf-8' \
-d @- << EOF
[
    {
      "renderType": "Folder",
      "data": {
        "product": "$PRODUCT",
        "channel": "smtp",
        "language": "en-us",
        "locationCode": "32550:4:US",
        "subscription_address": "$ADDR",
        "subId": "a",
        "subscription_userid": "Test123"
        $SUB_TR
      },
      "headers": {
      }
    }
  ]
EOF
done

echo
echo
echo "DONE"
