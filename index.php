<?php 
	header("Content-Type: text/html; charset=utf-8;");
    header("Content-Encoding: utf-8");

	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = @$_SERVER['REMOTE_ADDR'];
	 
	if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
	else $ip = $remote;

    $db = @mysqli_connect('localhost', 'root', 'root', 'records') or die('Ошибка соединения с БД');
    if(!$db) die(mysqli_connect_error());

	mysqli_set_charset($db, "utf8") or die('Не установлена кодировка');

	$res_insert = mysqli_query($db, "INSERT INTO records (ip) VALUES ('$ip')");

	$rec = mysqli_query($db, "SELECT id, ip, max1, max2, max3 FROM records");
	$data = mysqli_fetch_all($rec, MYSQLI_ASSOC);
	
	if(!empty($_GET['level'])){
		if($_GET['level'] == 'delete'){
			header('Location: index.php');
		}
	}

	if(!isset($_COOKIE['max1']) || !isset($_COOKIE['max2']) || !isset($_COOKIE['max3']))
	{
		foreach ($data as $key => $value)
		{
			if($value['ip'] == $ip)
			{
				setcookie("max1", $value['max1']);
				setcookie("max2", $value['max2']);
				setcookie("max3", $value['max3']);
				break;
			}	
		}
	}

	if(!empty($_COOKIE['max1']) || !empty($_COOKIE['max2']) || !empty($_COOKIE['max3'])){
		foreach ($data as $key => $value) {
			if($value['ip'] == $ip)
			{
				if($value['max1'] < $_COOKIE['max1'])
				{
					$update = "UPDATE records SET max1={$_COOKIE['max1']} WHERE id = {$value['id']}";
					$res_update = mysqli_query($db, $update) or die(mysqli_error($db));
				}

				if($value['max2'] < $_COOKIE['max2'])
				{
					$update = "UPDATE records SET max2={$_COOKIE['max2']} WHERE id = {$value['id']}";
					$res_update = mysqli_query($db, $update) or die(mysqli_error($db));
				}

				if($value['max3'] < $_COOKIE['max3'])
				{
					$update = "UPDATE records SET max3={$_COOKIE['max3']} WHERE id = {$value['id']}";
					$res_update = mysqli_query($db, $update) or die(mysqli_error($db));
				}
				break;
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New game!</title>
	<style>
		body {
			margin: 0;
			text-align: center;
			background-color: #62a1dd;

			color: #cfdff4;
		}

		.hide {
			display: none;
		}

		.container {
			display: flex;
			justify-content: center;
			align-items: center;
			text-align: center;
		}

		main {
			display: inline-block;

		}

		.art {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
			background-color: #5384b4;

			padding: 15px;
			margin-bottom: 100px;
		}

		.figure {
			width: 134px;
			height: 200px;
			margin: 5px;
			background-color: #62a1dd;
			border: solid 1px #62a1dd;
			border-radius: 15px;

			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
			display: inline-block;
		}

		.search {
			width: 134px;
			height: 200px;
			margin: 5px;
			background-color: #171e25;
			border: solid 1px #171e25;
			border-radius: 15px;

			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
			display: inline-block;
		}

		#rozysk {
			margin-left: 100px;
			float: right;
		}

		p {
			font-style: bold;
			text-transform: uppercase;
		}

		.btn {
			padding: 5px 20px;
			background-color: #62a1dd;
			border: solid 2px #b6cfee;
			border-radius: 5px;
			color: #b6cfee;
			font-size: 16px;

			text-transform: uppercase;

			transition: color .1s linear, background-color .1s linear; 
		}

		.btn:hover {
			color: #62a1dd;
			background-color: #b6cfee;
		}

		.btn-m {
			margin-top: 10px;
		}

		#art_1 {
			background-image: url(art/1.png);
		}
		#art_2 {
			background-image: url(art/2.png);
		}
		#art_3 {
			background-image: url(art/3.png);
		}
		#art_4 {
			background-image: url(art/4.png);
		}
		#art_5 {
			background-image: url(art/5.png);
		}
		#art_6 {
			background-image: url(art/6.png);
		}
		#art_7 {
			background-image: url(art/7.png);
		}
		#art_8 {
			background-image: url(art/8.png);
		}
		#art_9 {
			background-image: url(art/9.png);
		}
		#art_10 {
			background-image: url(art/10.png);
		}
		#art_11 {
			background-image: url(art/11.png);
		}
		#art_12 {
			background-image: url(art/12.png);
		}
		#art_13 {
			background-image: url(art/13.png);
		}
		#art_14 {
			background-image: url(art/14.png);
		}
		#art_15 {
			background-image: url(art/15.png);
		}
		#art_16 {
			background-image: url(art/16.png);
		}
		#art_17 {
			background-image: url(art/17.png);
		}
		#art_18 {
			background-image: url(art/18.png);
		}

		.parent {
			width: 100%;
			height: 100%;
			position: absolute;
			top: 0;
			left: 0;
			overflow: auto;
		}

		.block {
			width: auto;
			height: auto;
			position: absolute;
			top: 50%;
			left: 50%;
			
		}

		.level {
			margin: -125px 0 0 -200px;
		}

		.end {
			margin: -125px 0 0 -100px;
		}
	</style>
