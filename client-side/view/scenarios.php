<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/scenarios.action.php";		//server side folder url
		var tbName	= "tabs";												//tabs name
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		    	
		$(document).ready(function () {        	
			GetTabs(tbName);
			LoadTable();	
						
			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");			
			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
			
		});

        
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 2, "", 0, "", 1, "desc");
    		
		}
		function LoadDialog(){
			var id		= $("#template_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 800, "auto", "");
			$(".done").button({
	            
		    });
			$(".next").button({
	            
		    });
			$(".back").button({
	            
		    });
			$("#add_button_product").button({
	            
		    });
			$("#add_button_gift").button({
			    
			});
			$("#complete").button({
			    
			});
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act	="save_template";
	    	param.id	= $("#template_id").val();
	    	param.p1		= $("#p1").val();
	    	param.p2		= $("#p2").val();
	    	param.p3		= $("#p3").val();
	    	param.p4		= $("#p4").val();
	    	param.p5		= $("#p5").val();
	    	param.p6		= $("#p6").val();
	    	param.p7		= $("#p7").val();
	    	param.p8		= $("#p8").val();
	    	param.p9		= $("#p9").val();
	    	param.p10		= $("#p10").val();
	    	param.p11		= $("#p11").val();
	    	param.p12		= $("#p12").val();
	    	param.p13		= $("#p13").val();
	    	param.p14		= $("#p14").val();
	    	param.p15		= $("#p15").val();
	    	param.p16		= $("#p16").val();
	    	param.p17		= $("#p17").val();
	    	param.p18		= $("#p18").val();
	    	param.p19		= $("#p19").val();
	    	param.p20		= $("#p20").val();
	    	
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

	    function seller(id){
			if(id == '0'){
				$('#seller-0').removeClass('dialog_hidden');
	            $('#0').addClass('seller_select');
	            $('#seller-1').addClass('dialog_hidden');
	            $('#seller-2').addClass('dialog_hidden');
	            $('#1').removeClass('seller_select');
	            $('#2').removeClass('seller_select');
			}else if(id == '1'){
				$('#seller-1').removeClass('dialog_hidden');
	            $('#1').addClass('seller_select');
	            $('#seller-0').addClass('dialog_hidden');
	            $('#seller-2').addClass('dialog_hidden');
	            $('#0').removeClass('seller_select');
	            $('#2').removeClass('seller_select');
			}else if(id == '2'){
				$('#seller-2').removeClass('dialog_hidden');
	            $('#2').addClass('seller_select');
	            $('#seller-1').addClass('dialog_hidden');
	            $('#seller-0').addClass('dialog_hidden');
	            $('#1').removeClass('seller_select');
	            $('#0').removeClass('seller_select');
			}
		}

	    function research(id){
			if(id == 'r0'){
				$('#research-0').removeClass('dialog_hidden');
	            $('#r0').addClass('seller_select');
	            $('#research-1').addClass('dialog_hidden');
	            $('#research-2').addClass('dialog_hidden');
	            $('#r1').removeClass('seller_select');
	            $('#r2').removeClass('seller_select');
			}else if(id == 'r1'){
				$('#research-1').removeClass('dialog_hidden');
	            $('#r1').addClass('seller_select');
	            $('#research-0').addClass('dialog_hidden');
	            $('#research-2').addClass('dialog_hidden');
	            $('#r0').removeClass('seller_select');
	            $('#r2').removeClass('seller_select');
			}else if(id == 'r2'){
				$('#research-2').removeClass('dialog_hidden');
	            $('#r2').addClass('seller_select');
	            $('#research-1').addClass('dialog_hidden');
	            $('#research-0').addClass('dialog_hidden');
	            $('#r1').removeClass('seller_select');
	            $('#r0').removeClass('seller_select');
			}
		}

	   
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 1024px; margin: 0 auto;">
        <div id="container">        	
            <div id="dynamic">
            	<h2 align="center">შაბლონები</h2>
            	<div id="button_area">
        			<!--button id="add_button">დამატება</button>
        			<button id="delete_button">წაშლა</button-->
        		</div>
                <table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%;">დასახელება</th>
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
                            	<input type="checkbox" name="check-all" id="check-all">
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="სცენარები">
    	<!-- aJax -->
	</div>
</body>
</html>