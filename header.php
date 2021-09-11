<?php
include 'connect.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Профклимат СПб</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/css.css">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

	<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="js/jquery-ui-1.12.1/jquery-ui.js"></script>
	<link href ="js/jquery-ui-1.12.1/jquery-ui.css" rel = "stylesheet">
	<link href="js/jquery.maskedinput/jquery.maskedinput.min.js" >
	<script src="js/jquery.maskedinput/jquery.maskedinput.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
		<div class="container">
			<ul class="navbar-nav">
				<li class="nav-item active">
					<a href="../index.php" class="nav-link">Главная</a>
				</li>
				<li class="nav-item">
					<a href="../kondicionery.php" class="nav-link">Кондиционеры</a>
				</li>
				<li class="nav-item">
					<a href="../obogrevately.php" class="nav-link">Обогреватели</a>
				</li>
				<li class="dropdown">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Доставка и установка</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="../dostavka.php">Доставка</a></li>
						<li><a class="dropdown-item" href="../ustanovka.php">Установка</a></li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="../obsluzhivanie.php" class="nav-link">Обслуживание</a>
				</li>
				<!-- <li class="nav-item">
					<a href="#" class="nav-link">Галерея</a>
				</li> -->
				<li class="nav-item">
					<a href="../faq.php" class="nav-link">Справка</a>
				</li>
				<li class="nav-item">
					<a href="../kontakty.php" class="nav-link">Контакты</a>
				</li>
			</ul>
			<a href="../view_order_status.php" class="button btn"><i class="fas fa-user"></i></a>
			<a href="../cart.php" class="button btn"><i class="fas fa-shopping-cart"></i></a>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-3 logo-img">
				<a href="../index.php"><img src="img/prof_1.png"></a>
			</div>
			<div class="col-md-6"> <!-- Поиск по сайту -->
				<form class="search" method="POST" action="search.php">
					<input class="search-input" type="text" name="search_q" placeholder="Искать здесь...">
					<button class="search-btn" type="submit"></button>
				</form>
			</div>
			<div class="col-md-3">
				<div class="phoneNum">	
					<span id="mPhone">
						<i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;+7 (812) 904-18-98</span><br>
					<span>пн-чт: 09:00-21:00<br>	
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;пт: 09:00-17:30</span><br>
					<!-- <a href="#" id="callBack">Заказать обратный звонок</a> -->
				</div>
			</div>
		</div>
	</div>