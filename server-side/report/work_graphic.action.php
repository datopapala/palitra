<?php

require_once ('../../includes/classes/core.php');

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
switch ($action) {
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
	  	$rResult 	= mysql_query("SELECT 	id ,
				TIME_FORMAT(`start`, '%H:%i'),
				TIME_FORMAT(`breack_start`, '%H:%i'),
				TIME_FORMAT(`breack_end`, '%H:%i'),
				TIME_FORMAT(`end`, '%H:%i')
				FROM `work_graphic`
	  			where week_day_id=$_REQUEST[dey] AND actived=1
	  			");

		$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
				$row[] = $aRow[$i];

			}
			$data['aaData'][] = $row;
		}

		break;
	case "get_edit_page":
	$data['page'][]=page();
	break;
	case 'disable':
		mysql_query("UPDATE `work_graphic` SET `actived`='0' WHERE (`id`='$_REQUEST[id]')");
		break;
	case 'get_add_page' :
	$data['page'][]=page();
		break;
   	case 'save_dialog' :
   		if($_REQUEST[id]==''){
		mysql_query("
				INSERT INTO `work_graphic` (`week_day_id`, `start`, `breack_start`, `breack_end`, `end`)
				VALUES ('$_REQUEST[week_day_id]', '$_REQUEST[start]', '$_REQUEST[breack_start]', '$_REQUEST[breack_end]', '$_REQUEST[end]')
		");}
		else{

			mysql_query("UPDATE `work_graphic` SET
			`start`='$_REQUEST[start]',
			`breack_start`='$_REQUEST[breack_start]',
			`breack_end`='$_REQUEST[breack_end]',
			`end`='$_REQUEST[end]' WHERE (`id`='$_REQUEST[id]')");
		}
   		break;
	default:
		$error = 'Action is Null';
}
function page()
{
		$rResult 	= mysql_query("SELECT 	id ,
				TIME_FORMAT(`start`, '%H:%i') start,
				TIME_FORMAT(`breack_start`, '%H:%i') as breack_start,
				TIME_FORMAT(`breack_end`, '%H:%i') breack_end,
				TIME_FORMAT(`end`, '%H:%i') as end
				FROM `work_graphic`
				where id='$_REQUEST[id]' AND work_graphic.actived=1
	  			");
		$res = mysql_fetch_array( $rResult );

	return '
	<div id="dialog-form">
		<fieldset >
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 200px;"><label for="">მუშაობის დასაწყისი</label></td>
					<td style="width: 200px;"><label for="">შესვენების დასაწყისი</label></td>
				</tr>
					<td><input id="start" 	     class="idle time" type="text" value="'.$res[start]. 	'" /></td>
					<td><input id="breack_start" class="idle time" type="text" value="'.$res[breack_start].'" /></td>
				<tr>
					<td style="width: 200px;"><label for="">შესვენების დასასრული</label></td>
					<td style="width: 200px;"><label for="">მუშაობის დასასრული</label></td>
				</tr>
				</tr>
					<td><input id="breack_end" 	 class="idle time" type="text" value="'.$res[breack_end]. 	'" /></td>
					<td><input id="end" class="idle time" type="text" value="'.$res[end].'" /></td>
				<tr>
			</table>
		</fieldset >
	</div>
<input type="hidden" id="id" value='.$_REQUEST[id].'>';

}

$data['error'] = $error;

echo json_encode($data);

?>