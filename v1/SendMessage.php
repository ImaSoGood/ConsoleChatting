<?php 

require_once '../srv/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['user_id']) && isset($_POST['message']) && isset($_POST['chat_id']))
	{
		$db = new DbOperation(); 
        $message_id = $db->sendMessage($_POST['user_id'], $_POST['message'], $_POST['chat_id']);
        
		if($message_id !== 0)
		{
		    $response['message_id'] = $message_id;
			$response['error'] = false; 
		}
		else
		{
			$response['message'] = "Error happend";
			$response['error'] = true; 	
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

