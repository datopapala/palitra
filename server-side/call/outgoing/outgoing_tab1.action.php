<?php
/* ******************************
 *	Request aJax actions
 * ******************************
*/

require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];
$error	= '';
$data	= '';

$task_id				= $_REQUEST['id'];
$shabloni				= $_REQUEST['shabloni'];
$call_date				= $_REQUEST['call_date'];
$phone					= $_REQUEST['phone'];
$problem_comment 		= $_REQUEST['problem_comment'];
$call_duration 			= $_REQUEST['call_duration'];
$template_id			= $_REQUEST['template_id'];
$priority_id			= $_REQUEST['priority_id'];
$comment 	        	= $_REQUEST['comment'];

$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage('','',$shabloni);
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
								
								WHERE 		task.task_type_id=1 AND task.`status`=1");
	    
										    		
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
    case 'save_outgoing':
	
		$user_id		= $_SESSION['USERID'];
		
		Savetask($task_id, $problem_comment, $file, $rand_file);
        break;
        case 'done_outgoing':
        
        	$user_id		= $_SESSION['USERID'];
        
        	Savetask1($task_id, $problem_comment, $file, $rand_file);
        	break;
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	task Functions
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



function Savetask($task_id, $problem_comment, $file, $rand_file)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET  
								`user_id`			='$user',
								`problem_comment`	='$problem_comment', 
								`status`	='2', 
								`actived`	='1'
								 WHERE `id`			='$task_id'
									");

}
function Savetask1($task_id, $problem_comment, $file, $rand_file)
{
	$c_date		= date('Y-m-d H:i:s');
	$user  = $_SESSION['USERID'];
	mysql_query("UPDATE `task` SET
								`user_id`			='$user',
								`problem_comment`	='$problem_comment',
								`status`	='3'
				WHERE 			`id`				='$task_id'
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

	if($res['id'] == $status){
	$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['call_status'] . '</option>';
} else {
			$data .= '<option value="' . $res['id'] . '">' . $res['call_status'] . '</option>';
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
							WHERE actived=1 ");

	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $task_type_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
		$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
}


function Getpersons($persons_id)
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

function getCalls(){
		$db1 = new sql_db ( "212.72.155.176", "root", "Gl-1114", "asteriskcdrdb" );

	$req = mysql_query("

	SELECT  	DISTINCT
	IF(SUBSTR(cdr.src, 1, 3) = 995, SUBSTR(cdr.src, 4, 9), cdr.src) AS `src`
						FROM    	cdr
						GROUP BY 	cdr.src
						ORDER BY 	cdr.calldate DESC
						LIMIT 		12


						");

	$data = '<tr class="trClass">
					<th class="thClass">#</th>
					<th class="thClass">ნომერი</th>
					<th class="thClass">ქმედება</th>
	</tr>
	';
	$i	= 1;
	while( $res3 = mysql_fetch_assoc($req)){

		$data .= '
	    		<tr class="trClass">
					<td class="tdClass">' . $i . '</td>
			<td class="tdClass" style="width: 30px !important;">' . $res3['src'] . '</td>
					<td class="tdClass" style="font-size: 13px !important;"><button class="insert" number="' . $res3['src'] . '">დამატება</button></td>
					</tr>';
		$i++;
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

function Getshablon($department_id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shabloni
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


function GetPage($res='', $number,$shabloni)
{
	$num = 0;
	if($res[phone]==""){
		$num=$number;
	}else{ 
		$num=$res[phone]; 
	}

	$pattern = mysql_query("SELECT content FROM pattern_param");
	while ($pattern_param = mysql_fetch_assoc($pattern)){
		$pattern_param_arr[] .= $pattern_param[content]; 
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
												<input type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '" disabled="disabled" />
											</td>
											<td>
												<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'"  value="' .  $res['call_date']. '" disabled="disabled" />
											</td>		
										</tr>
									</table><br>
								
														
								<fieldset style="width:250px; float:left;">
							    	<legend>დავალების ტიპი</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 305px;" id="task_type_id_seller" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
								<fieldset style="width:340px; float:left; margin-left:10px;">
							    	<legend>სცენარის დასახელება</legend>
								<table class="dialog-form-table">
							    		<tr>
											<td><select style="width: 380px;" id="shabloni" class="idls object">'.Getshablon($res['task_type_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
						        ';
						
						// სატელეფონო გაყიდვები დასაწყისი
						$data .= '
							<div id="seller" >
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[0] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:right;"><span>აქვს</span></td>
					  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
					  							<td><span>(ვაგრძელებთ)</span></td>
					  						</tr>
											<tr>
												<td style="text-align:right;"><span>სურს სხვა დროს</span></td>
					  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
					  							<td><span>(ვიფორმირებთ დავალებას)</span></td>
					  						</tr>
					  						<tr>
												<td style="text-align:right;"><span>არ სურს</span></td>
					  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
					  							<td><span>(ვასრულებთ)</span></td>
					  						</tr>
									</table>
					  				<fieldset style="width:97%; float:left; ">
								    	<legend>კომენტარი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[1] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;">
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
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[2] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; ">
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[3] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset >
												<legend>ინფორმაცია</legend>
											<table class="dialog-form-table" style="width:250px; float:left;">
									    		<tr>
													<td style="text-align:right;">მოისმინა ბოლომდე</td>
													<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
													
												</tr>
												<tr>
													<td style="text-align:right;">მოისმინა და კითხვები დაგვისვა</td>
													<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>		
												</tr>
												<tr>
													<td style="text-align:right;">შეგვაწყვეტინა</td>
													<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
												</tr>
											</table>
											<table class="dialog-form-table" style="width:350px; float:left; margin-left: 15px;">
												<tr>
													<td>კომენტარი</td>
												</tr>
									    		<tr>
													<td><textarea  style="width: 100%; height:50px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(2)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" onclick="seller(0)" class="back"> << </button>
									
									</fieldset>
													
									 </div>
									 <div id="seller-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset style="width:97%;">
										    	<legend>შედეგი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[4] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td style="text-align:right;"><span>დადებითი</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td><span>(ვაგრძელებთ)</span></td>
						  						</tr>
												<tr>
													<td style="text-align:right;"><span>უარყოფითი</span></td>
						  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
						  							<td><span>(ვასრულებთ)</span></td>
						  						</tr>
						  						<tr>
													<td style="text-align:right;"><span>მოიფიქრებს</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  							<td><span>(ვუთანხმებთ განმეორებითი ზარის დროს. ვიფორმირებთ დავალებას)</span></td>
						  						</tr>	
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
														<td style="width:250px;text-align:right;"><button id="complete">დაასრულეთ</button></td>
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:97%; float:left; ">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[5] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    		<tr>
														<td style="width:150px;">მიწოდება დაიწყება</td>
														<td>
															<input type="text" id="send_time" class="idle" onblur="this.className=\'idle\'"  value="' .  $res['call_date']. '" />
														</td>
														<td> -დან</td>
													</tr>
											</table>
											</fieldset>
											<fieldset style="width:97%; float:left; ">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[6] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td><span>ნაღდი</span></td>
						  						</tr>
												<tr>
						  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
						  							<td><span>უნაღდო</span></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    		<tr>
						  								<td><span style="color:#649CC3">კომენტარი</span></td>
													</tr>
													<tr>
														<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
											</table>
											</fieldset>
													<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
									
							</div>';
							// სატელეფონო გაყიდვები დასასრული

						// სატელეფონო კვლევა დასაწყისი
						$data .= '<div id="research" class="dialog_hidden">
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" class="">დემოგრაფიული ბლოკი</li>
										<li id="r2" onclick="research(this.id)" class="">ძირითადი ნაწილი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>შესავალი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[7] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="width:500px;">
								    		<tr>
												<td style="text-align:center;"><span>უარი მონაწილეობაზე</span></td>
					  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
					  							<td><button class="done">დასრულება</button></td>
					  						</tr>
									</table>
									</fieldset>
									<table class="dialog-form-table" style="width:300px;">
								    		<tr>
												<td style="font-weight:bold;">თქვენი სახელი, როგორ მოგმართოთ?</td>
					  						</tr>
											<tr>
												<td><input type="text" style="width:100%;" id="" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '" /></td>
					  						</tr>
									</table>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="next"> >> </button>
									</fieldset>
									 </div>

											
									<div id="research-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>დემოგრაფიული ბლოკი</legend>
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[8] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
									
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>არა</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[9] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
									
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">გლდანი-ნაძალადევი</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">დიდუბე-ჩუღურეთი</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ისანი-სამგორი</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">ძვ.თბილისი</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">ვდიდგორი</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[10] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
									
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>რადიო (დაასრულეთ)</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>პრესა, ბეჭდვითი მედია (დაასრულეთ)</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>სარეკლამო  (დაასრულეთ)</td>
												<td><input type="radio" name="xx" value="4" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კვლევითი კომპანია (დაასრულეთ)</td>
												<td><input type="radio" name="xx" value="5" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
											</tr>
											<tr>
												<td>არცერთი (გააგრძელეთ)</td>
												<td><input type="radio" name="xx" value="6" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[11] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>ქალი</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[12] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">35-44</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">18-24</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">45-54</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">25-34</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:50px; text-align:right;">55-65</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:50px; text-align:right;">65 +</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[13] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td>დაბალი</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>საშუალო</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>მაღალი</td>
												<td><input type="radio" name="xx" value="4" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td>კძალიან მაღალი</td>
												<td><input type="radio" name="xx" value="5" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[14] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[15] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">100-1500</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">200-500</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">1500-2000</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:80px; text-align:right;">500-1000</td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:80px; text-align:right;">2000+</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td style="width:80px; text-align:right;">მპგ</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[16] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[17] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">თვითდასაქმებული</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">საჯარო სამსახური</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">მპგ</td>
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											<tr>
												<td style="width:150px; text-align:right;">არასამთავრობო/td>
												<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[18] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														
														
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
												<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
												<td style="width:150px; text-align:right;">დაიცავით ქვოტა</td>
											</tr>
											<tr>
												<td style="width:50px; text-align:right;">არა</td>
												<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='1')?"checked":"").'></td>
											</tr>
											
									</table>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $pattern_param_arr[19] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
										<button style="float:right; margin-top:10px;" onclick="research(\'r2\')" class="next"> >> </button>
										<button style="float:right; margin-top:10px;" onclick="research(\'r0\')" class="back"> << </button>
									</fieldset>			
									</div>
														
									 <div id="research-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset>
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
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>არ ვუსმენდი</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  						</tr>
												<tr>
													<td><span>რადიო 2</span></td>
						  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
						  						</tr>
											</table>
						  					<table class="dialog-form-table">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="back"> << </button>
											
										</fieldset>
									 </div>
									
							</div>';
						// სატელეფონო კვლევა დასასრული
						
						  $data .= '<fieldset style="width:350px;; float:left;">
								    	<legend>ზარის დაზუსტება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 350px; height:70px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
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
												<td><select style="width: 330px;" id="" class="idls object">'.$res['task_type_id'].'</select></td>
											</tr>
									</table>
									</fieldset>
								<fieldset style="margin-top: 5px;">
								    	<legend>დავალების ფორმირება</legend>
							
								    	<table class="dialog-form-table" >
											<tr>
												<td style="width: 180px;"><label for="task_type_id">დავალების ტიპი</label></td>
												<td style="width: 180px;"><label for="task_department_id">განყოფილება</label></td>
												<td style="width: 180px;"><label for="persons_id">პასუხისმგებელი პირი</label></td>
												<td style="width: 180px;"><label for="priority_id">პრიორიტეტი</label></td>
											</tr>
								    		<tr>
												<td><select style="width: 180px;" id="task_type_id" class="idls object">'.Gettask_type($res['task_type_id']).'</select></td>
												<td><select style="width: 180px;" id="task_department_id" class="idls object">'.Getdepartment($res['task_department_id']).'</select></td>
												<td><select style="width: 180px;" id="persons_id" class="idls object">'. Getpersons($res['persons_id']).'</select></td>
												<td><select style="width: 180px;" id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 700px;">
											<tr>
												<td style="width: 150px;"><label>შესრულების პერიოდი</label></td>
												<td style="width: 150px;"><label></label></td>
												<td style="width: 150px;"><label>კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">დან</span></td>
										  		<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">მდე</span></td>
												<td>
													<textarea  style="width: 250px; resize: none;" id="comment" class="idle" name="content" cols="300">' . $res['comment'] . '</textarea>
												</td>
											</tr>
										</table>
							        </fieldset>	
							</fieldset>	
							</div>';
						
						
						$data .='<div style="float: right;  width: 355px;">
								  <fieldset>
									<legend>სასარგებლო ბმულები</legend>
									<table>
										<tr>
											<td style="width:90px; height:60px;"><a id="link1" target="_blank" href="http://www.biblusi.ge/"></a></td>
											<td style="width:90px; height:60px;"><a id="link2" target="_blank" href="http://www.palitral.ge/"></a></td>
											<td style="width:90px; height:60px;"><a id="link3" target="_blank" href="http://palitra.ge/"></a></td>
											<td style="width:60px; height:60px;"><a id="link4" target="_blank" href="http://www.salesland.ge/"></a></td>
										</tr>
									</table>
								</fieldset>
								<fieldset>
								<legend>აბონენტი</legend>
								<table style="height: 243px;">						
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="personal_phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="personal_id" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_id'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="personal_contragent" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_contragent'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="personal_mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="personal_addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_addres'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="persons_id" class="idls object">'.Getpersons($res['persons_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="personal_status" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['personal_status'] . '" />		
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

?>