<?php
$game = "assaultandroidcactus";
$slug = "assault-android-cactus";
$url = "https://api.isthereanydeal.com/v01/game/prices/?key=6c235fc36e0e145a03011c4a28b4acfc9d4cb418&plains=". $game ."&country=US";

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec($ch);

curl_close($ch);

//echo $response;

$data = json_decode($response);

$price =  $data->data->$game->list[0]->price_new;

//Database connection
$dsn = 'mysql:host=localhost;dbname=capstone_db';
        $username = 'tasfia';
        $password = 'goldfishLumberjack59';

try{
        $db = new PDO($dsn, $username, $password);
} catch(PDOException $e) {
        $error_message = $e->getMessage();
        print($error_message);
        exit();
}

$query = "INSERT INTO test_isthereanyapi (name, slug, price) VALUES ('$game', '$slug', '$price')";
$db->exec($query);
?>