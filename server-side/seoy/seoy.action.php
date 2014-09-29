<?php
/* ******************************
 *	SEOoooY aJax actions
 * ******************************
 */

include('../../includes/classes/core.php');
$action = $_REQUEST['act'];
$data = '';

switch ($action) {
	case 'production_name':
        $rResult = mysql_query("SELECT	`name`
								FROM 	`production`
        						WHERE 	`production`.`actived` = 1");

		$data = array();
		$s = 0;

		while($aRow = mysql_fetch_array($rResult))
		{
			for ( $i = 0 ; $i < 1; $i++ )
			{
				/* General output */
				$data[$s] = htmlspecialchars_decode($aRow[$i], ENT_QUOTES);
				$s++;
			}
		}

		break;
	case 'production_name_gift':
		$rResult = mysql_query("SELECT	`name`
								FROM 	`production`
        						WHERE 	`production`.`actived` = 1");
		
		$data = array();
		$s = 0;
		
		while($aRow = mysql_fetch_array($rResult))
		{
			for ( $i = 0 ; $i < 1; $i++ )
			{
				/* General output */
				$data[$s] = htmlspecialchars_decode($aRow[$i], ENT_QUOTES);
				$s++;
			}
		}
		
		break;
    default:
       echo 'Action is Null';
}


echo json_encode($data);

?>