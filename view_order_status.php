<?php
include 'header.php';
?>
	<div class="container" style="padding: 50px 0;">
	<h5 class="aboutTitle">Введите номер телефона, чтобы проверить статус заказа</h5><br>
	<div class="row justify-content-around">
		<div class="col-md-4">
			<form action="<?$_SERVER['PHP_SELF']?>" method="POST">
				<div class="input-group">
				  <input type="tel" class="form-control" id="phone" name="client_phone" placeholder="+7 ХХХ ХХХ ХХХХ" required aria-label="" aria-describedby="basic-addon1">
				  <div class="input-group-append">
				    <input type="submit" class="btn btn-secondary" name="send" value="Отправить">
				  </div>
				</div><br><br><br>
			</form>
		</div>
		<div class="col-md-7">
	<?php
		if(isset($_POST['send'])){
			$query = "SELECT * FROM clients WHERE client_phone = '" . $_POST['client_phone'] ."'";
			$statement = $link->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach ($result as $cl) {
				$query = "SELECT * FROM orders WHERE client_id = " . $cl['client_id'];
				$statement = $link->prepare($query);
				$statement->execute();
				$order = $statement->fetchAll();
				foreach ($order as $o) {
					if($o['status'] == "новый"){
						echo "<div class='border border-primary rounded order-status'>
							<h5>Заказ № " . $o['order_id'] . " - в обратботке</h5>
							<p>Дата заказа: " . date("H:i d.m.Y", strtotime($o[order_date])) . "<p>
							<p>Заказчик: " . $cl[client_surname] . " " . mb_substr($cl[client_name], 0, 1, 'UTF-8') . ". ". mb_substr($cl[client_otch], 0, 1, 'UTF-8') . ".</p>
						</div>";
					} 
					elseif($o['status'] == "подтвержден"){
						echo "<div class='border border-warning rounded order-status'>
							<h5>Заказ № " . $o['order_id'] . " - принят</h5>
							<p>Дата заказа: " . date("H:i d.m.Y", strtotime($o[order_date])) . "<p>
							<p>Заказчик: " . $cl[client_surname] . " " . mb_substr($cl[client_name], 0, 1, 'UTF-8') . ". ". mb_substr($cl[client_otch], 0, 1, 'UTF-8') . ".</p>
						</div>";
					}
					elseif($o['status'] == "выполнен"){
						echo "<div class='border border-success rounded order-status'>
							<h5>Заказ № " . $o['order_id'] . " - " . $o[status] . "</h5>
							<p>Дата заказа: " . date("H:i d.m.Y", strtotime($o[order_date])) . "<p>
							<p>Заказчик: " . $cl[client_surname] . " " . mb_substr($cl[client_name], 0, 1, 'UTF-8') . ". ". mb_substr($cl[client_otch], 0, 1, 'UTF-8') . ".</p>
						</div>";
					} 
					else{
						echo "Заказов нет или номер введен неправильно";
					} 
				}
			}
		}
	?>
		</div>

	</div>
	</div>
<script>
$(document).ready(function(){
  $("#phone").mask("+7 999 999 9999");
});
</script>
<?php
include 'footer.php';
?>