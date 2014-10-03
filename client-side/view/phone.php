<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/phone.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "import_dialog";	
		var tbName		= "tabs";											//form name
		
		    	
		$(document).ready(function () {    
			GetTabs(tbName);     	
			LoadTable();
				
			$("#excel_template").button({
	            
		    });

			$("#back_1000").button({
	            
		    });
		    
			$("#next_1000").button({
			    
			});

			$("#back_1000_all").button({
	            
		    });
		    
			$("#next_1000_all").button({
			    
			});

			$("#back_1000_inc").button({
	            
		    });
		    
			$("#next_1000_inc").button({
			    
			});
		    
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
         }
         
		 function GetTable2() {
             LoadTable2();
         }
        
		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest(tName, aJaxURL, "get_list_import", 13, "", 0, "", 1, "desc");
    		
		}
		
		function LoadTable1(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example1", aJaxURL, "get_list_incomming", 12, "", 0, "", 1, "desc");

		}
		
		function LoadTable2(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example2", aJaxURL, "get_list_all", 12, "", 0, "", 1, "desc");
    		
		}
		
		function LoadDialog(){
			var id		= $("#status_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 600, "auto", "");
		}

		
		
		$(document).on("click", "#next_1000", function () {
			var next = $('#mtvleli').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli').val(next_ch);
			
			GetDataTableTest(tName, aJaxURL, "get_list_import&pager="+next_ch, 13, "", 0, "", 1, "desc");
		});
		$(document).on("click", "#back_1000", function () {
			var back = $('#mtvleli').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli').val(back_ch);
			
			GetDataTableTest(tName, aJaxURL, "get_list_import&pager="+back_ch, 13, "", 0, "", 1, "desc");
		});

		$(document).on("click", "#next_1000_inc", function () {
			var next = $('#mtvleli_inc').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_inc').val(next_ch);
			
			GetDataTableTest("example1", aJaxURL, "get_list_incomming&pager="+next_ch, 12, "", 0, "", 1, "desc");
		});
		$(document).on("click", "#back_1000_inc", function () {
			var back = $('#mtvleli_inc').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_inc').val(back_ch);
			
			GetDataTableTest("example1", aJaxURL, "get_list_incomming&pager="+back_ch, 12, "", 0, "", 1, "desc");
		});

		$(document).on("click", "#next_1000_all", function () {
			var next = $('#mtvleli_all').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_all').val(next_ch);
			
			GetDataTableTest("example2", aJaxURL, "get_list_all&pager="+next_ch, 12, "", 0, "", 1, "desc");
		});
		$(document).on("click", "#back_1000_all", function () {
			var back = $('#mtvleli_all').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_all').val(back_ch);
			
			GetDataTableTest("example2", aJaxURL, "get_list_all&pager="+back_ch, 12, "", 0, "", 1, "desc");
		});
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act				="up_phone_base";
	    	param.id				= $("#status_id").val();
	    	param.phone1			= $("#phone1").val();
	    	param.phone2			= $("#phone2").val();
	    	param.first_last_name	= $("#first_last_name").val();
	    	param.person_n			= $("#person_n").val();
	    	param.addres			= $("#addres").val();
	    	param.city				= $("#city").val();
	    	param.mail				= $("#mail").val();
	    	param.born_day			= $("#born_day").val();
	    	param.sorce				= $("#sorce").val();
	    	param.person_status		= $("#person_status").val();
	    	param.note				= $("#note").val();
	    	
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

	    $(document).on("click", "#excel_template", function () {
			SaveToDisk('server-side/view/satelefonoBazebi.xls', 'satelefonoBazebi.xls');
	    });
	    
	    function SaveToDisk(fileURL, fileName) {
	        // for non-IE
	        if (!window.ActiveXObject) {
	            var save = document.createElement('a');
	            save.href = fileURL;
	            save.target = '_blank';
	            save.download = fileName || 'unknown';

	            var event = document.createEvent('Event');
	            event.initEvent('click', true, true);
	            save.dispatchEvent(event);
	            (window.URL || window.webkitURL).revokeObjectURL(save.href);
	        }
		     // for IE
	        else if ( !! window.ActiveXObject && document.execCommand)     {
	            var _window = window.open(fileURL, "_blank");
	            _window.document.close();
	            _window.document.execCommand('SaveAs', true, fileName || fileURL)
	            _window.close();
	        }
	    }
	    
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
            	<div id="button_area" style="margin-top: 20px;">
	        		<div class="file-uploader">
    					 <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
    					 <button id="choose_button" >აირჩიეთ ფაილი</button>
    					 <button id="delete_button" >წაშლა</button>
    					 <button id="excel_template" >ექსელის შაბლონი</button>
    					 <button id="back_1000" > << 1000 </button>
    					 <input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli" value="0">
    					 <button id="next_1000" > 1000 >> </button>
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
            	<div id="button_area" style="margin-top: 20px;">
	        		<button id="back_1000_inc" > << 1000 </button>
	        		<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_inc" value="0">
    				<button id="next_1000_inc" > 1000 >> </button>
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
            	<div id="button_area" style="margin-top: 20px;">
	        		<button id="back_1000_all" > << 1000 </button>
	        		<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_all" value="0">
    				<button id="next_1000_all" > 1000 >> </button>
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
<div id="import_dialog" class="form-dialog" title="ზარები იმპორტირებული ფაილიდან">
<!-- aJax -->
</div>
</body>
</html>







