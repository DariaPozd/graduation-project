<?php
include 'header.php';
@session_start();
?>
	<div class="container" style="padding: 50px 0;">
		<?php
		$query = "SELECT * FROM nomenclature WHERE product_id = " . $_GET[id];
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetch();
		if (isset($result)) {
			echo "<h3 class='aboutTitle'>" . $result[product_name] . "</h3>";
		?>
			<div class="row" style="height: 420px;">
				<div class="col-md-2 r">
				<?php
				$photo = "SELECT image FROM photo WHERE product_id = '" . $_GET[id] . "'";
				$statement = $link->prepare($photo);
				$statement->execute();
				$ph = $statement->fetchAll();
				foreach ($ph as $im) {
					echo "<div  class='c'>
					<img src='" . $im[image] . "' onclick='openImg(this);'>
					</div>";
				}
				echo "<form action='fetch_data.php?action=add&id=" . $result[product_id] . "' method='POST'>";		
				?>
				</div>
				<div class="col-md-6 cont">				  
				    <?php echo "<img id='expandedImg' src='../photo/". $_GET[id]."_1.jpg'>"; ?>
					<div id="imgtext"></div>
				</div>
				<div class="col-md-4" style="padding: 50px 100px;">
					<?php echo "<p style='font-size: 24px;'>Цена:  " . number_format($result[price],  0, '.', ' ') . "&nbsp;₽</p><br>"; ?>
						<input type="hidden" name="quantity" value='1'>
						<input type="hidden" name="hidden_name" value="<? echo $result[product_name]?>">
	                    <input type="hidden" name="hidden_price" value="<? echo $result[price]?>">
				    <button type='submit' name='add_cart' class='btn btn-success add_cart'>В корзину</button>
				</div>
				</form>
			</div>
			<?php
			if (!is_null($result[description])) {
				echo "<h5>Описание</h5><p>" . $result[description] . "</p>";
			}
			?>
			<h5>Характеристики</h5>
			<?php
			echo "<p>" . $result[characteristics] . "</p>";
			?>
		<?php
		}
		?>
<script type="text/javascript">
	function openImg(imgs) {
  		// Получение расширенного изображения
		var expandImg = document.getElementById("expandedImg");
		var imgText = document.getElementById("imgtext");
  		// Использование того же самого src в расширенном изображении, что и изображение, на которое щелк. по сетке
		expandImg.src = imgs.src;
		imgText.innerHTML = imgs.alt;
  		// Показать элемент контейнера (скрытый с помощью CSS)
		expandImg.parentElement.style.display = "block";
	}
</script>
	</div>
<?php
include 'footer.php';
?>