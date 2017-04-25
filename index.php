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
$message_to_reply = ' ';

//skyscanner
$str = '22.06.2017, IST -> ESB, 2 adults';

$sentences = preg_split("/[\s,]+/", $message);

$sentences2 = preg_split("/[\s.]+/", $sentences[0]);

$url = 'http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/Tr/Try/en-US/'.$sentences[1].'/'.$sentences[3].'/'.$sentences2[2].'-'.$sentences2[1].'-'.$sentences2[0].'?apikey=prtl6749387986743898559646983194';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$content = curl_exec($ch);
echo $content;
$obj = json_decode($content);
echo $sentences[2];


$printObj = 'The flight choosen for you:\n';
if($obj->Places[0]->IataCode == $sentences[2]){
$printObj .= 'Departure Airport: ' .$obj->Places[0]->Name . '\n';
$printObj .= 'Arrival Airport: ' .$obj->Places[1]->Name . '\n';
}
else{
$printObj .= 'Departure Airport: ' .$obj->Places[1]->Name . '\n';
$printObj .= 'Arrival Airport: ' .$obj->Places[0]->Name . '\n';
}
 
$printObj .= 'Date: ' .strtok($obj->Quotes[0]->OutboundLeg->DepartureDate , 'T') . '\n';
$floatPrice = $obj->Quotes[0]->MinPrice * (int)$sentences[4];
$printObj .= 'Price for ' .$sentences[4] .' people: ' .$floatPrice . ' ' .$obj->Currencies[0]->Code . '\n';
$printObj .= 'Carrier: ' .$obj->Carriers[0]->Name . '\n';

//skyscanner
if($message == "Hello" || $message == "Hi"||$message == "hi" ||$message == "hello"){
 $message_to_reply = 'Hi. \nFor querying flights the format has to be like :\n22.06.2017, IST -> ESB, 2 adults';
}
elseif($obj->Quotes[0]->MinPrice > 0){
  $message_to_reply = $printObj;
}
else{
  $message_to_reply = 'Format error.\nFor querying flights the format has to be like :\n22.06.2017, IST -> ESB, 2 adults';
}

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
