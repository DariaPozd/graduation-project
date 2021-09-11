<?php
include 'header.php';
@session_start();
?>
	<div class="container" style="padding-top: 50px;">          
    <?php
        if(!empty($_SESSION["cart"])){
        	if (isset($_GET['update'])) {
			 	$arrQ = $_GET['quantity'];
			 	for ($i=0; $i<count($_SESSION['cart']); $i++){
			  		$_SESSION['cart'][$i]['item_quantity'] = $arrQ[$i];
			  	}
			}
    ?>
		<h3 class="title2">Выбранные товары</h3><br>
	        <div class="table-responsive">
	        	<form method="GET" action="<?$_SERVER['PHP_SELF']?>">
		            <table class="table table-bordered table-striped">
		            <tr class="bg-secondary text-light" align="center">
		                <td width="35%">Наименование</td>
		                <td width="15%">Цена</td>
		                <td width="5%">Количество<input type="submit" class="btn btn-danger" name="update" value="Обновить"></input></td>
		                <td width="15%">Стоимость</td>
		                <td width="10%">Удалить</td>
		            </tr>
		       	<?php
		            $total = 0;
		            foreach ($_SESSION["cart"] as $key => $value) {
		    	?>
	                <tr>
	                    <td><?php echo $value["item_name"]; ?></td>    
	                    <td align="right"><?php echo number_format($value["product_price"], 2, '.', ' '); ?></td>
	                    <td align="center" class="quantity">	                    	
	                    	<input type="number" min="1" name="quantity[]" value="<?php echo $value['item_quantity']; ?>" size="1" class="form-control quant">	                    	
	                    </td>
	                    <td align="right">
	                        <?php echo number_format($value["item_quantity"] * $value["product_price"], 2, '.', ' '); ?>
	                    </td>
	                    <td align="center" id="el">
	                    	<a href="cart.php?action=delete&id=<?php echo $value['product_id']; ?>">
	                    	<i class="fas fa-trash text-danger"></i></a>
	                    </td>
	                </tr>
	            <?php
	                $total = $total + ($value["item_quantity"] * $value["product_price"]);
	            }
	            ?>
	                <tr>
	                    <td colspan="3" align="right">Итого: </td>
	                    <th align="right"><?php echo number_format($total, 2, '.', ' '); ?> ₽</th>
	                    <td></td>
	                </tr>
		    		</table>
	    		</form>
	    	</div>
    </div>
	    <?php
	    	//удаление товара из корзины
	        if (isset($_GET["action"])){
		        if ($_GET["action"] == "delete"){
		            foreach ($_SESSION["cart"] as $keys => $value){
		                if ($value["product_id"] == $_GET["id"]){
		                    unset($_SESSION["cart"][$keys]);
		                    echo '<script>alert("Товар удален из корзины!")</script>';
		                    echo '<script>window.location="cart.php"</script>';
		                }
		            }
		        }
		    }
        }
        else {
        	echo "<h5 align='center' style='margin:100px 0;'>Ваша корзина пуста. <a href='kondicionery.php'>Перейти к выбору товаров</a></h5>
        	</div>";
        }
        ?>          
    <br><br>
    <div class="container" id="form" style="margin-bottom: 55px;">
    	<h3>Введите данные для оформления заказа:</h3><br>
		<form action="sending_order.php" method="POST" id="form">
			<div class="form-row">
			    <div class="col-md-4 mb-3">
					<label for="validationDefault01">Фамилия</label>
					<input type="text" class="form-control" id="validationDefault01" name="client_surname" placeholder="Иванов" required>
			    </div>
			    <div class="col-md-4 mb-3">
			    	<label for="validationDefault02">Имя</label>
			    	<input type="text" class="form-control" id="validationDefault02" name="client_name" placeholder="Иван" required>
			    </div>
			    <div class="col-md-4 mb-3">
			    	<label for="validationDefault03">Отчество</label>
			    	<input type="text" class="form-control" id="validationDefault03" name="client_otch" placeholder="Иванович" required>
			    </div>
			</div>
			<div class="form-row">
				<div class="col-md-4 mb-3">
					<label for="validationDefault02">Телефон</label>
					<input type="tel" class="form-control" name="client_phone" id="phone" placeholder="+7 ХХХ ХХХ ХХХХ" required>
				</div>
				<div class="col-md-8">
					<label for="validationDefault02">Адрес</label>
					<input type="tel" class="form-control" name="client_adress" id="adress" placeholder="190000, г. Санкт-Петрубрг, ХХХ ХХХ" required>
				</div>
			</div>
			<div style="float: right;">
				<button type="submit" class="btn btn-outline-danger">Оформить заказ</button>
			</div>
		</form>
	</div>       
<script>
$(document).ready(function(){
  $("#phone").mask("+7 999 999 9999");
  var form = $("#form");
  var element = $("#el");
	if(!element.length)
	{
		form.hide();
	} 
	else 
	{
		form.show();
	}
});
</script>
<?php
include 'footer.php';
?>


