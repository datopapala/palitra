<head>
	<script type="text/javascript">
		var aJaxURL		= "server-side/call/outgoing/outgoing_tab0.action.php";		//server side folder url
		var aJaxURL1	= "server-side/call/outgoing/outgoing_tab1.action.php";		//server side folder url
		var aJaxURL2	= "server-side/call/outgoing/outgoing_tab2.action.php";		//server side folder url
		var aJaxURL3	= "server-side/call/outgoing/outgoing_tab3.action.php";		//server side folder url
		var aJaxURL4	= "server-side/call/outgoing/outgoing_tab4.action.php";		//server side folder url
		var aJaxURL7	= "server-side/call/outgoing/outgoing_tab7.action.php";		//server side folder url
		var aJaxURL5	= "server-side/call/outgoing/suboutgoing/outgoing_tab1.action.php";		//server side folder url
		var aJaxURL6	= "server-side/call/outgoing/suboutgoing/outgoing_tab2.action.php";		//server side folder url
		var seoyURL	= "server-side/seoy/seoy.action.php";							//server side folder url
		var upJaxURL		= "server-side/upload/file.action.php";	
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';
		
		$(document).ready(function () { 
			$("#back_1000_active").button({
	            
		    });
		    
			$("#next_1000_active").button({
			    
			}); 
			  
			$("#back_1000_first").button({
	            
		    });
		    
			$("#next_1000_first").button({
			    
			});  

			$("#back_1000_mimd").button({
	            
		    });
		    
			$("#next_1000_mimd").button({
			    
			}); 

			$("#back_1000_done").button({
	            
		    });
		    
			$("#next_1000_done").button({
			    
			});
			GetTabs(tbName);   
			
			GetTable0();
			
			SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");
			GetButtons("add_responsible_person","delete_button");
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2()
            }else if(tab == 3){
            	GetTable3()
            }else if(tab == 4){
            	GetTable4()
            }
        });

		function GetTable0() {
            LoadTable0();
            //SetEvents("", "", "", "example0", "add-edit-form1", aJaxURL); 			
        }
        
		 function GetTable1() {
             LoadTable1();
             $("#add_button_n").button({
  	            
  		     });
             SetEvents("", "", "", "example1", "add-edit-form1", aJaxURL);
         }
         
		 function GetTable2() {
             LoadTable2();
             SetEvents("", "", "", "example2", "add-edit-form1", aJaxURL);
         }
         
		 function GetTable3() {
             LoadTable3();
             SetEvents("", "", "", "example3", "add-edit-form1", aJaxURL);
         }

		 function GetTable4() {
			 LoadTable7();
             SetEvents("", "", "", "example5", "add-edit-form1", aJaxURL);
         }

		 function LoadTable0(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			 GetDataTableTest("example0", aJaxURL, "get_list", 8, "", 0, "", 1, "asc", "");
		}
			
		function LoadTable1(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example1", aJaxURL1, "get_list", 12, "", 0, "", 1, "asc", "");
		}

		function LoadTable2(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example2", aJaxURL2, "get_list", 12, "", 0, "", 1, "asc", "");
		}
		
		function LoadTable3(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example3", aJaxURL3, "get_list", 12, "", 0, "", 1, "asc", "");
		}
		
		function LoadTable4(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example4", aJaxURL4, "get_list&id="+$("#id").val(), 12, "", 0, "", 1, "asc", "");
		}
		function LoadTable5(){		
			var scenar_name =	$("#shabloni").val();
			GetButtons("add_button_product","delete_button_product");
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("sub1", aJaxURL5, "get_list&scenar_name="+scenar_name, 5, "", 0, "", 1, "asc", "");
			
		}
		function LoadTable6(){		
			var scenar_name =	$("#shabloni").val();	
			GetButtons("add_button_gift","delete_button_gift");
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable("sub2", aJaxURL6, "get_list&scenar_name="+scenar_name, 5, "", 0, "", 1, "asc", "");
			
		}
		function LoadTable7(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example5", aJaxURL3, "get_list", 11, "", 0, "", 1, "asc", "");
		}

		$(document).on("click", "#check-all-in", function () {
		if ($('#check-all-in').is(':checked')) {
			$( ".check" ).prop( "checked", true );
    	}else{
    		$( ".check" ).prop( "checked", false );
    	}
		});
		//SeoYyy
		$(document.body).click(function (e) {
        	$("#send_to").autocomplete("close");
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
					GetDialog("add-edit-form", 1150, "auto", buttons);
					$(".done").button({
			            
				    });
					$(".next").button({
			            
				    });
					$(".back").button({
			            
				    });
					$("#complete").button({
					    
					});
					LoadTable5();
					LoadTable6();
					GetDateTimes("send_time");
				break;	
				case "add-edit-form1":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog1"
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
					GetDateTimes("set_start_time");
					GetDateTimes("set_done_time");
					GetDateTimes("send_time");
					/* Check All */
			        $("#check-all_p").on("click", function () {
			        	$("#sub1 INPUT[type='checkbox']").prop("checked", $("#check-all_p").is(":checked"));
			        });

			        $("#check-all_g").on("click", function () {
			        	$("#sub2 INPUT[type='checkbox']").prop("checked", $("#check-all_g").is(":checked"));
			        });
			        
				break;	
				case "add-edit-form2":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog1"
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
					GetDialog("add-edit-form2", 1150, "auto", buttons);
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
					GetDateTimes("set_start_time");
					GetDateTimes("set_done_time");
			    break;
			}
			LoadTable4()
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
			$("#add_button_pp").button({
	            
		    });
		}

       
        /* Disable Event */
        $(document).on("click", "#delete_button_product", function () {
            var data = $(".check_p:checked").map(function () { //Get Checked checkbox array
                return this.value;
            }).get();

            for (var i = 0; i < data.length; i++) {
                $.ajax({
                    url: aJaxURL5,
                    type: "POST",
                    data: "act=disable&id=" + data[i],
                    dataType: "json",
                    success: function (data) {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            $("#check-all_p").attr("checked", false);
                        }
                    }
                });
            }
            LoadTable5();
           
        });
        
        /* Disable Event */
        $(document).on("click", "#delete_button_gift", function () {
            var data = $(".check_g:checked").map(function () { //Get Checked checkbox array
                return this.value;
            }).get();

            for (var i = 0; i < data.length; i++) {
                $.ajax({
                    url: aJaxURL6,
                    type: "POST",
                    data: "act=disable&id=" + data[i],
                    dataType: "json",
                    success: function (data) {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            $("#check-all_g").attr("checked", false);
                        }
                    }
                });
            }
            LoadTable6();
           
        });
        

        $(document).on("click", "#add_button_product", function () {
        	var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save_chosse_product"
			        }, 
		        	"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        }
			    };
		    GetDialog("add_product_chosse", 400, "auto", buttons);
		    var notes = $("#content_3").val();
        	$.ajax({
  	            url: "server-side/call/outgoing/add_chosse_product.php",
  	            type: "POST",
  	            data: "act=get_table&notes="+notes,
  	            dataType: "json", 
  	            success: function (data) {
  	            	
					$("#add_product_chosse").html(data.page);
					SeoY("production_name", seoyURL, "production_name", "", 0);
  	            }
  	        });
        	$(document).on("keydown", "#production_name", function(event) {
            	if (event.keyCode == $.ui.keyCode.ENTER) {
            		GetProductionInfo(this.value);
                	event.preventDefault();
            	}
        	});
        	
        });

        $(document).on("click", "#save_chosse_product", function () {
        	var notes 			= $("#hidden_notes").val();
        	var shabloni		= $("#shabloni").val()
        	var product_id		= $("#hidden_product_id").val();
        	$.ajax({
  	            url: "server-side/call/outgoing/add_chosse_product.php",
  	            type: "POST",
  	            data: "act=add_product&notes="+notes+"&shabloni="+shabloni+"&product_id="+product_id,
  	            dataType: "json", 
  	            success: function (data) {
  	            	$("#add_product_chosse").dialog("close");
  	            	LoadTable5();
  	            }
  	        });
        });
        
        ////////////////////////////
        $(document).on("click", "#add_button_gift", function () {
        	var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save_chosse_gift"
			        }, 
		        	"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        }
			    };
		    GetDialog("add_gift_chosse", 400, "auto", buttons);
		    var notes = $("#content_4").val();
        	$.ajax({
  	            url: "server-side/call/outgoing/add_chosse_gift.php",
  	            type: "POST",
  	            data: "act=get_table&notes="+notes,
  	            dataType: "json", 
  	            success: function (data) {
  	            	
					$("#add_gift_chosse").html(data.page);
					SeoY("production_name_gift", seoyURL, "production_name_gift", "", 0);
  	            }
  	        });
        	
        });

        $(document).on("click", "#save_chosse_gift", function () {
        	var notes 			= $("#hidden_notes").val();
        	var shabloni		= $("#shabloni").val()
        	var product_id		= $("#hidden_product_id").val();
        	$.ajax({
  	            url: "server-side/call/outgoing/add_chosse_gift.php",
  	            type: "POST",
  	            data: "act=add_product&notes="+notes+"&shabloni="+shabloni+"&product_id="+product_id,
  	            dataType: "json", 
  	            success: function (data) {
  	            	$("#add_gift_chosse").dialog("close");
  	            	LoadTable6();
  	            }
  	        });
        });
        ///////////////////////////

        $(document).on("click", ".combobox", function(event) {
            var i = $(this).text();
            $("#" + i).autocomplete("search", "");
        });

        $(document).on("keydown", "#production_name_gift", function(event) {
        	if (event.keyCode == $.ui.keyCode.ENTER) {
        		GetProductionInfoo(this.value);
            	event.preventDefault();
        	}
    	});

	 function GetProductionInfo(name) {
            $.ajax({
                url: "server-side/call/outgoing/add_chosse_product.php",
                data: "act=get_product_info&name=" + name,
                success: function(data) {
              
                            $("#genre").val(data.genre);
                            $("#category").val(data.category);
                            $("#description").val(data.description);
                            $("#price").val(data.price);
                            $("#hidden_product_id").val(data.id);
                     
                }
            });
        }

	 function GetProductionInfoo(name) {
         $.ajax({
             url: "server-side/call/outgoing/add_chosse_gift.php",
             data: "act=get_product_info&name=" + name,
             success: function(data) {
           
                         $("#genre_gift").val(data.genre);
                         $("#category_gift").val(data.category);
                         $("#description_gift").val(data.description);
                         $("#price_gift").val(data.price);
                         $("#hidden_product_id_gift").val(data.id);
                  
             }
         });
     }
    	
        $(document).on("click", "#save-printer", function () {
 
	      	param = new Object();
	      	param.act		= "change_responsible_person";
	  	    param.rp		= $("#responsible_person").val();
		  	param.number	= $("#raodenoba").val();
	
	  	    var link	=  GetAjaxData(param);
	  	      
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
	  		
        });

		function LoadDialog1(){
			var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save-printer",
			            click: function () {
			            	$(this).dialog("close");			            
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
			param.task_department_id	= $("#task_department_id").val();
			param.persons_id			= $("#persons_id").val();
			
	 
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
		});

	    function set_task() {

			param 			= new Object();
			param.act			= "set_task";
			
			param.set_task_department_id	= $("#set_task_department_id").val();
	    	param.set_persons_id			= $("#set_persons_id").val();
	    	param.set_priority_id			= $("#set_priority_id").val();
			param.set_start_time			= $("#set_start_time").val();
			param.set_done_time				= $("#set_done_time").val();
			param.set_body					= $("#set_body").val();
			param.task_type_id_seller		= $("#task_type_id_seller").val();
			param.set_shabloni				= $("#shabloni").val();
			param.id						= $("#id").val();
			
	 		if(param.set_task_department_id == 0){
		 		alert('დავალების ფორმირებისთვის ამოირჩიეთ განყოფილება');
	 		}else{
		    $.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							LoadTable1();
						}
					}
			    }
		    });
	 		}
		}
	    $(document).on("click", "#sub1", function () {
	    	
			//var nTds 	= $("#sub1 td");
			//var pId 	= $(nTds[0]).text();
	    	param 				= new Object();
 			param.act			= "sfsdf";
			param.get_prod = '';
			
				
				 var data = $(".check_p").map(function () { //Get Checked checkbox array
					 return this.value;
		        }).get();
				for (var i = 0; i < data.length; i++) {
					if(param.get_prod != ''){
						param.get_prod+=',';
						
					}
					param.get_prod+="'"+data[i]+"'";
				}
		
			$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 				
 		    	}
			});
	    });
		
	    $(document).on("click", "#done-dialog1", function () {
			   
			param 				= new Object();
 			param.act			= "done_outgoing";
 			param.get_prod 		= '';
 			param.get_gift 		= '';
		    	
 			param.id					= $("#id").val();
			param.hello_quest			= $("input[name='hello_quest']:checked").val();
	    	param.hello_comment			= $("#hello_comment").val();
	    	param.info_quest			= $("input[name='info_quest']:checked").val();
			param.info_comment			= $("#info_comment").val();
			param.result_quest			= $("input[name='result_quest']:checked").val();
	    	param.result_comment		= $("#result_comment").val();
			param.payment_quest			= $("input[name='payment_quest']:checked").val();
			param.payment_comment		= $("#payment_comment").val();
			param.send_date				= $("#send_date").val();

			param.preface_name			= $("#preface_name").val();
			param.preface_quest			= $("input[name='preface_quest']:checked").val();
			param.d1					= $("input[name='d1']:checked").val();
			param.d2					= $("input[name='d2']:checked").val();
			param.d3					= $("input[name='d3']:checked").val();
			param.d4					= $("input[name='d4']:checked").val();
			param.d5					= $("input[name='d5']:checked").val();
			param.d6					= $("input[name='d6']:checked").val();
			param.d7					= $("input[name='d7']:checked").val();
			param.d8					= $("input[name='d8']:checked").val();
			param.d9					= $("input[name='d9']:checked").val();
			param.d10					= $("input[name='d10']:checked").val();
			param.d11					= $("input[name='d11']:checked").val();
			param.d12					= $("input[name='d12']:checked").val();
			param.q1					= $("input[name='q1']:checked").val();
			param.b1					= $("#biblus_quest1").val();
			param.b2					= $("#biblus_quest2").val();

			param.call_content			= $("#call_content").val();
			param.status				= $("#status").val();
			
			// person info
			param.phone					= $("#phone").val();
			param.phone1				= $("#phone1").val();
			param.person_n				= $("#person_n").val();
			param.first_name			= $("#first_name").val();
			param.mail					= $("#mail").val();
			param.city_id				= $("#city_id").val();
			param.b_day					= $("#b_day").val();
			param.addres				= $("#addres").val();

			// Task Formireba
			param.set_task_department_id	= $("#set_task_department_id").val();
	    	param.set_persons_id			= $("#set_persons_id").val();
	    	param.set_priority_id			= $("#set_priority_id").val();
			param.set_start_time			= $("#set_start_time").val();
			param.set_done_time				= $("#set_done_time").val();
			param.set_body					= $("#set_body").val();
			param.task_type_id_seller		= $("#task_type_id_seller").val();
			param.set_shabloni				= $("#shabloni").val();

			var data = $(".check_pp").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_prod != ''){
					param.get_prod+=',';
					
				}
				param.get_prod+=data[i];
			}

			var data = $(".check_gg").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_gift != ''){
					param.get_gift+=',';
					
				}
				param.get_gift+=data[i];
			}

			
 	    	$.ajax({
 		        url: aJaxURL1,
 			    data: param,
 		        success: function(data) {       
 					if(typeof(data.error) != "undefined"){
 						if(data.error != ""){
 							alert(data.error);
 						}else{
							LoadTable1();
							LoadTable2();
							LoadTable3();
							LoadTable0();
 							CloseDialog("add-edit-form1");
 						}
 					}
 		    	}
 		   });
		});

	    $(document).on("click", ".done", function () {
			   
	    	param 				= new Object();
 			param.act			= "done_outgoing";
 			param.get_prod 		= '';
 			param.get_gift 		= '';
		    	
 			param.id					= $("#id").val();
			param.hello_quest			= $("input[name='hello_quest']:checked").val();
	    	param.hello_comment			= $("#hello_comment").val();
	    	param.info_quest			= $("input[name='info_quest']:checked").val();
			param.info_comment			= $("#info_comment").val();
			param.result_quest			= $("input[name='result_quest']:checked").val();
	    	param.result_comment		= $("#result_comment").val();
			param.payment_quest			= $("input[name='payment_quest']:checked").val();
			param.payment_comment		= $("#payment_comment").val();
			param.send_date				= $("#send_date").val();

			param.preface_name			= $("#preface_name").val();
			param.preface_quest			= $("input[name='preface_quest']:checked").val();
			param.d1					= $("input[name='d1']:checked").val();
			param.d2					= $("input[name='d2']:checked").val();
			param.d3					= $("input[name='d3']:checked").val();
			param.d4					= $("input[name='d4']:checked").val();
			param.d5					= $("input[name='d5']:checked").val();
			param.d6					= $("input[name='d6']:checked").val();
			param.d7					= $("input[name='d7']:checked").val();
			param.d8					= $("input[name='d8']:checked").val();
			param.d9					= $("input[name='d9']:checked").val();
			param.d10					= $("input[name='d10']:checked").val();
			param.d11					= $("input[name='d11']:checked").val();
			param.d12					= $("input[name='d12']:checked").val();
			param.q1					= $("input[name='q1']:checked").val();
			param.b1					= $("#biblus_quest1").val();
			param.b2					= $("#biblus_quest2").val();

			param.call_content			= $("#call_content").val();
			param.status				= $("#status").val();
			
			// person info
			param.phone					= $("#phone").val();
			param.phone1				= $("#phone1").val();
			param.person_n				= $("#person_n").val();
			param.first_name			= $("#first_name").val();
			param.mail					= $("#mail").val();
			param.city_id				= $("#city_id").val();
			param.b_day					= $("#b_day").val();
			param.addres				= $("#addres").val();

			var data = $(".check_pp").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_prod != ''){
					param.get_prod+=',';
					
				}
				param.get_prod+=data[i];
			}

			var data = $(".check_gg").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_gift != ''){
					param.get_gift+=',';
					
				}
				param.get_gift+=data[i];
			}
	 
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

	    $(document).on("click", "#complete", function () {
			   
	    	param 				= new Object();
 			param.act			= "done_outgoing";
 			param.get_prod 		= '';
 			param.get_gift 		= '';
		    	
 			param.id					= $("#id").val();
			param.hello_quest			= $("input[name='hello_quest']:checked").val();
	    	param.hello_comment			= $("#hello_comment").val();
	    	param.info_quest			= $("input[name='info_quest']:checked").val();
			param.info_comment			= $("#info_comment").val();
			param.result_quest			= $("input[name='result_quest']:checked").val();
	    	param.result_comment		= $("#result_comment").val();
			param.payment_quest			= $("input[name='payment_quest']:checked").val();
			param.payment_comment		= $("#payment_comment").val();
			param.send_date				= $("#send_date").val();

			param.preface_name			= $("#preface_name").val();
			param.preface_quest			= $("input[name='preface_quest']:checked").val();
			param.d1					= $("input[name='d1']:checked").val();
			param.d2					= $("input[name='d2']:checked").val();
			param.d3					= $("input[name='d3']:checked").val();
			param.d4					= $("input[name='d4']:checked").val();
			param.d5					= $("input[name='d5']:checked").val();
			param.d6					= $("input[name='d6']:checked").val();
			param.d7					= $("input[name='d7']:checked").val();
			param.d8					= $("input[name='d8']:checked").val();
			param.d9					= $("input[name='d9']:checked").val();
			param.d10					= $("input[name='d10']:checked").val();
			param.d11					= $("input[name='d11']:checked").val();
			param.d12					= $("input[name='d12']:checked").val();
			param.q1					= $("input[name='q1']:checked").val();
			param.b1					= $("#biblus_quest1").val();
			param.b2					= $("#biblus_quest2").val();

			param.call_content			= $("#call_content").val();
			param.status				= $("#status").val();
			
			// person info
			param.phone					= $("#phone").val();
			param.phone1				= $("#phone1").val();
			param.person_n				= $("#person_n").val();
			param.first_name			= $("#first_name").val();
			param.mail					= $("#mail").val();
			param.city_id				= $("#city_id").val();
			param.b_day					= $("#b_day").val();
			param.addres				= $("#addres").val();

			var data = $(".check_pp").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_prod != ''){
					param.get_prod+=',';
					
				}
				param.get_prod+=data[i];
			}

			var data = $(".check_gg").map(function () { //Get Checked checkbox array
				 return this.value;
	        }).get();
			for (var i = 0; i < data.length; i++) {
				if(param.get_gift != ''){
					param.get_gift+=',';
					
				}
				param.get_gift+=data[i];
			}
	 
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

	    $(document).on("click", "#next_1000_active", function () {
			var next = $('#mtvleli_active').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_active').val(next_ch);
			GetDataTableTest("example0", aJaxURL, "get_list&pager="+next_ch, 9, "", 0, "", 1, "asc");
		});
		$(document).on("click", "#back_1000_active", function () {
			var back = $('#mtvleli_active').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_active').val(back_ch);
			
			GetDataTableTest("example0", aJaxURL, "get_list&pager="+back_ch, 9, "", 0, "", 1, "asc");
		});

		$(document).on("click", "#next_1000_first", function () {
			var next = $('#mtvleli_first').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_first').val(next_ch);
			GetDataTableTest("example1", aJaxURL1, "get_list&pager="+next_ch, 12, "", 0, "", 1, "asc");
		});
		$(document).on("click", "#back_1000_first", function () {
			var back = $('#mtvleli_first').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_first').val(back_ch);
			
			GetDataTableTest("example1", aJaxURL1, "get_list&pager="+back_ch, 12, "", 0, "", 1, "asc");
		});

		$(document).on("click", "#next_1000_mimd", function () {
			var next = $('#mtvleli_mimd').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_mimd').val(next_ch);
			GetDataTableTest("example2", aJaxURL2, "get_list&pager="+next_ch, 12, "", 0, "", 1, "asc");
		});
		$(document).on("click", "#back_1000_mimd", function () {
			var back = $('#mtvleli_mimd').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_mimd').val(back_ch);
			
			GetDataTableTest("example2", aJaxURL2, "get_list&pager="+back_ch, 12, "", 0, "", 1, "asc");
		});

		$(document).on("click", "#next_1000_done", function () {
			var next = $('#mtvleli_done').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_done').val(next_ch);
			GetDataTableTest("example3", aJaxURL3, "get_list&pager="+next_ch, 12, "", 0, "", 1, "asc");
		});
		$(document).on("click", "#back_1000_done", function () {
			var back = $('#mtvleli_done').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_done').val(back_ch);
			
			GetDataTableTest("example3", aJaxURL3, "get_list&pager="+back_ch, 12, "", 0, "", 1, "asc");
		});

		$(document).on("click", "#delete_button", function () {
            var data = $(".check:checked").map(function () { //Get Checked checkbox array
                return this.value;
            }).get();

            for (var i = 0; i < data.length; i++) {
                $.ajax({
                    url: aJaxURL,
                    type: "POST",
                    data: "act=disable&id_delete=" + data[i],
                    dataType: "json",
                    success: function (data) {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            $("#check-all-in").attr("checked", false);
                        }
                    }
                });
            }
            LoadTable0();
           
        });
    </script>
