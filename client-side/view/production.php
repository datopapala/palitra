<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/production.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name

		$(document).ready(function () {
			LoadTable();

			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");
			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
		});

		function LoadTable(){

			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 6, "", 0, "", 1, "desc");

		}

		function LoadDialog(){
			var id		= $("#priority_id").val();

			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 600, "auto", "");
		}

	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act							="save_priority";
	    	param.id							= $("#priority_id").val();
	    	param.name							= $("#name").val();
	    	param.genre_id						= $("#genre_id").val();
	    	param.production_category_id		= $("#production_category_id").val();
	    	param.par_id						= $("#parent_id").val();
	    	param.comment   					= $('#comment').val();
	    	param.decription 					= $('#decription').val();
	    	param.price 						= $('#price').val();


			if(param.name == ""){
				alert("შეავსეთ ველი!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable();
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}
		});


    </script>
 <style type="text/css">
 :nth-child(6){
	text-align: right;
 }
 </style>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 1024px; margin: 0 auto;">
        <div id="container">
            <div id="dynamic">
            	<h2 align="center">პროდუქტი</h2>
            	<div id="button_area">
        			<button id="add_button">დამატება</button>
        			<button id="delete_button">წაშლა</button>
        		</div>
                <table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">პროდუქტი</th>
                            <th style="width: 100%;">ჟანრი</th>
                            <th style="width: 100%;">კატეგორია</th>
                            <th style="width: 100%;">აღწერილობა</th>
                            <th style="width: 80px;">ფასი</th>
                        	<th class="check">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="" />
                            </th>
                          <th>
                            	<input type="checkbox" name="check-all" id="check-all">
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="პრიორიტეტები">
    	<!-- aJax -->
	</div>
</body>
</html>






