<?php
require_once('../../includes/classes/core.php');
header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$result = mysql_query("	SELECT 	COUNT(*) as count,
								'ნაპასუხები ზარები: ' AS `text`
						FROM    cdr
						WHERE   cdr.disposition = 'ANSWERED'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start'
						AND DATE(cdr.calldate) <= '$end'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queuet)
						UNION ALL
						SELECT 	COUNT(*) as count,
								'უპასუხო ზარები: ' AS `text`
						FROM    cdr
						WHERE   cdr.disposition = 'NO ANSWER'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start'
						AND DATE(cdr.calldate) <= '$end'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queuet)
		");


$row = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
	$row[0] = $r[1].(float)$r[0];
	$row[1] = (float)$r[0];
	array_push($rows,$row);
}

echo json_encode($rows);

?>