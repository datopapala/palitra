<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

$status_id 		= $_REQUEST['id'];
$status_name  	= $_REQUEST['name'];
$call_status  	= $_REQUEST['call_status'];


switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$template_id		= $_REQUEST['id'];
		$page		= GetPage(Getstatus($status_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	info_base.id,
										info_base.`name`,
										info_base.`body`
							    FROM 	info_base
							    WHERE 	info_base.actived=1");

		$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_status':
		


		if($status_id != ''){
			Savestatus($status_id, $status_name, $call_status);
			}
			else{
			if(!CheckstatusExist($status_name, $status_id)){
				if ($status_id == '') {
					Addstatus($status_name, $call_status);
				}else {
					
				$error = '"' . $status_name . '" უკვე არის სიაში!';

			}
		}
	}

		break;
	case 'disable':
		$status_id	= $_REQUEST['id'];
		Disablestatus($status_id);

		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Category Functions
* ******************************
*/

function Addstatus($status_name, $call_status)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`info_base`
									(`user_id`,`name`, `body`, `actived`)
								VALUES 	
									('$user_id','$status_name', '$call_status', 1)");
}

function Savestatus($status_id, $status_name, $call_status)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `info_base`
					SET     `user_id`		='$user_id',
							`name` 			= '$status_name',
							`body`	= '$call_status'
					WHERE	`id` 			= $status_id");
}

function Disablestatus($status_id)
{
	mysql_query("	UPDATE `info_base`
					SET    `actived` = 0
					WHERE  `id` = $status_id");
}

function CheckstatusExist($status_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `info_base`
											WHERE  `name` = '$status_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getstatus($status_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`,
													`body`
											FROM    `info_base`
											WHERE   `id` = $status_id" ));

	return $res;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="CallType">სათაური</label></td>
				</tr>
				<tr>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">აღწერა</label></td>
				</tr>
				<tr>
					<td>
						<textarea style="height:350px; width: 550px; resize: none;" id="call_status" class="idle" onblur="this.className=\'idle \'">' . $res['body'] . '</textarea>
					</td>
				</tr>

			</table>
			<!-- ID -->
			<input type="hidden" id="status_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>

