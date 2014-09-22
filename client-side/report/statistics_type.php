<html>
<head>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script type="text/javascript">
var title='0';
var old_title='';
var old_name='';
var name1='';
var i=0;
var done_n =['','','','','','',''];
var done_t =['','','','','','',''];
	var aJaxURL	= "server-side/report/statistics_type.action.php";		//server side folder url
	var url     = "server-side/report/prod_category_statistics/get_category_sum.php";
	var tName   = "report";
	var start	= $("#search_start").val();
	var end		= $("#search_end").val();
	$(document).ready(function() {
		GetDate("search_start");
		GetDate("search_end");
		$("#back").button({ disabled: true });
		$("#back").button({ icons: { primary: "ui-icon-arrowthick-1-w" }});
	    $('#back').click(function(){

		    i--;
		    i--;
		    title=done_t[i];
	    	drawFirstLevel(done_n[i]);
	    	if(i==0)$("#back").button({ disabled: true });
	     });

	    drawFirstLevel(name1);
	});

	$(document).on("change", "#search_start", function () 	{drawFirstLevel(name1);});
	$(document).on("change", "#search_end"  , function () 	{drawFirstLevel(name1);});

	 function drawFirstLevel(name){
		 var options = {
	                chart: {
	                    renderTo: 'chart_container',
	                    plotBackgroundColor: null,
	                    plotBorderWidth: null,
	                    plotShadow: false
	                },
	                title: {
	                    text: title
	                },
	                tooltip: {
	                    formatter: function() {
	                        return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
	                    }
	                },
	                plotOptions: {
	                	pie: {
	                        allowPointSelect: true,
	                        cursor: 'pointer',
	                        dataLabels: {
	                            enabled: true,
	                            color: '#000000',
	                            connectorColor: '#000000',
	                            formatter: function() {
	                                return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
	                            }
	                        },
	                        point: {
	                            events: {
	                                click: function() {
	                                	$("#back").button({ disabled: false });
	                                	title=options.title['text'];
		                                var nm = this.name.split("<",1);
		                                if(title=="შემოსული ინფორმაციული  ზარები ქვე-განყოფილებების  მიხედვით"){
		                                $("#hidden_name").val(nm);}
										name=this.name;
										name1=this.name;
		                        		drawFirstLevel(name);
	                                }
	                            }
	                        }
	                    }
	                },
	                series: [{
	                    type: 'pie',
	                    name: 'კატეგორიები',
	                    data: []
	                }]
	            }
					var start	= $("#search_start").val();
					var end		= $("#search_end").val();
	           		$.getJSON(aJaxURL+"?act=get_category&start="+start+"&end="+end+"&name="+name+"&title="+title+"&name1="+$("#hidden_name").val()+"&cc="+done_n[1], function(json) {
	            	GetDataTable(tName, aJaxURL, "get_list", 4, "start="+start+"&end="+end+"&name="+name+"&title="+title+"&name1="+$("#hidden_name").val()+"&cc="+done_n[1], 0, "", 1, "desc",[2]);
	                options.series[0].data = json.data;
	                options.title['text']=json.text;
	                chart = new Highcharts.Chart(options);
	                $("#total_quantity").html("იტვირთება....")
	                setTimeout(function(){ $("#total_quantity").html($("#qnt").html());}, 500);
	            });
	           		done_n[i]=name;
                	done_t[i]=title;
                	i++;

	 }
	</script>
	</head>
	<body>

      <div id="dt_example" class="ex_highlight_row">
       	 <div id="container" style="width:90%">
            <div id="dynamic">
             <div id="button_area" style="margin: 3% 0 0 0">
             <button id="back" style="margin-top:0px">უკან</button>
			</div>
	       <div id="button_area" style="margin: 3% 0 0 0">
	         <div class="left" style="width: 175px;">
	           <input type="text" name="search_start" id="search_start" class="inpt right"/>
	             </div>
	            	<label for="search_start" class="left" style="margin:5px 0 0 3px">-დან</label>
	             <div class="left" style="width: 185px;">
		            <input type="text" name="search_end" id="search_end" class="inpt right" />
	             </div>
	            	<label for="search_end" class="left" style="margin:5px 0 0 3px">–მდე</label>
	           <label class="left" style="margin:5px 0 0 40px">ზარების  ჯამური რაოდენობა: </label> <label id="total_quantity" class="left" style="margin:5px 0 0 2px; font-weight: bold;">5</label>
	       <br /><br /><br />
	            </div>
			<div id="chart_container" style="width: 100%; height: 480px; margin-top:-30px;"></div>
			<input type="text" id="hidden_name" value="" style="display: none;" />
			<br><br><br><br><br><br><br><br><br><br>

			 <table class="display" id="report">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width:100%">დასახელება</th>
                            <th class="min">რაოდენობა</th>
                            <th class="min">პროცენტი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init">
                            </th>
                            <th>
                            	<input type="text" name="search_object" value="ფილტრი" class="search_init">
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" />
                            </th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th id="qnt">&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
                 <div class="spacer">
            	</div>
		</div>
	</div>
  </div>
</body>
</html>
