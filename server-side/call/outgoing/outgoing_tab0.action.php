<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

$user				= $_SESSION['USERID'];
$task_id 			= $_REQUEST['id'];

$cur_date			= $_REQUEST['cur_date'];
$done_start_time	= $_REQUEST['done_start_time'];
$done_end_time		= $_REQUEST['done_end_time'];
$task_type_id		= $_REQUEST['task_type_id'];
$template_id		= $_REQUEST['template_id'];
$task_department_id	= $_REQUEST['task_department_id'];
$persons_id			= $_REQUEST['persons_id'];



switch ($action) {
	case 'get_add_page':
		$page		= GetPage($res='');
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
		    	
    	$rResult = mysql_query("SELECT 	task_detail.id,
										task_detail.id,
										'',
										incomming_call.first_name,
										task_type.`name`,
										department.`name`,
										users.username,
										task.end_date,
										status.`name`
								FROM task
								LEFT JOIN task_type ON task.task_type_id = task_type.id
								LEFT JOIN task_detail ON task.id = task_detail.task_id
								LEFT JOIN department ON task.department_id = department.id
								LEFT JOIN users ON task.responsible_user_id = users.id
								JOIN incomming_call ON task_detail.phone_base_inc_id = incomming_call.id
    							LEFT JOIN `status` ON task_detail.`status` = `status`.id
    							WHERE task_detail.user_id = '$user'
								UNION ALL
								SELECT 	task_detail.id,
										task_detail.id,
										IF(task_detail.person_n is NULL,phone.person_n,task_detail.person_n),
										IF(task_detail.first_name IS NULL,phone.first_last_name,(CONCAT(task_detail.first_name,' ',task_detail.last_name))),
										task_type.`name`,
										department.`name`,
										users.username,
										task.end_date,
										status.`name`
								FROM task
								LEFT JOIN task_type ON task.task_type_id = task_type.id
								LEFT JOIN task_detail ON task.id = task_detail.task_id
								LEFT JOIN department ON task.department_id = department.id
								LEFT JOIN users ON task.responsible_user_id = users.id
								JOIN phone ON task_detail.phone_base_id = phone.id
    							LEFT JOIN `status` ON task_detail.`status` = `status`.id
    							WHERE task_detail.user_id = '$user'");
		    
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
			Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id);
			//Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id);
		}else{
			
			Savetask($task_id, $cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id);
			//Savesite_user($incom_id, $personal_pin, $name, $personal_phone, $mail,  $personal_id);
			
		}
		
        break;
        
    case 'get_responsible_person_add_page':
        $page 		= GetResoniblePersonPage();
        $data		= array('page'	=> $page);
        
        break;
    case 'save_task':
    	// task_detail------------------------
    	$phone				= $_REQUEST['p_phone'];
    	$person_n			= $_REQUEST['p_person_n'];
    	$first_name			= $_REQUEST['p_first_name'];
    	$mail				= $_REQUEST['p_mail'];
    	$last_name 			= $_REQUEST['p_last_name'];
    	$person_status 		= $_REQUEST['p_person_status'];
    	$addres 			= $_REQUEST['p_addres'];
    	$b_day				= $_REQUEST['p_b_day'];
    	$city_id 			= $_REQUEST['p_city_id'];
    	$family_id 			= $_REQUEST['p_family_id'];
    	$profesion 			= $_REQUEST['p_profesion'];
    	$user				= $_SESSION['USERID'];
    	//------------------------------------
    	
    	mysql_query("INSERT INTO `task_detail` 
    			( `user_id`, `task_id`, `person_n`, `first_name`, `last_name`, `person_status`, `phone`, `mail`, `addres`, `b_day`, `city_id`, `family_id`, `profesion`, `actived`) 
    			VALUES 
    			( '$user', '$task_id', '$person_n', '$first_name', '$last_name', '$person_status', '$phone', '$mail', '$addres', '$b_day', '$city_id', '$family_id', '$profesion', '1')");
        
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


function Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id)
{  
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
				( `user_id`, `responsible_user_id`, `date`, `start_date`, `end_date`, `department_id`, `template_id`, `task_type_id`, `status`, `actived`)
				VALUES
				( '$user', '$persons_id', '$cur_date', '$done_start_time', '$done_end_time', '$task_department_id', '$template_id', '$task_type_id', '1', '1')
				");

}
function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '11111111', 22222, '333', '$personal_id', '$user')");

}
				

function Savetask($task_id, $cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id)
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

function Getpattern($id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `pattern`
						WHERE actived=1 ");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getincomming($task_id)
{
$res = mysql_fetch_assoc(mysql_query("" ));
	
	return $res;
}

function Getfamily($family_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	family
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $family_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getcity($city_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	city
							WHERE 	actived=1
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $city_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}

function Getscenar(){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shabloni
							GROUP BY 	`shabloni`.`name`
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $id){
			$data .= '<option value="' . $res['name'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['name'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
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
											<input type="text" id="p_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="p_person_n" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="p_first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_contragent'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="p_mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">ფიზ / იურ. პირი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="p_last_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="p_person_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარირი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="p_addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_status'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="p_b_day" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['b_day'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="p_city_id" class="idls object">'.Getcity($res['city_id']).'</select></td>
										<td><select style="width: 165px;" id="p_family_id" class="idls object">'.Getfamily($res['family_id']).'</select></td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="p_profesion" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['profesion'] . '" />		
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
											<td style="width: 350px;"><select style="width: 420px;" id="template_id" class="idls object">'. Getscenar($res['template_id']).'</select></td>
										</tr>
									</table>
									</fieldset>
									<fieldset style="width:200px; float:left;">
							    	<legend>ქვე-განყოფილება</legend>	
									<table width="100%" class="dialog-form-table">
										<tr>
											<td style="width: 200px;"><select style="width: 260px;" id="task_department_id" class="idls object">'.Getdepartment($res['task_department_id']).'</select></td>
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

		mysql_query("UPDATE 	task_detail
					JOIN 		task ON task_detail.task_id = task.id
					SET    	task_detail.`status`   			 = 2,
									task.`date` 			     = '$o_date',
									task.responsible_user_id     = '$responsible_person'
					WHERE  	task_detail.id = '$letter' AND task.id = task_detail.task_id");
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