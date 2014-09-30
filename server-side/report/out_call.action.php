<?php
include('../../includes/classes/core.php');
$start  	= $_REQUEST['start'];
$end    	= $_REQUEST['end'];
$count 		= $_REQUEST["count"];
$action 	= $_REQUEST['act'];
$task 		= $_REQUEST['task'];
$type       = $_REQUEST['type'];
$scenar   = $_REQUEST['scenar'];
$s_category = $_REQUEST['sub_category'];
$done 		= $_REQUEST['done']%3;
$name 		= $_REQUEST['name'];
$title 		= $_REQUEST['title'];
$text[0] 	= "გამავალი   ზარები დავალებების იხედვით";
$text[1] 	= "'$task'- სცენარის  მიხედვით";
$text[2] 	= "'$scenar'-'$task' მიხედვით";
//$text[3] 	= "'$departament'- შემოსული  ქვე–კატეგორიის მიხედვით";
$c="3 or incomming_call.call_type_id=0";
if ($type=="ინფორმაცია")  $c=1;
elseif ($type=="პრეტენზია") $c=2;
//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		$result = mysql_query("	SELECT 	shabloni.`name`,
										COUNT(*),
							 			CONCAT(ROUND(COUNT(*)/(
													SELECT
																	COUNT(*)
														FROM 		task
														JOIN 		task_type ON task.task_type_id=task_type.id
														JOIN 		task_detail ON task.id=task_detail.task_id
													  JOIN shabloni ON task.template_id = shabloni.id
													WHERE DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end' AND task_type.`name`='$task'
														)*100,2),'%')
							FROM 		task
							JOIN 		task_type ON task.task_type_id=task_type.id
							JOIN 		task_detail ON task.id=task_detail.task_id
							JOIN 		shabloni ON task.template_id = shabloni.id
							 WHERE DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end' AND task_type.`name`='$task'
						GROUP BY shabloni.`name`
								");
		$text[0]=$text[1];
	break;
	case 2:
		$result = mysql_query("SELECT
										CASE  	task_scenar.`result_quest`
												WHEN 1 THEN 'დადებითი'
												WHEN 2 THEN 'უარყოფითი'
												WHEN 3 THEN 'უარყოფითი'
												ELSE 'არაა დაფიქსირებული'
												END,
										COUNT(*),
										CONCAT(ROUND(COUNT(*)/(
													SELECT COUNT(*) FROM 		task
													JOIN 	task_type ON task.task_type_id=task_type.id
													JOIN 	task_detail ON task.id=task_detail.task_id
													JOIN	task_scenar ON task_scenar.task_detail_id=task_detail.id
													JOIN 	shabloni ON task.template_id = shabloni.id
											  		WHERE 	DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end' AND
																task_type.`name`='$task' AND shabloni.`name`='$scenar'
														)*100,2),'%')
								FROM 		task
								JOIN 		task_type ON task.task_type_id=task_type.id
								JOIN 		task_detail ON task.id=task_detail.task_id
								JOIN		task_scenar ON task_scenar.task_detail_id=task_detail.id
								JOIN 		shabloni ON task.template_id = shabloni.id
							   	WHERE DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end' AND
								 				task_type.`name`='$task' AND shabloni.`name`='$scenar'
								GROUP BY task_scenar.`result_quest`");
		$text[0]=$text[2];
	break;
	default:
		$result = mysql_query("SELECT 	task_type.`name`,
								COUNT(*),
								CONCAT(ROUND(COUNT(*)/(
										SELECT COUNT(*) FROM 		task
										JOIN 		task_type ON task.task_type_id=task_type.id
										JOIN 		task_detail ON task.id=task_detail.task_id
										WHERE DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end'
										)*100,2),'%')
								FROM 		task
								JOIN 		task_type ON task.task_type_id=task_type.id
								JOIN 		task_detail ON task.id=task_detail.task_id
							 	WHERE DATE(`task`.`date`) >= '$start' AND DATE(`task`.`date`) <= '$end'
								GROUP BY task_type.`name`");

		break;
}
///----------------------------------------------act------------------------------------------
switch ($action) {
	case "get_list":
		$data = array("aaData"	=> array());
		while ( $aRow = mysql_fetch_array( $result ) )
		{	$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				$row[0] = '0';

				$row[$i+1] = $aRow[$i];
			}
			$data['aaData'][] =$row;
		}
		echo json_encode($data); return 0;
		break;
	case 'get_category' :
		$rows = array();
		while($r = mysql_fetch_array($result)) {
			$row[0] = $r[0];
			$row[1] = (float) $r[1];
			$rows['data'][]=$row;
		}
		$rows['text']=$text[0];
		echo json_encode($rows);
		break;
	default :
		echo "Action Is Null!";
		break;

}



?>