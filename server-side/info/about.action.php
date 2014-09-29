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
										info_base.`name`
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
			}
			$data['aaData'][] = $row;
		}
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
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['name'] . '"disabled="disabled" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">აღწერა</label></td>
				</tr>
				<tr>
					<td>
						<textarea style="height:350px; width: 550px; resize: none;" id="call_status" class="idle" onblur="this.className=\'idle \'"disabled="disabled">' . $res['body'] . '</textarea>
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

