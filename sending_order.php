<?php
@session_start();
include 'header.php';

$c_surname = trim($_POST[client_surname]);
$c_name = trim($_POST[client_name]);
$c_otch = trim($_POST[client_otch]);
$c_phone = $_POST[client_phone];
$c_adress = trim($_POST[client_adress]);

$c_surname = ucfirst($c_surname);
$c_name = ucfirst($c_name);
$c_otch = ucfirst($c_otch);

$query = "INSERT INTO clients(client_surname, client_name, client_otch, client_phone, client_adress) VALUES ('$c_surname', '$c_name', '$c_otch', '$c_phone', '$c_adress')";
$statement = $link->prepare($query);
$statement->execute();
$c_id = $link->lastInsertId();

$query = "INSERT INTO orders(order_date, client_id) VALUES ('" . date("Y-m-d H:i:s") . "', $c_id)";
$statement = $link->prepare($query);
$statement->execute();
$or_id = $link->lastInsertId();

foreach ($_SESSION["cart"] as $key => $value) {
	$total = ($value["item_quantity"] * $value["product_price"]);
	$query = "INSERT INTO products_in_order(order_id, product_id, quantity, sum) VALUES ($or_id, " . $value[product_id] . ", " . $value["item_quantity"] . ", " . $total . ")";
	$statement = $link->prepare($query);
	$statement->execute();
}

session_unset();

if($statement->rowCount() > 0) {
	echo "
		<div style='width: 300px; margin: 150px auto;'>
			<h3>Ваш заказ отправлен!</h3>
		</div>
	";
}

include 'footer.php';
?>