</head>
<body class="container">
	<div class="floater">
		<main id="mainArt" class="hide">
			<article id="art" class="art"></article>
		</main>

		<main id="rozysk" class="hide">
			<article id="search" class="search"></article>
			<p class="p" style="color: red;">Розыскивается!</p>
			<h3 id="score">Очки: 0</h3>
			<h3 id="recordes">Рекорд: <?php if(!empty($_GET['btn'])){echo $_COOKIE['max' . ($_GET['btn'] - 1)];}  ?></h3>
			<div id="timerBlock"><p class="seconds"  style="color: #e7effa;">Осталось <span id="timer">30</span> секунд</p></div>
		</main>

		<form id="end" class="hide parent" method="get">
			<div class="block end">
				<h3 id="score">Вы набрали <span id="sc">0</span> очков</h3>
				<h3 id="recordes">Ваш предыдущий рекорд: <span id="rec">0</span></h3>
				<button class="btn" value="delete" name="level">Выбрать уровень</button>
			</div>
		</form>

		<form method="get" class="parent" id="levels">
			<div class="block level">
				<h1>Выберите уровень</h1>
				<button class="btn btn-m" value="2" name="btn">Уровень 1</button>
				<button class="btn btn-m" value="3" name="btn">Уровень 2</button>
				<button class="btn btn-m" value="4" name="btn">Уровень 3</button>
			</div>
		</form>
	</div>

	<script>
		let number = 0;
		let score = document.getElementById("score");


		let mainArt = document.getElementById("mainArt");
		let rozysk = document.getElementById("rozysk");
		let levels = document.getElementById("levels");
		let endBlock = document.getElementById("end");

		document.addEventListener("click", function(e) {
			let click = e.target;
			if (click.className=="figure") {
				if(click.id == "art_" + right){
					number++;
				}
				else{
					number--;
				}
				//ваши действия
				score.textContent = "Очки: " + number;
				render();
			}
		});

		function random(arr){
			return arr[Math.floor(Math.random()*arr.length)];
		}

		function check(value, arr){
			for (var i = 0; i < arr.length; i++) {
				if (arr[i] === value) {
					return true;
				}
			}
			show.push(n);
			return false;
		}
	</script>

	<?php 

	$numArt = array(2 => 4, 3 => 6, 4 => 12);//кол-во изображений  
	$size = array(4 => '432', 6 => '576', 12 => '720');//Размеры

	if(!empty($_GET['btn'])){
		echo "<script>

			let art = document.getElementById('art');
			let search = document.getElementById('search');
			let timer = document.getElementById('timer');

			let numberArt = {$numArt[$_GET['btn']]};
			let maxSize = String({$size[$numArt[$_GET['btn']]]}) + 'px';


			art.style.maxWidth = maxSize;
			rozysk.classList.remove('hide');
			mainArt.classList.remove('hide');
			levels.classList.add('hide');
			
			let seconds = 30;

			let timerId = setInterval(function() { seconds--; timer.textContent = seconds; }, 1000);

			setTimeout(() => { 
				clearInterval(timerId); 
				mainArt.classList.add('hide'); 
				rozysk.classList.add('hide');
				endBlock.classList.remove('hide');
				end(); }, 1000 * seconds);

			let options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
			let show = new Array();
			let right = 0;

			let repetitions = true;

			render();

			function render(){
				art.innerHTML = '';
				search.innerHTML = '';
				show = [];
				for(var i = 1; i <= numberArt; i++){
					while (repetitions){
						n = random(options);
						repetitions = check(n, show);
					}
			
					art.innerHTML += '<div id=\"art_' + n + '\" class=\"figure\"></div>';
					repetitions = true;
				}
				right = random(show);
				search.style.backgroundImage = \"url(art/\" + right + \".png)\";
			}


			function end(){
				if (number > " . ($_COOKIE['max' . ($_GET['btn'] - 1)]) . "){
					document.cookie = 'max" . ($_GET['btn'] - 1) . "=' + number;
				}
				document.getElementById('sc').textContent = number;
				document.getElementById('rec').textContent = " . $_COOKIE['max' . ($_GET['btn'] - 1)] . ";
			}
		</script>";
	}
	?>
</body>
</html>