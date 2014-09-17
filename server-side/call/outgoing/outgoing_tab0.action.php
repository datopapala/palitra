<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';



$priority_id			= $_REQUEST['priority_id'];
$template_id			= $_REQUEST['template_id'];

$problem_comment 		= $_REQUEST['problem_comment'];
$comment 	        	= $_REQUEST['comment'];
$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];



switch ($action) {
	case 'get_add_page':
		$number		= $_REQUEST['number'];
		$page		= GetPage($res='', $number);
		$data		= array('page'	=> $page);
		
        break;
    case 'get_task':
        $page		= Gettask();
        $data		= array('page'	=> $page);
        
        break;
    case 'get_edit_page':
	   
		$page		= GetPage(Getincomming($task_id));
        
        $data		= array('page'	=> $page);
        
        break;
	
 	case 'get_list' :
		$count		= $_REQUEST['count'];
	   	$hidden		= $_REQUEST['hidden'];
	    $user_id	= $_REQUEST['user_id'];
	    $user		= $_SESSION['USERID'];
	    $group		= checkgroup($user);
	    
	    $filter = '';
	    if ($group != 2) {
	    	$filter = 'AND outgoing_call.responsible_user_id ='. $user;
	    }
		    	
    	$rResult = mysql_query("SELECT 	 	`task`.id,
											`task`.id,
											`site_user`.`name`,
											`site_user`.`pin`,
											`person1`.`name` ,
											`person2`.`name` ,
											`incomming_call`.date,
											`status`.`call_status`
								FROM 		task			
								LEFT JOIN 		incomming_call ON task.incomming_call_id=incomming_call.id
								LEFT JOIN 	site_user		ON incomming_call.id=site_user.incomming_call_id
								
								
								JOIN 		users AS `user1`			ON task.responsible_user_id=user1.id
								JOIN 		persons AS `person1`		ON user1.person_id=person1.id
								
								JOIN 		users AS `user2`			ON task.user_id=user2.id
								JOIN 		persons AS `person2`		ON user2.person_id=person2.id
								
								LEFT JOIN `status`  	ON	task.`status`= `status`.id
								
								WHERE 		task.task_type_id=1 AND task.`status`=0");
		    
		$data = array(
			"aaData"	=> array()
		);
		
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			
			$row = array();
			for ( $i = 0 ; $i < $count; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
				if($i == ($count - 1)){
					$row[] ='<input type="checkbox" id="' . $aRow[$hidden] . '" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

        break;
    case 'save_outgoing':
	
		$user_id		= $_SESSION['USERID'];
		
		if(empty($task_id)){
			$task_id = mysql_insert_id();
			Addtask( $persons_id, $planned_end_date, $fact_end_date,$call_duration,  $priority_id, $template_id, $phone, $comment, $problem_comment,$file,$rand_file,$hidden_inc);
			Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id);
		}else{
			
			Savetask($task_id, $persons_id, $planned_end_date, $fact_end_date,$call_duration,  $priority_id, $template_id, $phone, $comment, $problem_comment,$file,$rand_file);
			//Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id);
			
		}
		
        break;
        
    case 'get_responsible_person_add_page':
        $page 		= GetResoniblePersonPage();
        $data		= array('page'	=> $page);
        
        break;
    case 'save_task':
    	// task_detail------------------------
    	$phone				= $_REQUEST['phone'];
    	$person_n			= $_REQUEST['person_n'];
    	$first_name			= $_REQUEST['first_name'];
    	$mail				= $_REQUEST['mail'];
    	$last_name 			= $_REQUEST['last_name'];
    	$person_status 		= $_REQUEST['person_status'];
    	$addres 			= $_REQUEST['addres'];
    	$user				= $_SESSION['USERID'];
    	//------------------------------------
    	
    	mysql_query("INSERT INTO `task_detail` 
    			( `user_id`, `person_n`, `first_name`, `last_name`, `person_status`, `phone`, `mail`, `addres`, `actived`) 
    			VALUES 
    			( '$user', '$person_n', '$first_name', '$last_name', '$person_status', '$phone', '$mail', '$addres', '1')");
        
        break;
        
    case 'change_responsible_person':
        $letters 			= json_decode( '['.$_REQUEST['lt'].']' );
        $responsible_person = $_REQUEST['rp'];
        
        ChangeResponsiblePerson($letters, $responsible_person);
        
        break;
        
        case 'delete_file':
        
        	mysql_query("DELETE FROM file WHERE id = $delete_id");
        
        	$increm = mysql_query("	SELECT  `name`,
        			`rand_name`,
        			`id`
        			FROM 	`file`
        			WHERE   `task_id` = $edit_id
        			");
        
        	$data1 = '';
        
        	while($increm_row = mysql_fetch_assoc($increm))	{
        	$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
				          <td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
				          <td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
        				          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
 					  </tr>';
		}
        
		$data = array('page' => $data1);
        
        		break;
        
        		case 'up_now':
        		$user		= $_SESSION['USERID'];
        		if($rand_file != ''){
        		mysql_query("INSERT INTO 	`file`
        		( 	`user_id`,
        		`task_id`,
        		`name`,
        		`rand_name`
        		)
        		VALUES
        		(	'$user',
        		'$edit_id',
        		'$file',
        		'$rand_file'
        		);");
        		}
        
        		$increm = mysql_query("	SELECT  `name`,
        		`rand_name`,
        		`id`
        		FROM 	`file`
        		WHERE   `task_id` = $edit_id
        		");
        
        		$data1 = '';
        
        		while($increm_row = mysql_fetch_assoc($increm))	{
        		$data1 .='<tr style="border-bottom: 1px solid #85b1de;">
        		<td style="width:110px; display:block;word-wrap:break-word;">'.$increm_row[name].'</td>
        		<td ><button type="button" value="media/uploads/file/'.$increm_row[rand_name].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none;background-image:url(\'media/images/get.png\');" id="download" ></button><input type="text" style="display:none;" id="download_name" value="'.$increm_row[rand_name].'"> </td>
				          <td ><button type="button" value="'.$increm_row[id].'" style="cursor:pointer; border:none; margin-top:25%; display:block; height:16px; width:16px; background:none; background-image:url(\'media/images/x.png\');" id="delete"></button></td>
        				          </tr>';
		}
        
		$data = array('page' => $data1);
		
		break;
        
    case '':
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Request Functions
 * ******************************
 */

function checkgroup($user){
	$res = mysql_fetch_assoc(mysql_query("
											SELECT users.group_id
											FROM    users
											WHERE  users.id = $user
										"));
	return $res['group_id'];
	
}


function Addtask( $persons_id, $planned_end_date, $fact_end_date,$call_duration,  $priority_id, $template_id, $phone, $comment, $problem_comment,$file,$rand_file,$hidden_inc)
{  
	$c_date		= date('Y-m-d H:i:s');
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` (`user_id`, `responsible_user_id`, `date`, `planned_end_date`, `fact_end_date`,`call_duration`,`priority_id`, `task_type_id`, `template_id`, `phone`, `comment`, `problem_comment`, `status`, `actived`)
						VALUES 
									('$user', '$persons_id', '$c_date', '$planned_end_date', '$fact_end_date', '$call_duration',  '$priority_id', '1', '$template_id', '$phone', '$comment', '$problem_comment', '0', '1');
	");
	
	if($rand_file != ''){
		mysql_query("INSERT INTO 	`file`
		( 	`user_id`,
		`task_id`,
		`name`,
		`rand_name`
		)
		VALUES
		(	'$user',
		'$hidden_inc',
		'$file',
		'$rand_file'
		);");
	}

}
function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '11111111', 22222, '333', '$personal_id', '$user')");

}
				

function Savetask($task_id, $persons_id, $planned_end_date, $fact_end_date,$call_duration,  $priority_id, $template_id, $phone, $comment, $problem_comment,$rand_file, $file)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  
								`user_id`				='$user',
								`responsible_user_id`	='$persons_id', 
								`date`					='$c_date',
								`planned_end_date`		='$planned_end_date',
								`fact_end_date`			='$fact_end_date', 
								`call_duration`			='$call_duration', 
								`priority_id`			='$priority_id',
								`template_id` 			='$template_id',
								`phone`					='$phone', 
								`comment`				='$comment', 
								`problem_comment`		='$problem_comment', 
								`status`='0', 
								`actived`='1'
								 WHERE `id`				='$task_id'
									");


}
function Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id)
{

	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE 	`site_user` 
				SET			
							`site`						='243', 
							`pin`						='$personal_pin', 
							`name`						='$name', 
							`phone`						='$personal_phone', 
							`mail`						='$mail', 
							`personal_id`				='$personal_id', 
							`user`						='$user'
							 WHERE `incomming_call_id`	='$incom_id'
							
					");

}


function Getcall_status($status)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `call_status`
						FROM 	`status`
						WHERE 	actived=1");
	

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		
		if($res['id'] == $call_status_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['$status'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['$status'] . '</option>';
		}
	}
	return $data;
}
	function Getpay_type($pay_type_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
							FROM 	`pay_type`
							WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $pay_type_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	function Get_bank($bank_id)
	{
		$data = '';
		$req = mysql_query("SELECT 	`id`, `name`
							FROM 	`bank`
							WHERE 	actived=1");
	
	
		$data .= '<option value="0" selected="selected">----</option>';
		while( $res = mysql_fetch_assoc($req)){
			if($res['id'] == $bank_id){
				$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
			} else {
				$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
			}
		}
	
		return $data;
	}
	
function Getbank_object($bank_object_id)
{  
	$data = '';
	$req = mysql_query("SELECT  id,
						     	`name`
						FROM 	bank_object
						WHERE 	bank_object.bank_id=$bank_object_id && actived =1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $bank_object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getbank_object_edit($bank_object_id)
{

	$data = '';
	$req = mysql_query("SELECT  id,
								`name`
						FROM 	bank_object
						WHERE 	bank_object.id=$bank_object_id && actived =1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $bank_object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
	
function Getcard_type($card_type_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`card_type`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $card_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getcard_type1($card_type1_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`card_type`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $card_type1_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getpay_aparat($pay_aparat_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`pay_aparat`
						WHERE 	actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $pay_aparat_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getobject($object_id)
{
	$data = '';
	$req = mysql_query("SELECT 	`id`, `name`
						FROM 	`object`
						WHERE 	actived=1");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $object_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}
function Getcategory($category_id)

{ 			

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=0 ");


	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcategory1($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && parent_id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
	
}

function Getcategory1_edit($category_id)
{

	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `category`
						WHERE actived=1 && id=$category_id");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $category_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;

}

function Getcall_type($call_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `call_type`
						WHERE actived=1");
		
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $call_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getpriority($priority_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `priority`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $priority_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Gettemplate($template_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `template`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $template_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Gettask_type($task_type_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
					    FROM `task_type`
					    WHERE actived=1");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getdepartment($department_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	department
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $department_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getpersonss($persons_id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `persons`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $persons_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getincomming($task_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT		task.id AS `id`,
													incomming_call.id AS `call_id`,
													IF(ISNULL(task.phone), incomming_call.phone, task.phone) AS `phone`,
													IF(ISNULL(incomming_call.date), task.date, incomming_call.date) AS call_date,
													incomming_call.call_type_id AS call_type_id,
													incomming_call.call_category_id AS category_id,
													IF(ISNULL(task.`status`), 3, task.`status`) AS `status`,
													incomming_call.call_subcategory_id AS category_parent_id,
													incomming_call.problem_date ,
													incomming_call.call_content AS call_content,
													incomming_call.pay_type_id AS pay_type_id,
													incomming_call.bank_id AS bank_id,
													incomming_call.bank_object_id AS bank_object_id,
													incomming_call.card_type_id AS card_type_id,
													incomming_call.card_type_id AS card_type1_id,
													incomming_call.pay_aparat_id AS pay_aparat_id,
													incomming_call.object_id AS object_id,
													site_user.`name` AS `name`,
													site_user.mail AS mail,
													site_user.personal_id AS personal_id,
													site_user.phone AS personal_phone,
													site_user.pin AS personal_pin,
													site_user.friend_pin AS friend_pin,
													site_user.`name` AS `name1`,
													site_user.`mail` AS `mail`,
													site_user.`user` AS `user`,
													task.task_type_id AS task_type_id,
													task.responsible_user_id AS persons_id,
													task.priority_id AS priority_id,
													task.planned_end_date AS planned_end_date,
													task.fact_end_date   AS fact_end_date,
													task.call_duration   AS 	call_duration,
													task.department_id AS task_department_id,
													task.phone AS phone,
													task.`comment` AS `comment`,
													task.problem_comment AS problem_comment,
													template.id AS template_id

										FROM 	   	task
										LEFT JOIN  	incomming_call  ON incomming_call.id = task.incomming_call_id
										LEFT JOIN  	site_user ON incomming_call.id = site_user.incomming_call_id
										LEFT JOIN  	template ON task.template_id = template.id
										WHERE      	task.id = $task_id
			" ));
	
	return $res;
}

function Gettask(){
	$data  .= '<div id="dialog-form">
							<div style="float: left; width: 380px;">
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
	
							    	<table style="height: 243px;">						
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_contragent'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">ფიზიკური პირი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="last_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="person_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_status'] . '" />		
										</td>
									</tr>
								</table>
									
					</fieldset>
				</div>
		    </div>';
	
	
	return $data;
}

function GetPage($res='', $number)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}

		$c_date		= date('Y-m-d H:i:s');
		
		$data  .= '<div id="dialog-form">
							<div style="float: left; width: 760px;">
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
						
							    	<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 180px;"><label for="req_num">დავალების №</label></td>
											<td style="width: 180px;"><label for="req_num">შექმნის თარიღი</label></td>
											<td style="width: 180px;"><label for="req_data">შესრულების პერიოდი</label></td>
											<td style="width: 180px;"><label for="req_phone"></label></td>
										</tr>
										<tr>
											<td style="width: 150px;">
												<input style="width: 150px;" type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . (($res['id']!='')?$res['id']:increment('task')). '" />
											</td>
											<td style="width: 150px;">
												<input style="width: 150px;" type="text" id="cur_date" class="idle" onblur="this.className=\'idle\'"  value="' . (($res['call_date']!='')?$res['call_date']:$c_date). '" />
											</td>
											<td style="width: 200px;">
												<input style="float:left;" type="text" id="done_start_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['call_date']. '"  /><span style="float:left; margin-top:5px;">-დან</span>
											</td>
											<td style="width: 200px;">
												<input style="float:left;" type="text" id="done_end_time" class="idle" onblur="this.className=\'idle\'" value="' .  $res['phone'] . '" /><span style="float:left; margin-top:5px;">-მდე</span>
											</td>
										</tr>
									</table>
									<fieldset style="width:200px; float:left;">
							    	<legend>დავალების ტიპი</legend>	
									<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 200px;"><select style="width: 260px;" id="task_type_id" class="idls object">'. Gettask_type($res['task_type_id']).'</select></td>
										</tr>
									</table>
									</fieldset>
									<fieldset style="width:390px; float:left; margin-left:10px;">
							    	<legend>სცენარი</legend>	
									<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 350px;"><select style="width: 420px;" id="task_department_id" class="idls object">'. Getdepartment($res['task_department_id']).'</select></td>
										</tr>
									</table>
									</fieldset>
									<fieldset style="width:200px; float:left;">
							    	<legend>ქვე-განყოფილება</legend>	
									<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 200px;"><select style="width: 260px;" id="task_department_id" class="idls object">'. Getdepartment($res['task_department_id']).'</select></td>
										</tr>
									</table>
									</fieldset>
									<fieldset style="width:390px; float:left; margin-left:10px; margin-bottom:15px;">
							    	<legend>პასუხისმგებელი პირი</legend>	
									<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 350px;"><select style="width: 420px;" id="persons_id" class="idls object">'. Getpersons($res['persons_id']).'</select></td>
										</tr>
									</table>
									</fieldset>
									
									<div id="dt_example" class="inner-table">
							        <div style="width:100%;" id="container" >        	
							            <div id="dynamic">
							            	<div id="button_area">
							            		<button id="add_button_pp">დამატება</button>
						        			</div>
							                <table class="" id="example4" style="width: 100%;">
							                    <thead>
													<tr  id="datatable_header">
							                           <th style="display:none">ID</th>
														<th style="width:4%;">#</th>
														<th style="width:%; word-break:break-all;">პირადი №<br>საიდ. კოდი</th>
														<th style="width:%; word-break:break-all;">დასახელება</th>
														<th style="width:%; word-break:break-all;">ფიზ / იურ.<br> პირი</th>
														<th style="width:%; word-break:break-all;">ტელეფონი</th>
														<th style="width:%; word-break:break-all;">ელ-ფოსტა</th>
														<th style="width:%; word-break:break-all;">მისამართი</th>
													</tr>
												</thead>
												<thead>
													<tr class="search_header">
														<th class="colum_hidden">
					                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
					                            		</th>
														<th>
															<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
														</th>
														<th>
															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
														</th>
													<th>
															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
														</th>
													<th>
															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
														</th>
													<th>
															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
														</th>
													<th>
															<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
														</th>
													</tr>
												</thead>
							                </table>
							            </div>
							            <div class="spacer">
							            </div>
							        </div>
								</fieldset>';
				
	
				         
		 $data .= '
 					</table>
					</fieldset>
				</div>
		    </div>';	

	
	$data .= '<input type="hidden" id="outgoing_call_id" value="' . $res['id'] . '" />';

	return $data;
}
function ChangeResponsiblePerson($letters, $responsible_person){
	$o_date		= date('Y-m-d H:i:s');
	foreach($letters as $letter) {

		mysql_query("UPDATE task
					SET    	task.`status`   			 = 1,
							task.`date` 			     = '$o_date',
							task.responsible_user_id     = '$responsible_person'
					WHERE  	task.id 					 = '$letter'");
	}
}

function GetPersons(){
	$data = '';
	$req = mysql_query("SELECT 		users.id AS `id`,
									persons.`name` AS `name`
						FROM 		`persons`
						JOIN    	users ON users.person_id = persons.id");

	$data .= '<option value="' . 0 . '" selected="selected">' . '' . '</option>';

	while( $res = mysql_fetch_assoc($req)){
		$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
	}
	return $data;
}

function GetResoniblePersonPage(){
	$data = '
		<div id="dialog-form">
			<fieldset>
				<legend>ძირითადი ინფორმაცია</legend>
				<table width="100%" class="dialog-form-table" cellpadding="10px" >
					<tr>
						<th><label for="responsible_person">პასუხისმგებელი პირი</label></th>
					</tr>
					<tr>
						<th>
							<select id="responsible_person" class="idls address">'. GetPersons() .'</select>
						</th>
					</tr>
				</table>
			</fieldset>
		</div>';
	return $data;

}
function increment($table){

	$result   		= mysql_query("SHOW TABLE STATUS LIKE '$table'");
	$row   			= mysql_fetch_array($result);
	$increment   	= $row['Auto_increment'];
	$next_increment = $increment+1;
	mysql_query("ALTER TABLE '$table' AUTO_INCREMENT=$next_increment");

	return $increment;
}
?>