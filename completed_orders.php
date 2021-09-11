<?php
include 'header_admin.php';
if (!isset($_SESSION['user'])) {
	echo "<script>document.location.replace('../login.php');</script>";
}
error_reporting(0);
?>
	<div class="container" style="padding: 15px 0;">
	<?php
	//вывод всех выполняемых заказов
	$query = "SELECT * FROM orders WHERE status = 'выполнен' ORDER BY order_date DESC";
	$statement = $link->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	$i = 0;
	if($total_row > 0) {
		echo "<h5 class='order-title'>Количество выполненных заказов: " . $total_row . "</h5>";
		foreach ($result as $order) {
			$i++;
			$total = 0;
			$query = "SELECT * FROM stuff WHERE tab_n = " . $order[tab_n];
			$statement = $link->prepare($query);
			$statement->execute();
			$empl = $statement->fetch();
	?> 
			<div id="collapse-group">
			    <div class="card" style="margin-top: 30px;">
			 		<div class="card-header bg-secondary" >
			    		<a data-toggle="collapse" data-parent="#collapse-group" href="<? echo '#el' . $i ?>" class="text-light">Заказ № <?php echo $order[order_id]; ?></a>
			    		<span style="color: #fff;"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $empl[s_surname]. " " . mb_substr($empl[s_name], 0, 1, 'UTF-8') . ". " . mb_substr($empl[s_otch], 0, 1, 'UTF-8') . ".";?></span>
			    		<span style="float: right; color: #fff;"><?php echo date("d.m.Y", strtotime($order[order_date]));?></span>
			    	</div>
				    <div id="<? echo 'el' . $i ?>" class="collapse in" style="background-color: #f7fafb;">
				    	<div class="card-body">
				    	<?php
				    	if ($_GET['acd'] == 1){
	                		$query = "DELETE FROM services_in_order WHERE service_id = " . $_GET['sId'] . " AND order_id = " . $_GET['id'];
	                		$statement = $link->prepare($query);
							$statement->execute();
						}
			    			//режим просмотра
		    	   			$query = "SELECT * FROM clients WHERE client_id = " . $order[client_id];
			    			$statement = $link->prepare($query);
							$statement->execute();
							$client = $statement->fetch();
			    		?>		    		
				    		<div>
				    			<p>Заказчик: <?php echo $client[client_surname] . " " . $client[client_name] . " " . $client[client_otch];?><span style="float: right;">Тел: <?php echo $client[client_phone]; ?></span></p>
				    			<p>Адрес доставки: <?php echo $client[client_adress]; ?></p>
				    			<p>Выполнял: <?php echo $empl[s_surname]. " " . mb_substr($empl[s_name], 0, 1, 'UTF-8') . ". " . mb_substr($empl[s_otch], 0, 1, 'UTF-8') . "."; ?></p>
				    		</div>
				    	<form action="<?$_SERVER['PHP_SELF']?>" method="GET" id="formQ">
				    		<input type="hidden" name="oId" name="oId" value="<?php echo $order[order_id];?>">
				    		<table class="table table-bordered table-striped table-sm" >
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
			                $query = "SELECT * FROM services_in_order WHERE order_id = " . $order[order_id];
			            	$statement = $link->prepare($query);
							$statement->execute();
							$sio = $statement->fetchAll();
			                foreach ($sio as $service_in) {
			                	$query = "SELECT service_name, service_price FROM services WHERE service_id = " . $service_in[service_id];
			                	$statement = $link->prepare($query);
								$statement->execute();
								$service = $statement->fetch();
			            ?>
			                    <tr>
			                        <td><?php echo $service["service_name"]; ?></td>    
			                        <td align="right"><?php echo number_format($service["service_price"], 2, '.', ' '); ?></td>
			                        <td align="center" class="quantity">
			                        	<?php echo $service_in["quantity"]; ?>
			                        </td>
			                        <td align="right">
			                            <?php echo number_format($service_in["sum"], 2, '.', ' '); ?>
			                        </td>
			                    </tr>
			                <?php
			                    $total = $total + $service_in["sum"];
			                }	                
			                ?>                
			                    <tr>
			                        <td colspan="3" align="right">Итого: </td>
			                        <th align="right"><?php echo number_format($total, 2, '.', ' '); ?> ₽</th>
			                    </tr>
			            	</table>
			            </form>								            			    		
				            	<div class="buttons">
				            	<form action="<?$_SERVER['PHP_SELF']?>" method="GET">
					            	<input type="hidden" name="oId" value="<?php echo $order[order_id];?>"></input>
					            	<input type="hidden" name="oPr" value="<?php echo $total;?>"></input>
				            		<input type="submit" class="btn btn-secondary" name="print" value="Распечатать договор"></input>
				            	</div>
			            	</form>  	
					    </div>
				    </div>
			    </div>
			</div>
    <?php   
    	}
	}
	else {
		echo "<div class='col-md-4 no-orders'>Выполненных заказов нет</div>";
	}
	//вывод договора на печать
	if(isset($_GET['print'])){
		$query = "UPDATE orders SET orders.order_price = " . $_GET['oPr'] . " WHERE order_id = " . $_GET['oId'];
		$statement = $link->prepare($query);
		$statement->execute();
		printContract($link,$total);
	}
	echo "</div>";
	//печать договора
	function printContract($link,$total)
	{
	?>
	<div class="dogovor" id="DivIdToPrint">
	<?php
		$query = "SELECT * FROM orders WHERE order_id = " . $_GET['oId'];
		$statement = $link->prepare($query);
		$statement->execute();
		$result = $statement->fetch();		

		$query = "SELECT * FROM clients WHERE client_id = " . $result[client_id];
		$statement = $link->prepare($query);
		$statement->execute();
		$client = $statement->fetch();

		$monthsList = array(".01." => "января", ".02." => "февраля", 
		".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня", 
		".07." => "июля", ".08." => "августа", ".09." => "сентября",
		".10." => "октября", ".11." => "ноября", ".12." => "декабря");
		$currentDate = date("d.m.Y", strtotime($result[order_date]));
		$mD = date(".m.", strtotime($currentDate)); //для замены
		$currentDate = str_replace($mD, " ".$monthsList[$mD]." ", $currentDate);
	?>
		<div align="center"><b>Договор на продажу и монтаж климатического оборудования №<?php echo $_GET['oId'];?></b></div><br>
	<br>
	<div style="float: left; display: inline-block;">г. Санкт-Петербург</div><div style="float: right; display: inline-block;"><? echo $currentDate;?> г.</div><br>
	<div style=" text-align: justify;">
		<p style="text-indent: 35px;"><?php echo $client[client_surname] . " " .  $client[client_name] . " " . $client[client_otch];?>, именуемый(-ая) в дальнейшем <b>"Заказчик"</b> с одной стороны, и Общество с Ограниченной Ответственностью «Профклимат», именуемое в дальнейшем <b>«Подрядчик»</b>, в лице Генерального директора Каземирова Виктора Алексеевича, действующего на основании Устава, совместно именуемые в дальнейшем <b>«Стороны»</b>, заключили настоящий Договор о нижеследующем:</p>
		<p style="text-indent: 55px;"><b>1. Предмет договора</b></p>
		<p style="text-indent: 35px;">Подрядчик обязуется передать в собственность Заказчика оборудование и расходные материалы, именуемые в дальнейшем «Товар», а также произвести монтаж этого оборудования и пусконаладочные работы согласно Техническому Заданию (Приложение №1 к настоящему Договору), а Заказчик обязуется оплатить стоимость оборудования, расходных материалов, монтажа и пусконаладочных работ.</p>
		<p style="text-indent: 55px;"><b>2. Цена договора. Спецификации</b></p>
		<span style="margin-left: 35px;">2.1.</span> Спецификация Товара и Услуг установлена сторонами в Приложении №1 к настоящему Договору.<br>
		<span style="margin-left: 35px;">2.2.</span> Цена по настоящему Договору составляет <?php echo $result[order_price];?> руб. 00 коп.<br>( __________________________________________________________ рублей 00 коп.)<br>
		<span style="margin-left: 35px;">2.3.</span> В цену Договора входят стоимость самого товара и расходных материалов, тары, упаковки и маркировки Товара, а так же стоимость монтажных и пусконаладочных работ (далее по тексту – «Работ»).<br>
		<span style="margin-left: 35px;">2.4.</span> НДС в сумме 20% включен в стоимость Договора.<br>
		<p style="text-indent: 55px;"><b>3. Срок действия Договора</b></p>
		<span style="margin-left: 35px;">3.1.</span> Настоящий Договор вступает в силу с момента его подписания сторонами и действует до полного выполнения сторонами взятых на себя обязательств.<br>
		<span style="margin-left: 35px;">3.2.</span> Стороны договорились и установили, что срок передачи Товара (согласно Спецификации), включая его поставку, составляет 7 (семь) рабочих дней с момента поступления авансового платежа на счёт Подрядчика в соответствии с п.6.2.<br>
		<span style="margin-left: 35px;">3.3.</span> Срок выполнения Работ составляет 12 (двенадцать) рабочих дней.</p>
		<p style="text-indent: 55px;"><b>4. Условия поставки товара</b></p>
		<span style="margin-left: 35px;">4.1.</span> Поставка Товара осуществляется Подрядчиком и считается исполненной после пе-редачи Товара Покупателю по Накладной с проставлением на ней подписей представите-лей обеих сторон не позднее 5 (пяти) банковских дней с момента поступления полной предоплаты за Товар на счёт Подрядчика.<br>
		<span style="margin-left: 35px;">4.2.</span> Доставка Товара осуществляется бесплатно (в пределах КАД Санкт-Петербурга) в день, запланированный сторонами для начала монтажных и пусконаладочных работ.<br>
		<span style="margin-left: 35px;">4.3.</span> Оплата доставки Товара за пределы КАД производится по прейскуранту Подряд-чика и составляет 1200 руб. 00 коп. (Одну тысячу двести рублей 00 коп.).<br>
		<span style="margin-left: 35px;">4.4.</span> При необходимости подвоза грузов к погрузочной площадке грузового лифта в паркинге, Подрядчику необходимо накануне предупредить Заказчика об этом. Максимальная высота -2,2 м («Газель» – только бортовая, с фургоном/тентом не входит).<br>
		<p style="text-indent: 55px;"><b>5. Тара и упаковка</b></p>
		<span style="margin-left: 35px;">5.1.</span> Тара и упаковка Товара должна соответствовать принятой для подобного рода товаров, а также обеспечивать сохранность Товара при его транспортировке.<br>
		<p style="text-indent: 55px;"><b>6. Условия платежа</b></p>
		<span style="margin-left: 35px;">6.1.</span> Платёж по Договору за исполненное Подрядчиком обязательство (проданный Товар, выполненные Работы) осуществляется Заказчиком в сроки, установленные в п. 6.2., в рублях.<br>
		<span style="margin-left: 35px;">6.2.</span> Порядок расчетов по Договору:<br>
		<span style="margin-left: 35px;">6.2.1.</span> Оплата стоимости Товара производится путём безналичного перечисления де-нежных средств с расчётного счёта Заказчика на расчётный счёт Подрядчика. Оплата со-ставляет 100% (сто процентов) стоимости товара.<br>
		<span style="margin-left: 35px;">6.2.2.</span> Оплата стоимости расходных материалов и услуг (монтажных и пусконаладоч-ных работ) производится путём безналичного перечисления денежных средств с расчёт-ного счёта Заказчика на расчётный счёт Подрядчика. Оплата составляет 100% (сто про-центов) стоимости расходных материалов и услуг.<br>
		<p style="text-indent: 55px;"><b>7. Права и обязанности сторон</b></p>
		<span style="margin-left: 35px;">7.1.</span> После получения платежа, согласно п. 6.2. настоящего договора, Подрядчик обязан уведомить Заказчика о своей готовности произвести Работы, а Заказчик обязан указать (подтвердить) дату проведения и место Работ.<br>
		<span style="margin-left: 35px;">7.2.</span> В целях выполнения Подрядчиком Работ во исполнение Договора, Заказчик обязан своими силами и средствами обеспечить возможность надлежащего проведения таковых, включая подготовку и доступность места выполнения Работ.<br>
		<span style="margin-left: 35px;">7.3.</span> Заказчик обязан обеспечить на весь срок проведения Работ соответствие мест установки оборудования требованиям эксплуатационной документации Изготовителя, правилам Техники безопасности и пожарной безопасности.<br>
		<span style="margin-left: 35px;">7.4.</span> Подрядчик отвечает за соблюдение техники безопасности своими специалистами во время проведения ими Работ в месте, определённом Заказчиком.<br>
		<span style="margin-left: 35px;">7.5.</span> Сторона Договора имеет право в случае нарушения другой стороной требований п.7.1, п.7.2., п.7.3, п.7.4, а также п.4.1 перенести сроки выполнения Работ, а также взыс-кать с виновной стороны штраф и убытки в соответствии с условиями настоящего Дого-вора.<br>
		<span style="margin-left: 35px;">7.6.</span> В случае перенесения сроков выполнения Работ согласно п. 7.5., стороны подпи-сывают соответствующий Протокол и назначают новую дату проведения Работ.<br>
		<span style="margin-left: 35px;">7.7.</span> Работы производятся непосредственно Подрядчиком либо его Контрагентом.<br>
		<p style="text-indent: 55px;"><b>8. Ответственность сторон</b></p>
		<span style="margin-left: 35px;">8.1.</span> В случае несвоевременного выполнения Подрядчиком условий настоящего Дого-вора Подрядчик выплачивает Заказчику неустойку в размере 0,1 процента от стоимости Товара по Договору за каждый день просрочки.<br>
		<span style="margin-left: 35px;">8.2.</span> При несвоевременной оплате исполненного по Договору обязательства Заказчик уплачивает Подрядчику неустойку в размере 0,1 процента от суммы просроченного пла-тежа за каждый день просрочки.<br>
		<span style="margin-left: 35px;">8.3.</span> При несвоевременной поставке Товара и/или проведения монтажных работ Под-рядчик уплачивает Заказчику неустойку в размере 0,1 процента от суммы просроченного платежа за каждый день просрочки.<br>
		<span style="margin-left: 35px;">8.4.</span> Ответственность за сохранность находящегося в монтаже оборудования и ком-плектующих материалов, в размере полной стоимости таковых, несёт Заказчик.<br>
		<span style="margin-left: 35px;">8.5.</span> В Случае, указанном в п.7.6., виновная в нарушении условий Договора сторона вы-плачивает штраф другой стороне в размере трёх процентов от стоимости Работ.<br>
		<span style="margin-left: 35px;">8.6.</span> Необоснованное уклонение (отказ) от подписания необходимых документов (п.4.1, п.7.6) понимается как просрочка исполнения стороной своих обязательств по настоящему Договору.<br>
		<span style="margin-left: 35px;">8.7.</span> Все споры и разногласия, которые могут возникнуть по настоящему Договору или в связи с ним, если не решены путём переговоров, подлежат передаче в Арбитражный Суд Санкт-Петербурга и Ленинградской области.<br>
		<p style="text-indent: 55px;"><b>9. Гарантийные обязательства</b></p>
		<span style="margin-left: 35px;">9.1.</span> Качество и комплектность Товара должны соответствовать указанным в паспорте товара Изготовителем.<br>
		<span style="margin-left: 35px;">9.2.</span> Подрядчик обязуется осуществлять ремонт/замену неисправных деталей, выполнять необходимые для этого работы за свой счёт после подписания Акта приёма-сдачи Работ в течение срока и при соблюдении условий, оговорённых в Гарантийном талоне на Товар. При этом гарантийный срок не может быть менее одного года на Товар.<br>
		<span style="margin-left: 35px;">9.3.</span> Гарантия на монтажные работы составляет 3 года с момента подписания Акта приёма-сдачи Работ при условии ежегодного технического обслуживания оборудования.<br>
		<span style="margin-left: 35px;">9.4.</span> Устранение неисправностей оборудования, возникших вследствие несоблюдения техники его эксплуатации или правил хранения, либо вследствие действий третьих лиц или обстоятельств непреодолимой силы, производится за счёт Заказчика.<br>
		<p style="text-indent: 55px;"><b>10. Форс-мажор</b></p>
		<span style="margin-left: 35px;">10.1.</span> Ни одна из сторон не будет нести ответственности за полное или частичное неисполнение любой из своих обязанностей, если такое неисполнение будет являться след-ствием обстоятельств непреодолимой силы, таких как наводнение, пожар, землетрясение и другие стихийные бедствия, война или военные действия, а также следствием вступления в силу акта нормативного характера государственного органа, имеющего обязатель-ную силу хотя бы для одной из сторон Договора и других подобных обстоятельств, возникших после заключения настоящего Договора.<br>
		<span style="margin-left: 35px;">10.2.</span> Если обстоятельства, указанные в п.10.1, длятся более 1 месяца, стороны имеют право в одностороннем порядке расторгнуть Договор.<br>
		<p style="text-indent: 55px;"><b>11. Прочие условия</b></p>
		<span style="margin-left: 35px;">11.1.</span> Все изменения и дополнения к настоящему Договору действительны лишь в том случае, если они изложены в письменной форме и подписаны обеими сторонами.<br>
		<span style="margin-left: 35px;">11.2.</span> Права и обязанности сторон по настоящему Договору могут быть переданы по письменному согласию сторон третьим лицам, за исключением оговоренных в п.7.7 и п. 9.2. настоящего Договора.<br>
		<span style="margin-left: 35px;">11.3.</span> Неоднократное нарушение Заказчиком требований п.7.2, п.7.3 и п.7.6. признаётся как существенное нарушение условий настоящего Договора.<br>
		<span style="margin-left: 35px;">11.4.</span> Настоящий Договор подписан в двух экземплярах - по одному экземпляру для каждой стороны, оба экземпляра имеют равную юридическую силу.<br>
		<span style="margin-left: 35px;">11.5.</span> Риск существенного изменения обстоятельств, из которых стороны исходили, заключая настоящий Договор, несёт Заказчик.<br>
		<span style="margin-left: 35px;">11.6.</span> В случае расторжения Договора стоимость использованных в процессе монтажа расходных материалов Покупателю не возмещается.<br>
		<p style="text-indent: 55px;"><b>12. Примечания</b></p>
		<span style="margin-left: 35px;">При</span> поставке оборудования  допускается поставка более новых моделей, имеющих отличия (расхождения) от буквенной аббревиатуры, указанной в спецификации (спецификациях), без изменений основных технических характеристик в связи с совершенствованием моделей фирмой-изготовителем.<br>
		<span style="margin-left: 35px;">Обязанность</span> за получение необходимых согласований (разрешений) собственником здания и (или) городскими службами для монтажа оборудования на фасадной части здания и (или) других его частях, если эти согласования потребуются, возлагается на Заказчика, при этом монтажную схему установки внешнего блока и фреоновой трассы должен сформировать Подрядчик и передать Заказчику не позднее трёх дней после подписания настоящего Договора.<br>
		<span style="margin-left: 35px;">Право</span> собственности на оборудование, а также риск его случайной гибели или порчи переходят с Подрядчика на Заказчика с момента передачи оборудования Представителем Подрядчика Заказчику.<br>
		<span style="margin-left: 35px;">Заказчик</span> обеспечивает соответствие сети электроснабжения потребляемой мощности монтируемого оборудования. Кроме того, Заказчик представляет схему расположения магистралей Электроснабжения в местах крепления оборудования и прокладки трубопроводов. Если при проведении Работ, в частности, при сверлении отверстий в стенах, будет поврежден кабель электропитания, о местоположении которого Заказчик не уведомил Подрядчика, все возникшие с этим убытки и ответственность несет Заказчик.<br>
		<span style="margin-left: 35px;">Демонтаж</span> и (или) перемещение установленных кондиционеров, а также удлинение дренажного трубопровода или его чистка (в случае засора) производится по желанию Заказчика за дополнительную плату и не является гарантийным ремонтом. Стоимость указанных выше работ определяется менеджером в каждом конкретном случае отдельно.<br>
		<span style="margin-left: 35px;">Если</span> в процессе выполнения работ возникнет необходимость проведения дополни-тельных общестроительных, такелажных, подъемно-транспортных или электротехнических работ, стоимость которых не была включена в спецификацию, а также использование дополнительных, не вошедших в спецификацию расходных материалов (термоизоля-ционных и дренажных труб, электрического кабеля, декоративных коробов, кронштейнов и пр.), стороны рассмотрят вопрос о составлении дополнительной спецификации и оплаты по выставленному Подрядчиком счёту в случае выполнения Работ силами Подрядчика, либо Заказчик выполняет эти работы своими силами и за свой счет.<br>
		<span style="margin-left: 35px;">Заказчик</span> ознакомлен с основными техническими характеристиками поставляемого оборудования, а также с особенностями его монтажа и последующей эксплуатации в различные сезонные периоды, в частности, невозможностью эксплуатации при положитель-ных температурах (выше +46 градусов Цельсия), если компрессорная часть оборудования находится в невентилируемом замкнутом техническом помещении. Подрядчик не может гарантировать соответствие технических возможностей оборудования требованиям За-казчика, выходящим за пределы этих возможностей.<br>
		<span style="margin-left: 35px;">Монтажные</span> работы с повышенным уровнем шума должны вестись в период с 10:00 до 13:00 и с 16:00 до 18:00 по будням.<br>
		<span style="margin-left: 35px;">Работы</span> по монтажу должны выполняться в два этапа:<br>
		<span style="margin-left: 35px;">1.	Монтаж оборудования и фреонотрасс, электропитания и дренажа.</span><br>
		<span style="margin-left: 35px;">2.	Пуско-наладка после выполнения чистовой отделки помещений.</span><br>
		<span style="margin-left: 35px;">Все приложения к настоящему Договору являются его неотъемлемой частью.</span><br>
		<span style="margin-left: 35px;">Все остальные условия, не предусмотренные настоящим Договором, регулируются нормами Российского Гражданского Законодательства.<br></span>
		<p style="text-indent: 55px;"><b>13. Реквизиты и подписи сторон</b></p>	
		<div style="height: 200px;">
			<div style="float: left; width: 300px; display: inline-block;">
				<?php
					echo $client[client_adress] . "<br>
					Тел. " . $client[client_phone];
				?>			
			</div>
			<div style="float: right;">
				194214, г.Санкт-Петербург, Удельный пр.,<br>
				д. 31, лит.А, помещение 10-Н<br>
				Тел. +7(812)9041898<br>
				ИНН 7802585734, КПП 780201001<br>
				ОГРН 1167847280039<br>
				р/с 40702810803000010800<br>
				к/с 30101810100000000723<br>
				Филиал «Северная столица»<br>
				АО «Райффайзенбанк»<br>
				г.Санкт-Петербург<br>
				БИК 044030723<br><br>
				Подрядчик:<br>
				Генеральный директор<br>
				ООО «Профклимат»<br><br>
				__________/Каземиров В.А/<br><br>
				М.П.<br>
			</div>
		</div>
		<br>
		<div style="float: left;">
			Заказчик:<br>
			__________/<?php echo $client[client_surname] . " " . mb_substr($client[client_name], 0, 1, 'UTF-8') . ". ". mb_substr($client[client_otch], 0, 1, 'UTF-8') . ".";?>/<br><br>
			М.П.<br>	
		</div>
		<div style="page-break-after: always;"></div>
		<div style="float: right; height: 80px;">
			<b>Приложение №1<br>
			к Договору № <?php echo $_GET['oId'];?><br>
			от <?php echo date("d.m.Y", strtotime($result[order_date])); ?></b><br>
		</div>
		<div style="margin-top: 100px;">
			<p>Смета на выполнение работ</p>
			<table border="1" cellspacing="0">
				<tr>
					<td width="5%" align="center">№</td>
					<td width="55%" align="center">Наименование товара/услуги</td>
					<td width="15%" align="center">Цена</td>
					<td width="10%" align="center">Количество</td>
					<td width="15%" align="center">Сумма</td>
				</tr>
		<?php
			$i = 0;
        	$query = "SELECT * FROM products_in_order WHERE order_id = " . $_GET['oId'];
        	$statement = $link->prepare($query);
			$statement->execute();
			$pio = $statement->fetchAll();
            foreach ($pio as $product_in) {
            	$query = "SELECT product_name, price FROM nomenclature WHERE product_id = " . $product_in[product_id];
            	$statement = $link->prepare($query);
				$statement->execute();
				$product = $statement->fetch();
				$i++;
        ?>
                <tr>
                	<td align="center"><?php echo $i;?></td>
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
            $query = "SELECT * FROM services_in_order WHERE order_id = " . $_GET['oId'];
        	$statement = $link->prepare($query);
			$statement->execute();
			$sio = $statement->fetchAll();
            foreach ($sio as $service_in) {
            	$query = "SELECT service_name, service_price FROM services WHERE service_id = " . $service_in[service_id];
            	$statement = $link->prepare($query);
				$statement->execute();
				$service = $statement->fetch();
				$i = $i + 1;
        ?>
                <tr>
                	<td align="center"><?php echo $i;?></td>
                    <td><?php echo $service["service_name"]; ?></td>    
                    <td align="right"><?php echo number_format($service["service_price"], 2, '.', ' '); ?></td>
                    <td align="center" class="quantity">
                    	<?php echo $service_in["quantity"]; ?>
                    </td>
                    <td align="right">
                        <?php echo number_format($service_in["sum"], 2, '.', ' '); ?>
                    </td>
                </tr>
        <?php
            }	                
        ?>                
			</table><br>				
			    Итого: <?php echo number_format($result[order_price], 2, '.', ' ');?> руб. 
			<div style="margin-top: 25px; width: 600px;">
			Подписи Сторон:<br><br>
			<div style="float: left;">
				Заказчик:<br>
				__________/<?php echo $client[client_surname] . " " . mb_substr($client[client_name], 0, 1, 'UTF-8') . ". ". mb_substr($client[client_otch], 0, 1, 'UTF-8') . ".";?>/<br><br>
				М.П.<br>	
			</div>
			<div style="float: right;">
				Подрядчик:<br>
				Генеральный директор<br>
				ООО «Профклимат»<br>
				__________/Каземиров В.А/<br><br>
				М.П.<br>
			</div>
			</div>
		</div>
	</div>
	</div>
<script type='text/javascript'>
	$(document).ready(function(){
    	printDiv();
	});
	function printDiv() {
     var divToPrint = document.getElementById('DivIdToPrint');
     var newWin = window.open('','Print-Window');
     newWin.document.open();
     newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
     newWin.document.close();
     setTimeout(function(){newWin.close();},1000);
	}
</script>
<?php
	}
?>
</body>
</html>
