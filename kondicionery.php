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
					$query = "SELECT DISTINCT(brand) FROM nomenclature WHERE kindP_id = '1'";
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
					<h5>Инверторный:</h5>
					<div class="list-group-item">
						<label><input type="radio" class="common_selector invert" name="rad" value="1">да</label><br>
						<label><input type="radio" class="common_selector invert" name="rad" value="0">нет</label><br>
						<label><input type="radio" class="common_selector invert" name="rad" value="">любой</label><br>
					</div>
				</div><br>
				<div class="list-group">
					<h5>Тип:</h5>
					<?php
					$query = "SELECT DISTINCT(typeP_name) FROM nomenclature WHERE kindP_id = '1'";
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
					<p id="price_show">15100-150000</p>
					<div id="price_range"></div>
				</div><br>
				<div class="list-group">
					<h5>Мощность (кВт):</h5>
					<p>при охлаждении:&nbsp;<span id="cold_show">1.5-56.3</span></p>
					<input type="hidden" id="hidden_minimum_cold" value="1.5">
					<input type="hidden" id="hidden_maximum_cold" value="56.3">
					<div id="cold_range"></div><br>
					<p>при обогреве:&nbsp;<span id="warm_show">0-58.6</span></p>
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
			var minimum_cold = $('#hidden_minimum_cold').val();
			var maximum_cold = $('#hidden_maximum_cold').val();
			var minimum_warm = $('#hidden_minimum_warm').val();
			var maximum_warm = $('#hidden_maximum_warm').val();
			var brand = get_filter('brand');
			if (get_filter('invert') != "") {
				var typeComp_invert = get_filter('invert');
			}
			var typeP_name = get_filter('typeTovara');
			$.ajax({
				url:"fetch_data.php?p_id=1",
				method:"POST",
				data:{action:action, minimum_price:minimum_price, maximum_price:maximum_price, minimum_cold:minimum_cold, maximum_cold:maximum_cold, minimum_warm:minimum_warm, maximum_warm:maximum_warm, brand:brand, typeComp_invert:typeComp_invert, typeP_name:typeP_name},
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
	        min:15100,
	        max:160000,
	        values:[15100, 160000],
	        step:500,
	        stop:function(event, ui)
	        {
	            $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
	            $('#hidden_minimum_price').val(ui.values[0]);
	            $('#hidden_maximum_price').val(ui.values[1]);
	            filter_data();
	        }
    	});

    	$('#cold_range').slider({
	        range:true,
	        min:1.5,
	        max:56.3,
	        values:[1.5, 56.3],
	        step:1,
	        stop:function(event, ui)
	        {
	            $('#cold_show').html(ui.values[0] + ' - ' + ui.values[1]);
	            $('#hidden_minimum_cold').val(ui.values[0]);
	            $('#hidden_maximum_cold').val(ui.values[1]);
	            filter_data();
	        }
    	});

    	$('#warm_range').slider({
	        range:true,
	        min:0,
	        max:58.6,
	        values:[0, 58.6],
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


