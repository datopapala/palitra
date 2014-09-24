<?php
include('../../includes/classes/core.php');
$start 		= $_REQUEST['start'];
$end  		= $_REQUEST['end'];
$count 		= $_REQUEST["count"];
$action 	= $_REQUEST['act'];
$name 		= $_REQUEST['name'];
$title  	= $_REQUEST[title];
$departament= $_REQUEST['departament'];
$type       = $_REQUEST['type'];
$category   = $_REQUEST['category'];
$s_category = $_REQUEST['sub_category'];
$done 		= $_REQUEST['done']%4;
$text[0] 	= "შემოსული ზარები ტიპების მიხედვით";
$text[1] 	= "შემოსული '$type' ქვე-განყოფილებების  მიხედვით";
$text[2] 	= "'$departament'- შემოსული '$type' კატეგორიების მიხედვით";
$text[3] 	= "'$departament'- შემოსული  '$type' ქვე-კატეგორიების მიხედვით";
//–––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
$c="3 or incomming_call.call_type_id=0";
if ($type=="ინფორმაციული")  $c=1;
elseif ($type=="პრეტენზია") $c=2;
//------------------------------------------------query-------------------------------------------
switch ($done){
	case  1:
		$result = mysql_query("	SELECT 	department.`name` AS d_name,
										COUNT(*),
										CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM incomming_call JOIN department ON incomming_call.department_id=department.id WHERE (incomming_call.call_type_id=$c) AND DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end')*100,2),'%')
								FROM 	incomming_call
								JOIN 	department ON incomming_call.department_id=department.id
								WHERE (incomming_call.call_type_id=$c) AND DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
								GROUP BY 	d_name");
		$text[0]=$text[1];
	break;
	case 2:
		$result = mysql_query("SELECT info_category.`name` as c_name,
										COUNT(*),
										CONCAT(ROUND(COUNT(*)/(SELECT COUNT(*) FROM incomming_call JOIN info_category ON info_category.id=incomming_call.information_category_id WHERE DATE(`incomming_call`.`date`) >= '$start'
										AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament' and (incomming_call.call_type_id=$c) GROUP BY c_name)*100,2),'%')
								FROM 	incomming_call
								JOIN 	info_category ON info_category.id=incomming_call.information_category_id
								JOIN 	department ON incomming_call.department_id=department.id
								WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament' and (incomming_call.call_type_id=$c)
								GROUP BY c_name");
		$text[0]=$text[2];
	break;
	case 3:
		$result = mysql_query("SELECT info_category.`name` as c_name,
										COUNT(*),
										CONCAT(
										ROUND(COUNT(*)/(SELECT COUNT(*)
										FROM incomming_call
										JOIN info_category AS inf1 ON inf1.`name`='$category'
										JOIN info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
										JOIN department ON incomming_call.department_id=department.id
										WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament' and (incomming_call.call_type_id=$c)
										)*100,2),'%')
								FROM 	incomming_call
								JOIN 	info_category AS inf1 ON inf1.`name`='$category'
								JOIN 	info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
								JOIN 	department ON incomming_call.department_id=department.id
								WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$departament' and (incomming_call.call_type_id=$c)
								GROUP BY c_name");
		$text[0]=$text[3];
		break;
	default:
		$result = mysql_query("SELECT
									IF(incomming_call.call_type_id=1,'ინფორმაცია',IF(incomming_call.call_type_id=2,'პრეტენზია','სხვა')) as type,
									COUNT(*),
									CONCAT(ROUND(COUNT(*)/(SELECT	COUNT(*)
									FROM incomming_call
									WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
									)*100,2),'%')	 AS `PERCENT`
									FROM incomming_call
							WHERE 	DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
							GROUP 	BY  type");

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

					  $row[$i+1] =$aRow[$i];

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