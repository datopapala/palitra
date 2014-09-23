<?php
require_once('../../includes/classes/core.php');
header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$result = mysql_query("SELECT	COUNT(*) AS `count1`,
								CONCAT('ნაპასუხები ზარები-',COUNT(*)) AS `cause` 
								FROM	queue_stats AS qs,
								qname AS q,
								qagent AS ag,
								qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start' AND DATE(qs.datetime) <= '$end'
								AND q.queue IN ($queuet)
								AND ac.event IN ( 'COMPLETECALLER', 'COMPLETEAGENT')
						UNION ALL
						SELECT 	COUNT(*) AS `count`,
								CONCAT('უპასუხო ზარები-',COUNT(*)) AS `cause`
								FROM	queue_stats AS qs,
										qname AS q,
										qagent AS ag,
										qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start' 
								AND DATE(qs.datetime) <= '$end'
								AND q.queue IN ($queuet)
								AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')");


$row = array();
$rows = array();
while($r = mysql_fetch_array($result)) {
	$row[0] = $r[1];
	$row[1] = (float)$r[0];
	array_push($rows,$row);
}

$rr = mysql_fetch_assoc($result);
$rows1[0] = $rr[1];
$rows1[1] = (float)$rr[0];
array_push($rows,$rows1);


echo json_encode($rows);

?>