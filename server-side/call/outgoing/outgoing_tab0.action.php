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
$status				= $_REQUEST['status'];


switch ($action) {
	case 'get_add_page':
		$page		= GetPage($res='');
		$data		= array('page'	=> $page);
		
        break;
    case 'get_task':
        $page		= Gettask();
        $data		= array('page'	=> $page);
        
        break;
    case 'set_task':
    	$set_task_department_id		= $_REQUEST['set_task_department_id'];
    	$set_persons_id				= $_REQUEST['set_persons_id'];
    	$set_priority_id			= $_REQUEST['set_priority_id'];
    	$set_start_time				= $_REQUEST['set_start_time'];
    	$set_done_time				= $_REQUEST['set_done_time'];
    	$set_body					= $_REQUEST['set_body'];
    	
    	$set_task_id = mysql_fetch_assoc(mysql_query("SELECT `task_id` FROM `task_detail` WHERE `id` = '$task_id'"));
    	$tas = $set_task_id[task_id]; 
        GetSetTask($task_id, $tas, $set_task_department_id, $set_persons_id, $set_priority_id, $set_start_time, $set_done_time, $set_body);
        
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
								LEFT JOIN users ON task_detail.responsible_user_id = users.id
								JOIN incomming_call ON task_detail.phone_base_inc_id = incomming_call.id
    							LEFT JOIN `status` ON task_detail.`status` = `status`.id
    							WHERE task_detail.user_id = '$user' and task_detail.`status` = 0
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
								LEFT JOIN users ON task_detail.responsible_user_id = users.id
								JOIN phone ON task_detail.phone_base_id = phone.id
    							LEFT JOIN `status` ON task_detail.`status` = `status`.id
    							WHERE task_detail.user_id = '$user' and task_detail.`status` = 0");
		    
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
			Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id, $status);
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

function GetSetTask($task_id, $tas, $set_task_department_id, $set_persons_id, $set_priority_id, $set_start_time, $set_done_time, $set_body)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET
	`user_id`				='$user',
	`responsible_user_id`	='$set_persons_id',
	`date`					='$c_date',
	`start_date`			='$set_start_time',
	`end_date`				='$set_done_time',
	`department_id`			='$set_task_department_id',
	`priority_id`			='$set_priority_id',
	`comment`				='$set_body'
	WHERE `id`				='$tas'
	");
	
	mysql_query("UPDATE `task_detail` SET
						`status`	='0'
				WHERE   `id`		='$task_id'
				");
}

