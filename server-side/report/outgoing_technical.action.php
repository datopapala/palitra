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

if($_REQUEST['act'] =='answear_dialog_table'){
	$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT cdr.calldate,
									   cdr.calldate,
									   SUBSTRING(cdr.lastdata,5,7),
								       cdr.dst,
									   cdr.src,
								       CONCAT(SUBSTR((cdr.duration / 60), 1, 1), ':', cdr.duration % 60) as `time`,
								       CONCAT('<p onclick=play(', '\'', SUBSTRING(cdr.userfield, 7), '\'',  ')>მოსმენა</p>', '<a download=\"image.jpg\" href=\"http://92.241.82.243:8181/records/', SUBSTRING(cdr.userfield, 7), '\">ჩამოტვირთვა</a>')
								FROM   cdr
							WHERE      cdr.disposition = 'ANSWERED'
							AND cdr.userfield != '' 
							AND cdr.src IN ($agent)
							AND DATE(cdr.calldate) >= '$start_time'
							AND DATE(cdr.calldate) <= '$end_time'
							AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)");
	$data = array(
			"aaData"	=> array()
	);
		
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i = 0 ; $i < $count ; $i++ )
		{
			/* General output */
			$row[] = $aRow[$i];
		}
		$data['aaData'][] = $row;
	}
		
}
else
if($_REQUEST['act'] =='unanswear_dialog_table'){
	$data		= array('page' => array(
			'answear_dialog' => ''
	));
	$count = 		$_REQUEST['count'];
	$hidden = 		$_REQUEST['hidden'];
	$rResult = mysql_query("SELECT cdr.calldate,
								   cdr.calldate,
								   SUBSTRING(cdr.lastdata,5,7)
								   cdr.src,
								   cdr.dst,
								   CONCAT(SUBSTR((cdr.duration / 60), 1, 1), ':', cdr.duration % 60) as `time`,
							FROM   cdr
							WHERE  cdr.disposition = 'NO ANSWER'
							AND cdr.userfield != '' 
							AND cdr.src IN ($agent)
							AND DATE(cdr.calldate) >= '$start_time'
							AND DATE(cdr.calldate) <= '$end_time'
							AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)");
	$data = array(
			"aaData"	=> array()
	);

	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i = 0 ; $i < $count ; $i++ )
		{
			/* General output */
			$row[] = $aRow[$i];
		}
		$data['aaData'][] = $row;
	}

}
else
if($_REQUEST['act'] =='answear_dialog'){

				$data['page']['answear_dialog'] = '
															
													
												                <table class="display" id="example">
												                    <thead>
												                        <tr id="datatable_header">
												                            <th>ID</th>
												                            <th style="width: 100%;">თარიღი</th>
												                            <th style="width: 120px;">წყარო</th>
												                            <th style="width: 120px;">ადრესატი</th>
																			<th style="width: 80px;">ოპერატორი</th>
												                            <th style="width: 80px;">დრო</th>
												                            <th style="width: 100px;">ქმედება</th>
												                        </tr>
												                    </thead>
												                    <thead>
												                        <tr class="search_header">
												                            <th class="colum_hidden">
												                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
												                            </th>
												                            <th>
												                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="">
																			</th>
												                            <th>
												                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
												                            </th>                            
												                            <th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
												                            </th>
												                            <th>
												                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" style="width: 70px;"/>
												                            </th>
												                            <th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 70px;" />
												                            </th>
																			<th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
												                            </th>
												                            
												                        </tr>
												                    </thead>
												                </table>
												        
						
													';
			
			
}
else
if($_REQUEST['act'] =='unanswear_dialog'){

	$data['page']['answear_dialog'] = '
								
							
												                <table class="display" id="example1">
												                    <thead>
												                        <tr id="datatable_header">
												                            <th>ID</th>
												                            <th style="width: 100%;">თარიღი</th>
												                            <th style="width: 120px;">წყარო</th>
												                            <th style="width: 100px;">ადრესატი</th>
												                            <th style="width: 80px;">დრო</th>
												                        </tr>
												                    </thead>
												                    <thead>
												                        <tr class="search_header">
												                            <th class="colum_hidden">
												                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
												                            </th>
												                            <th>
												                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="">
																			</th>
												                            <th>
												                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
												                            </th>
												                            <th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
												                            </th>
																			<th>
												                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 70px;" />
												                            </th>
												                        </tr>
												                    </thead>
												                </table>


													';
		
		
}
else
{
	
$row_done_blank = mysql_fetch_assoc(mysql_query("	SELECT COUNT(*) AS `count`
													FROM `incomming_call`
													WHERE DATE(date) >= '$start_time' AND DATE(date) <= '$end_time' AND phone != '' "));



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
										'call_distribution_per_day_of_week' => ''
								));

$data['error'] = $error;
//------------------------------- ტექნიკური ინფორმაცია

	$row_answer = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) as count,
															cdr.src,
														    cdr.dst,
															SUBSTRING(cdr.lastdata,5,7)
													FROM    cdr
													WHERE   cdr.disposition = 'ANSWERED'
													AND cdr.userfield != ''
													AND cdr.src IN ($agent)
													AND DATE(cdr.calldate) >= '$start_time'
													AND DATE(cdr.calldate) <= '$end_time'
													AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)"));

	$row_abadon = mysql_fetch_assoc(mysql_query("	SELECT 	COUNT(*) as count,
															cdr.src,
														    cdr.dst,
															SUBSTRING(cdr.lastdata,5,7)
													FROM    cdr
													WHERE   cdr.disposition = 'NO ANSWER'
													AND cdr.userfield != ''
													AND cdr.src IN ($agent)
													AND DATE(cdr.calldate) >= '$start_time'
													AND DATE(cdr.calldate) <= '$end_time'
													AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)"));
	
	
	
	
	$data['page']['technik_info'] = '
							
                    <td>ზარი</td>
                    <td>'.($row_answer[count] + $row_abadon[count]).'</td>
                    <td id="answear_dialog" style="cursor: pointer; text-decoration: underline;">'.$row_answer[count].'</td>
                    <td id="unanswear_dialog" style="cursor: pointer; text-decoration: underline;">'.$row_abadon[count].'</td>
                    <!--td></td-->
                    <td>'.round(((($row_answer[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <td>'.round(((($row_abadon[count]) / ($row_answer[count] + $row_abadon[count])) * 100),2).' %</td>
                    <!--td> %</td-->
                
							';
// -----------------------------------------------------

//------------------------------- ნაპასუხები ზარები რიგის მიხედვით

	$g = mysql_query("	SELECT 	COUNT(*) as count,
								SUBSTRING(cdr.lastdata,5,7) as queue
						FROM    cdr
						WHERE   cdr.disposition = 'ANSWERED'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
						GROUP BY SUBSTRING(cdr.lastdata,5,7)");
	
	while ($rr = mysql_fetch_assoc($g)){								
	$data['page']['answer_call'] .= '
							<tr><td>'.$rr[queue].'</td><td>'.$rr[count].' ზარი</td><td>'.round(((($rr[count]) / ($row_answer[count])) * 100)).' %</td></tr>
							';
	}
//-------------------------------------------------------

	
//---------------------------------------- რეპორტ ინფო

	$data['page']['report_info'] = '
				
                <tr>
                    <td class="tdstyle">რიგი:</td>
                    <td>'.$queue.'</td>
                </tr>
                <tr>
                    <td class="tdstyle">საწყისი თარიღი:</td>
                    <td>'.$start_time.'</td>
                </tr>
                <tr>
                    <td class="tdstyle">დასრულების თარიღი:</td>
                    <td>'.$end_time.'</td>
                </tr>
                <tr>
                    <td class="tdstyle">პერიოდი:</td>
                    <td>'.$day_format.' დღე</td>
                </tr>

							';
	
//----------------------------------------------


//------------------------------------ ნაპასუხები ზარები


$row_clock = mysql_fetch_assoc(mysql_query("	SELECT	ROUND((sum(cdr.duration)  / COUNT(*)),0) AS `sec`,
														ROUND((sum(cdr.duration) / 60),0) AS `min`
												FROM    cdr
												WHERE   cdr.disposition = 'ANSWERED'
												AND cdr.userfield != ''
												AND cdr.src IN ($agent)
												AND DATE(cdr.calldate) >= '$start_time'
												AND DATE(cdr.calldate) <= '$end_time'
												AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)"));




	$data['page']['answer_call_info'] = '

                   	<tr>
					<td class="tdstyle">ნაპასუხები ზარები</td>
					<td>'.$row_answer[count].' ზარი</td>
					</tr>
					<!-- tr>
					<td class="tdstyle">გადამისამართებული ზარები</td>
					<td> ზარი</td>
					</tr -->
					<tr>
					<td class="tdstyle">საშ. ხანგძლივობა:</td>
					<td>'.$row_clock[sec].' წამი</td>
					</tr>
					<tr>
					<td class="tdstyle">სულ საუბრის ხანგძლივობა:</td>
					<td>'.$row_clock[min].' წუთი</td>
					</tr>
					<!-- tr>
					<td class="tdstyle">ლოდინის საშ. ხანგძლივობა:</td>
					<td> წამი</td>
					</tr -->

							';
	
//---------------------------------------------

	
//--------------------------- ნაპასუხები ზარები ოპერატორების მიხედვით

 	$ress =mysql_query("SELECT	COUNT(*) AS `num`,
 								ROUND(((sum(cdr.duration)  / COUNT(*))),0) AS `sec`,
								ROUND((sum(cdr.duration) / 60),0) AS `min`,
 								cdr.src AS `agent`
						FROM    cdr
						WHERE   cdr.disposition = 'ANSWERED'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
 						GROUP BY cdr.src");

while($row = mysql_fetch_assoc($ress)){

	$data['page']['answer_call_by_queue'] .= '

                   	<tr>
					<td>'.$row[agent].'</td>
					<td>'.$row[num].'</td>
					<td>'.ROUND((($row[num] / $row_answer[count]) * 100),2).' %</td>
					<td>'.$row[min].' წუთი</td>
					<td>'.ROUND((($row[min] / $row_clock[min]) * 100),2).' %</td>
					<td>'.$row[sec].' წამი</td>
					<!-- td> წამი</td>
					<td> წამი</td -->
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
					<td class="tdstyle">ოპერატორმა გათიშა:</td>
					<td>'.$row_COMPLETEAGENT[count].' ზარი</td>
					<td>0.00 %</td>
					</tr>
					<tr>
					<td class="tdstyle">აბონენტმა გათიშა:</td>
					<td>'.$row_COMPLETECALLER[count].' ზარი</td>
					<td>0.00 %</td>
					</tr>

							';

//-----------------------------------------------

//----------------------------------- უპასუხო ზარები


	$data['page']['unanswer_call'] = '

                   	<tr>
					<td class="tdstyle">უპასუხო ზარების რაოდენობა:</td>
					<td>'.$row_abadon[count].' ზარი</td>
					</tr>
					<!--tr>
					<td class="tdstyle">ლოდინის საშ. დრო კავშირის გაწყვეტამდე:</td>
					<td> წამი</td>
					</tr -->
					<tr>
					<td class="tdstyle">საშ. რიგში პოზიცია კავშირის გაწყვეტამდე:</td>
					<td>1</td>
					</tr>
					<tr>
					<td class="tdstyle">საშ. საწყისი პოზიცია რიგში:</td>
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
                  <td class="tdstyle">აბონენტმა გათიშა</td>
			      <td>'.$row_abadon[count].' ზარი</td>
			      <td>'.round((($row_abadon[count] / $row_abadon[count]) * 100),2).' %</td>
		        </tr>
			    <tr> 
                  <td class="tdstyle">დრო ამოიწურა</td>
			      <td>'.$row_timeout[count].' ზარი</td>
			      <td>'.round((($row_timeout[count] / $row_timeout[count]) * 100),2).' %</td>
		        </tr>

							';

//--------------------------------------------

//------------------------------ უპასუხო ზარები რიგის მიხედვით

	$r = mysql_query("	SELECT 	COUNT(*) as count,
								cdr.src,
							    cdr.dst,
								SUBSTRING(cdr.lastdata,5,7) as `queue`
						FROM    cdr
						WHERE   cdr.disposition = 'NO ANSWER'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
						GROUP BY SUBSTRING(cdr.lastdata,5,7)");
	
	while ($Unanswered_Calls_by_Queue = mysql_fetch_assoc($r)){
	$data['page']['unanswered_calls_by_queue'] .= '

                   	<tr><td>'.$Unanswered_Calls_by_Queue[queue].'</td><td>'.$Unanswered_Calls_by_Queue[count].' ზარი</td><td>'.round((($Unanswered_Calls_by_Queue[count] / $row_abadon[count]) * 100),2).' %</td></tr>

							';
	}
	
//---------------------------------------------------

//------------------------------------------- სულ

	$data['page']['totals'] = '

                   	<tr> 
                  <td class="tdstyle">ნაპასუხები ზარების რაოდენობა:</td>
		          <td>'.$row_answer[count].' ზარი</td>
	            </tr>
                <tr>
                  <td class="tdstyle">უპასუხო ზარების რაოდენობა:</td>
                  <td>'.$row_abadon[count].' ზარი</td>
                </tr>
		        <tr>
                  <td class="tdstyle">ოპერატორი შევიდა:</td>
		          <td>0</td>
	            </tr>
                <tr>
                  <td class="tdstyle">ოპერატორი გავიდა:</td>
                  <td>0</td>
                </tr>

							';
	
//------------------------------------------------

	
//-------------------------------- ზარის განაწილება დღეების მიხედვით
	
	$res = mysql_query("
						SELECT 	cdr.calldate as `datetime`,
								COUNT(*) as `count`,
								cdr.src,
							    cdr.dst,
								ROUND((sum(cdr.duration)  / COUNT(*)),0) AS `sec`,
								SUBSTRING(cdr.lastdata,5,7)
						FROM    cdr
						WHERE   cdr.disposition = 'ANSWERED'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
						GROUP BY DATE(cdr.calldate)
						");

	$ress = mysql_query("
						SELECT 	cdr.calldate as `datetime`,
								COUNT(*) as `count`,
								cdr.src,
							    cdr.dst,
								SUBSTRING(cdr.lastdata,5,7)
						FROM    cdr
						WHERE   cdr.disposition = 'NO ANSWER'
						AND cdr.userfield != ''
						AND cdr.src IN ($agent)
						AND DATE(cdr.calldate) >= '$start_time'
						AND DATE(cdr.calldate) <= '$end_time'
						AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
						GROUP BY DATE(cdr.calldate)
						");
	
	
	
	while($row = mysql_fetch_assoc($res)){
		$roww = mysql_fetch_assoc($ress);
			$data['page']['call_distribution_per_day'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
					<td>'.$row[count].'</td>
					<td>'.ROUND((($row[count] / $row_answer[count]) * 100),2).' %</td>
					<td>'.$roww[count].'</td>
					<td>'.ROUND((($roww[count] / $row_abadon[count]) * 100),2).' %</td>
					<td>'.$row[sec].' წამი</td>
					<!--td> წამი</td-->
					<td></td>
					<td></td>
					</tr>

							';
	}
	
//----------------------------------------------------

	
//-------------------------------- ზარის განაწილება საათების მიხედვით

	
	
	

			
			$res124 = mysql_query("
					SELECT 	HOUR(cdr.calldate) as `datetime`,
							COUNT(*) as `count`,
							cdr.src,
						    cdr.dst,
							ROUND((sum(cdr.duration)  / COUNT(*)),0) AS `sec`,
							SUBSTRING(cdr.lastdata,5,7)
					FROM    cdr
					WHERE   cdr.disposition = 'ANSWERED'
					AND cdr.userfield != ''
					AND cdr.src IN ($agent)
					AND DATE(cdr.calldate) >= '$start_time'
					AND DATE(cdr.calldate) <= '$end_time'
					AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
					GROUP BY HOUR(cdr.calldate)
					");
			
			$res1244 = mysql_query("
					SELECT 	HOUR(cdr.calldate) as `datetime`,
							COUNT(*) as `count`,
							cdr.src,
						    cdr.dst,
							ROUND((sum(cdr.duration)  / COUNT(*)),0) AS `sec`,
							SUBSTRING(cdr.lastdata,5,7)
					FROM    cdr
					WHERE   cdr.disposition = 'ANSWERED'
					AND cdr.userfield != ''
					AND cdr.src IN ($agent)
					AND DATE(cdr.calldate) >= '$start_time'
					AND DATE(cdr.calldate) <= '$end_time'
					AND SUBSTRING(cdr.lastdata,5,7) IN ($queue)
					GROUP BY HOUR(cdr.calldate)
					");
			
		while($row = mysql_fetch_assoc($res124)){
		$roww = mysql_fetch_assoc($res1244);
			$data['page']['call_distribution_per_hour'] .= '
				<tr class="odd">
						<td>'.$row[datetime].':00</td>
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
					GROUP BY DAYOFWEEK(qs.datetime)
					");

	while($row = mysql_fetch_assoc($res12)){
	$roww = mysql_fetch_assoc($res122);
	$data['page']['call_distribution_per_day_of_week'] .= '

                   	<tr class="odd">
					<td>'.$row[datetime].'</td>
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
}

echo json_encode($data);

?>