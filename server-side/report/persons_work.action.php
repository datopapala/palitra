<?php

require_once ('../../includes/classes/core.php');
$user_id	= $_SESSION['USERID'];

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
switch ($action) {
	case 'get_list' :
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
			  	$rResult 	= mysql_query("
			  			SELECT 			week_day.id ,
										week_day.`name`,
										work_graphic.`start`,
										work_graphic.breack_start,
										work_graphic.`breack_end`,
										work_graphic.end,
			  							CONCAT('<div style=\'background-color:',
																						CASE person_work_graphic.`status`
																									WHEN 1 THEN 'Yellow'
																									WHEN 2 THEN 'Green'
																									ELSE	 'red'
																						END ,';width: 100%; height: 100%;\'></div>')
						FROM `week_day`
						LEFT JOIN person_work_graphic ON week_day.id=DAYOFWEEK(person_work_graphic.date) AND person_work_graphic.user_id=$user_id
			  			AND  WEEKOFYEAR(person_work_graphic.date + INTERVAL 1 DAY) =WEEKOFYEAR(('".date('Y-m-d', strtotime($_REQUEST[date]))."'+ INTERVAL 1 DAY))
						LEFT JOIN work_graphic ON person_work_graphic.work_graphic_id=work_graphic.id AND work_graphic.actived=1

			  			");

		$data = array(
				"aaData"	=> array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{

			$data['aaData'][] = $aRow;
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
	case 'get_list1':
		$count 		= $_REQUEST['count'];
		$hidden 	= $_REQUEST['hidden'];
		$res1=   mysql_fetch_array(mysql_query
		("SELECT work_graphic_id
				FROM person_work_graphic
				WHERE `date`=
				 ('".date('Y-m-d', strtotime($_REQUEST[date]))."') + INTERVAL $_REQUEST[id]-1 DAY
				 AND user_id=$user_id")
		);
		//return 0;
		$rResult 	= mysql_query(" SELECT 	work_graphic.id,
									work_graphic.`start`,
									work_graphic.`breack_start`,
									work_graphic.`breack_end`,
									work_graphic.`end`
									FROM `work_graphic`
									left JOIN person_work_graphic ON person_work_graphic.work_graphic_id = work_graphic.id
									WHERE work_graphic.week_day_id = $_REQUEST[id] AND work_graphic.actived=1");

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
				//	echo "$res1[0]__$aRow[id]";

					if ($res1[0]==$aRow[$hidden]){
						$row[] = '<input type="radio" name="check" class="check" checked value="' . $aRow[$hidden] . '" />';
					}else{
					$row[] = '<input type="radio" name="check" class="check" value="' . $aRow[$hidden] . '" />';}
				}
				$row[] = $aRow[$i];

			}
			$data['aaData'][] = $row;
		}


		break;
   	case 'save_dialog' :
   		$res1=   mysql_fetch_array(mysql_query("SELECT id
   				FROM person_work_graphic
   				WHERE `date`=(('".date('Y-m-d', strtotime($_REQUEST[date]))."') + INTERVAL $_REQUEST[dey]-1 DAY) AND user_id='$user_id'"));
   		if($res1[0]!=''){
   			mysql_query("UPDATE `person_work_graphic` SET `work_graphic_id`='$_REQUEST[work]',`status`='1' WHERE (`id`='$res1[0]')");
   		}else{
		mysql_query("
			INSERT INTO `person_work_graphic` (`user_id`, `date` ,`work_graphic_id`)
			VALUES ('$user_id',
			 (('".date('Y-m-d', strtotime($_REQUEST[date]))."') + INTERVAL $_REQUEST[dey]-1 DAY)
			 , '$_REQUEST[work]')
		");};
   		break;
	default:
		$error = 'Action is Null';
}
function page()
{
	return '
	<div id="dialog-form">
		<fieldset >
	    	<legend>ძირითადი ინფორმაცია</legend>
	    	<table id="inline" class="display">
			      <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th >მუშაობის დასაწყისი</th>
                            <th >შესვენების დასაწყისი</th>
                            <th >შესვენების დასასრული</th>
                            <th >სამუშაოს დასასრული</th>
                            <th style="width: 30px">#</th>
                        </tr>
                    </thead>
			</table>
		</fieldset >
	</div>
<input type="hidden" id="id" value='.$_REQUEST[id].'>';

}

$data['error'] = $error;

echo json_encode($data);

?>