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
		$cardtype_id		= $_REQUEST['id'];
		$page		= GetPage(Getcard_type($cardtype_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	id,
										`name`
							    FROM 	call_status
							    WHERE 	actived=1");

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
	case 'save_cardtype':
		$cardtype_id 		= $_REQUEST['id'];
		$cardtype_name    = $_REQUEST['name'];



		if($cardtype_name != ''){
			if(!Checkcard_typeExist($cardtype_name, $cardtype_id)){
				if ($cardtype_id == '') {
					Addcard_type( $cardtype_id, $cardtype_name);
				}else {
					Savecard_type($cardtype_id, $cardtype_name);
				}

			} else {
				$error = '"' . $cardtype_name . '" უკვე არის სიაში!';

			}
		}

		break;
	case 'disable':
		$cardtype_id	= $_REQUEST['id'];
		Disablecard_type($cardtype_id);

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

function Addcard_type($cardtype_id, $cardtype_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 `call_status`
								(`user_id`,`name`)
					VALUES 		('$user_id','$cardtype_name')");
}

function Savecard_type($cardtype_id, $cardtype_name)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `call_status`
					SET    `user_id`='$user_id',
							 `name` = '$cardtype_name'
					WHERE	`id` = $cardtype_id");
}

function Disablecard_type($cardtype_id)
{
	mysql_query("	UPDATE `call_status`
					SET    `actived` = 0
					WHERE  `id` = $cardtype_id");
}

function Checkcard_typeExist($cardtype_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `call_status`
											WHERE  `name` = '$cardtype_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getcard_type($cardtype_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `call_status`
											WHERE   `id` = $cardtype_id" ));

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
			<input type="hidden" id="cardtype_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
