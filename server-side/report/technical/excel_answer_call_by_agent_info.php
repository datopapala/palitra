<?php
require_once('../../../includes/classes/core.php');

$agent	= $_REQUEST['agent'];
$queue	= $_REQUEST['queuet'];
$start_time = $_REQUEST['start_time'];
$end_time 	= $_REQUEST['end_time'];

$ress =mysql_query("SELECT 	ag.agent as `agent`, 
 								count(ev.event) AS `num`,
 								round(((count(ev.event) / (
 	
 	SELECT count(ev.event) AS num
 	FROM queue_stats AS qs, qname AS q, qevent AS ev
 	WHERE qs.qname = q.qname_id
 	and qs.qevent = ev.event_id
 	and DATE(qs.datetime) >= '$start_time'
 	and DATE(qs.datetime) <= '$end_time'
 	and q.queue IN ($queue)
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	
 	)) * 100),2) AS `call_pr`,
 	ROUND((sum(qs.info2) / 60),2) AS `call_time`,
 	
 	round(((sum(qs.info2) / (
 	
 	SELECT sum(qs.info2)
 	FROM queue_stats AS qs, qname AS q, qevent AS ev
 	WHERE qs.qname = q.qname_id
 	and qs.qevent = ev.event_id
 	and DATE(qs.datetime) >= '$start_time'
 	and DATE(qs.datetime) <= '$end_time'
 	and q.queue IN ($queue)
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	
 	))* 100),2) AS `call_time_pr`,
 	TIME_FORMAT(SEC_TO_TIME(sum(qs.info2) / count(ev.event)), '%i:%s') AS `avg_call_time`,
 	sum(qs.info1) AS `hold_time`,
 	ROUND((sum(qs.info1) / count(ev.event)),2) AS `avg_hold_time`
 	FROM queue_stats AS qs, qname AS q, qevent AS ev, qagent AS `ag` WHERE ag.agent_id = qs.qagent AND
 	qs.qname = q.qname_id and qs.qevent = ev.event_id 
 	AND DATE(qs.datetime) >= '$start_time'
 	AND DATE(qs.datetime) <= '$end_time'
 	AND q.queue IN ($queue) 
 	AND ev.event IN ('COMPLETECALLER', 'COMPLETEAGENT')
 	GROUP BY ag.agent");

while($row = mysql_fetch_assoc($ress)){
	$dat .= '
						<ss:Row>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[agent].'</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[num].'</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[call_pr].'</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[call_time].'</ss:Data>
							</ss:Cell>							
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[call_time_pr].'</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[avg_call_time].' %</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[hold_time].' %</ss:Data>
							</ss:Cell>
							<ss:Cell>
								<ss:Data ss:Type="String">'.$row[avg_hold_time].' %</ss:Data>
							</ss:Cell>
						</ss:Row>';
}
	$name = "ნაპასუხები ზარები ოპერატორების მიხედვით";



$data = '
<?xml version="1.0" encoding="utf-8"?><?mso-application progid="Excel.Sheet"?>
<ss:Workbook xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:o="urn:schemas-microsoft-com:office:office">
	<o:DocumentProperties>
		<o:Title>'.$name.'</o:Title>
	</o:DocumentProperties>
	<ss:ExcelWorkbook>
		<ss:WindowHeight>9000</ss:WindowHeight>
		<ss:WindowWidth>50000</ss:WindowWidth>
		<ss:ProtectStructure>false</ss:ProtectStructure>
		<ss:ProtectWindows>false</ss:ProtectWindows>
	</ss:ExcelWorkbook>
	<ss:Styles>
		<ss:Style ss:ID="Default">
			<ss:Alignment ss:Vertical="Center" ss:Horizontal="Center" ss:WrapText="1" />
			<ss:Font ss:FontName="Sylfaen" ss:Size="12" />
			<ss:Interior />
			<ss:NumberFormat />
			<ss:Protection />
			<ss:Borders>
				<ss:Border ss:Position="Top" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
				<ss:Border ss:Position="Bottom" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
				<ss:Border ss:Position="Left" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
				<ss:Border ss:Position="Right" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
			</ss:Borders>
		</ss:Style>
		<ss:Style ss:ID="title">
			<ss:Borders />
			<ss:NumberFormat ss:Format="@" />
			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Vertical="Center" />
		</ss:Style>
		<ss:Style ss:ID="headercell">
			<ss:Font ss:Bold="1" />
			<ss:Interior ss:Pattern="Solid" />
			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Vertical="Center" />
		</ss:Style>
		<ss:Style ss:ID="headercellRotated">
			<ss:Font ss:Bold="1" />
			<ss:Interior ss:Pattern="Solid" />
			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Rotate="90" ss:Vertical="Center" />
		</ss:Style>
	</ss:Styles>
	<ss:Worksheet ss:Name="'.$name.'">
		<ss:Names>
			<ss:NamedRange ss:Name="Print_Titles" ss:RefersTo="=\' '.$name.' \'!R1:R2" />
		</ss:Names>
		<ss:Table x:FullRows="1" x:FullColumns="1" ss:ExpandedColumnCount="8" ss:ExpandedRowCount="6">
			<ss:Column ss:AutoFitWidth="1" ss:Width="50" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
			<ss:Row ss:Height="38">
				<ss:Cell ss:StyleID="title" ss:MergeAcross="7">
					<ss:Data xmlns:html="http://www.w3.org/TR/REC-html40" ss:Type="String">
						<html:B>
							<html:Font html:Size="14">'.$name.'</html:Font>
						</html:B>
					</ss:Data>
					<ss:NamedCell ss:Name="Print_Titles" />
				</ss:Cell>
			</ss:Row>
			
		
'; 
$data .= $dat; 
  
		
$data .='</ss:Table>
		<x:WorksheetOptions>
			<x:PageSetup>
				<x:Layout x:CenterHorizontal="1" x:Orientation="Portrait" />
				<x:Header x:Data="&amp;R&#10;&#10;&amp;D" />
				<x:Footer x:Data="Page &amp;P of &amp;N" x:Margin="0.5" />
				<x:PageMargins x:Top="0.5" x:Right="0.5" x:Left="0.5" x:Bottom="0.8" />
			</x:PageSetup>
			<x:FitToPage />
			<x:Print>
				<x:PrintErrors>Blank</x:PrintErrors>
				<x:FitWidth>1</x:FitWidth>
				<x:FitHeight>32767</x:FitHeight>
				<x:ValidPrinterInfo />
				<x:VerticalResolution>600</x:VerticalResolution>
			</x:Print>
			<x:Selected />
			<x:DoNotDisplayGridlines />
			<x:ProtectObjects>False</x:ProtectObjects>
			<x:ProtectScenarios>False</x:ProtectScenarios>
		</x:WorksheetOptions>
	</ss:Worksheet>
</ss:Workbook>
		';

if($number == '2'){
	$null= 1;
	echo json_encode($null);
}else{
	echo json_encode($data);
}
file_put_contents('excel.xls', $data);



	