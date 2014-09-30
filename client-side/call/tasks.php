<head>
<style type="text/css">
.download {

	background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#599bb3', endColorstr='#408c99',GradientType=0);
	background-color:#599bb3;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:14px;
	
	text-decoration:none;
	text-shadow:0px 1px 0px #3d768a;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #408c99), color-stop(1, #599bb3));
	background:-moz-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-webkit-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-o-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-ms-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#408c99', endColorstr='#599bb3',GradientType=0);
	background-color:#408c99;
}
.myButton:active {
	position:relative;
	top:1px;
}
.hidden{
	display: none;
}
</style>
<script type="text/javascript">
		var aJaxURL		= "server-side/call/tasks/tasks_tab0.action.php";		//server side folder url
		var aJaxURL1	= "server-side/call/tasks/tasks_tab1.action.php";		//server side folder url
		var aJaxURL2	= "server-side/call/tasks/tasks_tab2.action.php";		//server side folder url
		var aJaxURL3	= "server-side/call/tasks/tasks_tab3.action.php";		//server side folder url
		var aJaxURL4	= "server-side/call/tasks/tasks_tab4.action.php";		//server side folder url
		var aJaxURL7	= "server-side/call/tasks/tasks_tab7.action.php";		//server side folder url
		var aJaxURL5	= "server-side/call/tasks/subtasks/tasks_tab1.action.php";		//server side folder url
		var aJaxURL6	= "server-side/call/tasks/subtasks/tasks_tab2.action.php";		//server side folder url
        var seoyURL		= "server-side/seoy/seoy.action.php";					//server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";	
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () {     
			GetTabs(tbName);   	
			GetTable0();
			SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");
			GetButtons("add_button","add_responsible_person");
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2();
            }else if(tab == 3){
            	GetTable3();
            }else if(tab == 4){
            	GetTable4();
            }
        });

		function GetTable0() {
            LoadTable0();
            SetEvents("add_button", "", "", "example0", fName, aJaxURL);
        }
        
		 function GetTable1() {
             LoadTable1();
             $("#add_button_n").button({
  	            
  		     });
             SetEvents("", "", "", "example1", "task_dialog", aJaxURL1);
         }
         
		 function GetTable2() {
             LoadTable2();
             SetEvents("", "", "", "example2", "task_dialog", aJaxURL1);
         }
         
		 function GetTable3() {
             LoadTable3();
             SetEvents("", "", "", "example3", "task_dialog", aJaxURL1);
         }

		 function GetTable4() {
             LoadTable7();
             SetEvents("", "", "", "all_task", "task_dialog", aJaxURL1);
         }

		 function LoadTable0(){		
			 SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");	
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example0", aJaxURL, "get_list", 9, "", 0, "", 1, "asc", "");
		}
			
		function LoadTable1(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example1", aJaxURL1, "get_list", 10, "", 0, "", 1, "asc", "");
		}

		function LoadTable2(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example2", aJaxURL2, "get_list",10, "", 0, "", 1, "asc", "");
		}
		
		function LoadTable3(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example3", aJaxURL3, "get_list", 10, "", 0, "", 1, "asc", "");
		}
		
		function LoadTable4(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("example4", aJaxURL4, "get_list&id="+$("#id").val(), 11, "", 0, "", 1, "asc", "");
		}
		function LoadTable5(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("sub1", aJaxURL5, "get_list", 7, "", 0, "", 1, "asc", "");
		}
		function LoadTable6(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("sub2", aJaxURL6, "get_list", 7, "", 0, "", 1, "asc", "");
		}
		function LoadTable7(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("all_task", aJaxURL7, "get_list", 10, "", 0, "", 1, "asc", "");
		}

		//SeoYyy
		$(document.body).click(function (e) {
        	$("#send_to").autocomplete("close");
        });

		$(document).on("click", "#check-all-in", function () {
			if ($('#check-all-in').is(':checked')) {
				$( ".check" ).prop( "checked", true );
	    	}else{
	    		$( ".check" ).prop( "checked", false );
	    	}
		});
		
		$(document).on("click", "#save-printer", function () {
	       	 var data = $(".check:checked").map(function () {
	  	        return this.value;
	  	    }).get();
	  	    
	  	    var letters = [];
	  	    
	  	    for (var i = 0; i < data.length; i++) {
	  	    	letters.push(data[i]);        
	  	    }
	      	param = new Object();
	      	param.act	= "change_responsible_person";
	      	param.lt	= letters;
	  	    param.rp	= $("#responsible_person").val();
	
	  	    var link	=  GetAjaxData(param);
	  	    
	  	    if(param.rp == "0"){
	  		    alert("აირჩიეთ პასუხისმგებელი პირი!");
	  		}else if(param.ci == "0"){
	  		    alert("აირჩიეთ ავტომობილი");		
	  		}else{	    
	  	        $.ajax({
	  	            url: aJaxURL,
	  	            type: "POST",
	  	            data: link,
	  	            dataType: "json", 
	  	            success: function (data) {
	  	                if (typeof (data.error) != "undefined") {
	  	                    if (data.error != "") {
	  	                        alert(data.error);
	  	                    }else{
	  	                        $("#add-responsible-person").dialog("close");
	  	                        LoadTable0();
	  	                    }
	  	                }
	  	            }
	  	        });
	  		}
      });

        function LoadDialog(fName){
            //alert(form);
			switch(fName){
				case "add-edit-form":
					var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-dialog"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        } 
				    };
					GetDialog("add-edit-form", 790, "auto", buttons);
					GetDateTimes("done_start_time");
					GetDateTimes("done_end_time");

					
					
					$(document).on("change", "#task_type_id",function(){
						var cat_id = $("#task_type_id").val();
						if(cat_id == 1 || cat_id == 2){
							$("#additional").removeClass('hidden');
							$("#task_department_id").val(40);
						}else{
							$("#task_department_id").val(0);
						}
						
					});
					
					
					
				break;	
				case "add-edit-form1":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog1"
				        }, 
						"save": {
				            text: "შენახვა",
				            id: "save-dialog1"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form1", 1150, "auto", buttons);
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
					LoadTable5();
					LoadTable6();
					GetDateTimes("send_time");
				break;	
				case "task_dialog":
					var buttons = {
				        "save": {
				            text: "შენახვა",
				            id: "save_my_task",
				            click: function () {
								
				            	$.ajax({
							        url: aJaxURL1,
								    data: "act=save_my_task&id="+$("#id_my_task").val()+"&status_id="+$("#status_id").val()+"&problem_comment="+$("#problem_comment").val(),
							        success: function(data) {       
										if(typeof(data.error) != "undefined"){
											if(data.error != ""){
												alert(data.error);
											}else{
												LoadTable1();
												LoadTable2();
												LoadTable3();
												CloseDialog("task_dialog");
											}
										}
								    }
							    	});		            
				            }
				        },
						"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				                $(this).dialog("close");
				            }
				        }
				};
				GetDialog("task_dialog", 790, "auto", buttons);
				break;
				case "add-edit-form2":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog2"
				        }, 
				        "save": {
				            text: "შენახვა",
				            id: "save-dialog2"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form2", 1060, "auto", buttons);
					
			    break;
			}
			LoadTable4();
			var id = $("#incomming_id").val();
			var cat_id = $("#category_parent_id").val();
	
			if(id != '' && cat_id == 407){
				$("#additional").removeClass('hidden');
			}
	
			GetDateTimes("planned_end_date");
			
			$( ".calls" ).button({
			      icons: {
			        primary: " ui-icon-contact"
			      }
			});
			$("#choose_button").button({
	            
		    });
			$("#choose_base").button({
	            
		    });
			$("#add_button_pp").button({
	            
		    });
		   
		}

		function LoadDialog1(){
			var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save-printer",
			            click: function () {
			            	Change_person();			            
			            }
			        },
					"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			};
			GetDialog("add-responsible-person", 280, "auto", buttons);
		}

		$(document).on("click", "#incomming_base", function () {
			$("#hidden_base").val('1');
			SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
			GetDataTable("base", aJaxURL, "get_list_base", 12, "", 0, "", 1, "asc", "");
		});

		$(document).on("click", "#phone_base", function () {
			$("#hidden_base").val('2');
			SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
			GetDataTable("base", aJaxURL, "get_list_base_phone", 12, "", 0, "", 1, "asc", "");
		});
		
		 $(document).on("click", "#add_button_pp", function () {
			 param 			= new Object();
			 param.act		= "get_task";
			 $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								var buttons = {
								        "save": {
								            text: "შენახვა",
								            id: "save-task",
								            click: function () {
								            	add_task();			            
								            }
								        },
										"cancel": {
								            text: "დახურვა",
								            id: "cancel-dialog",
								            click: function () {
								                $(this).dialog("close");
								            }
								        }
								};
								$("#add_task").html(data.page);
								GetDialog("add_task", 400, "auto", buttons);
								
							}
						}
				    }
			    });

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
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {

			param 			= new Object();
			param.act			= "save_outgoing";
			
			param.cur_date				= $("#cur_date").val();
	    	param.done_start_time		= $("#done_start_time").val();
	    	param.done_end_time			= $("#done_end_time").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.persons_id			= $("#persons_id").val();
			param.task_department_id	= $("#task_department_id").val();
			param.task_comment			= $("#task_comment").val();
			param.priority_id			= $("#priority_id").val();

			if(param.task_type_id < 3){
		 		if(param.template_id == 0){
			 		alert('ამოირჩიეთ სცენარი');
		 		}else{
			 		$.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								LoadTable0();
								CloseDialog("add-edit-form");
							}
						}
				    }
			    	});
		 		}
			}else{
				$.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								LoadTable0();
								CloseDialog("add-edit-form");
							}
						}
				    }
			    	});
			}
		});

		
	    $(document).on("click", "#save-dialog1", function () {
		   
			param 				= new Object();
 			param.act			= "save_outgoing";
		    	
 			param.id					= $("#id").val();
			param.id1					= $("#id1").val();
	    	param.call_date				= $("#call_date").val();
	    	param.problem_date			= $("#problem_date").val();
			param.persons_id			= $("#persons_id").val();
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			param.planned_end_date		= $("#planned_end_date").val();
			param.fact_end_date			= $("#fact_end_date").val();
			param.call_duration			= $("#call_duration").val();
			param.phone					= $("#phone").val();
			param.comment				= $("#comment").val();
			param.problem_comment		= $("#problem_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();
	 
 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable1();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});
	    $(document).on("click", "#done-dialog1", function () {
			   
			param 				= new Object();
 			param.act			= "done_outgoing";
		    	
 			param.id					= $("#id").val();
			param.id1					= $("#id1").val();
	    	param.call_date				= $("#call_date").val();
	    	param.problem_date			= $("#problem_date").val();
			param.persons_id			= $("#persons_id").val();
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			param.planned_end_date		= $("#planned_end_date").val();
			param.fact_end_date			= $("#fact_end_date").val();
			param.call_duration			= $("#call_duration").val();
			param.phone					= $("#phone").val();
			param.comment				= $("#comment").val();
			param.problem_comment		= $("#problem_comment").val();
	    	param.rand_file				= rand_file;
	    	param.file_name				= file_name;
	    	param.hidden_inc			= $("#hidden_inc").val();
	 
 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable1();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});

	    $(document).on("click", "#choose_base", function () {
	    	param 				= new Object();
 			param.act			= "phone_base_dialog";
 			
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							$("#phone_base_dialog").html(data.page);
							var buttons = {
							        "save": {
							            text: "შენახვა",
							            id: "save_phone_base",
							            
							        },
									"cancel": {
							            text: "დახურვა",
							            id: "cancel-dialog",
							            click: function () {
							                $(this).dialog("close");
							            }
							        }
							};
							 $("#incomming_base").button({
								    
								});
								$("#phone_base").button({
								    
								});
							GetDialog("phone_base_dialog", 1260, "auto", buttons);
							SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
							GetDataTable("base", aJaxURL, "get_list_base_phone", 12, "", 0, "", 1, "asc", "");
						}
					}
			    }
			});
	    	
	    });

	    $(document).on("click", "#save_phone_base", function () {
			
		    	
 			
 			var data = $(".check:checked").map(function () { //Get Checked checkbox array
 	            return this.value;
 	        }).get();

 	        for (var i = 0; i < data.length; i++) {
 	        	param 				= new Object();
 	 			param.act			= "save_phone_base";
 	 			param.phone_base_id = data[i];
 	 			param.id			= $("#id").val();
 	 			param.hidden_base	= $("#hidden_base").val();
 	    	$.ajax({
 			        url: aJaxURL,
 				    data: param,
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								$("#phone_base_dialog").dialog("close");
 								LoadTable4();
 							}
						}
 				    }
 			});
 	        }
 		});
	    
	    $(document).on("click", "#save-dialog2", function () {
			param 				= new Object();
 			param.act			= "save_outgoing";
		    	
 			param.id					= $("#id").val();
			param.id1					= $("#id1").val();
	    	param.call_date				= $("#call_date").val();
	    	param.problem_date			= $("#problem_date").val();
			param.persons_id			= $("#persons_id").val();
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			param.planned_end_date		= $("#planned_end_date").val();
			param.fact_end_date			= $("#fact_end_date").val();
			param.call_duration			= $("#call_duration").val();
			param.phone					= $("#phone").val();
			param.comment				= $("#comment").val();
			param.problem_comment		= $("#problem_comment").val();
	 
 	    	$.ajax({
 			        url: aJaxURL2,
 				    data: param,
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								LoadTable2();
 								CloseDialog("add-edit-form2");
 							}
						}
 				    }
 			});
 		});
	    $(document).on("keydown", "#personal_pin", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {

            	param 			= new Object();
    		 	param.act		= "get_add_info";
    		 	param.pin		= $("#personal_pin").val();

    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#additional_info").html(data.info);
    						}
    					}
    			    }
    		    });
                
                event.preventDefault();
            }
        });
