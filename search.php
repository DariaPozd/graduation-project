<?php
$search_q = $_POST['search_q'];
include 'header.php';

$search_q = trim($search_q);
$search_q = strip_tags($search_q);

$query = "SELECT * FROM nomenclature WHERE brand LIKE '%$search_q%' OR typeP_name LIKE '%$search_q%'";
$statement = $link->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

$output = "";

echo "<div class='container' style='padding: 50px 0;'>
		<div class='row'>";

if($total_row > 0){
	foreach ($result as $product) {
		$ph = "SELECT image FROM photo WHERE image = '../photo/" . $product[product_id] . "_1.jpg'";
		$statement = $link->prepare($ph);
		$statement->execute();
		$res = $statement->fetch();

		$output .= "
			<div class='col-md-4 border'>
				<form action='fetch_data.php?action=add&id=" . $product[product_id] . "' method='POST' class='cardproduct'>
					<img src='" . $res[image] . "'><br>
					<div class='product-title'><a href = '../product.php?id=" . $product[product_id] . "'>" . $product[product_name] . "</a></div><br> 
					<p>Цена:  <span style='font-size: 23px;'>" . number_format($product[price],  0, '.', ' ') . "&nbsp;₽</span></p><br>
					<input type='hidden' name='quantity' value='1'>
                    <input type='hidden' name='hidden_name' value='" . $product[product_name] . "'>
                    <input type='hidden' name='hidden_price' value='" . $product[price] . "'>
					<button type='submit' name='add_cart' class='btn btn-success add_cart'>Купить</button>
				</form>
			</div>
		";
	}
}
else {
	$output = "<h3>Результатов нет</h3>";
}
echo $output;
echo "</div></div>";
include 'footer.php';
?>