</head>

<body>

<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
			<li><a href="#tab-0">აქტივაცია</a></li>
			<li><a href="#tab-1">პირველადი</a></li>
			<li><a href="#tab-2">მიმდინარე</a></li>
			<li><a href="#tab-3">დასრულებული</a></li>
			<li><a href="#tab-4">არქივი</a></li>
		</ul>
		<div id="tab-0">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">აქტივაცია</h2>
		            	<div id="button_area">
	        				<button id="add_responsible_person">პ. პირის აქტივაცია</button>
	        				<button id="delete_button">წაშლა</button>
	        				<button id="back_1000_active" > << 1000 </button>
    					 	<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_active" value="0">
    					 	<button id="next_1000_active" > 1000 >> </button>
	        			</div>
		                <table class="display" id="example0">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:8%;">№</th>
									<th style="width:19%;">პირადი №<br>საიდ. კოდი</th>
									<th style="width:19%;">დასახელება</th>
									<th style="width:19%;">დავალების<br>ტიპი</th>
									<th style="width:19%;">განყოფილება</th>
									<th style="width:19%;">ზარის შესრულების<br>თარიღი</th>
									<th style="width:19%;">შენიშვნა</th>
									<th class="check">#</th>
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
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
										<input type="checkbox" name="check-all" id="check-all-in"/>
									</th>
									
								</tr>
							</thead>
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
		            	<h2 align="center">პირველადი</h2>
		            	<div id="button_area">
		            		<button id="back_1000_first" > << 1000 </button>
    					 	<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_first" value="0">
    					 	<button id="next_1000_first" > 1000 >> </button>
	        			</div>
		                <table class="display" id="example1">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:8%;">№</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">სცენარი</th>
									<th style="width:19%;">დასახელება</th>
									<th style="width:19%;">ტელეფონი 1</th>
									<th style="width:19%;">ტელეფონი 2</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">შენიშვნა</th>
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
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		            	<h2 align="center">მიმდინარე</h2>
		            	<div id="button_area">
		            		<button id="back_1000_mimd" > << 1000 </button>
    					 	<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_mimd" value="0">
    					 	<button id="next_1000_mimd" > 1000 >> </button>
	        			</div>
		                <table class="display" id="example2">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:8%;">№</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">სცენარი</th>
									<th style="width:19%;">დასახელება</th>
									<th style="width:19%;">ტელეფონი 1</th>
									<th style="width:19%;">ტელეფონი 2</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">შენიშვნა</th>
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
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
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
		            	<h2 align="center">მიმდინარე</h2>
		            	<div id="button_area">
		            		<button id="back_1000_done" > << 1000 </button>
    					 	<input style="width: 60px; border: none; text-align: center; background: #DFEFFC;" id="mtvleli_done" value="0">
    					 	<button id="next_1000_done" > 1000 >> </button>
	        			</div>
		                <table class="display" id="example3">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:8%;">№</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">სცენარი</th>
									<th style="width:19%;">დასახელება</th>
									<th style="width:19%;">ტელეფონი 1</th>
									<th style="width:19%;">ტელეფონი 2</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">შენიშვნა</th>
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
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
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
		            	<h2 align="center">არქივი</h2>
		                <table class="display" id="example5">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:8%;">№</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">სცენარი</th>
									<th style="width:19%;">დასახელება</th>
									<th style="width:19%;">ტელეფონი 1</th>
									<th style="width:19%;">ტელეფონი 2</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">შენიშვნა</th>
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
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
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
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
</div>
<!-- jQuery Dialog -->
<div id="add-edit-form" class="form-dialog" title="დავალების ფორმირება">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form1" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<!-- jQuery Dialog -->
<div id="add-edit-form2" class="form-dialog" title="გამავალი ზარი">
<!-- aJax -->
</div>

<div id="add-responsible-person" class="form-dialog" title="პასუხისმგებელი პირი">
<!-- aJax -->
</div>
<div id="add_task" class="form-dialog" title="დავალების დამატება">
<!-- aJax -->
</div>
<div id="add_product" class="form-dialog" title="დავალების დამატება">
<!-- aJax -->
</div>
<div id="add_product_chosse" class="form-dialog" title="პროდუქტის დამატება">
<!-- aJax -->
</div>
<div id="add_gift_chosse" class="form-dialog" title="საჩუქრის დამატება">
<!-- aJax -->
</div>
</body>

