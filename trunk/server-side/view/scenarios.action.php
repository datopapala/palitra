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
			
		$rResult = mysql_query("SELECT 	pattern.id,
										pattern.`name`
							    FROM 	pattern
							    WHERE 	pattern.actived=1");

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
			$p1 = $_REQUEST['p1'];
			$p2 = $_REQUEST['p2'];
			$p3 = $_REQUEST['p3'];
			$p4 = $_REQUEST['p4'];
			$p5 = $_REQUEST['p5'];
			$p6 = $_REQUEST['p6'];
			$p7 = $_REQUEST['p7'];
			$p8 = $_REQUEST['p8'];
			$p9 = $_REQUEST['p9'];
			$p10 = $_REQUEST['p10'];
			$p11 = $_REQUEST['p11'];
			$p12 = $_REQUEST['p12'];
			$p13 = $_REQUEST['p13'];
			$p14 = $_REQUEST['p14'];
			$p15 = $_REQUEST['p15'];
			$p16 = $_REQUEST['p16'];
			$p17 = $_REQUEST['p17'];
			$p18 = $_REQUEST['p18'];
			$p19 = $_REQUEST['p19'];
			$p20 = $_REQUEST['p20'];
		
			Savetemplate($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p18,$p19,$p20);

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


function Savetemplate($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p18,$p19,$p20)
{
	$user_id	= $_SESSION['USERID'];
	if($_REQUEST[p7])
	{
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p1'
					WHERE	`id` 		= '1'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p2'
					WHERE	`id` 		= '2'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p3'
					WHERE	`id` 		= '3'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p4'
					WHERE	`id` 		= '4'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p5'
					WHERE	`id` 		= '5'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p6'
					WHERE	`id` 		= '6'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p7'
					WHERE	`id` 		= '7'");
	
	}else{
	
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p8'
					WHERE	`id` 		= '8'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p9'
					WHERE	`id` 		= '9'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p10'
					WHERE	`id` 		= '10'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p11'
					WHERE	`id` 		= '11'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p12'
					WHERE	`id` 		= '12'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p13'
					WHERE	`id` 		= '13'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p14'
					WHERE	`id` 		= '14'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p15'
					WHERE	`id` 		= '15'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p16'
					WHERE	`id` 		= '16'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p17'
					WHERE	`id` 		= '17'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p18'
					WHERE	`id` 		= '18'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p19'
					WHERE	`id` 		= '19'");
	mysql_query("	UPDATE `pattern_param`
					SET     `user_id`	='$user_id',
							`content` 	= '$p20'
					WHERE	`id` 		= '20'");
	}
}

function Disabletemplate($template_id)
{
	mysql_query("	UPDATE `pattern`
					SET    `actived` = 0
					WHERE  `id` = $template_id");
}

function ChecktemplateExist($template_name)
{
	$res = mysql_fetch_assoc(mysql_query("	SELECT `id`
											FROM   `pattern`
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
											FROM    `pattern`
											WHERE   `id` = $template_id" ));

	return $res;
}

function GetPage($res = '')
{
	function Getparttner($pattern_name){
	$pattern = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
														`pattern_name`,
														`content`
												FROM    `pattern_param`
												WHERE	`pattern_name` = '$pattern_name'
											" ));
		$data = $pattern[content];
		
		return $data;
	}
	
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="p1" class="idle" name="content" cols="300" >'.Getparttner('მისალმება').'</textarea></td>
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="p2" class="idle" name="content" cols="300" >' .Getparttner('შეთავაზება'). '</textarea></td>
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
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="p3" class="idle" name="content" cols="300" >' .Getparttner('პროდუქტი'). '</textarea></td>
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="p4" class="idle" name="content" cols="300" >' .Getparttner('საჩუქარი'). '</textarea></td>
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
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="p5" class="idle" name="content" cols="300" >' .Getparttner('შედეგი'). '</textarea></td>
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
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="p6" class="idle" name="content" cols="300" >' .Getparttner('მიწოდება'). '</textarea></td>
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
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="p7" class="idle" name="content" cols="300" >' .Getparttner('ანგარიშსწორება'). '</textarea></td>
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="p8" class="idle" name="content" cols="300" >' .Getparttner('შესავალი'). '</textarea></td>
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p9" class="idle" name="content" cols="300" >' .Getparttner('D1'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p10" class="idle" name="content" cols="300" >' .Getparttner('D2'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p11" class="idle" name="content" cols="300" >' .Getparttner('D3'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p12" class="idle" name="content" cols="300" >' .Getparttner('D4'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p13" class="idle" name="content" cols="300" >' .Getparttner('D5'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p14" class="idle" name="content" cols="300" >' .Getparttner('D6'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p15" class="idle" name="content" cols="300" >' .Getparttner('D7'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p16" class="idle" name="content" cols="300" >' .Getparttner('D8'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p17" class="idle" name="content" cols="300" >' .Getparttner('D9'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p18" class="idle" name="content" cols="300" >' .Getparttner('D10'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p19" class="idle" name="content" cols="300" >' .Getparttner('D11'). '</textarea></td>
												
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="p20" class="idle" name="content" cols="300" >' .Getparttner('D12'). '</textarea></td>
												
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

