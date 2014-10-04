<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/sale/elva.ge.action.php";	//server side folder url
		var tName	= "example";											//table name

		$(document).ready(function () {

			LoadTable();
			SetEvents('','','',tName,'in_page',aJaxURL);
		});

		function LoadDialog(fname){
			GetDialog("in_page","1150","auto");

			 $("#save-dialog").on("click",function(){
				param = new Object();
				param.act = "save_dialog";
				param.id=$("#id").val();
				param.oder_date=$("#oder_date").val();
				param.status=$("#status").val();
				param.cooradinator=$("#cooradinator").val();
				param.k_coment=$("#k_coment").val();
				param.elva=$("#elva").val();

				$.getJSON(
					aJaxURL, param, function(data) {
					LoadTable();
					});
				$('#'+fname).dialog("close");
				});
			 GetDate("oder_date");
			} ;
		function LoadTable(){
			var total=[9];
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 19, "", 0, "", 1, "desc", total);
		}


    </script>
    <style type="text/css">

    </style>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row">
    <h2 style="position: fixed; top: 80px; margin-left: 50%;">ჩანაწერები</h2>
        <div id="container" style="width: 100%; margin-top: 70px;"  >
            <div id="dynamic" style="width: 200%; ">
                <table class="display" id="example" >
                    <thead>
                        <tr id="datatable_header" class="search_header">
							<th style="width: 150px">პირადი ნომ</th>
							<th style="width: 150px">სახელი და გვარი</th>
							<th style="width: 150px">მეილი</th>
							<th style="width: 150px">მისამართი</th>
							<th style="width: 150px">ტელეფონი</th>
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
							<th style="width: 150px; text-align: right;">ჯამი :<br>სულ :</th>
							<th style="width: 150px; text-align: left;"></th>
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
