<?php
require_once('../../includes/classes/core.php');

header('Content-Type: application/json');
$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$agent = $_REQUEST['agent'];
$queuet = $_REQUEST['queuet'];

$quantity = array();
$cause = array();
$cause1 = array();

$name = array();
$agent = array();


//-----------------------ნაპასუხები ზარები ოპერატორების მიხედვით

											$ress =mysql_query("
													SELECT	COUNT(*) AS `num`,
							 								cdr.src AS `agent`
													FROM    cdr
													WHERE   cdr.disposition = 'ANSWERED'
													AND cdr.userfield != ''
													AND cdr.src IN ($agent)
													AND DATE(cdr.calldate) >= '$start_time'
													AND DATE(cdr.calldate) <= '$end_time'
													AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
							 						GROUP BY cdr.src
												");
			
while($row1 = mysql_fetch_assoc($ress)){

	$call_count[] = (float)$row1[num];
	$agent[]		= $row1[agent];
}
//------------------------------ ნაპასუხები ზარები კვირის დღეების მიხედვით
		$res3 =mysql_query("
							SELECT 	CASE
											WHEN DAYOFWEEK(cdr.calldate) = 1 THEN 'კვირა'
											WHEN DAYOFWEEK(cdr.calldate) = 2 THEN 'ორშაბათი'
											WHEN DAYOFWEEK(cdr.calldate) = 3 THEN 'სამშაბათი'
											WHEN DAYOFWEEK(cdr.calldate) = 4 THEN 'ოთხშაბათი'
											WHEN DAYOFWEEK(cdr.calldate) = 5 THEN 'ხუთშაბათი'
											WHEN DAYOFWEEK(cdr.calldate) = 6 THEN 'პარასკევი'
											WHEN DAYOFWEEK(cdr.calldate) = 7 THEN 'შაბათი'
									END AS `date`,
									COUNT(*) as `answer_count1`
							FROM    cdr
							WHERE   cdr.disposition = 'ANSWERED'
							AND cdr.userfield != ''
							AND cdr.src IN ($agent)
							AND DATE(cdr.calldate) >= '$start_time'
							AND DATE(cdr.calldate) <= '$end_time'
							AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
							GROUP BY DAYOFWEEK(cdr.calldate)
						");
									
		while($row3 = mysql_fetch_assoc($res3)){

		$answer_count1[] = (float)$row3[answer_count1];
		$datetime1[]		= $row3[date];
	}
	
	
	//------------------------------ უპასუხო ზარები კვირის დღეების მიხედვით
		
	$res10 =mysql_query("
						SELECT 	CASE
										WHEN DAYOFWEEK(cdr.calldate) = 1 THEN 'კვირა'
										WHEN DAYOFWEEK(cdr.calldate) = 2 THEN 'ორშაბათი'
										WHEN DAYOFWEEK(cdr.calldate) = 3 THEN 'სამშაბათი'
										WHEN DAYOFWEEK(cdr.calldate) = 4 THEN 'ოთხშაბათი'
										WHEN DAYOFWEEK(cdr.calldate) = 5 THEN 'ხუთშაბათი'
										WHEN DAYOFWEEK(cdr.calldate) = 6 THEN 'პარასკევი'
										WHEN DAYOFWEEK(cdr.calldate) = 7 THEN 'შაბათი'
								END AS `date`,
								COUNT(*) as `unanswer_count`
						FROM    cdr
						WHERE   cdr.disposition = 'NO ANSWER'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
						GROUP BY DAYOFWEEK(cdr.calldate)
									");
	
	while($row10 = mysql_fetch_assoc($res10)){
	
		$unanswer_call2[] = (float)$row10[unanswer_count];
		$date1[]		= $row10[date];
	}		


//------------------------------ ნაპასუხები ზარები რიგის მიხედვით
		$res7 =mysql_query("SELECT	COUNT(*) AS `count2`,
									q.queue AS `queue2`
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
							GROUP BY q.queue");

		while($row7 = mysql_fetch_assoc($res7)){

		$count2[] = (float)$row7[count2];
		$queue2[]		= $row7[queue2];
}
			
//------------------------------ უპასუხო ზარები რიგის მიხედვით
$res8 =mysql_query("SELECT 	DATE(qs.datetime) AS `datetime`,
							COUNT(*) AS `unanswer_call`
		
					FROM 	queue_stats AS qs,
							qname AS q,
							qagent AS ag,
							qevent AS ac
					WHERE 	qs.qname = q.qname_id
					AND 	qs.qagent = ag.agent_id
					AND 	qs.qevent = ac.event_id
					AND 	DATE(qs.datetime) >= '$start'
					AND 	DATE(qs.datetime) <= '$end'
					AND 	q.queue IN ($queuet,'NONE')
					AND 	ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')
					GROUP BY DATE(qs.datetime)
					");
		
		while($row8 = mysql_fetch_assoc($res8)){
		
			$unanswer_call[] = (float)$row8[unanswer_call];
			$times[]		= $row8[datetime];
		}
		
		
//------------------------------ ნაპასუხები ზარები დღეების მიხედვით		
			
$res4 =mysql_query("SELECT 	DATE(qs.datetime) AS `datetime`,
						COUNT(*) AS `answer_count2`
						FROM 	queue_stats AS qs,
						qname AS q,
						qagent AS ag,
						qevent AS ac
						WHERE qs.qname = q.qname_id
						AND qs.qagent = ag.agent_id
						AND qs.qevent = ac.event_id
						AND DATE(qs.datetime) >= '$start'
						AND DATE(qs.datetime) <= '$end'
						AND q.queue IN ($queuet,'NONE')
						AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
						GROUP BY DATE(qs.datetime)");

		while($row4 = mysql_fetch_assoc($res4)){

		$answer_count2[] = (float)$row4[answer_count2];
		$datetime2[]		= $row4[datetime];
			}
//------------------------------ უპასუხო ზარები საათების მიხედვით			
	$res9 =mysql_query("SELECT  CASE		
										WHEN HOUR(qs.datetime) >= 0 AND HOUR(qs.datetime) < 1 THEN '00:00'
										WHEN HOUR(qs.datetime) >= 1 AND HOUR(qs.datetime) < 2 THEN '01:00'
										WHEN HOUR(qs.datetime) >= 2 AND HOUR(qs.datetime) < 3 THEN '02:00'
										WHEN HOUR(qs.datetime) >= 3 AND HOUR(qs.datetime) < 4 THEN '03:00'
										WHEN HOUR(qs.datetime) >= 4 AND HOUR(qs.datetime) < 5 THEN '04:00'
										WHEN HOUR(qs.datetime) >= 5 AND HOUR(qs.datetime) < 6 THEN '05:00'
										WHEN HOUR(qs.datetime) >= 6 AND HOUR(qs.datetime) < 7 THEN '06:00'
										WHEN HOUR(qs.datetime) >= 7 AND HOUR(qs.datetime) < 8 THEN '07:00'
										WHEN HOUR(qs.datetime) >= 8 AND HOUR(qs.datetime) < 9 THEN '08:00'
										WHEN HOUR(qs.datetime) >= 9 AND HOUR(qs.datetime) < 10 THEN '09:00'
										WHEN HOUR(qs.datetime) >= 10 AND HOUR(qs.datetime) < 11 THEN '10:00'
										WHEN HOUR(qs.datetime) >= 11 AND HOUR(qs.datetime) < 12 THEN '11:00'
										WHEN HOUR(qs.datetime) >= 12 AND HOUR(qs.datetime) < 13 THEN '12:00'
										WHEN HOUR(qs.datetime) >= 13 AND HOUR(qs.datetime) < 14 THEN '13:00'
										WHEN HOUR(qs.datetime) >= 14 AND HOUR(qs.datetime) < 15 THEN '14:00'
										WHEN HOUR(qs.datetime) >= 15 AND HOUR(qs.datetime) < 16 THEN '15:00'
										WHEN HOUR(qs.datetime) >= 16 AND HOUR(qs.datetime) < 17 THEN '16:00'
										WHEN HOUR(qs.datetime) >= 17 AND HOUR(qs.datetime) < 18 THEN '17:00'
										WHEN HOUR(qs.datetime) >= 18 AND HOUR(qs.datetime) < 19 THEN '18:00'
										WHEN HOUR(qs.datetime) >= 19 AND HOUR(qs.datetime) < 20 THEN '19:00'
										WHEN HOUR(qs.datetime) >= 20 AND HOUR(qs.datetime) < 21 THEN '20:00'
										WHEN HOUR(qs.datetime) >= 21 AND HOUR(qs.datetime) < 22 THEN '21:00'
										WHEN HOUR(qs.datetime) >= 22 AND HOUR(qs.datetime) < 23 THEN '22:00'
										WHEN HOUR(qs.datetime) >= 23 AND HOUR(qs.datetime) < 24 THEN '23:00'
									END AS `times`,
									COUNT(*) AS `unanswer_count`
										
									FROM 	queue_stats AS qs,
									qname AS q,
									qagent AS ag,
									qevent AS ac
									WHERE qs.qname = q.qname_id
									AND qs.qagent = ag.agent_id
									AND qs.qevent = ac.event_id
									AND DATE(qs.datetime) >= '$start'
									AND DATE(qs.datetime) <= '$end'
									AND q.queue IN ($queuet,'NONE')
									AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
									GROUP BY HOUR(qs.datetime)
");
	
	while($row9 = mysql_fetch_assoc($res9)){
	
		$unanswer_count1[] = (float)$row9[unanswer_count];
		$times2[]		= $row9[times];
	}
	//------------------------------ ნაპასუხები ზარები საათების მიხედვით
	$res2 =mysql_query("SELECT  CASE
										WHEN HOUR(qs.datetime) >= 0 AND HOUR(qs.datetime) < 1 THEN '00:00'
										WHEN HOUR(qs.datetime) >= 1 AND HOUR(qs.datetime) < 2 THEN '01:00'
										WHEN HOUR(qs.datetime) >= 2 AND HOUR(qs.datetime) < 3 THEN '02:00'
										WHEN HOUR(qs.datetime) >= 3 AND HOUR(qs.datetime) < 4 THEN '03:00'
										WHEN HOUR(qs.datetime) >= 4 AND HOUR(qs.datetime) < 5 THEN '04:00'
										WHEN HOUR(qs.datetime) >= 5 AND HOUR(qs.datetime) < 6 THEN '05:00'
										WHEN HOUR(qs.datetime) >= 6 AND HOUR(qs.datetime) < 7 THEN '06:00'
										WHEN HOUR(qs.datetime) >= 7 AND HOUR(qs.datetime) < 8 THEN '07:00'
										WHEN HOUR(qs.datetime) >= 8 AND HOUR(qs.datetime) < 9 THEN '08:00'
										WHEN HOUR(qs.datetime) >= 9 AND HOUR(qs.datetime) < 10 THEN '10:00'
										WHEN HOUR(qs.datetime) >= 10 AND HOUR(qs.datetime) < 11 THEN '11:00'
										WHEN HOUR(qs.datetime) >= 11 AND HOUR(qs.datetime) < 12 THEN '12:00'
										WHEN HOUR(qs.datetime) >= 12 AND HOUR(qs.datetime) < 13 THEN '13:00'
										WHEN HOUR(qs.datetime) >= 13 AND HOUR(qs.datetime) < 14 THEN '14:00'
										WHEN HOUR(qs.datetime) >= 14 AND HOUR(qs.datetime) < 15 THEN '15:00'
										WHEN HOUR(qs.datetime) >= 15 AND HOUR(qs.datetime) < 16 THEN '16:00'
										WHEN HOUR(qs.datetime) >= 16 AND HOUR(qs.datetime) < 17 THEN '17:00'
										WHEN HOUR(qs.datetime) >= 17 AND HOUR(qs.datetime) < 18 THEN '18:00'
										WHEN HOUR(qs.datetime) >= 18 AND HOUR(qs.datetime) < 19 THEN '19:00'
										WHEN HOUR(qs.datetime) >= 19 AND HOUR(qs.datetime) < 20 THEN '20:00'
										WHEN HOUR(qs.datetime) >= 20 AND HOUR(qs.datetime) < 21 THEN '21:00'
										WHEN HOUR(qs.datetime) >= 21 AND HOUR(qs.datetime) < 22 THEN '22:00'
										WHEN HOUR(qs.datetime) >= 22 AND HOUR(qs.datetime) < 23 THEN '23:00'
										WHEN HOUR(qs.datetime) >= 23 AND HOUR(qs.datetime) < 24 THEN '24:00'
								
										END AS `times`,
										COUNT(*) AS `answer_count`
										FROM 	queue_stats AS qs,
										qname AS q,
										qagent AS ag,
										qevent AS ac
										WHERE 	qs.qname = q.qname_id
										AND 	qs.qagent = ag.agent_id
										AND 	qs.qevent = ac.event_id
										AND 	DATE(qs.datetime) >= '$start'
										AND 	DATE(qs.datetime) <= '$end'
										AND 	q.queue IN ($queuet,'NONE')
										AND 	ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
										GROUP BY HOUR(qs.datetime)");
				
			while($row2 = mysql_fetch_assoc($res2)){
	
			$answer_count[] = (float)$row2[answer_count];
			$datetime[] 	= $row2[times];
			}
			
			//------------------------------- მომსახურების დონე(Service Level)
			
			
			
			$res_service_level = mysql_query("	SELECT 	qs.info1
					FROM 	queue_stats AS qs,
					qname AS q,
					qagent AS ag,
					qevent AS ac
					WHERE 	qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start'
					AND DATE(qs.datetime) <= '$end'
					AND q.queue IN ($queuet)
					AND ac.event IN ('CONNECT')
					");
			$w15 = 0;
			$w30 = 0;
			$w45 = 0;
			$w60 = 0;
			$w75 = 0;
			$w90 = 0;
			$w91 = 0;
			
			
			
			
			while ($res_service_level_r = mysql_fetch_assoc($res_service_level)) {
			
			if ($res_service_level_r['info1'] < 15) {
			$w15++;
			}
			
			if ($res_service_level_r['info1'] < 30){
			$w30++;
			}
			
		if ($res_service_level_r['info1'] < 45){
					$w45++;
			}
			
			if ($res_service_level_r['info1'] < 60){
			$w60++;
			}
			
			if ($res_service_level_r['info1'] < 75){
			$w75++;
			}
			
			if ($res_service_level_r['info1'] < 90){
			$w90++;
			}
			
			$w91++;
			
			}	
			
			$d30 = $w30 - $w15;
			$d45 = $w45 - $w30;
			$d60 = $w60 - $w45;
			$d75 = $w75 - $w60;
			$d90 = $w90 - $w75;
			$d91 = $w91 - $w90;
						
$mas = array($w15,$d30,$d45,$d60,$d75,$d90,$d91);
$call_second=array('15 წამში','30 წამში','45წამში','60 წამში','75 წამში','90 წამში','90+წამში');			
							
$unit[]="ზარი";
$series[] = array('name' => $name, 'unit' => $unit, 'quantity' => $quantity, 'cause' => $cause);
$series[] = array('name' => $name, 'unit' => $unit, 'call_count' => $call_count, 'agent' => $agent);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count' => $answer_count, 'datetime' => $datetime);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count1' => $answer_count1, 'datetime1' => $datetime1);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count2' => $answer_count2, 'datetime2' => $datetime2);
$series[] = array('name' => $name, 'unit' => $unit, 'answer_count3' => $answer_count3, 'cause1' => $cause1);
$series[] = array('name' => $name, 'unit' => $unit, 'count1' => $count1, 'queue1' => $queue1);
$series[] = array('name' => $name, 'unit' => $unit, 'count2' => $count2, 'queue2' => $queue2);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_call' => $unanswer_call, 'times' => $times);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_count1' => $unanswer_count1, 'times2' => $times2);
$series[] = array('name' => $name, 'unit' => $unit, 'unanswer_count2' => $unanswer_call2, 'date1' => $date1);
$series[] = array('name' => $name, 'unit' => $unit, 'mas' => $mas, 'call_second' => $call_second);

echo json_encode($series);

?>