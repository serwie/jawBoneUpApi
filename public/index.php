

<?php
//http://forums.jawbone.com/t5/SUGGESTIONS/Get-JSON-data/td-p/101732

session_start();


unset($_SESSION['access_token']);

$code = null;
$response_type='code';
$client_id = '';
$scope = 'sleep_read ' . 'move_read ' . 'meal_read ' . 'mood_read';

$redirect_uri = 'http://jawBoneUpApi.com';

$app_secret = '';
$grant_type = 'authorization_code';

$access_token = null;

if (!$_SESSION['loggedIn']) {
	unset($_SESSION['access_token']);
}



if (isset ( $_GET ['code'] )) { // make HTTPS call
	
	$code = $_GET ['code'];
	
	//var_dump ( $code );
	
	$requestUrl = 'https://jawbone.com/auth/oauth2/token';
	$data = array (
			"client_id" => $client_id,
			"client_secret" => $app_secret,
			"grant_type" => $grant_type,
			"code" => $code 
	);
	
	$options = array (
			'http' => array (
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query ( $data ) 
			) 
	);
	$context = stream_context_create ( $options );
	$result = file_get_contents ( $requestUrl, false, $context );
	$result = json_decode ( $result, true );
	$_SESSION ['access_token'] = $result ['access_token'];
	$_SESSION ['refresh_token'] = $result ['refresh_token'];
	$_SESSION['loggedIn'] = true;
	
// 	$redirect = 'http://' . $_SERVER ['HTTP_HOST'];
// 	header ( 'Location: ' . filter_var ( $redirect, FILTER_SANITIZE_URL ) );
}

if (isset ( $_SESSION ['access_token'] ) && $_SESSION ['access_token']) {
	
	$access_token = $_SESSION ['access_token'] ;

} else {
	$_SESSION['loggedIn'] = false;
	$authUrl = 'https://jawbone.com/auth/oauth2/auth?response_type=' . $response_type . '&redirect_uri=' . $redirect_uri . '&scope=' . $scope . '&client_id=' . $client_id;
}


if ($access_token) {
	$header = array();
	$header[] = 'Authorization: Bearer ' . $access_token;
	$header[] ='Content-type:application/json';
			
		
// 	$start_time = new DateTime ();
// 	$start_time->add(DateInterval::createFromDateString('yesterday'));
// 	$start_time = $start_time->getTimestamp ();
	
	$start_time = mktime(21,00,00,10,03,2014);

	
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,'https://jawbone.com/nudge/api/v.1.1/users/@me/moves?start_time='.$start_time);
	
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$query_move = curl_exec($curl_handle);
	curl_close($curl_handle);
	
	$response_move = json_decode ( $query_move, true );
// 	var_dump($response_move);
	echo '<b> MOVE DATA </b> <br>' ;
	echo 'Start Time: '.$start_time;
	//var_dump($response_move['data']);
	//var_dump($response_move['data']['items']['0']);
	//var_dump($response_move['data']['items']['0']['title']);
	var_dump($response_move['data']['items']['0']);
	
	$curl_handle=curl_init();
	
	curl_setopt($curl_handle, CURLOPT_URL,'https://jawbone.com/nudge/api/v.1.1/users/@me/sleeps?start_time='.$start_time);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$query_sleep = curl_exec($curl_handle);
	curl_close($curl_handle);
	
	$response_sleep = json_decode ( $query_sleep, true );
	
	echo '<b> SLEEP DATA </b> <br>';
	
// 	var_dump($response_sleep['data']);
	
	var_dump($response_sleep['data']['items']['0']['title']);
	
	
	$header = array ();
	$header [] = 'Authorization: Bearer ' . $access_token;
	$header [] = 'Content-type:application/json';
	
	$curl_handle = curl_init ();
	
	curl_setopt ( $curl_handle, CURLOPT_URL, 'https://jawbone.com//nudge/api/v.1.1/users/@me/meals?start_time=' . $start_time );
	curl_setopt ( $curl_handle, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt ( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $curl_handle, CURLOPT_HTTPHEADER, $header );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$query_meals = curl_exec ( $curl_handle );
	curl_close ( $curl_handle );
	
	$response_meals = json_decode ( $query_meals, true );
	
	echo '<b> MEAL DATA </b> <br>';
	
	// 	var_dump($response_sleep['data']);
	
	var_dump($response_meals['data']['items']['1']['details']['calories']);
	
	

	$header = array ();
	$header [] = 'Authorization: Bearer ' . $access_token;
	$header [] = 'Content-type:application/json';
	
	$curl_handle = curl_init ();
	
	curl_setopt ( $curl_handle, CURLOPT_URL, 'https://jawbone.com//nudge/api/v.1.1/users/@me/mood?start_time=' . $start_time );
	curl_setopt ( $curl_handle, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt ( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $curl_handle, CURLOPT_HTTPHEADER, $header );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$query_mood = curl_exec ( $curl_handle );
	curl_close ( $curl_handle );
	
	$response_mood = json_decode ( $query_mood, true );
	
	echo '<b> MOOD DATA </b> <br>';
	
	// 	var_dump($response_sleep['data']);
	
	var_dump($response_mood['data']['title']);
	
}

?>
<div class="box">
	<div class="request">
    	<?php if (isset($authUrl)) { ?>
      	<a class='login' href='<?php echo $authUrl; ?>'>Connect Me!</a>
      	<?php }else{
      		//echo 'code '.$code;
      	} ?>
     </div>
</div>




