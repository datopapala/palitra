<?php
/* ******************************
	Incoming Tasks aJax actions
   ******************************
*/
	include('../../../includes/classes/core.php');
	$action = $_REQUEST['act'];
	$start		=	$_REQUEST['start'];
    $end		=	$_REQUEST['end'];
    $error 		= '';
    $data       = '';

	switch ($action) {
	    case 'get_list':
    	    $count = $_REQUEST['count'];
    	    $person_id  =   $_REQUEST['person_id'];
    	    $password   =   $_REQUEST['password'];
   	    

    	    $rResult = mysql_query("SELECT 		WA.id AS `id`,
												DATE(WA.start_date) AS `date`,
												persons.`name` AS `person`,
												TIME(WA.start_date) AS `job_start`,
												TIME(WA.timeout_start_date) AS `timeout_start_date`,
												TIME(WA.timeout_end_date) AS `timeout_end_date`,
												TIME(WA.end_date) AS `job_end`,
												(TIMEDIFF(MIN(WA.timeout_start_date), MAX(WA.timeout_end_date))) AS `timeout_time`,
												(TIMEDIFF(MIN(WA.end_date), MAX(WA.start_date))) AS `work_time`,	
    	    									IF(
													TIMEDIFF( TIMEDIFF(	(TIMEDIFF(MIN(WA.end_date), MAX(WA.start_date))), (TIMEDIFF(MIN(WA.timeout_start_date), MAX(WA.timeout_end_date))) 	), '07:00:00' ) > 0 ,
													TIMEDIFF( TIMEDIFF(	 (TIMEDIFF(MIN(WA.end_date), MAX(WA.start_date))), (TIMEDIFF(MIN(WA.timeout_start_date), MAX(WA.timeout_end_date)))	), '07:00:00' ),
													0
												)AS `diff`,
												IF(
													TIMEDIFF( TIMEDIFF(	(TIMEDIFF(MIN(WA.end_date), MAX(WA.start_date))), (TIMEDIFF(MIN(WA.timeout_start_date), MAX(WA.timeout_end_date))) 	), '07:00:00' ) < 0 ,
													SUBSTRING(TIMEDIFF( TIMEDIFF((TIMEDIFF(MIN(WA.end_date), MAX(WA.start_date))), (TIMEDIFF(MIN(WA.timeout_start_date), MAX(WA.timeout_end_date)))	), '07:00:00' ), 2),
													0
												)AS `diff1`
									FROM 		`worker_action` AS `WA`
									JOIN       	persons ON persons.id = WA.person_id
									WHERE       DATE(WA.start_date) >= '$start' and DATE(WA.start_date) <= '$end' AND WA.person_id = $person_id
									GROUP BY 	DATE(WA.start_date) , WA.person_id
									ORDER BY   	WA.start_date
    	    						");
												
			$output = array(
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
				$output['aaData'][] = $row;
			}
			
			echo json_encode( $output );


	        break;	    
	    default:
	       	echo "null";
	}
	
?>