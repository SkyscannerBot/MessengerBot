<?php
// parameters
$verify_token = 'hasbabalar';
$access_token = "EAACGUAjB1K4BAJpYYqzGN9mGvwqWk99OUaSbDB2k0Oc32WRDC9JVjRSobnqk32uZBEmA2K8FAZA1PmKGG8tVpjltA3Arh4FiPu9RTDCbYdamrsS4qG3PitZAI2cMhUpirXDu51bEnxloZAyjE5tMSotiAEW6gdK8PAZALZA4tZA9gZDZD";
$hub_verify_token = null;

$challenge = $_GET['hub_challenge'];
echo $challenge;
$hub_verify_token = $_REQUEST['hub_verify_token'];

$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = 'SelamALeykum';
/**
 * Some Basic rules to validate incoming messages
 */
if(preg_match('[time|current time|now]', strtolower($message))) {
    // Make request to Time API
    ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
    $result = file_get_contents("http://www.timeapi.org/utc/now?format=%25a%20%25b%20%25d%20%25I:%25M:%25S%20%25Y");
    if($result != '') {
        $message_to_reply = $result;
    }
} else {
    $message_to_reply = 'Selam aleykum gardas.';
}

//skyscanner
$str = '22.06.2017, IST -> ESB, 2 adults';
$sentences = preg_split("/[\s,]+/", $str);


$sentences2 = preg_split("/[\s.]+/", $sentences[0]);
print_r($sentences2);

$url = 'http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/Tr/Try/en-US/'.$sentences[1].'/'.$sentences[3].'/'.$sentences2[2].'-'.$sentences2[1].'-'.$sentences2[0].'?apikey=prtl6749387986743898559646983194';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$content = curl_exec($ch);
//skyscanner

if($message == "Selam")
 $message_to_reply = 'Selammmmm';

$json = file_get_contents('http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/TR/try/en-US/ISTA-sky/ADB-sky/2017-04-12/2017-04-19?apikey=prtl6749387986743898559646983194');

$obj = json_decode($json);
//echo $obj->Quotes[0]->QuoteId;

echo $json;
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}
?>