///

	    $(document).on("change", "#task_type_id",function(){

			if(this.value == 1 || this.value == 2){
				$("#additional").removeClass('hidden');
			}else{
				$("#additional").addClass('hidden');
			}
        });
///
	    
	    $(document).on("keydown", "#personal_id", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {

            	param 					= new Object();
    		 	param.act				= "get_add_info1";
    		 	param.personal_id		= $("#personal_id").val();

    	    	$.ajax({
    		        url: aJaxURL,
    			    data: param,
    		        success: function(data) {
    					if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							$("#additional_info").html(data.info1);
    						}
    					}
    			    }
    		    });
                
                event.preventDefault();
            }
        });
	    $(document).on("click", "#done-dialog2", function () {
			param 				= new Object();
 			param.act			= "done_outgoing";
		    	
 			param.id					= $("#id").val();
			param.id1					= $("#id1").val();
	    	param.call_date				= $("#call_date").val();
	    	param.problem_date			= $("#problem_date").val();
			param.persons_id			= $("#persons_id").val();
			param.task_type_id			= $("#task_type_id").val();
	    	param.priority_id			= $("#priority_id").val();
			param.planned_end_date		= $("#planned_end_date").val();
			param.fact_end_date			= $("#fact_end_date").val();
			param.call_duration			= $("#call_duration").val();
			param.phone					= $("#phone").val();
			param.comment				= $("#comment").val();
			param.problem_comment		= $("#problem_comment").val();
	 
 	    	$.ajax({
 			        url: aJaxURL2,
 				    data: param,
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								LoadTable2();
 								CloseDialog("add-edit-form2");
 							}
						}
 				    }
 			});
 		});
	 function SetPrivateEvents(add,check,formName){
		$(document).on("click", "#" + add, function () {    
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: "act=get_responsible_person_add_page",
	            dataType: "json",
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#" + formName).html(data.page);
	                        if ($.isFunction(window.LoadDialog)){
	                            //execute it
	                        	LoadDialog1();
	                        }
	                    }
	                }
	            }
	        });
	    });
		
	    $(document).on("click", "#" + check, function () {
	    	$("#" + tName + " INPUT[type='checkbox']").prop("checked", $("#" + check).is(":checked"));
	    });	
	}

	function add_task(formName){
    	param = new Object();
    	param.act			= "save_task";
    	param.id			= $("#id").val();
	    param.p_phone			= $("#p_phone").val();
	    param.p_person_n		= $("#p_person_n").val();
	    param.p_first_name		= $("#p_first_name").val();
	    param.p_mail			= $("#p_mail").val();
	    param.p_last_name		= $("#p_last_name").val();
	    param.p_person_status	= $("#p_person_status").val();
	    param.p_addres			= $("#p_addres").val();
	    param.p_b_day			= $("#p_b_day").val();
	    param.p_city_id			= $("#p_city_id").val();
	    param.p_family_id		= $("#p_family_id").val();
	    param.p_profesion		= $("#p_profesion").val();
	        
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: param,
	            dataType: "json", 
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#add_task").dialog("close");
	                        LoadTable4();
	                    }
	                }
	            }
	        });	    		
	}
	
	$(document).on("change", "#category_parent_id",function(){
 	 	param 			= new Object();
		 	param.act		= "sub_category";
		 	param.cat_id   	= this.value;
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#category_id").html(data.cat);
						}
					}
			    }
		    });

		if(this.value == 407){
			$("#additional").removeClass('hidden');
		}else{
			$("#additional").addClass('hidden');
		}
    });

	$(document).on("change", "#task_type_id_seller",function(){
		var task = $("#task_type_id_seller").val();
		if(task==1){
			$("#research").addClass('dialog_hidden');
			$("#seller").removeClass('dialog_hidden');
		}else{
			$("#seller").addClass('dialog_hidden');
			$("#research").removeClass('dialog_hidden');
		}
    });
    
	
	    $(document).on("keyup", "#req_time1, #req_time2", function() {
	        var val = $(this).val();
	        if(isNaN(val) || (val>60)){
		        
	         alert("მოცემულ ველში შეიყვანეთ მხოლოდ ციფრები");
	             val = val.replace(/[^0-9\.]/g,'');
	             if(val.split('.').length>2) 
	                 val =val.replace(/\.+$/,"");
	        }
	        $(this).val(val); 
	    });

	   

	    $(document).on("change", "#shabloni",function(){
	    		
	 	 		param 			= new Object();
			 	param.act		= "quest";
			 	param.shabloni   = $("#shabloni").val();
			 	
		    	$.ajax({
			        url: aJaxURL1,
				    data: param,
			        success: function(data) {
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								$("#quest").html(data.page);
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
								LoadTable5();
								LoadTable6();
								GetDateTimes("send_time");
								var task = $("#task_type_id_seller").val();
								if(task==1){
									$("#research").addClass('dialog_hidden');
									$("#seller").removeClass('dialog_hidden');
								}else{
									$("#seller").addClass('dialog_hidden');
									$("#research").removeClass('dialog_hidden');
								}
							}
						}
				    }
			    });
	    });

	    $(document).on("change", "#task_type_id_seller",function(){
    		
 	 		param 			= new Object();
		 	param.act		= "shablon";
		 	param.task_type_id_seller   = $("#task_type_id_seller").val();
		 	
	    	$.ajax({
		        url: aJaxURL1,
			    data: param,
		        success: function(data) {
					if(typeof(data.error) != 'undefined'){
						if(data.error != ''){
							alert(data.error);
						}else{
							$("#shabloni").html(data.page);
							
						}
					}
			    }
		    });
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
	    			url: 'import.php',
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
	    			success: function(data){alert("ghj");
	    				if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							alert("ghj");
    						}
    					}
    				},

    			});

	        }
		});
    </script>
