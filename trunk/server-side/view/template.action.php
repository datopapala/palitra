<?php
require_once('../../includes/classes/core.php');
$action	= $_REQUEST['act'];
$error	= '';
$data	= '';

$template_id 	= $_REQUEST['id'];
$template_name  = $_REQUEST['name'];
$content    	= $_REQUEST['content'];


switch ($action) {
	case 'get_add_page':
		$page		= GetPage();
		$data		= array('page'	=> $page);

		break;
	case 'get_edit_page':
		$template_id		= $_REQUEST['id'];
		$page		= GetPage(Gettemplate($template_id));
		$data		= array('page'	=> $page);

		break;
	case 'get_list' :
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
			
		$rResult = mysql_query("SELECT 	template.id,
										template.`name`
							    FROM 	template
							    WHERE 	template.actived=1");

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
	case 'save_template':
		


		if($template_id != ''){
			Savetemplate($template_id, $template_name, $content);
			}
			else{
			if(!ChecktemplateExist($template_name, $template_id)){
				if ($template_id == '') {
					Addtemplate($template_name, $content);
				}else {
					
				$error = '"' . $template_name . '" უკვე არის სიაში!';

			}
		}
	}

		break;
	case 'disable':
		$template_id	= $_REQUEST['id'];
		Disabletemplate($template_id);

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

function Addtemplate($template_name, $content)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("INSERT INTO 	 	`template`
									(`user_id`,`name`, `actived`)
								VALUES 	
									('$user_id','$template_name', 1)");
}

function Savetemplate($template_id, $template_name, $content)
{
	$user_id	= $_SESSION['USERID'];
	mysql_query("	UPDATE `template`
					SET     `user_id`	='$user_id',
							`name` 		= '$template_name'
					WHERE	`id` 		= $template_id");
}

function Disabletemplate($template_id)
{
	mysql_query("	UPDATE `template`
					SET    `actived` = 0
					WHERE  `id` = $template_id");
}

function ChecktemplateExist($template_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `template`
											WHERE  `name` = '$template_name' && `actived` = 1"));
	if($res['id'] != ''){
		return true;
	}
	return false;
}


function Gettemplate($template_id)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`
											FROM    `template`
											WHERE   `id` = $template_id" ));

	return $res;
}

function GetPage($res = '')
{
	if($res['id'] == 1){
	$data = '
	<div id="dialog-form">
	    <fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>

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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;">
								    	<legend>პროდუქტი</legend>
										<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; ">
								    	<legend>საჩუქარი</legend>
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
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
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:97%; float:left; ">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											</fieldset>
											<fieldset style="width:97%; float:left; ">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
													</tr>
													<tr>
														<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
													</tr>
											</table>
											</fieldset>
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
									
							</div>
			<!-- ID -->
			<input type="hidden" id="template_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
}else if($res['id'] == 2){
	$data = '
			<div id="dialog-form">
	    	<fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
	<div id="research" >
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" class="">დემოგრაფიული ბლოკი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>შესავალი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									
									</fieldset>
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
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
									
									<table class="dialog-form-table" style="margin-top:10px;">
								    		<tr>
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >' . $res['content'] . '</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
										<button style="float:right; margin-top:10px;" onclick="research(\'r0\')" class="back"> << </button>
									</fieldset>			
									</div>
														
									 
									
							</div>
			<!-- ID -->
			<input type="hidden" id="template_id" value="' . $res['id'] . '" />
        </fieldset>
    </div>
    ';
}
	
	return $data;
}

?>

