    <h1>flot.tooltip plugin example page</h1>

    <div id="placeholder" style="width: 500px; height: 400px;"></div>

	<script>
	$(function () {

		var data = [
			{ label: "Series 0", data: 1 },
			{ label: "Series 1", data: 3 },
			{ label: "Series 2", data: 9 },
			{ label: "Series 3", data: 20 }
		];
		
		var plotObj = $.plot($("#placeholder"), data, {
			series: {
				pie: {
					show: true
				}
			},
			grid: {
				hoverable: true 
			},
			tooltip: true,
			tooltipOpts: {
				content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
				shifts: {
					x: 20,
					y: 0
				},
				defaultTheme: false
			}
		});
		
	});
	</script>

