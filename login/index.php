<!-- SHOPPINGCART
 ****************************************************
 * Developer Team : Galang aidil akbar, Mardha Yuda Kurniawan, Ahmad Sofiyan Alfandi. 
 * Release Date   : 24 May 2021
 * Twitter        : https://twitter.com/galang_aidil, https://twitter.com/alfandi04_ 
 * E-mail         : galangaidil45@gmail.com, yumardha@gmail.com, alfafandi0@gmail.com.
-->

<?php
include 'functions.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login | Gadgets Pedia</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<?= load_icon() ?>
</head>

<body>
	<header>
		<h1><a href="../">Gadgets Pedia</a></h1>
	</header>
	<div class="container">
		<div class="login">
			<h1>Login</h1>
			<p>New in here? <a href="../register/.">Register</a>
			<form action="authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required autofocus autocomplete="off">
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</div>
</body>

</html>