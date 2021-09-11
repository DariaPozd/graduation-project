<?php
include 'header.php';
?>
	<div class="container" style="padding: 50px 0;">
		<div class="row justify-content-around">
			<div class="col-md-3 bg-light filters">
				<h5>Фильтр</h5><br>
				<div class="list-group">
					<h5>Производители:</h5>
					<?php
					$query = "SELECT DISTINCT(brand) FROM nomenclature WHERE kindP_id = '2'";
					$statement = $link->prepare($query);
					$statement->execute();
					$result = $statement->fetchAll();
					foreach ($result as $row) {
					?>
					<div class="list-group-item checkbox">
						<label><input type="checkbox" class="common_selector brand" value="<?php echo $row['brand']; ?>"> <?php echo $row['brand']; ?> </label>
					</div>
					<?php
					}
					?>
				</div><br>
				<div class="list-group">
					<h5>Тип:</h5>
					<?php
					$query = "SELECT DISTINCT(typeP_name) FROM nomenclature WHERE kindP_id = '2'";
					$statement = $link->prepare($query);
					$statement->execute();
					$result = $statement->fetchAll();
					foreach ($result as $row) {
					?>
					<div class="list-group-item checkbox">
						<label><input type="checkbox" class="common_selector typeTovara" value="<?php echo $row['typeP_name']; ?>"> <?php echo $row['typeP_name']; ?> </label>
					</div>
					<?php
					}
					?>
				</div><br>
				<div class="list-group">
					<h5>Цена:</h5>
					<input type="hidden" id="hidden_minimum_price" value="0">
					<input type="hidden" id="hidden_maximum_price" value="150000">
					<p id="price_show">15100-80000</p>
					<div id="price_range"></div>
				</div><br>
				<div class="list-group">
					<h5>Мощность (кВт):</h5>
					<p>при обогреве:&nbsp;<span id="warm_show">0.5-100</span></p>
					<input type="hidden" id="hidden_minimum_warm" value="0">
					<input type="hidden" id="hidden_maximum_warm" value="58.6">	
					<div id="warm_range"></div>
				</div>
				
			</div>
			<div class="col-md-9">
				<div class="row filter_data">
					
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function () {

		filter_data();

		function filter_data()
		{
			$('.filter_data').html('<div id="loading" style=""></div>');
			var action = 'fetch_data';
			var minimum_price = $('#hidden_minimum_price').val();
			var maximum_price = $('#hidden_maximum_price').val();
			var minimum_warm = $('#hidden_minimum_warm').val();
			var maximum_warm = $('#hidden_maximum_warm').val();
			var brand = get_filter('brand');
			var typeP_name = get_filter('typeTovara');
			$.ajax({
				url:"fetch_data.php?p_id=2",
				method:"POST",
				data:{action:action, minimum_price:minimum_price, maximum_price:maximum_price, minimum_warm:minimum_warm, maximum_warm:maximum_warm, brand:brand, typeP_name:typeP_name},
				success:function(data){
					$('.filter_data').html(data);
				}
			});
		}

		function get_filter(class_name)
		{
			var filter = [];
			$('.'+class_name+':checked').each(function(){
				filter.push($(this).val());
			})
			return filter;
		}

		$('.common_selector').click(function(){
        filter_data();
    	});

   		$('#price_range').slider({
	        range:true,
	        min:700,
	        max:80000,
	        values:[700, 80000],
	        step:500,
	        stop:function(event, ui)
	        {
	            $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
	            $('#hidden_minimum_price').val(ui.values[0]);
	            $('#hidden_maximum_price').val(ui.values[1]);
	            filter_data();
	        }
    	});

    	$('#warm_range').slider({
	        range:true,
	        min:0.5,
	        max:100,
	        values:[0.5, 100],
	        step:1,
	        stop:function(event, ui)
	        {
	            $('#warm_show').html(ui.values[0] + ' - ' + ui.values[1]);
	            $('#hidden_minimum_warm').val(ui.values[0]);
	            $('#hidden_maximum_warm').val(ui.values[1]);
	            filter_data();
	        }
    	});
	})
	</script>
<?php
include 'footer.php';
?>