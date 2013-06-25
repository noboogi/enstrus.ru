<script language="javascript" type="text/javascript" src="../js/FlotJs/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/FlotJs/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="../js/FlotJs/jquery.flot.selection.js"></script>

<div class="box left narrow">
	<div class="title">
		Фильтр	
	</div>
  	<div class="content">
			<form id="emeasurementsFilter" name="emeasurementsFilter" method="post" action="">
			<input type="hidden" name="emelementsId" />
			  <table width="100%" border="0" cellpadding="3">
                <tr>
                  <td colspan="3"><b>Дата (год-месяц-день)</b></td>              
                </tr>
                <tr>
                  <td><div align="right">от: </div></td>
                  <td colspan="2"><input type="text" name="startDate" class="width95 tcal" value="<?php echo $data['startDate']?>"/></td>
                </tr>
                <tr>
                  <td><div align="right">до: </div></td>
                  <td colspan="2"><input name="endDate" type="text" class="width95 tcal" value="<?php echo $data['endDate']?>"/></td>
                </tr>
                <tr>
                  <td colspan="3"><b>Время (час:минуты)</b></td>
                </tr>
                <tr>
                  <td><div align="right">от: </div></td>
                  <td colspan="2"><input type="text" name="startTime" class="width95" value="<?php echo $data['startTime']?>"/></td>
                </tr>
                <tr>
                  <td><div align="right">до: </div></td>
                  <td colspan="2"><input type="text" name="endTime" class="width95" value="<?php echo $data['endTime']?>"/></td>
                </tr>
                <tr>
                  <td>
				  	  <br />
					  <div align="right">
						<input type="checkbox" <?php echo $data["onlyMax"]?> name="onlyMax" value="true" />
					  </div>				  </td>
                  <td><br />только максимумы </td>
                  <td>
					  <div align="right">
					  	<br />
						<input name="submit" type="submit" value="Отбор" />
					  </div>
				  </td>
                </tr>

              </table>
	  </form>
  </div>

</div>

<div class="box right wide">
	<div class="title">
		Показания счётчиков электрической энергии	
	</div>

	<div class="content" >
		<!--График-->
		<div id="placeholder" style="width:97%;min-height:390px;"></div>
		<!--Мини-график навигации-->
		<div id="overview" style="width:97%;height:50px"></div>
	    <!----------------------->		

	</div>
</div>

<script id="source">
$(function () {
var d = [
	<?php
			date_default_timezone_set('UTC'); 
			$emeasurements = $data['emeasurement'];	
			$graphData='';
			while($row = mysql_fetch_array($emeasurements))
			{
					$tmp = strtotime($row['date'].' '.$row['time'])*1000;
					$graphData=$graphData.'['.$tmp.','.$row['value'].'],';
			}
			$graphData = substr($graphData, 0, strlen($graphData)-1);
			echo $graphData;
	?>
];



    // helper for returning the weekends in a period
    function weekendAreas(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        // go to the first Saturday
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            // when we don't set yaxis, the rectangle automatically
            // extends to infinity upwards and downwards
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }
    
    var options = {
        xaxis: { mode: "time" },
        selection: { mode: "x" },
        grid: { markings: weekendAreas }
    };
    
    var plot = $.plot($("#placeholder"), [d], options);
    
    var overview = $.plot($("#overview"), [d], {
        series: {
            lines: { show: true, lineWidth: 1 },
            shadowSize: 0
        },
        xaxis: { ticks: [], mode: "time" },
        yaxis: { ticks: [], min: 0, autoscaleMargin: 0.1 },
        selection: { mode: "x" }
    });

    // now connect the two
    
    $("#placeholder").bind("plotselected", function (event, ranges) {
        // do the zooming
        plot = $.plot($("#placeholder"), [d],
                      $.extend(true, {}, options, {
                          xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
                      }));

        // don't fire event on the overview to prevent eternal loop
        overview.setSelection(ranges, true);
    });
    
    $("#overview").bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
    });
});
</script>



