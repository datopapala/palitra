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
$task_type_id_seller 	= $_REQUEST['task_type_id_seller'];

$hidden_inc				= $_REQUEST['hidden_inc'];
$edit_id				= $_REQUEST['edit_id'];
$delete_id				= $_REQUEST['delete_id'];

// file
$rand_file				= $_REQUEST['rand_file'];
$file					= $_REQUEST['file_name'];

switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);
		
        break;
    case 'quest':
        $page		= Getquest($shabloni);
        $data		= array('page'	=> $page);
        
        break;
    case 'shablon':
        $page		= Getshablon($task_type_id_seller);
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
	    								`task`.`date`,
										`task`.start_date,
										task.end_date,
										task_type.`name`,
										pattern.`name`,
										CONCAT(`task_detail`.`first_name`, ' ', `task_detail`.`last_name`) AS `name`,
										task_detail.phone,
										'',
										IF(task_detail.`status`= 2, 'გადაცემულია გასარკვევად','') AS `status`
								FROM 	`task`
								LEFT JOIN	task_detail ON task.id = task_detail.task_id
								LEFT JOIN	task_type ON task.task_type_id = task_type.id
								LEFT JOIN	pattern ON task.template_id = pattern.id
	    						WHERE	task_detail.actived=1 AND task_detail.`status` = 2");
	    
										    		
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

