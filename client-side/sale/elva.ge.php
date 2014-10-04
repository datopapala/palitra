<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/sale/elva.ge.action.php";	//server side folder url
		var tName	= "example";											//table name

		$(document).ready(function () {

			

			GetDate("search_start");
			GetDate("search_end");
			
			var start 	= $("#search_start").val();
			var end 	= $("#search_end").val();
			LoadTable(start,end);
			SetEvents('','','',tName,'in_page',aJaxURL);
		});

		function LoadDialog(fname){
			GetDialog("in_page","1150","auto");

			 $("#save-dialog").on("click",function(){
				
				param = new Object();
				param.act = "save_dialog";

				// elva.ge
				param.id=$("#id").val();
				param.oder_date=$("#oder_date").val();
				param.status=$("#status").val();
				param.cooradinator=$("#cooradinator").val();
				param.k_coment=$("#k_coment").val();
				param.elva=$("#elva").val();

				// all user
				param.person_id		=$("#person_id").val();
				param.name_surname	=$("#name_surname").val();
				param.mail			=$("#mail").val();
				param.phone			=$("#phone").val();
				param.phone1		=$("#phone1").val();
				param.addres		=$("#addres").val();
				param.period		=$("#period").val();
				param.book			=$("#book").val();
				param.date			=$("#date").val();
				param.op_id			=$("#op_id").val();
				param.sum_price		=$("#sum_price").val();
				param.c_coment		=$("#c_coment").val();
				

				$.getJSON(
					aJaxURL, param, function(data) {
						var start 	= $("#search_start").val();
						var end 	= $("#search_end").val();
						LoadTable(start, end);
					});
				$('#'+fname).dialog("close");
				});
			 GetDate("oder_date");
			} ;
		function LoadTable(start, end){
			var total=[10];
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 20, "start=" + start + "&end=" + end, 0, "", 0, "desc", total);
		}

		$(document).on("change", "#search_start", function () {
	    	var start	= $(this).val();
	    	var end		= $("#search_end").val();
	    	LoadTable(start, end);
	    });
	    
	    $(document).on("change", "#search_end", function () {
	    	var start	= $("#search_start").val();
	    	var end		= $(this).val();
	    	LoadTable(start, end);
	    });

    </script>
    <style type="text/css">

    </style>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row">
    <h2 style="position: fixed; top: 80px; margin-left: 50%;">ჩანაწერები</h2>
        <div id="container" style="width: 100%; margin-top: 70px;"  >
            <div id="dynamic" style="width: 200%; ">
            <div id="button_area" style="position: fixed; top: 80px;">
	            	<div class="left" style="width: 175px;">
	            		<label for="search_start" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input style="width: 80px; height: 16px; margin-left: 5px;" type="text" name="search_start" id="search_start" class="inpt left"/>
	            	</div>
	            	<div class="right" style="">
	            		<label for="search_end" class="left" style="margin: 5px 0 0 9px;">დასასრული</label>
	            		<input style="width: 80px; height: 16px; margin-left: 5px;" type="text" name="search_end" id="search_end" class="inpt right" />
            		</div>	
            	</div>
                <table class="display" id="example" >
                    <thead>
                        <tr id="datatable_header" class="search_header">
                        	<th style="width: 150px">id</th>
							<th style="width: 150px">პირადი ნომ</th>
							<th style="width: 150px">სახელი და გვარი</th>
							<th style="width: 150px">მეილი</th>
							<th style="width: 150px">მისამართი</th>
							<th style="width: 150px">ტელეფონი 1</th>
							<th style="width: 150px">ტელეფონი 2</th>
							<th style="width: 150px">პერიოდი</th>
							<th style="width: 150px">გამოცემა</th>
							<th style="width: 150px">ქოლ-ცენტრის დარეკვის თარიღი</th>
							<th style="width: 150px">გადასახდელი თანხა</th>
							<th style="width: 150px">ქოლცენტრის კომენტარი</th>
							<th style="width: 150px">ოპერატორი</th>
							<th style="width: 150px">ქვითრის გაგზავნის დღე</th>
							<th style="width: 150px">სტატუსი</th>
							<th style="width: 150px">კოორდინატორი</th>
							<th style="width: 150px">კოოდინატორის შენიშვნა</th>
							<th style="width: 150px">ნინო (ელვა)</th>
                        </tr>
                      </thead>
					  <thead>
                        <tr>
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init"></th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init"  />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init"  />
                            </th>
                            <th>
                                <input type="text" name="search_phone" value="ფილტრი" class="search_init"/>
                            </th>
                           

                        </tr>
                    </thead>
                    <tfoot>
                        <tr id="datatable_header" class="search_header">
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px; text-align: right;">ჯამი :<br>სულ :</th>
							<th style="width: 150px; text-align: left;"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
							<th style="width: 150px"></th>
                        </tr>
                        </tfoot>
                </table>
            </div>
            <div class="spacer">
            </div>
        </div>
    </div>
<div id='in_page' class="form-dialog"></div>
    </body>
