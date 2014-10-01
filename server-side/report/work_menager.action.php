<?php
require_once ('../../includes/classes/core.php');
$action 	= $_REQUEST['act'];

$error		= '';
$data		= '';
switch ($action) {
	case 'get_list0' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
	  	$rResult 	= mysql_query("SELECT 	person_work_graphic.id,
									persons.`name`,
									week_day.`name`,
									TIME_FORMAT(`start`, '%H:%i'),
									TIME_FORMAT(`breack_start`, '%H:%i'),
									TIME_FORMAT(`breack_end`, '%H:%i'),
									TIME_FORMAT(`end`, '%H:%i')
								FROM person_work_graphic
								JOIN users   ON users.id=person_work_graphic.user_id
								JOIN persons ON persons.id=users.person_id
								JOIN work_graphic ON work_graphic.id=person_work_graphic.work_graphic_id
								JOIN week_day ON work_graphic.week_day_id=week_day.id
								WHERE person_work_graphic.`status`=1 AND work_graphic.actived=1");

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
		case 'get_list1' :
$time = array(  '09:00',
                '09:15',
                '09:30',
                '09:45',
                '10:00',
                '10:15',
                '10:30',
                '10:45',
                '11:00',
                '11:15',
                '11:30',
                '11:45',
                '12:00',
                '12:15',
                '12:30',
                '12:45',
                '13:00',
                '13:15',
                '13:30',
                '13:45',
                '14:00',
                '14:15',
                '14:30',
                '14:45',
                '15:00',
                '15:15',
                '15:30',
                '15:45',
                '16:00',
                '16:15',
                '16:30',
                '16:45',
                '17:00',
                '17:15',
                '17:30',
                '17:45',
                '18:00',
                '18:15',
                '18:30',
                '18:45',
                '19:00',
                '19:15',
                '19:30',
                '19:45',
                '20:00');

$result 	= mysql_query("SELECT	persons.`name` AS `name`,
        							TIME_FORMAT(`start`, '%H:%i') AS `start`,
        							TIME_FORMAT(`breack_start`, '%H:%i') AS `breack_start`,
        							TIME_FORMAT(`breack_end`, '%H:%i') AS `breack_end`,
        							TIME_FORMAT(`end`, '%H:%i') AS `end`
							FROM 	work_graphic
							JOIN    person_work_graphic ON work_graphic.id = person_work_graphic.work_graphic_id
							JOIN    users ON users.id = person_work_graphic.user_id
							JOIN    persons ON persons.id = users.person_id
							WHERE   person_work_graphic.`status` = 2 AND person_work_graphic.date='$_REQUEST[time]' AND work_graphic.actived=1
                            ");


$data1  = '<table style="font-weight: bold;">';
$data1 .= '<tr style="height:30px; ">';
$data1 .= '<td><div style="width: 160px; " ></div></td>';

for ($i = 0; $i < sizeof($time); $i++) {
    $data1.='<td><div style="transform: rotate(270deg);margin: 12px -7px;" >' . $time[$i] . '</div></td>';
}

$data1 .= '</tr>';

while ( $row = mysql_fetch_array( $result ) ){

    $data1 .= '<tr>';
    $data1 .= '<td>' . $row['name'] . '</td>';

    for ($i = 0; $i < sizeof($time); $i++) {

        switch ($time[$i]) {
            case $time[$i] >= $row['start'] && $time[$i] <= $row['breack_start']:
                $data1.='<td style="background: red;"></td>';
            break;

            case $time[$i] >= $row['breack_start'] && $time[$i] <= $row['breack_end']:
            	$data1.='<td style="background: green;"></td>';
            break;

            case $time[$i] >= $row['breack_end'] && $time[$i] <= $row['end']:
                $data1.='<td style="background: red;"></td>';
            break;

            default:
                $data1.='<td></td>';
            break;
        }
    }

    $data1 .= '</td>';

}
$data1 .= '</table>';
$data['aaData'] = $data1;
break;
	case "get_edit_page":
	$data['page'][]=page();
	break;
	case 'disable':
		mysql_query("UPDATE `person_work_graphic` SET `status`='2' WHERE (`id`='$_REQUEST[id]')");
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
				where id='$_REQUEST[id]'
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