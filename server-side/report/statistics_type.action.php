<?php
include('../../includes/classes/core.php');

$text[0]="შემოსული ზარები ტიპების მიხედვით";
$text[1]="შემოსული ინფორმაციული  ზარები ქვე-განყოფილებების  მიხედვით";
$text[2]="ქვე-განყოფილებაში შემოსული ინფორმაციული ზარები კატეგორიების მიხედვით";
$text[3]="ქვე-განყოფილებაში შემოსული ინფორმაციული ზარები ქვე-კატეგორიების მიხედვით";
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$count = $_REQUEST["count"];
$action = $_REQUEST['act'];
$name = $_REQUEST['name'];
$title=$_REQUEST[title];
$c1=3;
if ($_REQUEST['cc']=="ინფორმაციული")  $c1=1;
elseif ($_REQUEST['cc']=="პრეტენზია") $c1=2;
switch ($title){ //-----------------------query---------------------------------
case $text[0]:
	$c=3;
	if ($name=="ინფორმაციული") $c=1;
	elseif ($name=="პრეტენზია")    $c=2;
		$result = mysql_query("SELECT department.`name` AS d_name,
				COUNT(*),
				CONCAT(COUNT(*)/(SELECT COUNT(*) FROM incomming_call JOIN department ON incomming_call.department_id=department.id WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end')*100,'%')
				FROM incomming_call
				JOIN department ON incomming_call.department_id=department.id
				WHERE incomming_call.call_type_id=$c AND DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
				GROUP BY 	d_name");
		$text[0]=$text[1];
	break;
	case $text[1]:
			$result = mysql_query("SELECT info_category.`name` as c_name,
			COUNT(*),
			CONCAT(COUNT(*)/(SELECT COUNT(*) FROM incomming_call JOIN info_category ON info_category.id=incomming_call.information_category_id WHERE DATE(`incomming_call`.`date`) >= '$start'
			AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$name' and incomming_call.call_type_id=$c1 GROUP BY c_name)*100,'%')
			FROM incomming_call
			JOIN info_category ON info_category.id=incomming_call.information_category_id
			JOIN department ON incomming_call.department_id=department.id
			WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$name' and incomming_call.call_type_id=$c1
			GROUP BY c_name");
		$text[0]=$text[2];
	break;
	case $text[2]:
	//	echo  "asdsd";
		$result = mysql_query("SELECT info_category.`name` as c_name,
			COUNT(*),
			CONCAT(
			COUNT(*)/(SELECT COUNT(*)
			FROM incomming_call
			JOIN info_category AS inf1 ON inf1.`name`='$name'
			JOIN info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
			JOIN department ON incomming_call.department_id=department.id
			WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$_REQUEST[name1]' and incomming_call.call_type_id=$c1
			)*100,'%')
			FROM incomming_call
			JOIN info_category AS inf1 ON inf1.`name`='$name'
			JOIN info_category ON info_category.id=incomming_call.information_sub_category_id AND info_category.parent_id=inf1.id
			JOIN department ON incomming_call.department_id=department.id
			WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end' AND department.`name`='$_REQUEST[name1]' and incomming_call.call_type_id=$c1
			GROUP BY c_name");
		$text[0]=$text[3];
		break;
	default:
		$result = mysql_query("SELECT
				IF(incomming_call.call_type_id=1,'ინფორმაციული',IF(incomming_call.call_type_id=2,'პრეტენზია','სხვა')) as type,
				COUNT(*),
				CONCAT(COUNT(*)/(SELECT	COUNT(*)
				FROM incomming_call
				WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
		)*100,'%')	 AS `PERCENT`
				FROM incomming_call
				WHERE DATE(`incomming_call`.`date`) >= '$start' AND DATE(`incomming_call`.`date`) <= '$end'
				GROUP BY  type");

		break;
}
switch ($action) {///---------------act---------------------------
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