<?php 

require_once '../srv/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['user_id']) && isset($_POST['username']))
	{
		$db = new DbOperation(); 
        $chat_id = $db->startChat($_POST['user_id'], $_POST['username']);
        
		if($chat_id === 0)
		{
		    $response['message'] = "Some error happend..";
			$response['error'] = true; 
		}
		else
		{
			$response['message'] = "Chat created";
			$response['error'] = false; 	
			$response['chat_id'] = $chat_id;
		}
	}
	else
	{
		$response['error'] = true; 
		$response['message'] = "Required fields are missing";
	}
}
else
{
    $response['error'] = true; 
	$response['message'] = "Some shit";
}

echo json_encode($response);
?>

