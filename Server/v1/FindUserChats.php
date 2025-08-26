<?php 

require_once '../srv/DbOperations.php';

$response = array(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['user_id']))
	{
		$db = new DbOperation(); 
        $chats = $db->findAllChats($_POST['user_id']);
        
		if($chats === 0)
		{
		    $response['message'] = "No chats in list :)";
			$response['error'] = true; 
		}
		else
		{
			$response['error'] = false; 	
			$response['chats'] = $chats;
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

