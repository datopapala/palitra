<?php

require_once ('../../includes/classes/core.php');


$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';
$start		= $_REQUEST['start'];
$end		= $_REQUEST['end'];

switch ($action) {
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("

					  			SELECT 			elva_sale.id,
												elva_sale.person_id,
												elva_sale.name_surname,
												elva_sale.mail,
												elva_sale.address,
												elva_sale.phone,
	  											elva_sale.phone1,
												shipping.`name` AS `period`,
												elva_sale.books,
												elva_sale.call_date,
												elva_sale.sum_price,
												elva_sale.callceenter_comment,
												persons.`name` as operator_id,
												elva_sale.oder_send_date,
												elva_sale.`status`,
												elva_sale.coordinator_id,
												elva_sale.coordinator_comment,
												elva_sale.elva_status
												FROM `elva_sale`
												left JOIN shipping ON elva_sale.period = shipping.id
												JOIN users ON elva_sale.operator_id = users.id
												JOIN persons ON users.person_id = persons.id
								WHERE 			DATE(elva_sale.call_date) >= '$start' AND DATE(elva_sale.call_date) <= '$end'
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
		$disabled = '';
		$other_disabled = '';
		$user		= $_SESSION['USERID'];
		
		$chek_gr = mysql_fetch_row(mysql_query("SELECT `group_id` FROM `users` WHERE `id` = '$user'; "));
		
		if($chek_gr[0] == 6){
			$disabled = '';
		}else{
			$disabled = 'disabled';
		}
		
		if($chek_gr[0] != 6){
			$other_disabled = '';
		}else{
			$other_disabled = 'disabled';
		}
		
		$rResult = mysql_query(	"SELECT elva_sale.id,
								elva_sale.person_id,
								elva_sale.name_surname,
								elva_sale.mail,
								elva_sale.address,
								elva_sale.phone,
								elva_sale.phone1,
								shipping.`name` AS `period`,
								elva_sale.books,
								elva_sale.call_date,
								elva_sale.sum_price,
								elva_sale.callceenter_comment,
								persons.`name` as operator_id,
								elva_sale.oder_send_date,
								elva_sale.`status`,
								elva_sale.coordinator_id,
								elva_sale.coordinator_comment,
								elva_sale.task_id,
								elva_sale.elva_status
								FROM `elva_sale`
								left JOIN shipping ON elva_sale.period = shipping.id
								JOIN users ON elva_sale.operator_id = users.id
								JOIN persons ON users.person_id = persons.id
								WHERE elva_sale.id='$_REQUEST[id]'");
		$res = mysql_fetch_array( $rResult );
		$data['page'][0] = '';
		$data['page'][0] .= '<div id="dialog-form">
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
											<td><input id="person_id" 	 class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[person_id]. 	'" /></td>
											<td><input id="name_surname" class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[name_surname].'" /></td>
											<td><input id="mail" 		 class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[mail].       	'" /></td>
										</tr>
										<tr>
											<td style="width: 280px;"><label for="">ტელეფონი 1</label></td>
											<td style="width: 280px;"><label for="">ტელეფონი 2</label></td>
											<td style=""><label for="">მისმართი</label></td>
										</tr>
										<tr>
											<td><input id="phone" class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[phone].'" /></td>
											<td><input id="phone1" class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[phone1].'" /></td>
											<td><input id="addres" class="idle" style="width: 200px;" '.$other_disabled.' type="text" value="'.$res[address].'" /></td>
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
												<td><input style="width: 200px;" id="period" 				value="'.$res[period].'"	class="idls object" '.$other_disabled.'></td>
												<td colspan="2"><input style="width: 444px;" id="book" 		value="'.$res[books]. '" 	class="idls object" '.$other_disabled.'></td>
											</tr>
											<tr>
												<td style="width: 280px;"><label for="date">ქოლ-ცენტრის დარეკვის თარიღი</label></td>
												<td style="width: 280px;"><label for="op_id">ოპერატორი</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="date" 		value="'.$res[call_date].'" 		class="idls object" '.$other_disabled.'></td>
												<td><input style="width: 200px;" id="op_id" 	value="'.$res[operator_id].'" 		class="idls object" '.$other_disabled.'></td>
											</tr>
											</table>
											<table class="dialog-form-table" >
											<tr>
												<td style="width: 270px;"><label> გადასახდელი თანხა  </label></td>
												<td style="width: 270px;"><label>ქოლცენტრის კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 200px;" id="sum_price" value="'.$res[sum_price].  '" class="idls object" '.$other_disabled.'></td>
												<td><textarea  style="width: 444px; height:80px; resize: none;" id="c_coment" class="idle" name="content" cols="300" '.$other_disabled.'>'.$res[callceenter_comment].'</textarea></tr>

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
												<td><input style="width: 200px;" id="status" 		value="'.$res[status].'" 			class="idls object" '.$disabled.'></td>
												<td><input style="width: 200px;" id="cooradinator"  value="'.$res[coordinator_id].'" 	class="idls object" '.$disabled.'></td>
												<td><input style="width: 200px;" id="elva"          value="'.$res[elva_status].'" 		class="idls object" '.$disabled.'></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 520px;"><label>კოოდინატორის შენიშვნა</label></td>
												<td style="width: 280px;"><label for="oder_date">ქვითრის გაგზავნის დღე</label></td>
											</tr>
											<tr>
												<td>
													<textarea  style="width: 444px; resize: none; height:80px;" id="k_coment" class="idle" name="content" cols="300" '.$disabled.'>'.$res[coordinator_comment].'</textarea>
												</td>
												<td><input style="width: 200px;" id="oder_date" value="'.$res[oder_send_date].'" 	class="idls object" '.$disabled.'></td>
											</tr>
										</table>
							        </fieldset>
							</fieldset>
							</div>
					<div style="float: right;  width: 355px;">
					<fieldset>
					<legend>გაყიდვა</legend>
		                <table style="border:2px solid #85B1DE; width:100%;">
		                   		<tr style="background:#F2F2F2; ">
									<th style="width:7%; padding:5px; border:1px solid #85B1DE;">#</th>
									<th style="border:1px solid #85B1DE; padding:5px;">თარიღი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">მომხმარებელი</th>
									<th style="width:12%; padding:5px; border:1px solid #85B1DE;">ფასი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">წიგნები</th>
								</tr>';

		$query_list1 = mysql_fetch_row(mysql_query("SELECT 	product_ids,
															gift_ids
												  FROM 		`task_scenar`
												  WHERE 	task_detail_id = '$res[task_id]'"));
		$cvladi = $query_list1[0];
		$query11 = mysql_query("SELECT 	`name`,`price`,`id`
							  FROM 		`production`
							  WHERE 	`id` in ($cvladi)");

		while ($row_prod1 = mysql_fetch_row($query11)) {
			$number = $row_prod1[2];
			$book_name = $row_prod1[0];
			$book_price = $row_prod1[1];

			$data['page'][0] .= '<tr style="background: #FEFEFE">
									<td style="border:1px solid #85B1DE; padding:2px;">'.$number.'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$res[call_date].'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$res[name_surname].'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$book_price.'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$book_name.'</td>
								</tr>';
		}
	 $data['page'][0] .= '</table>
					</fieldset>

			 		<fieldset>
							<legend>საჩუქარი</legend>
				                <table style="border:2px solid #85B1DE; width:100%;">
				                   		<tr style="background:#F2F2F2; ">
											<th style="width:7%; padding:5px; border:1px solid #85B1DE;">#</th>
											<th style="border:1px solid #85B1DE; padding:5px;">თარიღი</th>
											<th style="border:1px solid #85B1DE; padding:5px;">მომხმარებელი</th>
											<th style="width:12%; padding:5px; border:1px solid #85B1DE;">ფასი</th>
											<th style="border:1px solid #85B1DE; padding:5px;">წიგნები</th>
										</tr>';

				$query_list = mysql_fetch_row(mysql_query("SELECT 	product_ids,
																	gift_ids
														  FROM 		`task_scenar`
														  WHERE 	task_detail_id = '$res[task_id]'"));
				$cvladi1 = $query_list[1];
				$query1 = mysql_query("SELECT 	`name`,`price`,`id`
									  FROM 		`production`
									  WHERE 	`id` in ($cvladi1)");

				while ($row_prod = mysql_fetch_row($query1)) {
					$number1 = $row_prod[2];
					$book_name1 = $row_prod[0];
					$book_price1 = $row_prod[1];

					$data['page'][0] .= '<tr style="background: #FEFEFE">
											<td style="border:1px solid #85B1DE; padding:2px;">'.$number1.'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$res[call_date].'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$res[name_surname].'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$book_price1.'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$book_name1.'</td>
										</tr>';
				}
			 $data['page'][0] .= '</table>
					</fieldset>
					</div>
				    </div>
								<input type="hidden" id="id" value="'.$_REQUEST[id].'" />
			</div>
		</fieldset></div>';
			   	break;
   	case 'save_dialog' :
   		$op_id = mysql_fetch_row(mysql_query("	SELECT users.id 
													FROM persons
													JOIN users ON persons.id = users.person_id
													WHERE persons.`name` = '$_REQUEST[op_id]'"));
   		
   		$per_id = mysql_fetch_row(mysql_query("	SELECT id
										   		FROM shipping
										   		WHERE `name` = '$_REQUEST[period]'"));
   		
   		
		mysql_query("UPDATE `elva_sale` SET 
							`status`				='$_REQUEST[status]', 
							`oder_send_date`		='$_REQUEST[oder_date]', 
							`coordinator_id`		='$_REQUEST[cooradinator]', 
							`coordinator_comment`	='$_REQUEST[k_coment]', 
							`elva_status`			='$_REQUEST[elva]',
							`person_id`				='$_REQUEST[person_id]', 
							`name_surname`			='$_REQUEST[name_surname]', 
							`mail`					='$_REQUEST[mail]', 
							`address`				='$_REQUEST[addres]', 
							`phone`					='$_REQUEST[phone]', 
							`phone1`				='$_REQUEST[phone1]', 
							`period`				='$per_id[0]', 
							`books`					='$_REQUEST[book]', 
							`call_date`				='$_REQUEST[date]', 
							`sum_price`				='$_REQUEST[sum_price]', 
							`callceenter_comment`	='$_REQUEST[c_coment]', 
							`operator_id`			='$op_id[0]'
					WHERE (`id`='$_REQUEST[id]')");
   		break;
	default:
		$error = 'Action is Null';
}


$data['error'] = $error;

echo json_encode($data);

?>