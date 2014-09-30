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
	    
	    $user_checker = '';
	    if($user == 1){
	    	$user_checker = '';
	    }else{
	    	$user_checker = 'and task_detail.responsible_user_id='.$user;
	    }
	     
	    $rResult = mysql_query("SELECT 	task_detail.id,
	    								task_detail.id,
	    								`task`.`date`,
										`task`.start_date,
										task.end_date,
										task_type.`name`,
										shabloni.`name`,
										IF(task_detail.phone_base_inc_id != '', incomming_call.first_name, phone.first_last_name),
										IF(task_detail.phone_base_inc_id != '', incomming_call.phone, phone.phone1),
										priority.`name`,
										IF(task_detail.status= 3, 'დასრულებული','') AS `status`
								FROM 	`task`
								LEFT JOIN	task_detail ON task.id = task_detail.task_id
								LEFT JOIN	task_type ON task.task_type_id = task_type.id
								LEFT JOIN	pattern ON task.template_id = pattern.id
	    						LEFT JOIN shabloni ON task.template_id = shabloni.id
								LEFT JOIN incomming_call ON task_detail.phone_base_inc_id = incomming_call.id
								LEFT JOIN phone ON task_detail.phone_base_id = phone.id
	    						LEFT JOIN priority ON task.priority_id = priority.id
	    						WHERE	task_detail.status=3 $user_checker");
	    
										    		
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
	
		
		Savetask($task_id, $problem_comment, $file, $rand_file);
        break;
        case 'done_outgoing':
        
        	$user_id			= $_SESSION['USERID'];
			$task_detail_id		= $_REQUEST['id'];
			$hello_quest		= $_REQUEST['hello_quest'];
			$hello_comment		= $_REQUEST['hello_comment'];
			$info_quest			= $_REQUEST['info_quest'];
			$info_comment		= $_REQUEST['info_comment'];
			$result_quest		= $_REQUEST['result_quest'];
			$result_comment		= $_REQUEST['result_comment'];
			$payment_quest		= $_REQUEST['payment_quest'];
			$payment_comment	= $_REQUEST['payment_comment'];
			$send_date			= $_REQUEST['send_date'];
			
			$preface_name			= $_REQUEST['preface_name'];
			$preface_quest		= $_REQUEST['preface_quest'];
			$d1			= $_REQUEST['d1'];
			$d2			= $_REQUEST['d2'];
			$d3			= $_REQUEST['d3'];
			$d4			= $_REQUEST['d4'];
			$d5			= $_REQUEST['d5'];
			$d6			= $_REQUEST['d6'];
			$d7			= $_REQUEST['d7'];
			$d8			= $_REQUEST['d8'];
			$d9			= $_REQUEST['d9'];
			$d10		= $_REQUEST['d10'];
			$d11		= $_REQUEST['d11'];
			$d12		= $_REQUEST['d12'];
			$q1			= $_REQUEST['q1'];
			
			$call_content		= $_REQUEST['call_content'];
			$status				= $_REQUEST['status'];
        
        	Savetask1($task_detail_id, $hello_quest, $hello_comment, $info_quest, $info_comment, $result_quest, $result_comment, $payment_quest, $payment_comment, $send_date, $preface_name, $preface_quest, $d1, $d2, $d3, $d4, $d5, $d6, $d7, $d8, $d9, $d10, $d11, $d12, $q1);
        	Savetask2($task_detail_id, $call_content, $status);
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



function Savetask2($task_detail_id, $call_content, $status)
{

	mysql_query("UPDATE `task_detail` SET  
						`call_content` = '$call_content',
						`status`	='3'
				 WHERE  `id`		='$task_detail_id'
									");

}
function Savetask1($task_detail_id, $hello_quest, $hello_comment, $info_quest, $info_comment, $result_quest, $result_comment, $payment_quest, $payment_comment, $send_date, $preface_name, $preface_quest, $d1, $d2, $d3, $d4, $d5, $d6, $d7, $d8, $d9, $d10, $d11, $d12, $q1)
{
	mysql_query("INSERT INTO `task_scenar`
(`task_detail_id`, `hello_comment`, `hello_quest`, `info_comment`, `info_quest`, `result_comment`, `result_quest`, `send_date`, `payment_comment`, `payment_quest`, `preface_name`, `preface_quest`, `d1`, `d2`, `d3`, `d4`, `d5`, `d6`, `d7`, `d8`, `d9`, `d10`, `d11`, `d12`, `q1`)
VALUES
( '$task_detail_id', '$hello_comment', '$hello_quest', '$info_comment', '$info_quest', '$result_comment', '$result_quest', '$send_date', '$payment_comment', '$payment_quest', '$preface_name', '$preface_quest', '$d1', '$d2', '$d3', '$d4', '$d5', '$d6', '$d7', '$d8', '$d9', '$d10', '$d11', '$d12', '$q1')
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
						
						for($key=1;$key<23;$key++){
						
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
											<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
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
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[2][notes].'</textarea></td>
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" disabled class="idle" name="content" cols="300" >'.$notes[3][notes].'</textarea></td>
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
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['result_comment'] . '</textarea></td>
														<td style="width:250px;text-align:right;"><button id="complete">დაასრულეთ</button></td>
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
														<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['payment_comment'] . '</textarea></td>
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
					  							<td><button class="done">დასრულება</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
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
												<td><textarea  style="width: 350px; height:70px; resize: none;" id="call_content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
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
												<td style="width: 280px;"><label for="task_department_id">განყოფილება</label></td>
												<td style="width: 280px;"><label for="persons_id">პასუხისმგებელი პირი</label></td>
												<td style="width: 280px;"><label for="priority_id">პრიორიტეტი</label></td>
											</tr>
								    		<tr>
												<td><select style="width: 200px;"  id="task_department_id" class="idls object">'.Getdepartment($res['task_department_id']).'</select></td>
												<td><select style="width: 200px;" id="persons_id" class="idls object">'. Getpersons($res['persons_id']).'</select></td>
												<td><select style="width: 200px;" id="priority_id" class="idls object">'.Getpriority($res['priority_id']).'</select></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label>შესრულების პერიოდი</label></td>
												<td style="width: 150px;"><label></label></td>
												<td style="width: 150px;"><label>კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">დან</span></td>
										  		<td><input style="width: 130px; float:left;" class="idle" type="text"><span style="margin-left:5px; ">მდე</span></td>
												<td>
													<textarea  style="width: 270px; resize: none;" id="comment" class="idle" name="content" cols="300">' . $res['comment'] . '</textarea>
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
											<input type="text" id="phone" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['phone'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['person_n'] . '" />
										</td>					
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['first_name'] . '" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['mail'] . '" />
										</td>			
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td><input type="text" id="city_id" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['city_id'] . '" /></td>	
										<td td style="width: 180px;">
											<input type="text" id="b_day" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['b_day'] . '" />		
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="addres" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="' . $res['addres'] . '" />		
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
											<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
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
														<td style="width:250px;text-align:right;"><button id="complete">დაასრულეთ</button></td>
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
													<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
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
					  							<td><button class="done">დასრულება</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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
												<td style="width:150px; text-align:right;"><button style="" class="done">დაასრულეთ</button></td>
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