function checkgroup($user){
	$res = mysql_fetch_assoc(mysql_query("
											SELECT users.group_id
											FROM    users
											WHERE  users.id = $user
										"));
	return $res['group_id'];
	
}


function Addtask($cur_date, $done_start_time, $done_end_time, $task_type_id, $template_id, $task_department_id, $persons_id, $status)
{  
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `task` 
				( `user_id`, `responsible_user_id`, `date`, `start_date`, `end_date`, `department_id`, `template_id`, `task_type_id`, `status`, `actived`)
				VALUES
				( '$user', '$persons_id', '$cur_date', '$done_start_time', '$done_end_time', '$task_department_id', '$template_id', '$task_type_id', '$status', '1')
				");

}
function Addsite_user($incomming_call_id, $personal_pin, $friend_pin, $personal_id)
{
	
	$user		= $_SESSION['USERID'];
	mysql_query("INSERT INTO `site_user` 	(`incomming_call_id`, `site`, `pin`, `friend_pin`, `name`, `phone`, `mail`, `personal_id`, `user`)
						           		 VALUES 
											( '$incomming_call_id', '243', '$personal_pin', '$friend_pin', '11111111', 22222, '333', '$personal_id', '$user')");

}

function Getshabl($templ){

	$req = mysql_query("	SELECT 	`id`,
			`name`
			FROM 	shabloni
			WHERE 	id = $templ
			GROUP BY 	`shabloni`.`name`
			");

			$res = mysql_fetch_assoc($req);
			$shabl_name .= $res[name];

			return $shabl_name;
}

function Getstatus($status){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	status

							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $status){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
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

function Getshipping($id)
{
	$data = '';
	$req = mysql_query("SELECT `id`, `name`
						FROM `shipping`
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

function Getshablon($id,$templ){
	if($templ !=''){
		$req = mysql_query("	SELECT 	`id`,
				`name`
				FROM 	shabloni
				WHERE 	id = $templ
				GROUP BY 	`shabloni`.`name`
				");
	}else{
		$req = mysql_query("	SELECT 	`id`,
				`name`
				FROM 	shabloni
				WHERE 	scenar_id = $id
				GROUP BY 	`shabloni`.`name`
				");
		}

				$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
	if(($res['id'] == $id)or ($res['id'] == $templ)){
	$data .= '<option value="' . $res['name'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
		$data .= '<option value="' . $res['name'] . '">' . $res['name'] . '</option>';
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

function Getincomming($task_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	task_detail.id,
			    								`task`.`date`,
												`task_detail`.`status`,
												`task`.start_date,
												task.end_date,
												task.`task_type_id`,
												task.`template_id`,
												IF(task_detail.phone_base_inc_id != '', incomming_call.phone, phone.phone1) as phone,
												IF(task_detail.phone_base_inc_id != '', '', phone.born_day) as b_day,
												IF(task_detail.phone_base_inc_id != '', incomming_call.first_name, phone.first_last_name) as first_name,
												IF(task_detail.phone_base_inc_id != '', '', phone.addres) as addres,
												IF(task_detail.phone_base_inc_id != '', '', phone.person_n) as person_n,
												IF(task_detail.phone_base_inc_id != '', '', phone.city) as city_id,
												IF(task_detail.phone_base_inc_id != '', '', phone.mail) as mail,
												task_scenar.hello_comment,
												task_detail.call_content,
												task_scenar.hello_quest,
												task_scenar.info_comment,
												task_scenar.info_quest,
												task_scenar.payment_comment,
												task_scenar.payment_quest,
												task_scenar.result_comment,
												task_scenar.result_quest,
												task_scenar.send_date,
												task_scenar.preface_quest,
												task_scenar.preface_name,
												task_scenar.d1,
												task_scenar.d2,
												task_scenar.d3,
												task_scenar.d4,
												task_scenar.d5,
												task_scenar.d6,
												task_scenar.d7,
												task_scenar.d8,
												task_scenar.d9,
												task_scenar.d10,
												task_scenar.d11,
												task_scenar.d12,
												task_scenar.q1
										FROM 	`task`
										LEFT JOIN	task_detail ON task.id = task_detail.task_id
										LEFT JOIN	task_type ON task.task_type_id = task_type.id
										LEFT JOIN	pattern ON task.template_id = pattern.id
										LEFT JOIN	task_scenar ON task_detail.id = task_scenar.task_detail_id
										LEFT JOIN incomming_call ON task_detail.phone_base_inc_id = incomming_call.id
										LEFT JOIN phone ON task_detail.phone_base_id = phone.id
			    						WHERE	task_detail.id = '$task_id'
			" ));
	
	return $res;
}


function GetPage($res='', $shabloni)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}
	

		$data  .= '<div id="dialog-form">
							<div style="float: left; width: 710px;">
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
						
							    	<table width="65%" class="dialog-form-table">
										<tr>
											<td style="width: 180px;"><label for="">დავალების №</label></td>
											<td style="width: 180px;"><label for="">თარიღი</label></td>
										</tr>
										<tr>
											<td>
												<input type="text" id="id" class="idle" onblur="this.className=\'idle\'" disabled value="' . $res['id']. '" disabled="disabled" />
											</td>
											<td>
												<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'" disabled  value="' .  $res['date']. '" disabled="disabled" />
											</td>		
										</tr>
									</table><br>
								
														
								<fieldset style="width:250px; float:left;">
							    	<legend>დავალების ტიპი</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 305px;" id="task_type_id_seller" disabled class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
								<fieldset style="width:340px; float:left; margin-left:10px;">
							    	<legend>სცენარის დასახელება</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 380px;" id="shabloni" disabled class="idls object">'.Getshablon('',$res['template_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
						        ';
		
						$test = Getshabl($res['template_id']);
						
						//$notes = array();
						//$a 	= 	array();
						
						for($key=1;$key<25;$key++){
						
						$rows1 = mysql_query("	SELECT 	quest_id,
														notes,
														qvota
												FROM 	shabloni
												WHERE 	`name`='$test' and quest_id='$key'");
						$row = mysql_fetch_assoc($rows1);
							
								$notes[] = array('id' => $row[quest_id],'notes' => $row[notes], 'qvota' => $row[qvota]);
							
						}
						// სატელეფონო გაყიდვები დასაწყისი
						$data .= '
							<div id="quest">
						<div id="seller" class="'.(($notes[0][id]!="")?"":"dialog_hidden").'" >
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0" >
									<fieldset style="width:97%;   float:left; overflow-y:scroll; max-height:400px;" class="'.(($notes[0][id]!="")?"":"dialog_hidden").'">
									<fieldset style="width:97%;" >
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[0][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:right;"><span>აქვს</span></td>
					  							<td><input type="radio" name="hello_quest" value="1" '.(($res['hello_quest']=='1')?"checked":"").'></td>
					  							<td><span>(ვაგრძელებთ)</span></td>
					  						</tr>
											<tr>
												<td style="text-align:right;"><span>სურს სხვა დროს</span></td>
					  							<td><input type="radio" name="hello_quest" value="2" '.(($res['hello_quest']=='2')?"checked":"").'></td>
					  							<td><span>(ვიფორმირებთ დავალებას)</span></td>
					  						</tr>
					  						<tr>
												<td style="text-align:right;"><span>არ სურს</span></td>
					  							<td><input type="radio" name="hello_quest" value="3" '.(($res['hello_quest']=='3')?"checked":"").'></td>
					  							<td><span>(ვასრულებთ)</span></td>
					  						</tr>
									</table>
					  				<fieldset style="width:97%; float:left; ">
								    	<legend>კომენტარი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="hello_comment" class="idle" name="content" cols="300" >' . $res['hello_comment'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
											
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="'.(($notes[1][id]!="")?"":"dialog_hidden").'">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[1][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;" class="'.(($notes[2][id]!="")?"":"dialog_hidden").'">
								    	<legend>პროდუქტი</legend>
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_product">დამატება</button>
													<button id="delete_button_product">წაშლა</button>
							        			</div>
								                <table class="" id="sub1" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
															<th style="width:4%;">#</th>
															<th style="width:50%;">პაკეტი</th>
															<th style="width:50%;">ფასი</th>
															<th style="width:50%;">აღწერილობა</th>
															<th style="width:50%;">შენიშვნა</th>
															<th style="width:5%;">#</th>
														</tr>
													</thead>
													<thead>
														<tr class="search_header">
															<th>
																<input style="width:20px;" type="text" name="search_overhead" value="" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
															</th>
															<th>
                            									<input type="checkbox" name="check-all" id="check-all_p">
                            								</th>
														</tr>
													</thead>
								                </table>
								            </div>
								            <div class="spacer">
								            </div>
								        </div>
										<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content_3" disabled class="idle" name="content" cols="300" >'.$notes[2][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; " class="'.(($notes[3][id]!="")?"":"dialog_hidden").'">
								    	<legend>საჩუქარი</legend>														
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_gift">დამატება</button>
					  								<button id="delete_button_gift">წაშლა</button>
							        			</div>
								                <table class="" id="sub2" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
															<th style="width:4%;">#</th>
															<th style="width:50%;">პაკეტი</th>
															<th style="width:50%;">ფასი</th>
															<th style="width:50%;">აღწერილობა</th>
															<th style="width:50%;">შენიშვნა</th>
					  										<th style="width:5%;">#</th>
														</tr>
													</thead>
													<thead>
														<tr class="search_header">
															<th>
																<input style="width:20px;" type="text" name="search_overhead" value="" class="search_init" />
															</th>
					  										<th>
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
															</th>
															<th>
																<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
															</th>
					  										<th>
                            									<input type="checkbox" name="check-all" id="check-all_g">
                            								</th>
					  										
														</tr>
													</thead>
								                </table>
								            </div>
								            <div class="spacer">
								            </div>
								        </div>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content_4" disabled class="idle" name="content" cols="300" >'.$notes[3][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset class="'.(($notes[20][id]!="")?"":"dialog_hidden").'">
												<legend>ინფორმაცია</legend>
											<table class="dialog-form-table" style="width:250px; float:left;">
									    		<tr>
													<td style="text-align:right;">მოისმინა ბოლომდე</td>
													<td><input type="radio" name="info_quest" value="1" '.(($res['info_quest']=='1')?"checked":"").'></td>
													
												</tr>
												<tr>
													<td style="text-align:right;">მოისმინა და კითხვები დაგვისვა</td>
													<td><input type="radio" name="info_quest" value="2" '.(($res['info_quest']=='2')?"checked":"").'></td>		
												</tr>
												<tr>
													<td style="text-align:right;">შეგვაწყვეტინა</td>
													<td><input type="radio" name="info_quest" value="3" '.(($res['info_quest']=='3')?"checked":"").'></td>
												</tr>
											</table>
											<table class="dialog-form-table" style="width:350px; float:left; margin-left: 15px;">
												<tr>
													<td>კომენტარი</td>
												</tr>
									    		<tr>
													<td><textarea  style="width: 100%; height:50px; resize: none;" id="info_comment" class="idle" name="content" cols="300" >' . $res['info_comment'] . '</textarea></td>
												</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(2)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" onclick="seller(0)" class="back"> << </button>
									
									</fieldset>
													
									 </div>
									 <div id="seller-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset style="width:97%;" class="'.(($notes[4][id]!="")?"":"dialog_hidden").'">
										    	<legend>შედეგი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[4][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td style="text-align:right;"><span>დადებითი</span></td>
						  							<td><input type="radio" name="result_quest" value="1" '.(($res['result_quest']=='1')?"checked":"").'></td>
						  							<td><span>(ვაგრძელებთ)</span></td>
						  						</tr>
												<tr>
													<td style="text-align:right;"><span>უარყოფითი</span></td>
						  							<td><input type="radio" name="result_quest" value="2" '.(($res['result_quest']=='2')?"checked":"").'></td>
						  							<td><span>(ვასრულებთ)</span></td>
						  						</tr>
						  						<tr>
													<td style="text-align:right;"><span>მოიფიქრებს</span></td>
						  							<td><input type="radio" name="result_quest" value="3" '.(($res['result_quest']=='3')?"checked":"").'></td>
						  							<td><span>(ვუთანხმებთ განმეორებითი ზარის დროს. ვიფორმირებთ დავალებას)</span></td>
						  						</tr>	
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="result_comment" class="idle" name="content" cols="300" >' . $res['result_comment'] . '</textarea></td>
														
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:97%; float:left; " class="'.(($notes[5][id]!="")?"":"dialog_hidden").'">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[5][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    		<tr>
														<td style="width:150px;">მიწოდება დაიწყება</td>
														<td><select style="width: 305px;" id="send_date" class="idls object">'.Getshipping($res['send_date']).'</select></td>			
													</tr>
											</table>
											</fieldset>
											<fieldset style="width:97%; float:left; " class="'.(($notes[6][id]!="")?"":"dialog_hidden").'">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[6][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
						  							<td><input type="radio" name="payment_quest" value="1" '.(($res['payment_quest']=='1')?"checked":"").'></td>
						  							<td><span>ნაღდი</span></td>
						  						</tr>
												<tr>
						  							<td><input type="radio" name="payment_quest" value="2" '.(($res['payment_quest']=='2')?"checked":"").'></td>
						  							<td><span>უნაღდო</span></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 680px; height:60px; resize: none;" id="payment_comment" class="idle" name="content" cols="300" >' . $res['payment_comment'] . '</textarea></td>
													</tr>
											</table>
											</fieldset>
													
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
									
							</div>';
							// სატელეფონო გაყიდვები დასასრული

						// სატელეფონო კვლევა დასაწყისი
						$data .= '<div id="research" class="'.(($notes[7][id]!="")?"":"dialog_hidden").'">
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" '.(($notes[22][id]!="")?"style=\"display:none; !important;\"":"").'>დემოგრაფიული ბლოკი</li>
										<li id="r2" onclick="research(this.id)" '.(($notes[22][id]!="")?"style=\"display:none; !important;\"":"").'>ძირითადი ნაწილი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="'.(($notes[7][id]!="")?"":"dialog_hidden").'">
								    	<legend>შესავალი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >'.$notes[7][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:center;"><span>უარი მონაწილეობაზე</span></td>
					  							<td><input type="radio" name="preface_quest" value="1" '.(($res['preface_quest']=='1')?"checked":"").'></td>
					  							
					  						</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:300px;">
								    		<tr>
												<td style="font-weight:bold;">თქვენი სახელი, როგორ მოგმართოთ?</td>
					  						</tr>
											<tr>
												<td><input type="text" style="width:100%;" id="preface_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['preface_name']. '" /></td>
					  						</tr>
									</table>
									<hr>
																
									<div class="'.(($notes[22][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">B1</td>
												<td style="font-weight:bold;">რას ურჩევდით ბიბლუსს?</td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td><textarea  style="width: 500px; height:60px; resize: none;" id="biblus_quest1" class="idle" name="content" cols="300" >'. $res['biblus_quest1'].'</textarea></td>
												
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
											<tr>
												<td style="">მინიშნება</td>
											</tr>			
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >'.$notes[22][notes].'</textarea></td>
											</tr>
									</table>
									<hr>
									</div>
														
									<div class="'.(($notes[23][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">B2</td>
												<td style="font-weight:bold;">რამდენად კმაყოფილი ხართ ამ ფილიალში მიღებული მომსახურებით ?</td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td><input type="text" style="width:100%;" id="biblus_quest2" class="idle" onblur="this.className=\'idle\'"  value="' . $res['biblus_quest2']. '" /></td>
					  						
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
											<tr>
												<td style="">მინიშნება</td>
											</tr>
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >'.$notes[23][notes].'</textarea></td>
											</tr>
									</table>
									<hr>
									</div>
														
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="next"> >> </button>
									</fieldset>
									 </div>

											
									<div id="research-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>დემოგრაფიული ბლოკი</legend>
														<div class="'.(($notes[8][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D1</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, მუდმივად ცხოვრობთ თუ არა ამ მისამართზე?<br><span style="font-weight:normal;">(6 თვე მაინც უნდა ცხოვრებდეს)</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">დიახ</td>
												<td><input type="radio" name="d1" value="1" '.(($res['d1']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="d1" value="2" '.(($res['d1']=='2')?"checked":"").'></td>
												
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >'.$notes[8][notes].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[9][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D2</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, ხომ არ მიგიღიათ მონაწილეობა რაიმე კვლევაში ბოლო 6 თვის განმავლობაში?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">დიახ</td>
												<td><input type="radio" name="d2" value="1" '.(($res['d2']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="d2" value="2" '.(($res['d2']=='2')?"checked":"").'></td>
												
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >'.$notes[9][notes].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[10][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D3</td>
												<td style="font-weight:bold;">გთხოვთ დამიზუსტოთ, თბილისის რომელ რაიონში ცხოვრობთ?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px; text-align:right;">ვაკე-საბურთალო</td>
												<td><input type="radio" name="d3" value="1" '.(($res['d3']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">გლდანი-ნაძალადევი</td>
												<td><input type="radio" name="d3" value="2" '.(($res['d3']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">დიდუბე-ჩუღურეთი</td>
												<td><input type="radio" name="d3" value="3" '.(($res['d3']=='3')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ისანი-სამგორი</td>
												<td><input type="radio" name="d3" value="4" '.(($res['d3']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">ძვ.თბილისი</td>
												<td><input type="radio" name="d3" value="5" '.(($res['d3']=='5')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ვდიდგორი</td>
												<td><input type="radio" name="d3" value="6" '.(($res['d3']=='6')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[10][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
												</div>
															
									<div class="'.(($notes[11][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D4</td>
												<td style="font-weight:bold;">გთხოვთ მითხრათ, ხომ არ მუშაობთ თქვენ ან თქვენი ოჯახის წევრი, ახლობელი/მეგობარი </span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:250px;">ტელევიზია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="1" '.(($res['d4']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>რადიო (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="2" '.(($res['d4']=='2')?"checked":"").'></td>
											</tr>
											<tr>
												<td>პრესა, ბეჭდვითი მედია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="3" '.(($res['d4']=='3')?"checked":"").'></td>
											</tr>
											<tr>
												<td>სარეკლამო  (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="4" '.(($res['d4']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კვლევითი კომპანია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="5" '.(($res['d4']=='5')?"checked":"").'></td>
												
											</tr>
											<tr>
												<td>არცერთი (გააგრძელეთ)</td>
												<td><input type="radio" name="d4" value="6" '.(($res['d4']=='6')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[11][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
												</div>
																
									<div class="'.(($notes[12][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D5</td>
												<td style="font-weight:bold;">სქესი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">მამაკაცი</td>
												<td><input type="radio" name="d5" value="1" '.(($res['d5']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>ქალი</td>
												<td><input type="radio" name="d5" value="2" '.(($res['d5']=='2')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[12][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[13][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D6</td>
												<td style="font-weight:bold;">ასაკი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">12-17</td>
												<td><input type="radio" name="d6" value="1" '.(($res['d6']=='1')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">35-44</td>
												<td><input type="radio" name="d6" value="2" '.(($res['d6']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">18-24</td>
												<td><input type="radio" name="d6" value="3" '.(($res['d6']=='3')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">45-54</td>
												<td><input type="radio" name="d6" value="4" '.(($res['d6']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">25-34</td>
												<td><input type="radio" name="d6" value="5" '.(($res['d6']=='5')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">55-65</td>
												<td><input type="radio" name="d6" value="6" '.(($res['d6']=='6')?"checked":"").'></td>
												
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:50px; text-align:right;">65 +</td>
												<td><input type="radio" name="d6" value="7" '.(($res['d6']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[13][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
													</div>
															
									<div class="'.(($notes[14][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D7</td>
												<td style="font-weight:bold;">ჩამოთვლილთაგან რომელი გამოხატავს ყველაზე უკეთ თქვენი ოჯახის მატერიალურ მდგომარეობას?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px;">ძალიან დაბალი</td>
												<td><input type="radio" name="d7" value="1" '.(($res['d7']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>დაბალი</td>
												<td><input type="radio" name="d7" value="2" '.(($res['d7']=='2')?"checked":"").'></td>
											</tr>
											<tr>
												<td>საშუალო</td>
												<td><input type="radio" name="d7" value="3" '.(($res['d7']=='3')?"checked":"").'></td>
											</tr>
											<tr>
												<td>მაღალი</td>
												<td><input type="radio" name="d7" value="4" '.(($res['d7']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კძალიან მაღალი</td>
												<td><input type="radio" name="d7" value="5" '.(($res['d7']=='5')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[14][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[15][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D8</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რამდენ ლარს შეადგენს თქვენი ოჯახის ყოველთვიური შემოსავალი?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:90px; text-align:right;">200 ლარამდე</td>
												<td><input type="radio" name="d8" value="1" '.(($res['d8']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="d8" value="2" '.(($res['d8']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="d8" value="3" '.(($res['d8']=='3')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="d8" value="4" '.(($res['d8']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="d8" value="5" '.(($res['d8']=='5')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="d8" value="6" '.(($res['d8']=='6')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d8" value="7" '.(($res['d8']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[15][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[16][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D9</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რამდენ ლარს შეადგენს თქვენი პირადი ყოველთვიური შემოსავალი?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:90px; text-align:right;">200 ლარამდე</td>
												<td><input type="radio" name="d9" value="1" '.(($res['d9']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="d9" value="2" '.(($res['d9']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="d9" value="3" '.(($res['d9']=='3')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="d9" value="4" '.(($res['d9']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="d9" value="5" '.(($res['d9']=='5')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="d9" value="6" '.(($res['d9']=='6')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d9" value="7" '.(($res['d9']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[16][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[17][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D10</td>
												<td style="font-weight:bold;">ხართ თუ არა დასაქმებული?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">დიახ</td>
												<td><input type="radio" name="d10" value="1" '.(($res['d10']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="d10" value="2" '.(($res['d10']=='2')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[17][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[18][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D11</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რა ტიპის ორგანიზაციაში მუშაობთ?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px; text-align:right;">კერძო სექტორი</td>
												<td><input type="radio" name="d11" value="1" '.(($res['d11']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">თვითდასაქმებული</td>
												<td><input type="radio" name="d11" value="2" '.(($res['d11']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">საჯარო სამსახური</td>
												<td><input type="radio" name="d11" value="3" '.(($res['d11']=='3')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d11" value="4" '.(($res['d11']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">არასამთავრობო/td>
												<td><input type="radio" name="d11" value="5" '.(($res['d11']=='5')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[18][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
													</div>
															
									<div class="'.(($notes[19][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D12</td>
												<td style="font-weight:bold;">გყავთ თუ არა ავტომობილი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">დიახ</td>
												<td><input type="radio" name="d12" value="1" '.(($res['d12']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="d12" value="2" '.(($res['d12']=='2')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" disabled name="content" cols="300" >' . $notes[19][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
										<button style="float:right; margin-top:10px;" onclick="research(\'r2\')" class="next"> >> </button>
										<button style="float:right; margin-top:10px;" onclick="research(\'r0\')" class="back"> << </button>
									</fieldset>			
									</div>
														
									 <div id="research-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset '.(($notes[21][id]!="")?"":"dialog_hidden").'>
										    	<legend>რადიო</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q1</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით რადიოსადგურებს და თქვენ მიპასუხეთ, რომელ რადიოს უსმენდით გუშინ, თუნდაც მხოლოდ 5 წუთით? კიდევ, კიდევ.</td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td><span>რადიო 1</span></td>
						  							<td><input type="radio" name="q1" value="1" '.(($res['q1']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>არ ვუსმენდი</span></td>
						  							<td><input type="radio" name="q1" value="2" '.(($res['q1']=='2')?"checked":"").'></td>
						  						</tr>
												<tr>
													<td><span>რადიო 2</span></td>
						  							<td><input type="radio" name="q1" value="3" '.(($res['q1']=='3')?"checked":"").'></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
														
													</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="back"> << </button>
											
										</fieldset>
									 </div>
									
							</div>';
						
						$data .= '</div>';
						// სატელეფონო კვლევა დასასრული
						
						  $data .= '<fieldset style="width:350px;; float:left;">
								    	<legend>ზარის დაზუსტება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 350px; height:70px; resize: none;" id="call_content" class="idle" name="content" cols="300" >' . $res['call_content'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>	
									<fieldset style="width:300px;; float:left; margin-left:10px; max-height:90px;">
								    	<legend>სტატუსი</legend>
									<table class="dialog-form-table" style="height: 80px;">
											<tr>
												<td></td>
											</tr>
								    		<tr>
												<td><select style="width: 330px;" id="status" class="idls object">'.Getstatus($res['status']).'</select></td>
											</tr>
									</table>
									</fieldset>
								<fieldset style="margin-top: 5px;">
								    	<legend>დავალების ფორმირება</legend>
							
								    	<table class="dialog-form-table" >
											<tr>
												<td style="width: 280px;"><label for="set_task_department_id">განყოფილება</label></td>
												<td style="width: 280px;"><label for="set_persons_id">პასუხისმგებელი პირი</label></td>
												<td style="width: 280px;"><label for="set_priority_id">პრიორიტეტი</label></td>
											</tr>
								    		<tr>
												<td><select style="width: 200px;"  id="set_task_department_id" class="idls object">'.Getdepartment($res['task_department_id']).'</select></td>
												<td><select style="width: 200px;" id="set_persons_id" class="idls object">'. Getpersons($res['persons_id']).'</select></td>
												<td><select style="width: 200px;" id="set_priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label>შესრულების პერიოდი</label></td>
												<td style="width: 150px;"><label></label></td>
												<td style="width: 150px;"><label>კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 130px; float:left;" id="set_start_time" class="idle" type="text"><span style="margin-left:5px; ">დან</span></td>
										  		<td><input style="width: 130px; float:left;" id="set_done_time" class="idle" type="text"><span style="margin-left:5px; ">მდე</span></td>
												<td>
													<textarea  style="width: 270px; resize: none;" id="set_body" class="idle" name="content" cols="300">' . $res['comment'] . '</textarea>
												</td>
											</tr>
										</table>
							        </fieldset>	
							</fieldset>	
							</div>';
						
						
						$data .='<div style="float: right;  width: 355px;">
								<fieldset>
								<legend>აბონენტი</legend>
								<table style="height: 243px;">						
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="phone"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_n'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td><input type="text" id="city_id"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['city_id'] . '" /></td>	
										<td td style="width: 180px;">
											<input type="text" id="b_day"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['b_day'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
										
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="addres"  class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['addres'] . '" />		
										</td>
									</tr>
									
								</table>
							</fieldset>';
							$data .= GetRecordingsSection($res='');	
						$data .=	'</div>
				    </div>';
	
	
	$data .= '<input type="hidden" id="outgoing_call_id" value="' . $res['id'] . '" />';

	return $data;
}

function GetRecordingsSection($res)
{
	$db2 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("SELECT  TIME(`calldate`) AS 'time',
			`userfield`
			FROM     `cdr`
			WHERE     (`dst` = 2470017 && `userfield` != '' && DATE(`calldate`) = '$res[date]' && `src` LIKE '%$res[phone]%')
			OR      (`dst` LIKE '%$res[phone]%' && `userfield` != '' && DATE(`calldate`) = '$res[date]');");

	$data .= '
        <fieldset style="margin-top: 10px; width: 333px; float: right;">
            <legend>ჩანაწერები</legend>

            <table style="width: 65%; border: solid 1px #85b1de; margin:auto;">
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <th style="padding-left: 10px;">დრო</th>
                    <th  style="border: solid 1px #85b1de; padding-left: 10px;">ჩანაწერი</th>
                </tr>';
	if (mysql_num_rows($req) == 0){
		$data .= '<td colspan="2" style="height: 20px; text-align: center;">ჩანაწერები ვერ მოიძებნა</td>';
	}

	while( $res2 = mysql_fetch_assoc($req)){
		$src = $res2['userfield'];
		$link = explode("/", $src);
		$data .= '
                <tr style="border-bottom: solid 1px #85b1de; height: 20px;">
                    <td>' . $res2['time'] . '</td>
                    <td><button class="download" str="' . $link[5] . '">მოსმენა</button></td>
                </tr>';
	}

	$data .= '
            </table>
        </fieldset>';

	return $data;
}

function Getquest($shabloni){
		$test = Getshabl($res['template_id']);
						
		for($key=1;$key<23;$key++){
		
		$rows1 = mysql_query("	SELECT 	quest_id,
										notes,
										qvota
								FROM 	shabloni
								WHERE 	`name`='$test' and quest_id='$key'");
		$row = mysql_fetch_assoc($rows1);
			
				$notes[] = array('id' => $row[quest_id],'notes' => $row[notes], 'qvota' => $row[qvota]);
			
		}
	
	$data .='<div id="seller" class="'.(($notes[0][id]!="")?"":"dialog_hidden").'" >
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0" >
									<fieldset style="width:97%; border: 4px solid #CDCDCD; float:left; overflow-y:scroll; max-height:400px;" class="'.(($notes[0][id]!="")?"":"dialog_hidden").'">
									<fieldset style="width:97%;" >
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[0][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:right;"><span>აქვს</span></td>
					  							<td><input type="radio" name="hello_quest" value="1" '.(($res['hello_quest']=='1')?"checked":"").'></td>
					  							<td><span>(ვაგრძელებთ)</span></td>
					  						</tr>
											<tr>
												<td style="text-align:right;"><span>სურს სხვა დროს</span></td>
					  							<td><input type="radio" name="hello_quest" value="2" '.(($res['hello_quest']=='2')?"checked":"").'></td>
					  							<td><span>(ვიფორმირებთ დავალებას)</span></td>
					  						</tr>
					  						<tr>
												<td style="text-align:right;"><span>არ სურს</span></td>
					  							<td><input type="radio" name="hello_quest" value="3" '.(($res['hello_quest']=='3')?"checked":"").'></td>
					  							<td><span>(ვასრულებთ)</span></td>
					  						</tr>
									</table>
					  				<fieldset style="width:97%; float:left; ">
								    	<legend>კომენტარი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="hello_comment" class="idle" name="content" cols="300" >' . $res['hello_comment'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
											
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="'.(($notes[1][id]!="")?"":"dialog_hidden").'">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[1][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;" class="'.(($notes[2][id]!="")?"":"dialog_hidden").'">
								    	<legend>პროდუქტი</legend>
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_product">დამატება</button>
							        			</div>
								                <table class="" id="sub1" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
																
								                           <th style="display:none">ID</th>
															<th style="width:4%;">#</th>
															<th style="">პაკეტი</th>
															<th style="">ფასი</th>
															<th style="">აღწერილობა</th>
															<th style="">შენიშვნა</th>
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
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
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
										<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[2][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; " class="'.(($notes[3][id]!="")?"":"dialog_hidden").'">
								    	<legend>საჩუქარი</legend>														
									<div id="dt_example" class="inner-table">
								        <div style="width:100%;" id="container" >        	
								            <div id="dynamic">
								            	<div id="button_area">
								            		<button id="add_button_gift">დამატება</button>
							        			</div>
								                <table class="" id="sub2" style="width: 100%;">
								                    <thead>
														<tr  id="datatable_header">
																
								                           <th style="display:none">ID</th>
															<th style="width:4%;">#</th>
															<th style="">პაკეტი</th>
															<th style="">ფასი</th>
															<th style="">აღწერილობა</th>
															<th style="">შენიშვნა</th>
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
																<input style="width:100px;" type="text" name="search_overhead" value="ფილტრი" class="search_init" />
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
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[3][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset class="'.(($notes[20][id]!="")?"":"dialog_hidden").'">
												<legend>ინფორმაცია</legend>
											<table class="dialog-form-table" style="width:250px; float:left;">
									    		<tr>
													<td style="text-align:right;">მოისმინა ბოლომდე</td>
													<td><input type="radio" name="info_quest" value="1" '.(($res['info_quest']=='1')?"checked":"").'></td>
													
												</tr>
												<tr>
													<td style="text-align:right;">მოისმინა და კითხვები დაგვისვა</td>
													<td><input type="radio" name="info_quest" value="2" '.(($res['info_quest']=='2')?"checked":"").'></td>		
												</tr>
												<tr>
													<td style="text-align:right;">შეგვაწყვეტინა</td>
													<td><input type="radio" name="info_quest" value="3" '.(($res['info_quest']=='3')?"checked":"").'></td>
												</tr>
											</table>
											<table class="dialog-form-table" style="width:350px; float:left; margin-left: 15px;">
												<tr>
													<td>კომენტარი</td>
												</tr>
									    		<tr>
													<td><textarea  style="width: 100%; height:50px; resize: none;" id="info_comment" class="idle" name="content" cols="300" >' . $res['info_comment'] . '</textarea></td>
												</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(2)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" onclick="seller(0)" class="back"> << </button>
									
									</fieldset>
													
									 </div>
									 <div id="seller-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset style="width:97%;" class="'.(($notes[4][id]!="")?"":"dialog_hidden").'">
										    	<legend>შედეგი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[4][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td style="text-align:right;"><span>დადებითი</span></td>
						  							<td><input type="radio" name="result_quest" value="1" '.(($res['result_quest']=='1')?"checked":"").'></td>
						  							<td><span>(ვაგრძელებთ)</span></td>
						  						</tr>
												<tr>
													<td style="text-align:right;"><span>უარყოფითი</span></td>
						  							<td><input type="radio" name="result_quest" value="2" '.(($res['result_quest']=='2')?"checked":"").'></td>
						  							<td><span>(ვასრულებთ)</span></td>
						  						</tr>
						  						<tr>
													<td style="text-align:right;"><span>მოიფიქრებს</span></td>
						  							<td><input type="radio" name="result_quest" value="3" '.(($res['result_quest']=='3')?"checked":"").'></td>
						  							<td><span>(ვუთანხმებთ განმეორებითი ზარის დროს. ვიფორმირებთ დავალებას)</span></td>
						  						</tr>	
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['result_comment'] . '</textarea></td>
														
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:97%; float:left; " class="'.(($notes[5][id]!="")?"":"dialog_hidden").'">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[5][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    		<tr>
														<td style="width:150px;">მიწოდება დაიწყება</td>
														<td>
															<input type="text" id="send_date" class="idle" onblur="this.className=\'idle\'"  value="' .  $res['send_date']. '" />
														</td>
														<td> -დან</td>
													</tr>
											</table>
											</fieldset>
											<fieldset style="width:97%; float:left; " class="'.(($notes[6][id]!="")?"":"dialog_hidden").'">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[6][notes].'</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span></span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
						  							<td><input type="radio" name="payment_quest" value="1" '.(($res['payment_quest']=='1')?"checked":"").'></td>
						  							<td><span>ნაღდი</span></td>
						  						</tr>
												<tr>
						  							<td><input type="radio" name="payment_quest" value="2" '.(($res['payment_quest']=='2')?"checked":"").'></td>
						  							<td><span>უნაღდო</span></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['payment_comment'] . '</textarea></td>
													</tr>
											</table>
											</fieldset>
													
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
									
							</div>';
							// სატელეფონო გაყიდვები დასასრული

						// სატელეფონო კვლევა დასაწყისი
						$data .= '<div id="research" class="'.(($notes[7][id]!="")?"":"dialog_hidden").'">
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" class="">დემოგრაფიული ბლოკი</li>
										<li id="r2" onclick="research(this.id)" class="">ძირითადი ნაწილი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="'.(($notes[7][id]!="")?"":"dialog_hidden").'">
								    	<legend>შესავალი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[7][notes].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span></span></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:center;"><span>უარი მონაწილეობაზე</span></td>
					  							<td><input type="radio" name="preface_quest" value="1" '.(($res['preface_quest']=='1')?"checked":"").'></td>
					  							
					  						</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:300px;">
								    		<tr>
												<td style="font-weight:bold;">თქვენი სახელი, როგორ მოგმართოთ?</td>
					  						</tr>
											<tr>
												<td><input type="text" style="width:100%;" id="preface_name" class="idle" onblur="this.className=\'idle\'"  value="' . $res['preface_name']. '" /></td>
					  						</tr>
									</table>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="next"> >> </button>
									</fieldset>
									 </div>

											
									<div id="research-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>დემოგრაფიული ბლოკი</legend>
														<div class="'.(($notes[8][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D1</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, მუდმივად ცხოვრობთ თუ არა ამ მისამართზე?<br><span style="font-weight:normal;">(6 თვე მაინც უნდა ცხოვრებდეს)</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">დიახ</td>
												<td><input type="radio" name="d1" value="1" '.(($res['d1']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="d1" value="2" '.(($res['d1']=='2')?"checked":"").'></td>
												
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[8][notes].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[9][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D2</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, ხომ არ მიგიღიათ მონაწილეობა რაიმე კვლევაში ბოლო 6 თვის განმავლობაში?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">დიახ</td>
												<td><input type="radio" name="d2" value="1" '.(($res['d2']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="d2" value="2" '.(($res['d2']=='2')?"checked":"").'></td>
												
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[9][notes].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[10][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D3</td>
												<td style="font-weight:bold;">გთხოვთ დამიზუსტოთ, თბილისის რომელ რაიონში ცხოვრობთ?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px; text-align:right;">ვაკე-საბურთალო</td>
												<td><input type="radio" name="d3" value="1" '.(($res['d3']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">გლდანი-ნაძალადევი</td>
												<td><input type="radio" name="d3" value="2" '.(($res['d3']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">დიდუბე-ჩუღურეთი</td>
												<td><input type="radio" name="d3" value="3" '.(($res['d3']=='3')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ისანი-სამგორი</td>
												<td><input type="radio" name="d3" value="4" '.(($res['d3']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">ძვ.თბილისი</td>
												<td><input type="radio" name="d3" value="5" '.(($res['d3']=='5')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ვდიდგორი</td>
												<td><input type="radio" name="d3" value="6" '.(($res['d3']=='6')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[10][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
												</div>
															
									<div class="'.(($notes[11][id]!="")?"":"dialog_hidden").'">
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D4</td>
												<td style="font-weight:bold;">გთხოვთ მითხრათ, ხომ არ მუშაობთ თქვენ ან თქვენი ოჯახის წევრი, ახლობელი/მეგობარი </span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:250px;">ტელევიზია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="1" '.(($res['d4']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>რადიო (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="2" '.(($res['d4']=='2')?"checked":"").'></td>
											</tr>
											<tr>
												<td>პრესა, ბეჭდვითი მედია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="3" '.(($res['d4']=='3')?"checked":"").'></td>
											</tr>
											<tr>
												<td>სარეკლამო  (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="4" '.(($res['d4']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კვლევითი კომპანია (დაასრულეთ)</td>
												<td><input type="radio" name="d4" value="5" '.(($res['d4']=='5')?"checked":"").'></td>
												
											</tr>
											<tr>
												<td>არცერთი (გააგრძელეთ)</td>
												<td><input type="radio" name="d4" value="6" '.(($res['d4']=='6')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[11][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
												</div>
																
									<div class="'.(($notes[12][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D5</td>
												<td style="font-weight:bold;">სქესი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px;">მამაკაცი</td>
												<td><input type="radio" name="d5" value="1" '.(($res['d5']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>ქალი</td>
												<td><input type="radio" name="d5" value="2" '.(($res['d5']=='2')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[12][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[13][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D6</td>
												<td style="font-weight:bold;">ასაკი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">12-17</td>
												<td><input type="radio" name="d6" value="1" '.(($res['d6']=='1')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">35-44</td>
												<td><input type="radio" name="d6" value="2" '.(($res['d6']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">18-24</td>
												<td><input type="radio" name="d6" value="3" '.(($res['d6']=='3')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">45-54</td>
												<td><input type="radio" name="d6" value="4" '.(($res['d6']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">25-34</td>
												<td><input type="radio" name="d6" value="5" '.(($res['d6']=='5')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">55-65</td>
												<td><input type="radio" name="d6" value="6" '.(($res['d6']=='6')?"checked":"").'></td>
												
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:50px; text-align:right;">65 +</td>
												<td><input type="radio" name="d6" value="7" '.(($res['d6']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[13][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
													</div>
															
									<div class="'.(($notes[14][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D7</td>
												<td style="font-weight:bold;">ჩამოთვლილთაგან რომელი გამოხატავს ყველაზე უკეთ თქვენი ოჯახის მატერიალურ მდგომარეობას?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px;">ძალიან დაბალი</td>
												<td><input type="radio" name="d7" value="1" '.(($res['d7']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>დაბალი</td>
												<td><input type="radio" name="d7" value="2" '.(($res['d7']=='2')?"checked":"").'></td>
											</tr>
											<tr>
												<td>საშუალო</td>
												<td><input type="radio" name="d7" value="3" '.(($res['d7']=='3')?"checked":"").'></td>
											</tr>
											<tr>
												<td>მაღალი</td>
												<td><input type="radio" name="d7" value="4" '.(($res['d7']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კძალიან მაღალი</td>
												<td><input type="radio" name="d7" value="5" '.(($res['d7']=='5')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[14][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[15][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D8</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რამდენ ლარს შეადგენს თქვენი ოჯახის ყოველთვიური შემოსავალი?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:90px; text-align:right;">200 ლარამდე</td>
												<td><input type="radio" name="d8" value="1" '.(($res['d8']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="d8" value="2" '.(($res['d8']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="d8" value="3" '.(($res['d8']=='3')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="d8" value="4" '.(($res['d8']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="d8" value="5" '.(($res['d8']=='5')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="d8" value="6" '.(($res['d8']=='6')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d8" value="7" '.(($res['d8']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[15][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[16][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D9</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რამდენ ლარს შეადგენს თქვენი პირადი ყოველთვიური შემოსავალი?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:90px; text-align:right;">200 ლარამდე</td>
												<td><input type="radio" name="d9" value="1" '.(($res['d9']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="d9" value="2" '.(($res['d9']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="d9" value="3" '.(($res['d9']=='3')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="d9" value="4" '.(($res['d9']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="d9" value="5" '.(($res['d9']=='5')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="d9" value="6" '.(($res['d9']=='6')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d9" value="7" '.(($res['d9']=='7')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[16][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[17][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D10</td>
												<td style="font-weight:bold;">ხართ თუ არა დასაქმებული?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">დიახ</td>
												<td><input type="radio" name="d10" value="1" '.(($res['d10']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="d10" value="2" '.(($res['d10']=='2')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[17][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="'.(($notes[18][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D11</td>
												<td style="font-weight:bold;">თუ შეიძლება მითხარით, რა ტიპის ორგანიზაციაში მუშაობთ?</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:150px; text-align:right;">კერძო სექტორი</td>
												<td><input type="radio" name="d11" value="1" '.(($res['d11']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">თვითდასაქმებული</td>
												<td><input type="radio" name="d11" value="2" '.(($res['d11']=='2')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">საჯარო სამსახური</td>
												<td><input type="radio" name="d11" value="3" '.(($res['d11']=='3')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">მპგ</td>
												<td><input type="radio" name="d11" value="4" '.(($res['d11']=='4')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">არასამთავრობო/td>
												<td><input type="radio" name="d11" value="5" '.(($res['d11']=='5')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[18][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
													</div>
															
									<div class="'.(($notes[19][id]!="")?"":"dialog_hidden").'">					
									<table class="dialog-form-table">
								    		<tr>
												<td style="width:30px; font-weight:bold;">D12</td>
												<td style="font-weight:bold;">გყავთ თუ არა ავტომობილი</span></td>
												<td></td>
											</tr>
									</table>
									<table class="dialog-form-table">
											<tr>
												<td style="width:50px; text-align:right;">დიახ</td>
												<td><input type="radio" name="d12" value="1" '.(($res['d12']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="d12" value="2" '.(($res['d12']=='2')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $notes[19][notes] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;"></td>
											</tr>
									</table>
									<hr>
														</div>
										<button style="float:right; margin-top:10px;" onclick="research(\'r2\')" class="next"> >> </button>
										<button style="float:right; margin-top:10px;" onclick="research(\'r0\')" class="back"> << </button>
									</fieldset>			
									</div>
														
									 <div id="research-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset '.(($notes[21][id]!="")?"":"dialog_hidden").'>
										    	<legend>რადიო</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q1</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით რადიოსადგურებს და თქვენ მიპასუხეთ, რომელ რადიოს უსმენდით გუშინ, თუნდაც მხოლოდ 5 წუთით? კიდევ, კიდევ.</td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td><span>რადიო 1</span></td>
						  							<td><input type="radio" name="q1" value="1" '.(($res['q1']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>არ ვუსმენდი</span></td>
						  							<td><input type="radio" name="q1" value="2" '.(($res['q1']=='2')?"checked":"").'></td>
						  						</tr>
												<tr>
													<td><span>რადიო 2</span></td>
						  							<td><input type="radio" name="q1" value="3" '.(($res['q1']=='3')?"checked":"").'></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
														
													</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="back"> << </button>
											
										</fieldset>
									 </div>
									
							</div>';
	
	return $data;
}
function ChangeResponsiblePerson($letters, $responsible_person){
	$o_date		= date('Y-m-d H:i:s');
	foreach($letters as $letter) {

		mysql_query("UPDATE 	task_detail
					JOIN 		task ON task_detail.task_id = task.id
					SET    		task_detail.`status`   			 = 1,
								task.`date` 			     = '$o_date',
								task_detail.responsible_user_id     = '$responsible_person'
					WHERE  		task_detail.id = '$letter' AND task.id = task_detail.task_id");
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