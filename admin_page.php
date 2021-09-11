<?php
include 'header_admin.php';
if (!isset($_SESSION['user'])) {
	echo "<script>document.location.replace('../login.php');</script>";
}
?>
	<div class="container" style="padding: 15px 0;">
	<?php
	$query = "SELECT * FROM orders WHERE status = 'новый'";
	$statement = $link->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	$i = 0;
	if($total_row > 0) {
		echo "<h5 class='order-title'>Количество новых заказов: " . $total_row . "</h5>";
		if(isset($_GET['save'])){
			$query = "UPDATE clients SET 
			clients.client_surname = '" . $_GET['cSname'] . "', 
			clients.client_name = '" . $_GET['cName'] . "',
			clients.client_otch = '" . $_GET['cOtch'] . "',
			clients.client_adress = '" . $_GET['cAdr'] . "'
			WHERE client_id = " . $_GET['cId'];
			$statement = $link->prepare($query);
			$statement->execute();	

			$query = "SELECT * FROM nomenclature";
			$statement = $link->prepare($query);
			$statement->execute();
			$product = $statement->fetchAll();
			foreach($product as $value){
				if(isset($_GET[$value['product_id']])){
					$sum = $value['price'] *  $_GET[$value['product_id']];
				    $query = "UPDATE products_in_order SET       
				    products_in_order.quantity = " . $_GET[$value['product_id']] . ", products_in_order.sum = " . $sum . "
				    WHERE order_id = " . $_GET['oId'] . " AND product_id = " . $value['product_id'];
				    $statement = $link->prepare($query);
				    $statement->execute();
				}
			}
		}
		if(isset($_GET['accept'])) {
			$query = "UPDATE orders SET 
			orders.tab_n = '" . $_GET['select'] . "', 
			orders.status = 'подтвержден',
			orders.comment = '" . $_GET['comment'] . "'
			WHERE order_id = " . $_GET['oId'];
			$statement = $link->prepare($query);
			$statement->execute();
		}
		if(isset($_GET['cancel'])) {
			$query = "UPDATE orders SET 
			orders.status = 'отменен',
			orders.comment = '" . $_GET['comment'] . "'
			WHERE order_id = " . $_GET['oId'];
			$statement = $link->prepare($query);
			$statement->execute();
		}
		foreach ($result as $order) {
			$i = $i + 1;
			$total = 0;
	?> 
			<div id="collapse-group">
			    <div class="card" style="margin-top: 30px;">
			 		<div class="card-header" style="background-color: #005b96;">
			    		<a data-toggle="collapse" data-parent="#collapse-group" href="<? echo '#el' . $i ?>" class="text-light">Заказ № <?php echo $order[order_id];?></a> <span style="float: right; color: #fff;"><?php echo date("H:i d.m.Y", strtotime($order[order_date])); ?></span>
			    	</div>
				    <div id="<? echo 'el' . $i ?>" class="collapse show" style="background-color: #f7fafb;">
				    	<div class="card-body">
				    	<?php 
			    		//режим редактирования
			    		if(isset($_GET['id']) && ($_GET['id']) == $order[order_id]){ 
			    			if ($_GET['acd'] == 1){
		                		$query = "DELETE FROM products_in_order WHERE product_id = " . $_GET['prId'] . " AND order_id = " . $_GET['id'];
		                		$statement = $link->prepare($query);
								$statement->execute();
							}
				    	?>
				    		<form action="<?$_SERVER['PHP_SELF']?>" method="GET" id="formEdit">
				    			<input type="hidden" name="oId" value="<?php echo $order[order_id];?>"></input>
				    			<input type="hidden" name="cId" value="<?php echo $order[client_id];?>"></input>
				    		<?php
				    			$query = "SELECT * FROM clients WHERE client_id = " . $order[client_id];
				    			$statement = $link->prepare($query);
								$statement->execute();
								$client = $statement->fetch();
							?>
				    		<div>
				    			<p>Заказчик: <input name="cSname" class="edit" value="<?php echo $client[client_surname];?>"></input>
				    				<input name="cName" class="edit" value="<?php echo $client[client_name];?>"></input>
				    				<input name="cOtch" class="edit" value="<?php echo $client[client_otch];?>"></input>
				    				<span style="float: right;">Тел: <?php echo $client[client_phone];?></span></p>
				    			<p>Адрес доставки: <input name="cAdr" class="edit" size="87" value="<?php echo $client[client_adress];?>"></input></p>
				    		</div>
				    		<table class="table table-bordered table-striped table-sm">
				            <tr class="bg-secondary text-light" align="center">
				                <td width="35%">Наименование</td>
				                <td width="15%">Цена</td>
				                <td width="5%">Количество</td>
				                <td width="15%">Стоимость</td>
				                <td width="10%">Редактировать</td>
				            </tr>
			            <?php
			            	$query = "SELECT * FROM products_in_order WHERE order_id = " . $order[order_id];
			            	$statement = $link->prepare($query);
							$statement->execute();
							$pio = $statement->fetchAll();
			                foreach ($pio as $product_in) {
			                	$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $product_in[product_id];
			                	$statement = $link->prepare($query);
								$statement->execute();
								$product = $statement->fetch();
								$total = 0;
								
			                    ?>
			                    <tr>
			                        <td>
			                        	<?php echo $product["product_name"]; ?>
			                        	<input type="hidden" name="pId" value="<?php echo $product_in[product_id];?>"></input>
			                        </td>   
			                        <td align="right"><?php echo number_format($product["price"], 2, '.', ' '); ?></td>
			                        <td align="center" class="quantity">
			                        	<input name="<?php echo $product_in[product_id];?>" type="number" min="1" class="edit" required value="<?php echo $product_in["quantity"]; ?>"></input>
			                        </td>
			                        <td align="right">
			                            <?php echo number_format($product_in[sum], 2, '.', ' '); ?>
			                        </td>
			                        <td align="center" id="el">
			                        	<a href="<? echo 'admin_page.php?prId=' . $product_in[product_id] . '&acd=1&id=' . $order[order_id];?>"><i class="fas fa-trash text-danger"></i></a>
			                        </td>
			                    </tr>			  
			                <?php
			                	
			                    $total = $total + ($product_in["quantity"] * $product["price"]);
			                }
			                	echo "<tr>
				                        <td colspan='3' align='right'>Итого: </td>
				                        <th align='right'>" . number_format($total, 2, '.', ' ') . " ₽</th>
				                        <td></td>
			                    	</tr>
			                	</form>";             		    		
				    	}
			    		else {
			    			//режим просмотра
		    	   			$query = "SELECT * FROM clients WHERE client_id = " . $order[client_id];
			    			$statement = $link->prepare($query);
							$statement->execute();
							$client = $statement->fetch();
			    		?>
			    		<div>
			    			<p>Заказчик: <?php echo $client[client_surname] . " " . $client[client_name] . " " . $client[client_otch];?><span style="float: right;">Тел: <?php echo $client[client_phone];?></span></p>
			    			<p>Адрес доставки: <?php echo $client[client_adress];?></p>
			    		</div>
			    		<table class="table table-bordered table-striped table-sm">
			            <tr class="bg-secondary text-light" align="center">
			                <td width="35%">Наименование</td>
			                <td width="15%">Цена</td>
			                <td width="5%">Количество</td>
			                <td width="15%">Стоимость</td>
			            </tr>
		            <?php
		            	$query = "SELECT * FROM products_in_order WHERE order_id = " . $order[order_id];
		            	$statement = $link->prepare($query);
						$statement->execute();
						$pio = $statement->fetchAll();
		                foreach ($pio as $product_in) {
		                	$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $product_in[product_id];
		                	$statement = $link->prepare($query);
							$statement->execute();
							$product = $statement->fetch();
		            ?>
		                    <tr>
		                        <td><?php echo $product["product_name"]; ?></td>    
		                        <td align="right"><?php echo number_format($product["price"], 2, '.', ' '); ?></td>
		                        <td align="center" class="quantity">
		                        	<?php echo $product_in["quantity"]; ?>
		                        </td>
		                        <td align="right">
		                            <?php echo number_format($product_in["sum"], 2, '.', ' '); ?>
		                        </td>
		                    </tr>
		                    <?php
		                    $total = $total + ($product_in["quantity"] * $product["price"]);
		                    }	                
		                	?>
		                    <tr>
		                        <td colspan="3" align="right">Итого: </td>
		                        <th align="right"><?php echo number_format($total, 2, '.', ' '); ?> ₽</th>
		                    </tr>
		            	<?php
		                }
		            	?>
		            	</table>		            	
			            <?php 
				    		if(isset($_GET['id']) && ($_GET['id']) == $order[order_id]){ 
				    	?>
				    		<div class="buttons">
					    		<input type="submit" form="formEdit" class="btn btn-success" name="save" id="formSubmit" value="Сохранить"></input>
					    		<a href="admin_page.php" type='submit' class="btn btn-danger text-light">Отменить</a>
					    	</div>
				    	<?php
				    		}
				    		else{
				    	?>
				    		<form action="<?$_SERVER['PHP_SELF']?>" method="GET" id="<?php echo $order[order_id];?>">
				    		<div class="masters">
		            		<?php
		            			$query = "SELECT * FROM stuff WHERE s_post = 'мастер'";
		            			$statement = $link->prepare($query);
								$statement->execute();
								$stuff = $statement->fetchAll();
								$out = '';
		            			foreach ($stuff as $empl) {
									$out .= "<option value='" . $empl[tab_n] . "'>" . $empl[s_surname] . " " . mb_substr($empl[s_name], 0, 1, 'UTF-8') . ". " . mb_substr($empl[s_otch], 0, 1, 'UTF-8') .".</option>";
								}
								$out = "<div class='sel-empl'>
									Назначить мастера: <select class='custom-select col-md-3' name='select'>$out</select>
								</div>";
								echo $out;
							?>	            		
			            	</div>
				    		<div class="comment">
			            		<textarea class="form-control col-md-5" maxlength="200" name="comment" cols="60%" rows="2" placeholder="Комментарий к заказу"></textarea>
			            	</div>		           
			            	<div class="buttons">
			            		<input type="hidden" name="oId" value="<?php echo $order[order_id];?>"></input>
			            		<input type="submit" class="btn btn-success" name="accept" value="Принять"></input>
			            		<a href="admin_page.php?id=<?echo $order[order_id];?>" type="button" class="btn btn-secondary text-light">Редактировать</a>
			            		<input type="submit" class="btn btn-danger" name="cancel" value="Отменить"></input>
			            	</div>    	
		            		</form>
			            <?php
			        		}
			        	?>
					    </div>
				    </div>
			    </div>
			</div>
    <?php   
    	}
	}
	else {
		echo "<div class='col-md-4 no-orders'>Новых заказов нет</div>";
	}
	?>
	</div>
	<script type="text/javascript">
	$('#formSubmit').on('click', function() {
	   $('#formEdit').submit();
	}) 
	</script>
</body>
</html>