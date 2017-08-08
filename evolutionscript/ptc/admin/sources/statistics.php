<?php
/**
 * @ EvolutionScript
 * Created by NextPay.ir
 * author: Nextpay Company
 * ID: @nextpay
 * Date: 04/01/2017
 * Time: 5:45 PM
 * Website: NextPay.ir
 * Email: info@nextpay.ir
 * @copyright 2017
 * @package NextPay_Gateway
 * @version 1.0
 */
if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

$contrynum = $db->fetchOne("SELECT COUNT(*) AS NUM FROM country");
$statsquery = $db->query("SELECT * FROM statistics");

while ($list = $db->fetch_array($statsquery)) {
	$stats[$list['field']] = $list['value'];
}

$query = $db->query("SELECT name, total_deposit, total_withdraw FROM gateways ORDER BY name ASC");

while ($row = $db->fetch_array($query)) {
	$gatewaynames .= "'" . $row['name'] . "',";
	$gatewayincome .= $row['total_deposit'] . ",";
	$gatewayoutcome .= $row['total_withdraw'] . ",";
}

$gatewaynames = substr($gatewaynames, 0, strlen($gatewaynames) - 1);
$gatewayincome = substr($gatewayincome, 0, strlen($gatewayincome) - 1);
$gatewayoutcome = substr($gatewayoutcome, 0, strlen($gatewayoutcome) - 1);
$deposits = $db->fetchOne("SELECT SUM(amount) FROM deposit_history");
$deposits = ($deposits == "" ? "0.00" : $deposits);

if (!$pendingcashout) {
	$pendingcashout = "0.00";
}

echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geomap']});
   google.setOnLoadCallback(drawMap);

    function drawMap() {
      var data = new google.visualization.DataTable();
	  ";
$sqlcountry = $db->query("SELECT name, users FROM country WHERE users>0 ORDER BY name ASC");
$n = 0;

while ($row = $db->fetch_array($sqlcountry)) {
	$googlec .= "data.setValue(" . $n . ", 0, '" . $row['name'] . "');
";
	$googlec .= "data.setValue(" . $n . ", 1, " . $row['users'] . ");
";
	$n = $n + 1;
}


if ($n == 0) {
	$n = 1;
	$googlec .= "data.setValue(0, 0, 'test');
";
	$googlec .= "data.setValue(0, 1, 0);
";
}

echo "      data.addRows(";
echo $n;
echo ");
      data.addColumn('string', 'Country');
      data.addColumn('number', 'Members');
	  ";
echo $googlec;
echo "      var options = {};
      options['dataMode'] = 'regions';

      var container = document.getElementById('map_canvas');
      var geomap = new google.visualization.GeoMap(container);
      geomap.draw(data, options);
  };
  </script>
<script type=\"text/javascript\" src=\"./js/highcharts.js\"></script>
		<script type=\"text/javascript\">

			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'members_stats',
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: null,
					},
					subtitle: {
						text: 'مجموع اعضا : ";
echo $stats['members'];
echo "'
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b> : '+ this.y +' عضو';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
				    series: [{
						type: 'pie',
						name: 'Browser share',
						data: [
							{name:'فعال',   y: ";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Active' AND username!='BOT'");
echo ", sliced: true, selected: true},
							['غیرفعال',  ";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Inactive' AND username!='BOT'");
echo "],
							['معلق', ";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Suspended' AND username!='BOT'");
echo "],
							['تایید نشده',    ";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Un-verified' AND username!='BOT'");
echo "],
							['ربات',    ";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='BOT'");
echo "]
						]
					}],
				  credits: {
					 enabled: false
				  },
				});
			});

		</script>

		<script type=\"text/javascript\">

			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'financial_stats',
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: null,
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b> : '+ this.y +' تومان';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
				    series: [{
						type: 'pie',
						name: 'Browser share',
						data: [
							['واریزی شارژ حساب',   ";
echo $deposits;
echo "],
							['تسویه حساب شده',       ";
echo $stats['cashout'];
echo "],
							['منتظر',       ";
echo $pendingcashout;
echo "],
						]
					}],
				  credits: {
					 enabled: false
				  },
				});
			});

		</script>


		<script type=\"text/javascript\">

var chart;
$(document).ready(function() {
   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'gateway_stats',
         defaultSeriesType: 'bar'
      },
      title: {
         text: null,
      },

      xAxis: {

         categories: [";
echo $gatewaynames;
echo "],
         title: {
            text: null
         }
      },
      yAxis: {
         min: 0,
         title: {
            text: 'Income/Outcome',
            align: 'high'
         }
      },
      tooltip: {
		formatter: function() {
			return ''+
				this.x +' : '+ this.y + 'تومان';
		}
      },
      plotOptions: {
         bar: {
            dataLabels: {
               enabled: true
            }
         }
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -100,
         y: 100,
         floating: true,
         borderWidth: 1,
         backgroundColor: '#FFFFFF',
         shadow: true
      },
      credits: {
         enabled: false
      },
           series: [{
			name: 'ورودی : شارژ',
         data: [";
echo $gatewayincome;
echo "]
      },{
	  	name: 'خروجی : تسویه حساب',
		data: [";
echo $gatewayoutcome;
echo "]
	  }]
   });


});
		</script>


        <div class=\"dashbaord-img-1\">
            <div class=\"widget-title\">آمار اعضا</div>
            <div class=\"widget-content\" style=\"direction:ltr !important;\">
                <div id=\"members_stats\" style=\"height: 250px;\"></div>
            </div>
        </div>
        <div class=\"dashbaord-img-2\">
            <div class=\"widget-title\">آمار مالی</div>
            <div class=\"widget-content\" style=\"direction:ltr !important;\">
                <div id=\"financial_stats\" style=\"min-height: 250px;\"></div>
            </div>
        </div>
        <div class=\"clear\"></div>
        <div class=\"widget-title\">ورودی/خروجی هر درگاه پرداخت</div>
        <div class=\"widget-content\" style=\"direction:ltr !important;\">
            <div id=\"gateway_stats\" style=\"min-height: 300px;\"></div>
        </div>

        <div class=\"widget-title\">آمار کشورها</div>
        <div class=\"widget-content\" style=\"background:#eaf7fe;direction:ltr !important;\"><div id='map_canvas' align=\"center\"></div>
        </div>";
?>