<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/phone.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";	
		var tbName		= "tabs";											//form name
		    	
		$(document).ready(function () {    
			GetTabs(tbName);     	
			LoadTable();	
		
			GetButtons("choose_button", "delete_button");
			/* Add Button ID, Delete Button ID */
			SetEvents("", "delete_button", "check-all", tName, fName, aJaxURL);
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2()
            }
        });

		function GetTable0() {
            LoadTable();
        }
        
		 function GetTable1() {
             LoadTable1();
  			 SetEvents("add_button", "", "", "example1", fName, aJaxURL);
         }
         
		 function GetTable2() {
             LoadTable2();
         }
        
		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list_import", 13, "", 0, "", 1, "desc");
    		
		}
		
		function LoadTable1(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example1", aJaxURL, "get_list_incomming", 12, "", 0, "", 1, "desc");

		}
		
		function LoadTable2(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example2", aJaxURL, "get_list_all", 12, "", 0, "", 1, "desc");
    		
		}
		
		function LoadDialog(){
			var id		= $("#status_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 600, "auto", "");
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act			="save_status";
	    	param.id			= $("#status_id").val();
	    	param.name			= $("#name").val();
	    	param.call_status	= $("#call_status").val();
	    	
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

	    $(document).on("click", "#choose_button", function () {
		    $("#choose_file").click();
		});

	    $(document).on("change", "#choose_file", function () {
	    	var file		= $(this).val();
		    var name		= uniqid();
		    var path		= "../../media/uploads/images/client/";

		    var ext = file.split('.').pop().toLowerCase();
	        if($.inArray(ext, ['xls']) == -1) { //echeck file type
	        	alert('This is not an allowed file type.');
                this.value = '';
	        }else{
	        	img_name = name + "." + ext;
	        //	$("#choose_button").button("disable");
	        	$.ajaxFileUpload({
	    			url: 'server-side/view/import/import.php',
	    			secureuri: false,
	    			fileElementId: "choose_file",
	    			dataType: 'json',
	    			data:{
	    				task_id:$("#id").val(),
						act: "upload_file",
						path: path,
						file_name: name,
						type: ext
					},
					complete: function(data){
						location.reload();
    				},

    			});

	        }
		});
	    
    </script>
</head>

<body>
<div id="tabs" style="width: 98%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">ბაზები  იმპორტირებული ფაილიდან</a></li>
			<li><a href="#tab-1">ბაზები  შემომავალი ზარებიდან</a></li>
			<li><a href="#tab-2">სრული ბაზა</a></li>
		</ul>
		<div id="tab-0">
            	<h2 align="center">ბაზები  იმპორტირებული ფაილიდან</h2>
            	<div id="button_area">
	        		<div class="file-uploader">
    					 <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
    					 <button id="choose_button" >აირჩიეთ ფაილი</button>
    					 <button id="delete_button" >წაშლა</button>
    				</div>
	        	</div>
            	<table class="display" id="example" >
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">ტელეფონი 1</th>
                            <th style="width: 50%;">ტელეფონი 2</th>
                            <th style="width: 50%;">სახელი/ <br> გვარი</th>
                            <th style="width: 50%;">პირადი N/<br> საიდ. კოდი</th>
                            <th style="width: 50%;">მისამართი</th>
                            <th style="width: 50%;">ქალაქი</th>
                            <th style="width: 50%;">ელ-ფოსტა</th>
                            <th style="width: 50%;">დაბ. წელი</th>
                            <th style="width: 50%;">განყოფილება</th>
                            <th style="width: 50%;">ფორმირების<br>თარიღი</th>
                            <th style="width: 50%;">ფიზიკური/<br>იურიდიული</th>
                            <th style="width: 50%;">შენიშვნა</th>
                            <th style="width: 15%;">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="checkbox" name="check-all" id="check-all">
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tab-1">
            	<h2 align="center">ბაზები  შემომავალი ზარებიდან</h2>
            	<div id="button_area">
	        		
	        	</div>
            	<table class="display" id="example1" >
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">ტელეფონი 1</th>
                            <th style="width: 50%;">ტელეფონი 2</th>
                            <th style="width: 50%;">სახელი/ <br> გვარი</th>
                            <th style="width: 50%;">პირადი N/<br> საიდ. კოდი</th>
                            <th style="width: 50%;">მისამართი</th>
                            <th style="width: 50%;">ქალაქი</th>
                            <th style="width: 50%;">ელ-ფოსტა</th>
                            <th style="width: 50%;">დაბ. წელი</th>
                            <th style="width: 50%;">განყოფილება</th>
                            <th style="width: 50%;">ფორმირების<br>თარიღი</th>
                            <th style="width: 50%;">ფიზიკური/<br>იურიდიული</th>
                            
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tab-2">
            	<h2 align="center">სრული ბაზა</h2>
            	<div id="button_area">
	        		
	        	</div>
            	<table class="display" id="example2" >
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">ტელეფონი 1</th>
                            <th style="width: 50%;">ტელეფონი 2</th>
                            <th style="width: 50%;">სახელი/ <br> გვარი</th>
                            <th style="width: 50%;">პირადი N/<br> საიდ. კოდი</th>
                            <th style="width: 50%;">მისამართი</th>
                            <th style="width: 50%;">ქალაქი</th>
                            <th style="width: 50%;">ელ-ფოსტა</th>
                            <th style="width: 50%;">დაბ. წელი</th>
                            <th style="width: 50%;">განყოფილება</th>
                            <th style="width: 50%;">ფორმირების<br>თარიღი</th>
                            <th style="width: 50%;">ფიზიკური/<br>იურიდიული</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
</body>
</html>