function Getshablon($id){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	shabloni
							WHERE 	scenar_id = $id
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

function Getstatus($status){
	$req = mysql_query("	SELECT 	`id`,
									`name`
							FROM 	status
						
							");

	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res['id'] == $status){
			$data .= '<option value="' . $res['name'] . '" selected="selected">' . $res['name'] . '</option>';
		} else {
			$data .= '<option value="' . $res['name'] . '">' . $res['name'] . '</option>';
		}
	}

	return $data;
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

function Getincomming($task_id)
{
$res = mysql_fetch_assoc(mysql_query("	SELECT 	task_detail.id,
	    								`task`.`date`,
										`task`.status,
										`task`.start_date,
										task.end_date,
										task.`task_type_id`,
										task.`template_id`,
										`task_detail`.`first_name`,
										`task_detail`.`last_name`,
										task_detail.person_n,
										task_detail.person_status,
										task_detail.phone,
										task_detail.mail,
										task_detail.addres,
										task_detail.city_id,
										task_detail.family_id,
										task_detail.b_day,
										task_detail.profesion
								FROM 	`task`
								LEFT JOIN	task_detail ON task.id = task_detail.task_id
								LEFT JOIN	task_type ON task.task_type_id = task_type.id
								LEFT JOIN	pattern ON task.template_id = pattern.id
	    						WHERE	task_detail.actived=1 AND task_detail.id = '1'
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
												<input type="text" id="id" class="idle" onblur="this.className=\'idle\'"  value="' . $res['id']. '" disabled="disabled" />
											</td>
											<td>
												<input type="text" id="c_date" class="idle" onblur="this.className=\'idle\'"  value="' .  $res['date']. '" disabled="disabled" />
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
											<td><select style="width: 380px;" id="shabloni" class="idls object">'.Getshablon($res['template_id']).'</select></td>
										</tr>
									</table>
								</fieldset>
						        ';
						
						// სატელეფონო გაყიდვები დასაწყისი
						$data .= '
							<div id="quest">
							
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
												<td><select style="width: 330px;" id="status" class="idls object">'.Getstatus($res['status']).'</select></td>
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
											<input type="text" id="phone" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_n'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">გვარი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="last_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['last_name'] . '" />		
										</td>
										<td td style="width: 180px;">
											<input type="text" id="b_day" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['b_day'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="city_id" class="idls object">'.Getcity($res['city_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="addres" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['addres'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ოჯახური სტატუსი</td>
										<td td style="width: 180px; color: #3C7FB1;">პროფესია</td>
									</tr>
									<tr>
										<td><select style="width: 165px;" id="family_id" class="idls object">'.Getfamily($res['family_id']).'</select></td>
										<td td style="width: 180px;">
											<input type="text" id="profesion" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['profesion'] . '" />		
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
	$rows = mysql_query("SELECT quest_id,notes
			FROM shabloni
			WHERE `name`='$shabloni'");
	$notes = array();
	while ($rows_shab = mysql_fetch_assoc($rows)){
		$rows_shablon[] = $rows_shab[quest_id];
		$a		 = array('id'=>$rows_shab[quest_id],'name'=>$rows_shab[notes]);
		$notes[] = $a;
	}
	$pattern = mysql_query("SELECT content FROM pattern_param");
	while ($pattern_param = mysql_fetch_assoc($pattern)){
		$pattern_param_arr[] .= $pattern_param[content];
	}
	
	$data .='<div id="seller" class="">
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0" >
									<fieldset style="width:97%;  float:left; overflow-y:scroll; max-height:400px;" class="'.((in_array('1',$rows_shablon))?"":"dialog_hidden").'">
									<fieldset style="width:97%;" >
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[0][name].'</textarea></td>
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
									<fieldset style="width:97%;" class="'.((in_array('2',$rows_shablon))?"":"dialog_hidden").'">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[1][name].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;" class="'.((in_array('3',$rows_shablon))?"":"dialog_hidden").'">
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
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[2][name].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; " class="'.((in_array('4',$rows_shablon))?"":"dialog_hidden").'">
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[3][name].'</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset class="'.((in_array('21',$rows_shablon))?"":"dialog_hidden").'">
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
											<fieldset style="width:97%;" class="'.((in_array('5',$rows_shablon))?"":"dialog_hidden").'">
										    	<legend>შედეგი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[4][name].'</textarea></td>
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
											
															
																
							  				<fieldset style="width:97%; float:left; " class="'.((in_array('6',$rows_shablon))?"":"dialog_hidden").'">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[5][name].'</textarea></td>
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
											<fieldset style="width:97%; float:left; " class="'.((in_array('7',$rows_shablon))?"":"dialog_hidden").'">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[6][name].'</textarea></td>
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
						$data .= '<div id="research" class="">
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" class="">დემოგრაფიული ბლოკი</li>
										<li id="r2" onclick="research(this.id)" class="">ძირითადი ნაწილი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="'.((in_array('8',$rows_shablon))?"":"dialog_hidden").'">
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
														<div class="'.((in_array('9',$rows_shablon))?"":"dialog_hidden").'">
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
														</div>
														
									<div class="'.((in_array('10',$rows_shablon))?"":"dialog_hidden").'">
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
														</div>
														
									<div class="'.((in_array('11',$rows_shablon))?"":"dialog_hidden").'">
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
												</div>
															
									<div class="'.((in_array('12',$rows_shablon))?"":"dialog_hidden").'">
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
												</div>
																
									<div class="'.((in_array('13',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
														
									<div class="'.((in_array('14',$rows_shablon))?"":"dialog_hidden").'">					
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
													</div>
															
									<div class="'.((in_array('15',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
														
									<div class="'.((in_array('16',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
														
									<div class="'.((in_array('17',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
														
									<div class="'.((in_array('18',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
														
									<div class="'.((in_array('19',$rows_shablon))?"":"dialog_hidden").'">					
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
													</div>
															
									<div class="'.((in_array('20',$rows_shablon))?"":"dialog_hidden").'">					
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
														</div>
										<button style="float:right; margin-top:10px;" onclick="research(\'r2\')" class="next"> >> </button>
										<button style="float:right; margin-top:10px;" onclick="research(\'r0\')" class="back"> << </button>
									</fieldset>			
									</div>
														
									 <div id="research-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset '.((in_array('22',$rows_shablon))?"":"dialog_hidden").'>
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
	
	return $data;
}

?>