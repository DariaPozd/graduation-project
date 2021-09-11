<?php
session_start();
include 'connect.php';

if (isset($_POST['action'])) {
	if($_GET['p_id'] == '1'){
		$query = "SELECT * FROM nomenclature WHERE kindP_id = '1'";
		if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"])) {
			$query .= " AND price BETWEEN '" . $_POST["minimum_price"] . "' AND '" . $_POST["maximum_price"] . "'";
		}
		if(isset($_POST["brand"])) {
			$brand_filter = implode("','", $_POST["brand"]);
			$query .= " AND brand IN('" . $brand_filter . "')";
		}
		if(isset($_POST["typeComp_invert"])) {
			$tipComp_filter = implode("','", $_POST["typeComp_invert"]);
			$query .= " AND typeComp_invert IN('" . $tipComp_filter . "')";
		}	
		if(isset($_POST["typeP_name"])) {
			$tipT_filter = implode("','", $_POST["typeP_name"]);
			$query .= " AND typeP_name IN('" . $tipT_filter . "')";
		}
		if(isset($_POST["minimum_cold"], $_POST["maximum_cold"]) && !empty($_POST["minimum_cold"]) && !empty($_POST["maximum_cold"])) {
			$query .= " AND cooling_kW BETWEEN '" . $_POST["minimum_cold"] . "' AND '" . $_POST["maximum_cold"] . "'";
		}
		if(isset($_POST["minimum_warm"], $_POST["maximum_warm"]) && !empty($_POST["minimum_warm"]) && !empty($_POST["maximum_warm"])) {
			$query .= " AND heating_kW BETWEEN '" . $_POST["minimum_warm"] . "' AND '" . $_POST["maximum_warm"] . "'";
		}
	}
	if($_GET['p_id'] == '2'){
		$query = "SELECT * FROM nomenclature WHERE kindP_id = '2'";
		if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"])) {
			$query .= " AND price BETWEEN '" . $_POST["minimum_price"] . "' AND '" . $_POST["maximum_price"] . "'";
		}
		if(isset($_POST["brand"])) {
			$brand_filter = implode("','", $_POST["brand"]);
			$query .= " AND brand IN('" . $brand_filter . "')";
		}	
		if(isset($_POST["typeP_name"])) {
			$tipT_filter = implode("','", $_POST["typeP_name"]);
			$query .= " AND typeP_name IN('" . $tipT_filter . "')";
		}
		if(isset($_POST["minimum_warm"], $_POST["maximum_warm"]) && !empty($_POST["minimum_warm"]) && !empty($_POST["maximum_warm"])) {
			$query .= " AND heating_kW BETWEEN '" . $_POST["minimum_warm"] . "' AND '" . $_POST["maximum_warm"] . "'";
		}
	}
	$statement = $link->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	$output = "";

	if($total_row > 0) {
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
					<br><button type='submit' name='add_cart' class='btn btn-success add_cart'>Купить</button>
				</form>
				</div>
			";
		}
	}
	else {
		$output = "<h3>Результатов нет</h3>";
	}
	echo $output;
}
if (isset($_POST["add_cart"])){
    if (isset($_SESSION["cart"])){
        $item_array_id = array_column($_SESSION["cart"],"product_id");
        if (!in_array($_GET["id"], $item_array_id)){
            $count = count($_SESSION["cart"]);
            $item_array = array(
                'product_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][$count] = $item_array;
            echo '<script>alert ("Added to cart"); window.history.back();</script>';
        }
        else{
            echo '<script>alert ("Already added to cart"); window.history.back();</script>';
        }
    }
    else{
        $item_array = array(
            'product_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'product_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"],
        );
        $_SESSION["cart"][0] = $item_array;
        echo '<script>alert ("Added to cart"); window.history.back();</script>';
    }
}
?>

