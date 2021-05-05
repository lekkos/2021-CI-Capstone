<?php
	// Author: Ryan Morgan
	// Set usernames and access tokens
	$username = 'Morgan34API';
	$password = 'spgg4MMP!';
	$clientId = 'Kc0d94gJLkqoGA';
	$clientSecret = 'eJw6JDYXRP64CCxaLBWQzjrV080fdQ';
	$params = array(
		'grant_type' => 'password',
		'username' => $username,
        'password' => $password
	);
	
	// mysql login
	$dsn = 'mysql:host=localhost;dbname=capstone_db';
	$dbuser = 'ryan';
	$dbpass = 'pigMachine12';
	
	$conn = new mysqli("localhost", $dbuser, $dbpass, "capstone_db");
	if ($conn->connect_error) {
	   die("Connection failed:" . $conn-connect_error);
	}
	
	// request OAuth token
	$ch = curl_init( 'https://www.reddit.com/api/v1/access_token' );
	curl_setopt( $ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
	$response_raw = curl_exec( $ch );
	$response = json_decode( $response_raw );
	curl_close( $ch );
	
	// Send request
	$accessToken = $response->access_token;
	$accessTokenType = 'bearer';
	$apiCallEndpoint = 'https://oauth.reddit.com/r/Gaming/new';
	$apiCallEndpoint2 = 'https://oauth.reddit.com/r/GameDeals/hot';
	
	// endpoint for r/gaming
	$ch = curl_init( $apiCallEndpoint );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt( $ch, CURLOPT_USERAGENT, 'CapstoneAPI/0.0.1');
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Authorization: " . $accessTokenType . " " . $accessToken ) );

	// endpoint for r/gamedeals
	$ch2 = curl_init( $apiCallEndpoint2 );
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt( $ch2, CURLOPT_USERAGENT, 'CapstoneAPI/0.0.1');
        curl_setopt( $ch2, CURLOPT_HTTPHEADER, array( "Authorization: " . $accessTokenType . " " . $accessToken ) );
	
	$response_raw = curl_exec( $ch );
	$response_raw2 = curl_exec( $ch2 );
	curl_close( $ch );
	curl_close( $ch2 );
	$response = json_decode ( $response_raw );
	$response2 = json_decode ( $response_raw2 );
	$response = $response->data->children;
	$response2 = $response2->data->children;
	for($i=0;$i<20; $i++)
	{
		$items=$response[$i];
		$items2=$response2[$i];

		$title = $items->data->title;
		$author = $items->data->author;
		$text = $items->data->selftext;
		$id = $items->data->id;

		$title2 = $items2->data->title;
		$author2 = $items->data->author;
                $text2 = $items->data->selftext;
                $id2 = $items->data->id;
		
		if ($text != "") {
		   $sql = "INSERT INTO reddit_api (id, author, titles, selftext) VALUES ('$id', '$author', '$title', '$text');";
		   if ($conn->query($sql) === TRUE) {
		      // echo "Inserted Successfully <br>";
		   } else {
		     // echo "Error: " . $sql . "<br>" . $db->error;
		   }
		}

		$sql2 = "INSERT INTO reddit_gamedeals (id, author, titles, selftext) VALUES ('$id2', '$author2', '$title2', '$text2');";
                if ($conn->query($sql2) === TRUE) {
                   // echo "Inserted Successfully <br>";
                } else {
                  // echo "Error: " . $sql . "<br>" . $db->error;
                }
		
	}
	$conn->close();
?>