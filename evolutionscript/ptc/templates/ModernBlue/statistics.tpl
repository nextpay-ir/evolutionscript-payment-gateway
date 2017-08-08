<!-- Content -->
<div class="widget-main-title">{$lang.txt.statistics}</div>
<div class="widget-content">

<script language="JavaScript" src="js/FusionCharts.js"></script>

<table width="100%">
	<tr>
    	<td width="50%">

	<div id="chartdiv" align="center"> 
        FusionCharts. </div>
      <script type="text/javascript">
		   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
		   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart1}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$myclicks}</chart>");		   
		   chart.render("chartdiv");
		</script>
 

		</td>

		<td>


	<div id="chartdiv2" align="center"> 
        FusionCharts. </div>
      <script type="text/javascript">
		   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
		   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart2}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$refclicks}</chart>");		   
		   chart.render("chartdiv2");
		</script>
 

		</td>
	</tr>
    
   {if $settings.rent_referrals == 'yes'} 
    <tr>
    	<td><br />

	<div id="chartdiv3" align="center"> 
        FusionCharts. </div>
      <script type="text/javascript">
		   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
		   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart3}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$rentedrefclicks}</chart>");		   
		   chart.render("chartdiv3");
		</script>
        
        </td>
    	<td><br />

	<div id="chartdiv4" align="center"> 
        FusionCharts. </div>
      <script type="text/javascript">
		   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
		   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart4}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='4' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$autopayclicks}</chart>");		   
		   chart.render("chartdiv4");
		</script>
        </td>

   </tr>
   {/if}
</table>

</div>
{$initmember}

<!-- End Content -->