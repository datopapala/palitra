<style type='text/css'>
#box-table-b
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	text-align: center;
	border-collapse: collapse;
	border-top: 7px solid #9baff1;
	border-bottom: 7px solid #9baff1;
}
#box-table-b th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #e8edff;
	border-right: 1px solid #9baff1;
	border-left: 1px solid #9baff1;
	color: #039;
}
#box-table-b td
{
	padding: 8px;
	background: #e8edff; 
	border-right: 1px solid #aabcfe;
	border-left: 1px solid #aabcfe;
	color: #669;
}
</style>

<?php

mysql_connect('212.72.155.176', 'root', '') or die('kavsh');
mysql_select_db('stats') or die ('baza');

//require_once '../../includes/classes/asteriskcore.php';
mysql_query("SET @i=0;");
$res = mysql_query("SELECT 		@i := @i + 1 AS `id`,
								qname.queue,
								COUNT(*) AS `quant`,
								ROUND((COUNT(*) / (SELECT COUNT(*) FROM queue_stats WHERE queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()) * 100), 2) AS `percent`
					FROM 		`queue_stats`
					JOIN 		qname ON queue_stats.qname = qname.qname_id
					WHERE 		queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()
					GROUP BY 	queue_stats.qname"); 

$res1 = mysql_query("SELECT 	@i := @i + 1 AS `iterator`,
								qagent.agent,
								COUNT(*) AS `quant`,
								ROUND((COUNT(*) / (SELECT COUNT(*) FROM queue_stats WHERE queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()) * 100), 2) AS `percent`
					FROM 		`queue_stats`
					JOIN 		qagent ON queue_stats.qagent = qagent.agent_id
					WHERE 		queue_stats.qevent = 10 AND DATE(queue_stats.datetime) = CURDATE()
					GROUP BY 	queue_stats.qagent");

$res2 = mysql_query("SELECT	COUNT(*) as counter
					FROM	queue_stats AS qs,
								qname AS q, 
								qagent AS ag,
								qevent AS ac
					WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND 
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE() AND 
					q.queue IN ('2470017') AND ag.agent in ('ALF1','ALF2','ALF3','ALF4') AND ac.event IN ( 'COMPLETEAGENT') ORDER BY ag.agent");

$res3 = mysql_query("SELECT	COUNT(*) as counter
					FROM	queue_stats AS qs,
							qname AS q,
							qagent AS ag,
							qevent AS ac
					WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE() AND
					q.queue IN ('2470017') AND ag.agent in ('ALF1','ALF2','ALF3','ALF4') AND ac.event IN ('COMPLETECALLER') ORDER BY ag.agent");

$res4 = mysql_query("SELECT COUNT(*) as unanswer, q.queue AS qname
					FROM queue_stats AS qs, qname AS q, 
					qagent AS ag, qevent AS ac WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND 
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE()
					AND q.queue IN ('2470017') AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT') ORDER BY qs.datetime");

$res5 = mysql_query("SELECT COUNT(*) as unanswer, q.queue AS qname
					FROM queue_stats AS qs, qname AS q,
					qagent AS ag, qevent AS ac WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE()
					AND q.queue IN ('2470017') AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT') ORDER BY qs.datetime");

$res6 = mysql_query("SELECT DISTINCT (SELECT count(*) FROM queue_stats AS qs, qname AS q,qagent AS ag, qevent AS ac WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND 
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE()
					AND q.queue IN ('2470017','NONE') AND ac.event IN ('COMPLETECALLER','COMPLETEAGENT','AGENTLOGIN','AGENTLOGOFF','AGENTCALLBACKLOGIN')) as answer,
					(SELECT count(*) FROM queue_stats AS qs, qname AS q,qagent AS ag, qevent AS ac WHERE qs.qname = q.qname_id AND qs.qagent = ag.agent_id AND 
					qs.qevent = ac.event_id AND DATE(qs.datetime) = CURDATE()
					AND q.queue IN ('2470017','NONE') AND ac.event IN ('ABANDON', 'EXITWITHTIMEOUT')) as unanswer, CURDATE() as date
					FROM queue_stats");

$queue = '';
while ($row = mysql_fetch_assoc($res)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}
	
	$queue .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[queue].'</th>
		 			<th style="width: 80px;">'.$row[quant].'</th>
		 			<th style="width: 80px;">'.$row[percent].'</th>
		 		</tr>';
}

$disconect = '';
while ($row = mysql_fetch_assoc($res2)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$disconect .= '<tr '. $odd .'>
			 		<th style="width: 80px;">ოპერატორი</th>
		 			<th style="width: 80px;">'.$row[counter].'</th>
		 			<th style="width: 80px;">0</th>
		 		</tr>';
}

$distribution  = '';
while ($row = mysql_fetch_assoc($res6)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$distribution .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[date].'</th>
		 			<th style="width: 80px;">'.$row[answer].'</th>
		 			<th style="width: 80px;">'.$row[unanswer].'</th>
		 		</tr>';
}

$disconect1 = '';
while ($row = mysql_fetch_assoc($res3)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$disconect1 .= '<tr '. $odd .'>
			 		<th style="width: 80px;">მომხმარებელი</th>
		 			<th style="width: 80px;">'.$row[counter].'</th>
		 			<th style="width: 80px;">0</th>
		 		</tr>';
}

$unanswer = '';
while ($row = mysql_fetch_assoc($res4)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$unanswer .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[qname].'</th>
		 			<th style="width: 80px;">'.$row[unanswer].'</th>
		 			<th style="width: 80px;">0</th>
		 		</tr>';
}

$unanswer1 = '';
while ($row = mysql_fetch_assoc($res5)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$unanswer1 .= '<tr '. $odd .'>
			 		<th style="width: 80px;">User Abandon</th>
		 			<th style="width: 80px;">'.$row[unanswer].'</th>
		 			<th style="width: 80px;">0</th>
		 		</tr>';
}

$agent = '';
while ($row = mysql_fetch_assoc($res1)) {
	if ($row[id]%2 ) {
		$odd = 'class="odd"';
	}

	$agent .= '<tr '. $odd .'>
			 		<th style="width: 80px;">'.$row[agent].'</th>
		 			<th style="width: 80px;">'.$row[quant].'</th>
		 			<th style="width: 80px;">'.$row[percent].'</th>
		 		</tr>';
}

 $data = '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">რიგი</th>
		 			<th style="width: 80px;">ზარები</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
 				$queue	
	 		.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">ოპერატორი</th>
		 			<th style="width: 80px;">ზარები</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
  					$agent
  			.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">მიზეზი</th>
		 			<th style="width: 80px;">ჯანი</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
  			$disconect
  			.$disconect1
  			.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">Cause</th>
		 			<th style="width: 80px;">Count</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
  			$unanswer1
  			.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">Queue</th>
		 			<th style="width: 80px;">Count</th>
		 			<th style="width: 80px;">%</th>
		 		</tr>
 			<thead>
 			<tbody>'.
 			$unanswer
  			.'<tbody>
 		</table>';
 
 $data .= '<table id="box-table-b">
 			<thead>
		 		<tr>
			 		<th style="width: 80px;">Date</th>
		 			<th style="width: 80px;">Answered</th>
		 			<th style="width: 80px;">Unanswered</th>
		 		</tr>
 			<thead>
 			<tbody>'.
  			$distribution
  			.'<tbody>
 		</table>';
 
 echo  $data;