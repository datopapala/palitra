<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$paytype_id		= $_REQUEST['id'];
		$page		= GetPage(Getpay_type($paytype_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT persons.`name`,
										persons.`name`,
								       task_scenar.date,
								       phone.first_last_name,
								       phone.note,
								       phone.phone1,
								       phone.phone2,
								       task_scenar.b1,
								       task_scenar.b2
								FROM   `task_scenar`
								JOIN    task_detail ON task_scenar.task_detail_id = task_detail.id
								JOIN    phone ON task_detail.phone_base_id = phone.id
								JOIN    users ON users.id = task_detail.responsible_user_id 
								JOIN    persons ON persons.id = users.person_id");

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
	case 'save_paytype':
		$paytype_id 		= $_REQUEST['id'];
		$paytype_name    = $_REQUEST['name'];



		if($paytype_name != ''){
			if(!Checkpay_typeExist($paytype_name, $paytype_id)){
				if ($paytype_id == '') {
					Addpay_type( $paytype_id, $paytype_name);
				}else {
					Savepay_type($paytype_id, $paytype_name);
				}

			} else {
				$error = '"' . $paytype_name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		$paytype_id	= $_REQUEST['id'];
		Disablepay_type($paytype_id);

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

function Addpay_type($paytype_id, $paytype_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`city`
									(`user_id`,`name`)
						VALUES 		('$user_id','$paytype_name')");
}

function Savepay_type($paytype_id, $paytype_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE  `city`
					SET     `user_id`='$user_id',
							`name` = '$paytype_name'
					WHERE	`id` = $paytype_id");
}

function Disablepay_type($paytype_id)
{
	mysql_query("	UPDATE `city`
					SET    `actived` = 0
					WHERE  `id` = $paytype_id");
}

function Checkpay_typeExist($paytype_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `city`
											WHERE  `name` = '$paytype_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getpay_type($paytype_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `city`
											WHERE   `id` = $paytype_id" ));

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

			</table>
			<!-- ID -->
			<input type="hidden" id="paytype_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
