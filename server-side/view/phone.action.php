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
	case 'get_list_import' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("	SELECT 	id,
											phone1,
											phone2,
											first_last_name,
											person_n,
											addres,
											city,
											mail,
											born_day,
											sorce,
											create_date,
											person_status,
											note
											
									FROM 	`phone`
									WHERE	actived = 1");

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
	case 'get_list_incomming' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("	SELECT 	incomming_call.id,
											incomming_call.phone,
											'',
											incomming_call.first_name,
											'',
											'',
											'',
											'',
											'',
											'',
											incomming_call.date,
											IF(incomming_call.type_id=1, 'ფიზიკური','იურიდიული') AS `type`
									FROM 	incomming_call
									LEFT JOIN	personal_info ON incomming_call.id = personal_info.incomming_call_id
									LEFT JOIN	department ON incomming_call.department_id = department.id
									LEFT JOIN	city ON personal_info.personal_city = city.id
									WHERE incomming_call.phone != ''");
	
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
	case 'get_list_all' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("	SELECT 	incomming_call.id,
											incomming_call.phone,
											personal_info.personal_phone,
											incomming_call.first_name,
											personal_info.personal_id,
											personal_info.personal_addres,
											city.`name`,
											personal_info.personal_mail,
											personal_info.personal_d_date,
											department.`name`,
											incomming_call.date,
											IF(incomming_call.type_id=1, 'ფიზიკური','იურიდიული') AS `type`
									FROM 	incomming_call
									LEFT JOIN	personal_info ON incomming_call.id = personal_info.incomming_call_id
									LEFT JOIN	department ON incomming_call.department_id = department.id
									LEFT JOIN	city ON personal_info.personal_city = city.id
									WHERE incomming_call.phone != ''
									UNION ALL
									SELECT 	id,
											phone1,
											phone2,
											first_last_name,
											person_n,
											addres,
											city,
											mail,
											born_day,
											sorce,
											create_date,
											person_status
											
									FROM 	`phone`
									WHERE	actived = 1");
	
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
	case 'save_template':
		


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
	mysql_query("INSERT INTO 	 	`status`
									(`user_id`,`name`, `call_status`, `actived`)
								VALUES 	
									('$user_id','$status_name', '$call_status', 1)");
}

function Savestatus($status_id, $status_name, $call_status)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `status`
					SET     `user_id`		='$user_id',
							`name` 			= '$status_name',
							`call_status`	= '$call_status'
					WHERE	`id` 			= $status_id");
}

function Disablestatus($status_id)
{
	mysql_query("	UPDATE `phone`
					SET    `actived` = 0
					WHERE  `id` = $status_id");
}

function CheckstatusExist($status_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `status`
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
													`call_status`
											FROM    `status`
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
					<td style="width: 170px;"><label for="CallType">სახელი</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">საუბრის შინაარსი</label></td>
					<td>
						<input type="text" id="call_status" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['call_status'] . '" />
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

