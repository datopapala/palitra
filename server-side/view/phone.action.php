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
		$page		= GetPage(Getedit($status_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list_import' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		$pager 	= $_REQUEST['pager'];
		$pager_ch = 0;
		if($pager == 0){
			$pager_ch = 0;
		}else{
			$pager_ch = $pager.'000';
		}	
		
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
									WHERE	actived = 1
									LIMIT 	1000 OFFSET $pager_ch");

		$data = array(
				"aaData"	=> array()
		);
	
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = addslashes($aRow[$i]);
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
		$pager 	= $_REQUEST['pager'];
		$pager_ch = 0;
		if($pager == 0){
			$pager_ch = 0;
		}else{
			$pager_ch = $pager.'000';
		}
			
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
									WHERE incomming_call.phone != ''
									LIMIT 	1000 OFFSET $pager_ch");
	
		$data = array(
				"aaData"	=> array()
		);
	
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = addslashes($aRow[$i]);
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
		$pager 	= $_REQUEST['pager'];
		$pager_ch = 0;
		if($pager == 0){
			$pager_ch = 0;
		}else{
			$pager_ch = $pager.'000';
		}
			
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
									WHERE	actived = 1
									LIMIT 	1000 OFFSET $pager_ch");
	
		$data = array(
				"aaData"	=> array()
		);
	
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = addslashes($aRow[$i]);
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'up_phone_base':
		$phone1 			= $_REQUEST['phone1'];
		$phone2 			= $_REQUEST['phone2'];
		$first_last_name 	= $_REQUEST['first_last_name'];
		$person_n 			= $_REQUEST['person_n'];
		$addres 			= $_REQUEST['addres'];
		$city 				= $_REQUEST['city'];
		$mail 				= $_REQUEST['mail'];
		$born_day 			= $_REQUEST['born_day'];
		$sorce 				= $_REQUEST['sorce'];
		$person_status 		= $_REQUEST['person_status'];
		$note 				= $_REQUEST['note'];
		
			UpPhoneBase($status_id, $phone1, $phone2, $first_last_name, $person_n, $addres, $city, $mail, $born_day, $sorce, $person_status, $note);
			
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

function UpPhoneBase($status_id, $phone1, $phone2, $first_last_name, $person_n, $addres, $city, $mail, $born_day, $sorce, $person_status, $note)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE 	`phone` SET 
							`user_id`			='$user_id',  
							`phone1`			='$phone1', 
							`phone2`			='$phone2', 
							`first_last_name`	='$first_last_name', 
							`person_n`			='$person_n', 
							`addres`			='$addres', 
							`person_status`		='$person_status', 
							`mail`				='$mail', 
							`city`				='$city', 
							`born_day`			='$born_day', 
							`sorce`				='$sorce', 
							`note`				='$note'
					WHERE 	`id`				='$status_id' ");
}

function Disablestatus($status_id)
{
	mysql_query("	UPDATE `phone`
					SET    `actived` = 0
					WHERE  `id` = $status_id");
}

function Getedit($status_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT 	id,
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
									WHERE	id= $status_id" ));

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
					<td style="width: 130px;"><label for="CallType">ტელეფონი 1</label></td>
					<td>
						<input style="width: 120px;" type="text" id="phone1" class="idle" onblur="this.className=\'idle \'" value="' . $res['phone1'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">ტელეფონი 2</label></td>
					<td>
						<input style="width: 120px;" type="text" id="phone2" class="idle" onblur="this.className=\'idle \'" value="' . $res['phone2'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 130px;"><label for="CallType">სახელი / გვარი</label></td>
					<td>
						<input style="width: 120px;" type="text" id="first_last_name" class="idle" onblur="this.className=\'idle \'" value="' . $res['first_last_name'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">პირადი N</label></td>
					<td>
						<input style="width: 120px;" type="text" id="person_n" class="idle" onblur="this.className=\'idle \'" value="' . $res['person_n'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 130px;"><label for="CallType">მისამართი</label></td>
					<td>
						<input style="width: 120px;" type="text" id="addres" class="idle" onblur="this.className=\'idle \'" value="' . $res['addres'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">ქალაქი</label></td>
					<td>
						<input style="width: 120px;" type="text" id="city" class="idle" onblur="this.className=\'idle \'" value="' . $res['city'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 130px;"><label for="CallType">ელ-ფოსტა</label></td>
					<td>
						<input style="width: 120px;" type="text" id="mail" class="idle" onblur="this.className=\'idle \'" value="' . $res['mail'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">დაბ. წელი</label></td>
					<td>
						<input style="width: 120px;" type="text" id="born_day" class="idle" onblur="this.className=\'idle \'" value="' . $res['born_day'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 130px;"><label for="CallType">განყოფილება</label></td>
					<td>
						<input style="width: 120px;" type="text" id="sorce" class="idle" onblur="this.className=\'idle \'" value="' . $res['sorce'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">ფორმირების თარიღი</label></td>
					<td>
						<input disabled style="width: 120px;" type="text" id="create_date" class="idle" onblur="this.className=\'idle \'" value="' . $res['create_date'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 130px;"><label for="CallType">ფიზიკური/იურიდიული</label></td>
					<td>
						<input style="width: 120px;" type="text" id="person_status" class="idle" onblur="this.className=\'idle \'" value="' . $res['person_status'] . '" />
					</td>
					<td style="width: 130px;"><label style="margin-left:10px;" for="CallType">შენიშვნა</label></td>
					<td>
						<input style="width: 120px;" type="text" id="note" class="idle" onblur="this.className=\'idle \'" value="' . $res['note'] . '" />
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

