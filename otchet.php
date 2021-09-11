<?php
include 'header_admin.php';
if (!isset($_SESSION['user'])) {
	echo "<script>document.location.replace('../login.php');</script>";
}
?>
<div class='container' style='padding: 20px 0;'>	
	<div class="row">
		<div class="col-md-3 form-inline form-group">
			<form action="<?$_SERVER['PHP_SELF']?>" method="GET">
				<select class="form-control" name="month">
					<option value="1">Январь</option>
					<option value="2">Февраль</option>
					<option value="3">Март</option>
					<option value="4">Апрель</option>
					<option value="5">Май</option>
					<option value="6">Июнь</option>
					<option value="7">Июль</option>
					<option value="8">Август</option>
					<option value="9">Сентябрь</option>
					<option value="10">Октябрь</option>
					<option value="11">Ноябрь</option>
					<option value="12">Декабрь</option>
				</select>
				<input type="hidden" name="report" value="<? echo $_GET['report'];?>"></input>
				<input type="submit" class='btn btn-success' name="send" value="Сформировать">
			</form>
		</div>
		<div class="col-md-1">
			<button class='btn btn-secondary' onclick='printDiv()'>Раcпечатать</button><br>
		</div>
	</div>
<?php
	if(isset($_GET['report'])){
		if ($_GET['report'] == 1) {
			report1($link, $_GET['month']);
		}
		if ($_GET['report'] == 2) {
			report2($link, $_GET['month']);
		}
		if ($_GET['report'] == 3) {
			report3($link, $_GET['month']);
		}
		
	}
	function report1($link, $month)
	{
		$monthsList = array('1' => 'январь', '2' => 'февраль','3' => 'март', '4' => 'апрель', '5' => 'май',
			'6' => 'июнь', '7' => 'июль', '8' => 'август', '9' => 'сентябрь', '10' => 'октябрь',
			'11' => 'ноябрь', '12' => 'декабрь');
?>
	<div class="otchet" id="otchet">
		<p style="float: right;"><? echo date('d.m.Y');?></p>
		<br><br><br><br>
		<P style="text-align: center;"><b>Статистический отчет по количеству проданного климатического оборудования ООО "Профклимат"<br>
		за <? echo $monthsList[$month]; ?> 2020 г.</b></P>
		<table class="table table-bordered table-striped table-sm" border="1" cellspacing="0" style="width: 85%; margin: 0 auto;">
	    <tr class="bg-light" align="center">
	    	<td width="5%">№</td>
	        <td width="50%">Наименование</td>
	        <td width="20%">Артикул</td>
	        <td width="20%">Цена</td>
	        <td width="15%">Количество</td>
	    </tr>
	<?php
		$query = "SELECT *, SUM(quantity) as q FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'
			GROUP BY product_id 
			ORDER BY q DESC";
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$query = "SELECT *, SUM(quantity) as totality FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'";
		$statement = $link->prepare($query);
		$statement->execute();
		$res = $statement->fetch();
		$i = 0;
		foreach ($result as $pio) {
			$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $pio[product_id];
			$statement = $link->prepare($query);
			$statement->execute();
			$pr = $statement->fetch();
			$i = $i + 1;
	?>
		<tr>
            <td align="center"><?php echo $i;?></td>    
            <td><?php echo $pr[product_name]; ?></td>
            <td align="center"><?php echo $pio[product_id]; ?></td>
            <td align="center"><?php echo $pr[price]; ?></td>
            <td align="center"><?php echo $pio[q]; ?></td>
        </tr>
	<?php
		}
	?>
		<tr class="bg-secondary text-light">
            <td colspan='4' align='right'><b>Итого: </b></td>
            <td align='center'><b><?php echo $res[totality]; ?></b></td>
    	</tr>
		</table>
	</div>
<?php
	}

	function report2($link, $month)
	{
		$monthsList = array('1' => 'январь', '2' => 'февраль','3' => 'март', '4' => 'апрель', '5' => 'май',
			'6' => 'июнь', '7' => 'июль', '8' => 'август', '9' => 'сентябрь', '10' => 'октябрь',
			'11' => 'ноябрь', '12' => 'декабрь');
?>
	<div class="otchet" id="otchet">
		<p style="float: right;"><? echo date('d.m.Y');?></p>
		<br><br><br><br>
		<P style="text-align: center;"><b>Статистический отчет по количеству проданного климатического оборудования с указанием итоговых сумм ООО "Профклимат"<br>
		за <? echo $monthsList[$month]; ?> 2020 г.</b></P>
		<table class="table table-bordered table-striped table-sm" border="1" cellspacing="0" style="width: 85%; margin: 0 auto;">
	    <tr class="bg-light" align="center">
	    	<td width="5%">№</td>
	        <td width="50%">Наименование</td>
	        <td width="10%">Артикул</td>
	        <td width="15%">Цена</td>
	        <td width="5%">Количество</td>
	        <td width="15%">Итоговая сумма</td>
	    </tr>
	<?php
		$query = "SELECT *, SUM(quantity) as q, SUM(sum) as amount FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'
			GROUP BY product_id 
			ORDER BY amount DESC";
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$query = "SELECT *, SUM(quantity) as totality, SUM(sum) as lump_sum FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'";
		$statement = $link->prepare($query);
		$statement->execute();
		$res = $statement->fetch();
		$i = 0;
		foreach ($result as $pio) {
			$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $pio[product_id];
			$statement = $link->prepare($query);
			$statement->execute();
			$pr = $statement->fetch();
			$i = $i + 1;
	?>
		<tr>
            <td align="center"><?php echo $i;?></td>    
            <td><?php echo $pr[product_name]; ?></td>
            <td align="center"><?php echo $pio[product_id]; ?></td>
            <td align="center"><?php echo $pr[price]; ?></td>
            <td align="center"><?php echo $pio[q]; ?></td>
            <td align="center"><?php echo $pio[amount]; ?></td>
        </tr>
	<?php
		}
	?>
		<tr class="bg-secondary text-light">
            <td colspan='4' align='right'><b>Итого: </b></td>
            <td align='center'><b><?php echo $res[totality]; ?></b></td>
            <td align='center'><b><?php echo $res[lump_sum]; ?></b></td>
    	</tr>
		</table>
	</div>
<?php
	}

	function report3($link, $month)
	{
		$monthsList = array('1' => 'январь', '2' => 'февраль','3' => 'март', '4' => 'апрель', '5' => 'май',
			'6' => 'июнь', '7' => 'июль', '8' => 'август', '9' => 'сентябрь', '10' => 'октябрь',
			'11' => 'ноябрь', '12' => 'декабрь');
?>
	<div class="otchet" id="otchet">
		<p style="float: right;"><? echo date('d.m.Y');?></p>
		<br><br><br><br>
		<P style="text-align: center;"><b>Статистический отчет по количеству проданного климатического оборудования и оказанных услуг с указанием итоговых сумм ООО "Профклимат"<br>
		за <? echo $monthsList[$month]; ?> 2020 г.</b></P>
		<table class="table table-bordered table-striped table-sm" border="1" cellspacing="0" style="width: 85%; margin: 0 auto;">
	    <tr class="bg-light" align="center">
	    	<td width="5%">№</td>
	        <td width="55%">Наименование услуги</td>
	        <td width="10%">Код услуги</td>
	        <td width="10%">Цена</td>
	        <td width="5%">Количество</td>
	        <td width="20%">Итоговая сумма</td>
	    </tr>
	<?php
		$query = "SELECT *, SUM(quantity) as sum, SUM(sum) as price FROM orders, services_in_order 
			WHERE orders.order_id = services_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'
			GROUP BY service_id 
			ORDER BY price DESC";
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$query = "SELECT *, SUM(quantity) as totality, SUM(sum) as lump_sum FROM orders, services_in_order 
			WHERE orders.order_id = services_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'";
		$statement = $link->prepare($query);
		$statement->execute();
		$res = $statement->fetch();
		$i = 0;
		foreach ($result as $sio) {
			$query = "SELECT service_name, service_price FROM services WHERE service_id = " . $sio[service_id];
			$statement = $link->prepare($query);
			$statement->execute();
			$serv = $statement->fetch();
			$i = $i + 1;
	?>
		<tr>
            <td align="center"><?php echo $i;?></td>    
            <td><?php echo $serv[service_name]; ?></td>
            <td align="center"><?php echo $sio[service_id]; ?></td>
            <td align="center"><?php echo $serv[service_price]; ?></td>
            <td align="center"><?php echo $sio[sum]; ?></td>
            <td align="center"><?php echo $sio[price]; ?></td>
        </tr>
	<?php
		}
	?>
		<tr class="bg-secondary text-light">
            <td colspan='4' align='right'><b>Итого: </b></td>
            <td align='center'><b><?php echo $res[totality]; ?></b></td>
            <td align='center'><b><?php echo $res[lump_sum]; ?></b></td>
    	</tr>
		</table><br><br><div style="page-break-after: always;"></div>
		<table class="table table-bordered table-striped table-sm" border="1" cellspacing="0" style="width: 85%; margin: 0 auto;">
	    <tr class="bg-light" align="center">
	    	<td width="5%">№</td>
	        <td width="55%">Наименование оборудования</td>
	        <td width="10%">Артикул</td>
	        <td width="10%">Цена</td>
	        <td width="5%">Количество</td>
	        <td width="20%">Итоговая сумма</td>
	    </tr>
	<?php
		$query = "SELECT *, SUM(quantity) as sum, SUM(sum) as price FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'
			GROUP BY product_id 
			ORDER BY price DESC";
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$query = "SELECT *, SUM(quantity) as totality, SUM(sum) as lump_sum FROM orders, products_in_order 
			WHERE orders.order_id = products_in_order.order_id AND MONTH(order_date) = " . $_GET['month'] . " AND orders.status = 'выполнен'";
		$statement = $link->prepare($query);
		$statement->execute();
		$res = $statement->fetch();
		$i = 0;
		foreach ($result as $pio) {
			$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $pio[product_id];
			$statement = $link->prepare($query);
			$statement->execute();
			$pr = $statement->fetch();
			$i = $i + 1;
	?>
		<tr>
            <td align="center"><?php echo $i;?></td>    
            <td><?php echo $pr[product_name]; ?></td>
            <td align="center"><?php echo $pio[product_id]; ?></td>
            <td align="center"><?php echo $pr[price]; ?></td>
            <td align="center"><?php echo $pio[sum]; ?></td>
            <td align="center"><?php echo $pio[price]; ?></td>
        </tr>
	<?php
		}
	?>
		<tr class="bg-secondary text-light">
            <td colspan='4' align='right'><b>Итого: </b></td>
            <td align='center'><b><?php echo $res[totality]; ?></b></td>
            <td align='center'><b><?php echo $res[lump_sum]; ?></b></td>
    	</tr>
		</table>
	</div>
<?php
	}
?>
<script type='text/javascript'>
function printDiv() {
 var divToPrint = document.getElementById('otchet');
 var newWin = window.open('','Print-Window');
 newWin.document.open();
 newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
 newWin.document.close();
 setTimeout(function(){newWin.close();},1000);
}
</script>
</div>