</head>

<body>

<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">დავალების ფორმირება</a></li>
			<?php 
			if($_SESSION['USERID'] == 1){
				echo '<li><a href="#tab-1">გადაცემულია გასარკვევად</a></li>';
			}else{
				echo '<li><a href="#tab-1">ჩემი დავალებები</a></li>';
			}
			?>
			<li><a href="#tab-2">გარკვევის პროცესშია</a></li>
			<li><a href="#tab-3">მოგვარებულია</a></li>
			<li><a href="#tab-4">ყველა დავალება</a></li>
		</ul>
<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">დავალების ფორმირება</h2>
		            	<div id="button_area">
		            		<button id="add_button">დამატება</button>
	        				<button id="add_responsible_person">პ. პირის აქტივაცია</button>
	        			</div>
		                <table class="display" id="example0">
		                   <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:25px;;">#</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input type="checkbox" name="check-all" id="check-all-in"/>
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>

  <div id="tab-1">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გადაცემულია გასარკვევად</h2>
		            	
		                <table class="display" id="example1">
		                   <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
    <div id="tab-2">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გარკვევის პროცესშია</h2>
		                <table class="display" id="example2">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
  <div id="tab-3">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">მოგვარებულია</h2>
		                <table class="display" id="example3">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-4">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">მოგვარებულია</h2>
		                <table class="display" id="all_task">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
</div>

	<!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="დავალება">
	</div>
	
	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
	<div id="add_task" class="form-dialog" title="დავალების დამატება">
	<!-- aJax -->
	</div>
	<!-- jQuery Dialog -->
	<div id="add-edit-form2" class="form-dialog" title="გამავალი ზარი">
	<!-- aJax -->
	</div>
	
	<div id="add-responsible-person" class="form-dialog" title="პასუხისმგებელი პირი">
	<!-- aJax -->
	</div>
	<div id="phone_base_dialog" class="form-dialog" title="სატელეფონო ბაზა">
	<!-- aJax -->
	</div>
	<div id="task_dialog" class="form-dialog" title="სატელეფონო ბაზა">
	<!-- aJax -->
	</div>
</body>
	