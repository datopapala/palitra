<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$priority_id				= $_REQUEST['id'];
		$page		= GetPage(Getpriority($priority_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	`production`.`id`,
										`production`.`name`,
										`genre`.`name`,
										`production_category`.`name`
								FROM 	`production`
								LEFT JOIN	`genre` ON 	`production`.`genre_id` = `genre`.`id`
								LEFT JOIN	`production_category` ON `production`.`production_category_id` = `production_category`.`id`
								WHERE 	`production`.`actived`=1");

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
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_priority':
		$priority_id 	= $_REQUEST['id'];
		$priority_name  = $_REQUEST['name'];
		$production_category_id		= $_REQUEST['production_category_id'];
		$genre_id					= $_REQUEST['genre_id'];
			
		if($priority_name != ''){
			
				if ($priority_id == '') {
					//$error = 'ghxtfjhu';
					Addpriority( $priority_id, $priority_name, $genre_id, $production_category_id);
				}else {
					Savepriority($priority_id, $priority_name, $genre_id, $production_category_id);
				}				
			
		}
		

		break;
	case 'disable':
		$priority_id	= $_REQUEST['id'];
		Disablepriority($priority_id);

		break;
	default:
		$error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Category Functions
* ******************************
*/

function Addpriority($priority_id, $priority_name, $genre_id, $production_category_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`production`
									(`user_id`,`name`,`genre_id`,`production_category_id`)
					VALUES 		('$user_id','$priority_name','$genre_id','$production_category_id')");
}

function Savepriority($priority_id, $priority_name, $genre_id, $production_category_id)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `production`
					SET     `user_id`='$user_id',
							`name` = '$priority_name',
							`genre_id` = '$genre_id',
							`production_category_id` = '$production_category_id'
					WHERE	`id` = $priority_id");
}

function Disablepriority($priority_id)
{
	mysql_query("	UPDATE `production`
					SET    `actived` = 0
					WHERE  `id` = $priority_id");
}

function CheckpriorityExist($priority_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `production`
											WHERE  `name` = '$priority_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Getpriority($priority_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT 	`production`.`id`,
													`production`.`name` AS `production`,
													`genre`.`id` AS `genre`,
													`production_category`.`id` AS `production_category`
											FROM 	`production`
											LEFT JOIN	`genre` ON 	`production`.`genre_id` = `genre`.`id`
											LEFT JOIN	`production_category` ON `production`.`production_category_id` = `production_category`.`id`
											WHERE   `production`.`id` = $priority_id" ));

	return $res;
}

function Getproduction_category($production_category_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	production_category
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $production_category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getgenre($genre_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	genre
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $genre_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function GetPage($res = '')
{
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

	    	<table class="dialog-form-table">
				<tr>
					<td style="width: 170px;"><label for="CallType">სახელი</label></td>
					<td>
						<input type="text" id="name" class="idle address" onblur="this.className=\'idle address\'" onfocus="this.className=\'activeField address\'" value="' . $res['production'] . '" />
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">ჟანრი</label></td>
					<td>
						<select style="width: 231px;" id="genre_id" class="idls object">'. Getgenre($res['genre']).'</select>
					</td>
				</tr>
				<tr>
					<td style="width: 170px;"><label for="CallType">პროდუქტის კატეგორია</label></td>
					<td>
						<select style="width: 231px;" id="production_category_id" class="idls object">'. Getproduction_category($res['production_category']).'</select>
					</td>
				</tr>
			</table>
			<!-- ID -->
			<input type="hidden" id="priority_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
	return $data;
}

?>
