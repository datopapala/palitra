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
				
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'save_template':
			

		break;
	case 'disable':
		

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
		<div id="seller" class="" >
									<ul>
										<li style="margin-left:0;" id="0" onclick="seller(this.id)" class="seller_select">მისალმება</li>
										<li id="1" onclick="seller(this.id)" class="">შეთავაზება</li>
										<li id="2" onclick="seller(this.id)" class="">შედეგი</li>
									</ul>
									<div id="seller-0" >
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;" class="">
									<fieldset style="width:97%;" >
								    	<legend>მისალმება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
											</tr>
									</table>
									</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
									</fieldset>
									 </div>

														
														
									<div id="seller-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="">
								    	<legend>შეთავაზება</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
									<fieldset style="width:97%;" class="">
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
												<td><textarea  style="width: 99%; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
										</table>
									</fieldset>
					  				<fieldset style="width:97%; float:left; " class="">
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
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
											</tr>
											<tr>
												<td style="text-align:right;"><span>შეიყვანეთ ტექსტი</span></td>
											</tr>
									</table>
									</fieldset>
											<fieldset class="">
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
													<td><textarea  style="width: 100%; height:50px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
												</tr>
											</table>
											</fieldset>
											<button style="float:right; margin-top:10px;" onclick="seller(2)" class="next"> >> </button>
											<button style="float:right; margin-top:10px;" onclick="seller(0)" class="back"> << </button>
									
									</fieldset>
													
									 </div>
									 <div id="seller-2" class="dialog_hidden">
											<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
											<fieldset style="width:97%;" class="">
										    	<legend>შედეგი</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
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
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button id="complete">დაასრულეთ</button></td>
													</tr>
											</table>
											</fieldset>
											
															
																
							  				<fieldset style="width:97%; float:left; " class="">
										    	<legend>მიწოდება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
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
											<fieldset style="width:97%; float:left; " class="">
										    	<legend>ანგარიშსწორება</legend>
											<table class="dialog-form-table">
										    		<tr>
														<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
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
														<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
													</tr>
											</table>
											</fieldset>
													<button style="float:right; margin-top:10px;" class="done">დასრულება</button>
													<button style="float:right; margin-top:10px;" onclick="seller(1)" class="next"> << </button>
											</fieldset>		
									 </div>
	    	
        </fieldset>
    </div>
    ';
}else if($res['id'] == 2){
	$data = '
			<div id="dialog-form">
	    	<fieldset>
	    	<legend>ძირითადი ინფორმაცია</legend>
			<div id="research" class="">
									<ul>
										<li style="margin-left:0;" id="r0" onclick="research(this.id)" class="seller_select">შესავალი</li>
										<li id="r1" onclick="research(this.id)" class="">დემოგრაფიული ბლოკი</li>
										<li id="r2" onclick="research(this.id)" class="">ძირითადი ნაწილი</li>
									</ul>
									<div id="research-0">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;" class="">
								    	<legend>შესავალი</legend>
									<table class="dialog-form-table">
								    		<tr>
												<td><textarea  style="width: 680px; height:80px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[7][name].'</textarea></td>
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
												<td><input type="text" style="width:100%;" id="" class="idle" onblur="this.className=\'idle\'"  value="" /></td>
					  						</tr>
									</table>
											<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="next"> >> </button>
									</fieldset>
									 </div>

											
									<div id="research-1" class="dialog_hidden">
									<fieldset style="width:97%; float:left; overflow-y:scroll; max-height:400px;">
									<fieldset style="width:97%;">
								    	<legend>დემოგრაფიული ბლოკი</legend>
														<div class="">
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[8][name].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="">
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
												<td><textarea  style="width: 680px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" >'.$notes[9][name].'</textarea></td>
												
											</tr>
											<tr>
												<td style="text-align:right;">შეიყვანეთ ტექსტი</td>
											</tr>
									</table>
									<hr>
														</div>
														
									<div class="">
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
															
									<div class="">
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
																
									<div class="">					
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
														
									<div class="">					
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
															
									<div class="">					
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
														
									<div class="">					
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
														
									<div class="">					
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
														
									<div class="">					
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
														
									<div class="">					
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
															
									<div class="">					
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
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>
						  									
						  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q2</td>
														<td style="font-weight:bold; font-size:12px;">გთხოვთ მითხრათ, რა საშუალებებით მოუსმინეთ  რადიოს?</td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td><span>მობილური ტელეფონი</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>რადიომიმღები</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  							
						  						</tr>
			  									
												<tr>
													<td style="width:180px; "><span>ინტერნეტი</span></td>
						  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>ავტომობილის პლეერი</span></td>
						  							<td><input type="radio" name="xx" value="2" '.(($res['call_vote']=='2')?"checked":"").'></td>
						  						</tr>
						  						<tr>
			  										<td style="width:180px;"><span>პლეერი (აიპადი, <br>აიპოდი, მპ3 პლეერი)</span></td>
			  										<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
			  									</tr>
											</table>
						  					<table class="dialog-form-table" style="margin-top:10px;">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>
			  												
			  								<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q3</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით რადიოსადგურებს და თქვენ მიპასუხეთ, რომელ რადიოს უსმენდით   ბოლო ერთი კვირის განმავლობაში?  კიდევ, კიდევ.</td>
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
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>
						  									
						  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q4</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით რადიოსადგურებს და თქვენ მიპასუხეთ, რომელ რადიოს უსმენდით ბოლო ერთი თვის განმავლობაში? კიდევ, კიდევ.</td>
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
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>
						  									
						  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q5</td>
														<td style="font-weight:bold; font-size:12px;">მოგისმენიათ თუ არა ‘რადიო პალიტრისთვის’ ბოლო ერთი თვის განმავლობაში?</td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td><span>დიახ</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>არა</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  						</tr>
												
											</table>
						  					<table class="dialog-form-table">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>	
						  										
						  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">Q6</td>
														<td style="font-weight:bold; font-size:12px;">ძირითადად, სად უსმენთ რადიოს?</td>
													</tr>
											</table>
											<table class="dialog-form-table">
										    	<tr>
													<td><span>სახლში</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>სამსახურში/სასწავლებელში</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  						</tr>
						  						<tr>
													<td><span>საკუთარ ავტომობილში</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>სხვა ტრანსპორტში</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  						</tr>
						  						<tr>
													<td><span>ქუჩაში/სეირნობისას</span></td>
						  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
						  							<td style="width:180px; text-align:right;"><span>სხვა შენობაში</span></td>
						  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
						  						</tr>
												
											</table>
						  					<table class="dialog-form-table">
										    	
													<tr>
														<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
														<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
													</tr>
											</table>
						  					<hr>			
											</fieldset>
											
						  									
						  				<fieldset>
										    	<legend>ტელევიზია</legend>
						  						<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">T1</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით ტელეარხებს და თქვენ მიპასუხეთ, რომელ  ტელეარხებს უყურებდით გუშინ თუნდაც ხუთი წუთით? კიდევ, კიდევ?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>არ ვუყურებდი</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">T2</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით ტელეარხებს და თქვენ მიპასუხეთ, რომელ  ტელეარხებს უყურებდით ბოლო ერთი კვირის განმავლობაში?  კიდევ, კიდევ?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>არ ვუყურებდი</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">T3</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით ტელეარხებს და თქვენ მიპასუხეთ, რომელ  ტელეარხებს უყურებდით ბოლო ერთი თვის განმავლობაში? კიდევ, კიდევ?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>არ ვუყურებდი</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">T4</td>
														<td style="font-weight:bold; font-size:12px;">გთხოვთ მითხრათ, გიყურებიათ თუ არა პალიტრისთვის TV-თვის ბოლო ერთი თვის განმავლობაში?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">T5</td>
														<td style="font-weight:bold; font-size:12px;">პალიტრა TV -ის რომელ გადაცემებს უყურებთ ყველაზე ხშირად? </td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
						  				</fieldset>

										
							  			<fieldset>
										    	<legend>პრესა</legend>
						  						<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P1</td>
														<td style="font-weight:bold; font-size:12px;">კითხულობთ თუ არა ჟურნალ-გაზეთებს?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>მხოლოდ გაზეთებს </span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td><span>მხოლოდ ჟურნალებს </span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
							  						<tr>
														<td><span>ვკითხულობ ორივეს</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td><span>არ ვკითხულობ (დაასრულეთ)</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P2</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით გაზეთებს და დამისახელეთ, რომელი წაგიკითხავთ ბოლო ერთი კვირის განმავლობაში? კიდევ, კიდევ?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>არ ვუყურებდი</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P3</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით გაზეთებს და დამისახელეთ, რომელი წაგიკითხავთ ბოლო ერთი თვის განმავლობაში?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>არ ვუყურებდი</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P4</td>
														<td style="font-weight:bold; font-size:12px;">გთხოვთ, დაასახელოთ მიზეზი, რის გამოც ირჩევთ ამ გამოცემას?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
											    	
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P5</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით ჟურნალებს და დამისახელეთ, რომელი წაგიკითხავთ ბოლო ერთი კვირის განმავლობაში? კიდევ, კიდევ?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P6</td>
														<td style="font-weight:bold; font-size:12px;">თუ შეიძლება, მე ჩამოგითვლით ჟურნალებს და დამისახელეთ, რომელი წაგიკითხავთ ბოლო ერთი თვის განმავლობაში?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
							  									
							  					<table class="dialog-form-table">
										    		<tr>
														<td style="font-weight:bold; width:30px;">P7</td>
														<td style="font-weight:bold; font-size:12px;">გთხოვთ, დაასახელოთ მიზეზი, რის გამოც ირჩევთ ამ გამოცემას?</td>
													</tr>
												</table>
												<table class="dialog-form-table">
											    	<tr>
														<td><span>დიახ</span></td>
							  							<td><input type="radio" name="xx" value="1" '.(($res['call_vote']=='1')?"checked":"").'></td>
							  							<td style="width:180px; text-align:right;"><span>არა</span></td>
							  							<td><input type="radio" name="xx" value="3" '.(($res['call_vote']=='3')?"checked":"").'></td>
							  						</tr>
													
												</table>
							  					<table class="dialog-form-table">
														<tr>
															<td><textarea  style="width: 400px; height:60px; resize: none;" id="content" class="idle" name="content" cols="300" ></textarea></td>
															<td style="width:250px;text-align:right;"><button class="done">დაასრულეთ</button></td>
														</tr>
												</table>
							  					<hr>
						  				</fieldset>						
							  									
							  				<button style="float:right; margin-top:10px;" onclick="research(\'r1\')" class="back"> << </button>
											
										</fieldset>				
									 </div>
									
							</div>
        	</fieldset>
    		</div>
    ';
}
	
	return $data;
}

?>

