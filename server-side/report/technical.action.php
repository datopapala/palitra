<?php

require_once('../../includes/classes/core.php');

//----------------------------- ცვლადი

$agent	= $_REQUEST['agent'];
$queue	= $_REQUEST['queuet'];
$start_time = $_REQUEST['start_time'];
$end_time 	= $_REQUEST['end_time'];
$day = (strtotime($end_time)) -  (strtotime($start_time));
$day_format = ($day / (60*60*24)) + 1;
// ----------------------------------

$row_done_blank = mysql_fetch_assoc(mysql_query("	SELECT COUNT(*) AS `count`
		FROM `incomming_call`
		WHERE DATE(date) >= '$start_time' AND DATE(date) <= '$end_time' AND phone != '' "));

mysql_close();
$conn = mysql_connect('212.72.155.176', 'root', 'Gl-1114');
mysql_select_db('stats');


$data		= array('page' => array(
										'answer_call' => '',
										'technik_info' => '',
										'report_info' => '',
										'answer_call_info' => '',
										'answer_call_by_queue' => '',
										'disconnection_cause' => '',
										'unanswer_call' => '',
										'disconnection_cause_unanswer' => '',
										'unanswered_calls_by_queue' => '',
										'totals' => '',
										'call_distribution_per_day' => '',
										'call_distribution_per_hour' => '',
										'call_distribution_per_day_of_week' => '',
										'service_level' => ''
								));


//------------------------------- ტექნიკური ინფორმაცია

	$row_answer = mysql_fetch_assoc(mysql_query("	SELECT	COUNT(*) AS `count`,
															q.queue AS `queue`
													FROM	queue_stats AS qs,
															qname AS q,
															qagent AS ag,
															qevent AS ac
													WHERE qs.qname = q.qname_id 
													AND qs.qagent = ag.agent_id 
													AND qs.qevent = ac.event_id 
													AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
													AND q.queue IN ($queue) 
													AND ag.agent in ($agent)
													AND ac.event IN ( 'COMPLETECALLER', 'COMPLETEAGENT') 
													ORDER BY ag.agent"));
	
	$row_abadon = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) AS `count`,
															ROUND((SUM(qs.info3) / COUNT(*))) AS `sec`
													FROM	queue_stats AS qs,
															qname AS q, 
															qagent AS ag,
															qevent AS ac
													WHERE qs.qname = q.qname_id
													AND qs.qagent = ag.agent_id
													AND qs.qevent = ac.event_id
													AND DATE(qs.datetime) >= '$start_time'
													AND DATE(qs.datetime) <= '$end_time' 
													AND q.queue IN ($queue) 
													AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')"));
	
	
	
	
	$data['page']['technik_info'] = '
							
                    <td>ზარი</td>
                    <td>'.($row_answer[count] + $row_abadon[count]).'</td>
                    <td>'.$row_answer[count].'</td>
                    <td>'.$row_abadon[count].'</td>
                    <td>'.$row_done_blank[count].'</td>
                    <td>'.round(((($row_answer[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_abadon[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_done_blank[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                
							';
// -----------------------------------------------------

//------------------------------- ნაპასუხები ზარები რიგის მიხედვით

	$data['page']['answer_call'] = '
							<tr><td>'.$row_answer[queue].'</td><td>'.$row_answer[count].' ზარი</td><td>'.round(((($row_answer[count]) / ($row_answer[count])) * 100)).' %</td></tr>
							';

//-------------------------------------------------------

//------------------------------- მომსახურების დონე(Service Level)

	
	
	$res_service_level = mysql_query("	SELECT 	qs.info1
							FROM 	queue_stats AS qs,
									qname AS q,
									qagent AS ag,
									qevent AS ac 
							WHERE 	qs.qname = q.qname_id 
							AND qs.qagent = ag.agent_id 
							AND qs.qevent = ac.event_id 
							AND DATE(qs.datetime) >= '$start_time'
							AND DATE(qs.datetime) <= '$end_time'
							AND q.queue IN ($queue)
							AND ag.agent in ($agent) 
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
	
	
	$p15 = round($w15 * 100 / $w91);
	$p30 = round($w30 * 100 / $w91);
	$p45 = round($w45 * 100 / $w91);
	$p60 = round($w60 * 100 / $w91);
	$p75 = round($w75 * 100 / $w91);
	$p90 = round($w90 * 100 / $w91);
	
	
	
	
	
	$data['page']['service_level'] = '
							
							<tr class="odd">
						 		<td>15 წამში</td>
					 			<td>'.$w15.'</td>
					 			<td></td>
					 			<td>'.$p15.'%</td>
					 		</tr>
				 			<tr>
						 		<td>30 წამში</td>
					 			<td>'.$w30.'</td>
					 			<td>'.$d30.'</td>
					 			<td>'.$p30.'%</td>
					 		</tr>
				 			<tr class="odd">
						 		<td>45 წამში</td>
					 			<td>'.$w45.'</td>
					 			<td>'.$d45.'</td>
					 			<td>'.$p45.'%</td>
					 		</tr>
				 			<tr>
						 		<td>60 წამში</td>
					 			<td>'.$w60.'</td>
					 			<td>'.$d60.'</td>
					 			<td>'.$p60.'%</td>
					 		</tr>
				 			<tr class="odd">
						 		<td>75 წამში</td>
					 			<td>'.$w75.'</td>
					 			<td>'.$d75.'</td>
					 			<td>'.$p75.'%</td>
					 		</tr>
					 		<tr>
						 		<td>90 წამში</td>
					 			<td>'.$w90.'</td>
					 			<td>'.$d90.'</td>
					 			<td>'.$p90.'%</td>
					 		</tr>
					 		<tr class="odd">
						 		<td>90+ წამში</td>
					 			<td>'.$w91.'</td>
					 			<td>'.$d91.'</td>
					 			<td>100%</td>
					 		</tr>
							';
	
//-------------------------------------------------------
	
//---------------------------------------- რეპორტ ინფო

	$data['page']['report_info'] = '
				
                    <tr>
                    <td>რიგი:</td>
                    <td>'.$queue.'</td>
                </tr>
                
                       <tr><td>საწყისი თარიღი:</td>
                       <td>'.$start_time.'</td>
                </tr>
                
                <tr>
                       <td>დასრულების თარიღი:</td>
                       <td>'.$end_time.'</td>
                </tr>
                <tr>
                       <td>პერიოდი:</td>
                       <td>'.$day_format.' დღე</td>
                </tr>

							';
	
//----------------------------------------------


//------------------------------------ ნაპასუხები ზარები

$row_transfer = mysql_fetch_assoc(mysql_query("	SELECT	COUNT(*) AS `count`
												FROM	queue_stats AS qs,
												qname AS q,
												qagent AS ag,
												qevent AS ac
												WHERE qs.qname = q.qname_id
												AND qs.qagent = ag.agent_id
												AND qs.qevent = ac.event_id
												AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
												AND q.queue IN ($queue)
												AND ag.agent in ($agent)
												AND ac.event IN ( 'TRANSFER')
												ORDER BY ag.agent"));

$row_clock = mysql_fetch_assoc(mysql_query("	SELECT	ROUND((SUM(qs.info1) / COUNT(*)),2) AS `hold`,
														ROUND((SUM(qs.info2) / COUNT(*)),2) AS `sec`,
														ROUND((SUM(qs.info2) / 60 ),2) AS `min`
												FROM 	queue_stats AS qs,
														qname AS q,
														qagent AS ag,
														qevent AS ac 
												WHERE	qs.qname = q.qname_id 
												AND qs.qagent = ag.agent_id 
												AND qs.qevent = ac.event_id 
												AND q.queue IN ($queue) 
												AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
												AND ac.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
												ORDER BY qs.datetime"));




	$data['page']['answer_call_info'] = '

                   	<tr>
					<td>ნაპასუხები ზარები</td>
					<td>'.$row_answer[count].' ზარი</td>
					</tr>
					<tr>
					<td>გადამისამართებული ზარები</td>
					<td>'.$row_transfer[count].' ზარი</td>
					</tr>
					<tr>
					<td>საშ. ხანგძლივობა:</td>
					<td>'.$row_clock[sec].' წამი</td>
					</tr>
					<tr>
					<td>სულ საუბრის ხანგძლივობა:</td>
					<td>'.$row_clock[min].' წუთი</td>
					</tr>
					<tr>
					<td>ლოდინის საშ. ხანგძლივობა:</td>
					<td>'.$row_clock[hold].' წამი</td>
					</tr>

							';
	
//---------------------------------------------

	
//--------------------------- ნაპასუხები ზარები ოპერატორების მიხედვით

 	$ress =mysql_query("SELECT 	ag.agent as `agent`, 
 								count(ev.event) AS `num`,
 								round(((count(ev.event) / (
 	
 	SELECT count(ev.event) AS num
 	FROM queue_stats AS qs, qname AS q, qevent AS ev
 	WHERE qs.qname = q.qname_id
 	and qs.qevent = ev.event_id
 	and DATE(qs.datetime) >= '$start_time'
 	and DATE(qs.datetime) <= '$end_time'
 	and q.queue IN ($queue)
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	
 	)) * 100),2) AS `call_pr`,
 	ROUND((sum(qs.info2) / 60),2) AS `call_time`,
 	
 	round(((sum(qs.info2) / (
 	
 	SELECT sum(qs.info2)
 	FROM queue_stats AS qs, qname AS q, qevent AS ev
 	WHERE qs.qname = q.qname_id
 	and qs.qevent = ev.event_id
 	and DATE(qs.datetime) >= '$start_time'
 	and DATE(qs.datetime) <= '$end_time'
 	and q.queue IN ($queue)
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	
 	))* 100),2) AS `call_time_pr`,
 	TIME_FORMAT(SEC_TO_TIME(sum(qs.info2) / count(ev.event)), '%i:%s') AS `avg_call_time`,
 	sum(qs.info1) AS `hold_time`,
 	ROUND((sum(qs.info1) / count(ev.event)),2) AS `avg_hold_time`
 	FROM queue_stats AS qs, qname AS q, qevent AS ev, qagent AS `ag` WHERE ag.agent_id = qs.qagent AND
 	qs.qname = q.qname_id and qs.qevent = ev.event_id 
 	AND DATE(qs.datetime) >= '$start_time'
 	AND DATE(qs.datetime) <= '$end_time'
 	AND q.queue IN ($queue) 
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	GROUP BY ag.agent");

while($row = mysql_fetch_assoc($ress)){

	$data['page']['answer_call_by_queue'] .= '

                   	<tr>
					<td>'.$row[agent].'</td>
					<td>'.$row[num].'</td>
					<td>'.$row[call_pr].' %</td>
					<td>'.$row[call_time].' წუთი</td>
					<td>'.$row[call_time_pr].' %</td>
					<td>'.$row[avg_call_time].' წუთი</td>
					<td>'.$row[hold_time].' წამი</td>
					<td>'.$row[avg_hold_time].' წამი</td>
					</tr>

							';

}

//----------------------------------------------------

//--------------------------- კავშირის გაწყვეტის მიზეზეი


$row_COMPLETECALLER = mysql_fetch_assoc(mysql_query("	SELECT	COUNT(*) AS `count`,
																	q.queue AS `queue`
												FROM	queue_stats AS qs,
														qname AS q,
														qagent AS ag,
														qevent AS ac
												WHERE qs.qname = q.qname_id
												AND qs.qagent = ag.agent_id
												AND qs.qevent = ac.event_id
												AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
												AND q.queue IN ($queue)
												AND ag.agent in ($agent)
												AND ac.event IN ( 'COMPLETECALLER')
												ORDER BY ag.agent"));

$row_COMPLETEAGENT = mysql_fetch_assoc(mysql_query("	SELECT	COUNT(*) AS `count`,
																q.queue AS `queue`
														FROM	queue_stats AS qs,
																qname AS q,
																qagent AS ag,
																qevent AS ac
														WHERE qs.qname = q.qname_id
														AND qs.qagent = ag.agent_id
														AND qs.qevent = ac.event_id
														AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
														AND q.queue IN ($queue)
														AND ag.agent in ($agent)
														AND ac.event IN (  'COMPLETEAGENT')
														ORDER BY ag.agent"));

	$data['page']['disconnection_cause'] = '

                   <tr>
					<td>ოპერატორმა გათიშა:</td>
					<td>'.$row_COMPLETEAGENT[count].' ზარი</td>
					<td>0.00 %</td>
					</tr>
					<tr>
					<td>აბონენტმა გათიშა:</td>
					<td>'.$row_COMPLETECALLER[count].' ზარი</td>
					<td>0.00 %</td>
					</tr>

							';

//-----------------------------------------------

//----------------------------------- უპასუხო ზარები


	$data['page']['unanswer_call'] = '

                   	<tr>
					<td>უპასუხო ზარების რაოდენობა:</td>
					<td>'.$row_abadon[count].' ზარი</td>
					</tr>
					<tr>
					<td>ლოდინის საშ. დრო კავშირის გაწყვეტამდე:</td>
					<td>'.$row_abadon[sec].' წამი</td>
					</tr>
					<tr>
					<td>საშ. რიგში პოზიცია კავშირის გაწყვეტამდე:</td>
					<td>1</td>
					</tr>
					<tr>
					<td>საშ. საწყისი პოზიცია რიგში:</td>
					<td>1</td>
					</tr>

							';


//--------------------------------------------

	
//----------------------------------- კავშირის გაწყვეტის მიზეზი

	$row_timeout = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) AS `count`
			FROM 	queue_stats AS qs,
			qname AS q,
			qagent AS ag,
			qevent AS ac
			WHERE qs.qname = q.qname_id
			AND qs.qagent = ag.agent_id
			AND qs.qevent = ac.event_id
			AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
			AND q.queue IN ($queue)
			AND ac.event IN ('EXITWITHTIMEOUT')
			ORDER BY qs.datetime"));
	

	$data['page']['disconnection_cause_unanswer'] = '

                  <tr> 
                  <td>აბონენტმა გათიშა</td>
			      <td>'.$row_abadon[count].' ზარი</td>
			      <td>'.round((($row_abadon[count] / $row_abadon[count]) * 100),2).' %</td>
		        </tr>
			    <tr> 
                  <td>დრო ამოიწურა</td>
			      <td>'.$row_timeout[count].' ზარი</td>
			      <td>'.round((($row_timeout[count] / $row_timeout[count]) * 100),2).' %</td>
		        </tr>

							';

//--------------------------------------------

//------------------------------ უპასუხო ზარები რიგის მიხედვით

	$Unanswered_Calls_by_Queue = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) AS `count`,
			q.queue as `queue`
			FROM 	queue_stats AS qs,
			qname AS q,
			qagent AS ag,
			qevent AS ac
			WHERE qs.qname = q.qname_id
			AND qs.qagent = ag.agent_id
			AND qs.qevent = ac.event_id
			AND DATE(qs.datetime) >= '$start_time' AND DATE(qs.datetime) <= '$end_time'
			AND q.queue IN ($queue)
			AND ac.event IN ('ABANDON')
			ORDER BY qs.datetime"));
	
	$data['page']['unanswered_calls_by_queue'] = '

                   	<tr><td>'.$Unanswered_Calls_by_Queue[queue].'</td><td>'.$Unanswered_Calls_by_Queue[count].' ზარი</td><td>'.round((($Unanswered_Calls_by_Queue[count] / $Unanswered_Calls_by_Queue[count]) * 100),2).' %</td></tr>

							';

//---------------------------------------------------

//------------------------------------------- სულ

	$data['page']['totals'] = '

                   	<tr> 
                  <td>ნაპასუხები ზარების რაოდენობა:</td>
		          <td>'.$row_answer[count].' ზარი</td>
	            </tr>
                <tr>
                  <td>უპასუხო ზარების რაოდენობა:</td>
                  <td>'.$row_abadon[count].' ზარი</td>
                </tr>
		        <tr>
                  <td>ოპერატორი შევიდა:</td>
		          <td>0</td>
	            </tr>
                <tr>
                  <td>ოპერატორი გავიდა:</td>
                  <td>0</td>
                </tr>

							';
	
//------------------------------------------------

	
//-------------------------------- ზარის განაწილება დღეების მიხედვით
	
	$res = mysql_query("
						SELECT 	DATE(qs.datetime) AS `datetime`,
								COUNT(*) AS `answer_count`,
								ROUND((( COUNT(*) / (
									SELECT 	COUNT(*) AS `count`
									FROM 	queue_stats AS qs,
											qname AS q, 
											qagent AS ag,
											qevent AS ac 
									WHERE qs.qname = q.qname_id
									AND qs.qagent = ag.agent_id 
									AND qs.qevent = ac.event_id
									AND DATE(qs.datetime) >= '$start_time'
									AND DATE(qs.datetime) <= '$end_time'
									AND q.queue IN ($queue,'NONE')
									AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
										)) *100),2) AS `call_answer_pr`,
						TIME_FORMAT(SEC_TO_TIME((SUM(qs.info2) / COUNT(*))), '%i:%s') AS `avg_durat`,
						ROUND((SUM(qs.info1) / COUNT(*))) AS `avg_hold`
						FROM 	queue_stats AS qs,
									qname AS q, 
									qagent AS ag,
									qevent AS ac 
						WHERE qs.qname = q.qname_id
						AND qs.qagent = ag.agent_id 
						AND qs.qevent = ac.event_id
						AND DATE(qs.datetime) >= '$start_time'
						AND DATE(qs.datetime) <= '$end_time'
						AND q.queue IN ($queue,'NONE')
						AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
						GROUP BY DATE(qs.datetime)
						");

	$ress = mysql_query("
						SELECT 	COUNT(*) AS `unanswer_call`,
				
								ROUND((( COUNT(*) / (
									SELECT 	COUNT(*) AS `count`
									FROM 		queue_stats AS qs,
													qname AS q, 
													qagent AS ag,
													qevent AS ac 
									WHERE qs.qname = q.qname_id
									AND qs.qagent = ag.agent_id 
									AND qs.qevent = ac.event_id
									AND DATE(qs.datetime) >= '$start_time'
									AND DATE(qs.datetime) <= '$end_time'
									AND q.queue IN ($queue,'NONE')
									AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
								)) *100),2) AS `call_unanswer_pr`
						FROM 	queue_stats AS qs,
									qname AS q, 
									qagent AS ag,
									qevent AS ac 
						WHERE qs.qname = q.qname_id
						AND qs.qagent = ag.agent_id 
						AND qs.qevent = ac.event_id
						AND DATE(qs.datetime) >= '$start_time'
						AND DATE(qs.datetime) <= '$end_time'
						AND q.queue IN ($queue,'NONE')
						AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')
						GROUP BY DATE(qs.datetime)
						");
	
	
	
	while($row = mysql_fetch_assoc($res)){
		$roww = mysql_fetch_assoc($ress);
			$data['page']['call_distribution_per_day'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.$row[answer_count].'</td>
					<td>'.$row[call_answer_pr].' %</td>
					<td>'.$roww[unanswer_call].'</td>
					<td>'.$roww[call_unanswer_pr].' %</td>
					<td>'.$row[avg_durat].' წუთი</td>
					<td>'.$row[avg_hold].' წამი</td>
					<td></td>
					<td></td>
					</tr>

							';
	}
	
//----------------------------------------------------

	
//-------------------------------- ზარის განაწილება საათების მიხედვით

	
	
	
		for($key=0;$key<24;$key++){
			
			$res124 = mysql_query("
					SELECT  HOUR(qs.datetime) AS `datetime`,
					COUNT(*) AS `answer_count`,
					ROUND((( COUNT(*) / (
					SELECT 	COUNT(*) AS `count`
					FROM 	queue_stats AS qs,
					qname AS q,
					qagent AS ag,
					qevent AS ac
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
			)) *100),2) AS `call_answer_pr`,
					ROUND((SUM(qs.info2) / COUNT(*)),0) AS `avg_durat`,
					ROUND((SUM(qs.info1) / COUNT(*)),0) AS `avg_hold`
					FROM 	queue_stats AS qs,
					qname AS q,
					qagent AS ag,
					qevent AS ac
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
					AND HOUR(qs.datetime) = $key
					GROUP BY HOUR(qs.datetime)
					");
			
			$res1244 = mysql_query("
					SELECT  HOUR(qs.datetime) AS `datetime`,
					COUNT(*) AS `unanswer_count`,
					ROUND((( COUNT(*) / (
					SELECT 	COUNT(*) AS `count`
					FROM 	queue_stats AS qs,
					qname AS q,
					qagent AS ag,
					qevent AS ac
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
			)) *100),2) AS `call_unanswer_pr`
					FROM 	queue_stats AS qs,
					qname AS q,
					qagent AS ag,
					qevent AS ac
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
					AND HOUR(qs.datetime) = $key
					GROUP BY HOUR(qs.datetime)
					");
			
		$row = mysql_fetch_assoc($res124);
		$roww = mysql_fetch_assoc($res1244);
			$data['page']['call_distribution_per_hour'] .= '
				<tr class="odd">
						<td>'.$key.':00</td>
						<td>'.(($row[answer_count]!='')?$row[answer_count]:"0").'</td>
						<td>'.(($row[call_answer_pr]!='')?$row[call_answer_pr]:"0").' %</td>
						<td>'.(($roww[unanswer_count]!='')?$roww[unanswer_count]:"0").'</td>
						<td>'.(($roww[call_unanswer_pr]!='')?$roww[call_unanswer_pr]:"0").'%</td>
						<td>'.(($row[avg_durat]!='')?$row[avg_durat]:"0").' წამი</td>
						<td>'.(($row[avg_hold]!='')?$row[avg_hold]:"0").' წამი</td>
						<td></td>
						<td></td>
						</tr>
				';
		}

//-------------------------------------------------


//------------------------------ ზარის განაწილება კვირების მიხედვით

		for($i=1;$i<=7;$i++){
$res12 = mysql_query("
					SELECT  CASE
									WHEN DAYOFWEEK(qs.datetime) = 1 THEN 'კვირა'
									WHEN DAYOFWEEK(qs.datetime) = 2 THEN 'ორშაბათი'
									WHEN DAYOFWEEK(qs.datetime) = 3 THEN 'სამშაბათი'
									WHEN DAYOFWEEK(qs.datetime) = 4 THEN 'ოთხშაბათი'
									WHEN DAYOFWEEK(qs.datetime) = 5 THEN 'ხუთშაბათი'
									WHEN DAYOFWEEK(qs.datetime) = 6 THEN 'პარასკევი'
									WHEN DAYOFWEEK(qs.datetime) = 7 THEN 'შაბათი'
							END AS `datetime`,
							COUNT(*) AS `answer_count`,
							ROUND((( COUNT(*) / (
								SELECT COUNT(*) AS `count`
								FROM 	queue_stats AS qs,
											qname AS q, 
											qagent AS ag,
											qevent AS ac 
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id 
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start_time'
								AND DATE(qs.datetime) <= '$end_time'
								AND q.queue IN ($queue,'NONE')
								AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
							)) *100),2) AS `call_answer_pr`,
							ROUND((SUM(qs.info2) / COUNT(*)),0) AS `avg_durat`,
							ROUND((SUM(qs.info1) / COUNT(*)),0) AS `avg_hold`
					FROM 	queue_stats AS qs,
								qname AS q, 
								qagent AS ag,
								qevent AS ac 
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id 
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT')
					AND DAYOFWEEK(qs.datetime) = $i
					GROUP BY DAYOFWEEK(qs.datetime)
					");

$res122 = mysql_query("
					SELECT 
							COUNT(*) AS `unanswer_count`,
							ROUND((( COUNT(*) / (
								SELECT COUNT(*) AS `count`
								FROM 	queue_stats AS qs,
											qname AS q,
											qagent AS ag,
											qevent AS ac
								WHERE qs.qname = q.qname_id
								AND qs.qagent = ag.agent_id
								AND qs.qevent = ac.event_id
								AND DATE(qs.datetime) >= '$start_time'
								AND DATE(qs.datetime) <= '$end_time'
								AND q.queue IN ($queue,'NONE')
								AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
							)) *100),2) AS `call_unanswer_pr`
					FROM 	queue_stats AS qs,
								qname AS q,
								qagent AS ag,
								qevent AS ac
					WHERE qs.qname = q.qname_id
					AND qs.qagent = ag.agent_id
					AND qs.qevent = ac.event_id
					AND DATE(qs.datetime) >= '$start_time'
					AND DATE(qs.datetime) <= '$end_time'
					AND q.queue IN ($queue,'NONE')
					AND ac.event IN ('ABANDON','EXITWITHTIMEOUT')
					AND DAYOFWEEK(qs.datetime) = $i
					GROUP BY DAYOFWEEK(qs.datetime)
					");


	$row = mysql_fetch_assoc($res12);
	$roww = mysql_fetch_assoc($res122);
	
	switch ($i)
	{
		case 1:
			$week = 'კვირა';
			break;
		case 2:
			$week = 'ორშაბათი';
			break;
		case 3:
			$week = 'სამშაბათი';
			break;
		case 4:
			$week = 'ოთხშაბათი';
			break;
		case 5:
			$week = 'ხუთშაბათი';
			break;
		case 6:
			$week = 'პარასკევი';
			break;
		case 7:
			$week = 'შაბათი';
			break;
	}
	
	$data['page']['call_distribution_per_day_of_week'] .= '

                   	<tr class="odd">
					<td>'.$week.'</td>
					<td>'.(($row[answer_count]!='')?$row[answer_count]:"0").'</td>
					<td>'.(($row[call_answer_pr]!='')?$row[call_answer_pr]:"0").' %</td>
					<td>'.(($roww[unanswer_count]!='')?$roww[unanswer_count]:"0").'</td>
					<td>'.(($roww[call_unanswer_pr]!='')?$roww[call_unanswer_pr]:"0").'%</td>
					<td>'.(($row[avg_durat]!='')?$row[avg_durat]:"0").' წამი</td>
					<td>'.(($row[avg_hold]!='')?$row[avg_hold]:"0").' წამი</td>
					<td></td>
					<td></td>
					</tr>
						';

}

//---------------------------------------------------


echo json_encode($data);

?>