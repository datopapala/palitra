<?php
mysql_close();
$conn = mysql_connect('92.241.82.243', 'root', 'Gl-1114');
mysql_select_db('asteriskcdrdb');


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
	$row[1] = $r[0];
	array_push($rows,$row);
}

echo json_encode($rows, JSON_NUMERIC_CHECK);

?>