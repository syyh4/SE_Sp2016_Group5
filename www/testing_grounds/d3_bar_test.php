<!doctype html>
<?php
	
	$some_test_data_values = create_array_of_rand_ints( 7 );
	$some_test_data_labels = create_array_of_labels();
	$bar_fill_color = get_bar_color_with_r_b_g_a( 40.0, 50.0, 60.0, 0.5);
	$highlight_color = get_bar_color_with_r_b_g_a( 10.0, 10.0, 10.0, 0.7);
	
	
	
	
	
	
	
	function get_bar_fill_color()
	{
		$red_value = 56.0;
		$blue_value = 55.0;
		$green_value = 155.0;
		$alpha_value = 0.7;
		
		return '"'.get_bar_color_with_r_b_g_a( $red_value, $blue_value, $green_value, $alpha_value).'"';
	}
	function get_bar_color_with_r_b_g_a( $red, $blue, $green, $alpha)
	{
		$quote_mark = '"';
		
		return $quote_mark."rgba(".$red.",".$blue.",".$green.",".$alpha.")".$quote_mark;
	}
	
	function create_array_of_labels()
	{
		$final_array = array("January", "February", "March", "April", "May", "June", "July");
		return $final_array;
	}
	function create_array_of_rand_ints( $count )
	{
		$final_array = array();
		
		for ($i = 0; $i < $count; $i++)
		{
			array_push($final_array, create_random_int());
		}
		
		return $final_array;
	}
	function create_random_int()
	{
		$min_value = 0.0;
		$max_value = 100.0;
		
		return rand($min_value, $max_value);
	}
?>
<html>
	<head>
		<title>Bar Chart</title>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
	</head>
	<body>
		<div style="width: 50%">
			<canvas id="canvas" height="450" width="600"></canvas>
		</div>


	<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var barChartData = {
		labels : <?php echo json_encode($some_test_data_labels); ?>,
		datasets : [
			{
				fillColor : <?php echo $bar_fill_color; ?>,
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: <?php echo $highlight_color ?>,
				highlightStroke: "rgba(220,220,220,1)",
				data : <?php echo json_encode($some_test_data_values); ?>
			}		]
	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}
	</script>
	
	<p>
		The data labels => <?php echo json_encode($some_test_data_labels); ?>
	</p>
	<p>
		The data values => <?php echo json_encode($some_test_data_values); ?>
	</p>
	</body>
</html>