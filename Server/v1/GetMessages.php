<?php 

require_once '../srv/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['chat_id']) && isset($_POST['message_id']))
	{
		$db = new DbOperation(); 
		$chat_id;
		if($_POST['message_id'] !== 0)
		    $chat_id = $db->getNewMessages($_POST['chat_id'], $_POST['message_id']);
		else
            $chat_id = $db->getMessages($_POST['chat_id']);
        
        if($chat_id !== 0)
        {
            $response["error"] = false;
            $response['messages'] = $chat_id;
        }
		else
		{
		    $response["error"] = true;
		    $response["message"] = "No messages in chat";
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

