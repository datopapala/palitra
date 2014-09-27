<?php

require_once ('../../includes/classes/core.php');

if (!$conn) {
	$error = 'dgfhg';
}
mysql_select_db('asteriskcdrdb');

$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

switch ($action) {
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("

	  			SELECT * FROM `elva_sale`

	  			");

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
	case 'get_edit_page' :
		$rResult = mysql_query(	"SELECT *
								FROM `elva_sale`
								WHERE id='$_REQUEST[id]'");
		$res = mysql_fetch_array( $rResult );

		$data['page'][] = '<div id="dialog-form">
								<div style="float: left; width: 710px;" disabled>
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
									<fieldset >
							    	<table class="dialog-form-table">
										<tr>
											<td style="width: 280px;"><label for="">პირადი №</label></td>
											<td style="width: 280px;"><label for="">სახელი გვარი</label></td>
											<td style="width: 280px;"><label for="">მეილი</label></td>
										</tr>
										<tr>
											<td><input id="person_id" 	 class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[person_id]. 	'" /></td>
											<td><input id="name_surname" class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[name_surname].'" /></td>
											<td><input id="c_date" 		 class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[mail].       	'" /></td>
										</tr>
										<tr>
											<td style="width: 280px;"><label for="">ტელეფონი</label></td>
											<td style="" colspan="2"><label for="">მისმართი</label></td>
										</tr>
										<tr>
											<td><input id="phone" class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[phone].'" /></td>
											<td colspan="2"><input id="c_date" class="idle" style="width: 444px;" disabled="disabled" type="text" value="'.$res[address].'" /></td>
										</tr>
									</table>
								</fieldset >
									<fieldset style="margin-top: 5px;">
							    		<legend>დავალების ტიპი</legend>
											<table class="dialog-form-table" >
											<tr>
												<td style="width: 280px;"><label for="period">პერიოდი</label></td>
												<td colspan="2" style="width: 280px;"><label for="book">გამოცემა</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="period" 				value="'.$res[period].'"	class="idls object" disabled></td>
												<td colspan="2"><input style="width: 444px;" id="book" 		value="'.$res[books]. '" 	class="idls object" disabled></td>
											</tr>
											<tr>
												<td style="width: 280px;"><label for="date">ქოლ-ცენტრის დარეკვის თარიღი</label></td>
												<td style="width: 280px;"><label for="op_id">ოპერატორი</label></td>
												<td style="width: 280px;"><label for="oder_date">ქვითრის გაგზავნის დღე</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="date" 		value="'.$res[call_date]. 		'" class="idls object" disabled></td>
												<td><input style="width: 200px;" id="op_id" 	value="'.$res[operator_id]. 	'" class="idls object" disabled></td>
												<td><input style="width: 200px;" id="oder_date" value="'.$res[oder_send_date].  '" class="idls object" disabled></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label> გადასახდელი თანხა  </label></td>
												<td style="width: 150px;"><label>ქოლცენტრის კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 200px;" id="oder_date" value="'.$res[sum_price].  '" class="idls object" disabled></td>
												<td><textarea  style="width: 270px; resize: none;" id="c_coment" class="idle" name="content" cols="300" disabled>'.$res[callceenter_comment].'</textarea></tr>

										</table>
									</fieldset>
								<fieldset style="margin-top: 5px;">
								    	<legend>ელვა.ჯი</legend>
								    	<table class="dialog-form-table" >
											<tr>
												<td style="width: 280px;"><label for="status">სტატუსი</label></td>
												<td style="width: 280px;"><label for="cooradinator">კოორდინატორი</label></td>
												<td style="width: 280px;"><label for="elva">ნინო (ელვა)</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="status" 		value='.$res[status]. 		' class="idls object"></td>
												<td><input style="width: 200px;" id="cooradinator"  value='.$res[coordinator_id]. 		' class="idls object"></td>
												<td><input style="width: 200px;" id="elva"          value='.$res[elva_status]. 		' class="idls object"></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label>კოოდინატორის შენიშვნა</label></td>
											</tr>
											<tr>
												<td><textarea  style="width: 270px; resize: none;" id="k_coment" class="idle" name="content" cols="300">'.$res[coordinator_comment].'</textarea></tr>

										</table>
							        </fieldset>
							</fieldset>
							</div>
					<div style="float: right;  width: 355px;">
								<fieldset>
								<legend>აბონენტი</legend>
								<table style="height: 243px;">
									<tr>
										<td style="width: 180px; color: #3C7FB1;">ტელეფონი</td>
										<td style="width: 180px; color: #3C7FB1;">პირადი ნომერი</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="phone" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="" />
										</td>
										<td style="width: 180px;">
											<input type="text" id="person_n" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" value="" />
										</td>
									</tr>
									<tr>
										<td style="width: 180px; color: #3C7FB1;">სახელი</td>
										<td style="width: 180px; color: #3C7FB1;">ელ-ფოსტა</td>
									</tr>
									<tr >
										<td style="width: 180px;">
											<input type="text" id="first_name" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'"  />
										</td>
										<td style="width: 180px;">
											<input type="text" id="mail" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'"  />
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">ქალაქი</td>
										<td td style="width: 180px; color: #3C7FB1;">დაბადების თარიღი</td>
									</tr>
									<tr>
										<td style="width: 180px;">
											<input type="text" id="city_id" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'"  />
										</td>
										<td td style="width: 180px;">
											<input type="text" id="b_day" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'"  />
										</td>
									</tr>
									<tr>
										<td td style="width: 180px; color: #3C7FB1;">მისამართი</td>
									</tr>
									<tr>
										<td td style="width: 180px;">
											<input type="text" id="addres" disabled class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" />
										</td>
									</tr>
									
								</table>
							</fieldset>
					</div>
				    </div>
								<input type="hidden" id="id" value="'.$_REQUEST[id].'" />
			</div>
		</fieldset></div>
										';
			   	break;
   	case 'save_dialog' :
		mysql_query("UPDATE `elva_sale` SET `status`='$_REQUEST[status]', `coordinator_id`='$_REQUEST[cooradinator]', `coordinator_comment`='$_REQUEST[k_coment]', `elva_status`='$_REQUEST[elva]'
					WHERE (`id`='$_REQUEST[id]')");
   		break;
	default:
		$error = 'Action is Null';
}


$data['error'] = $error;

echo json_encode($data);

?>