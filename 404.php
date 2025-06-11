<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>404 Page non trouvée</title>
		<meta name="description" content="<?php echo htmlspecialchars($settings['store']);?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="css/modern_theme.css">
		<link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
		<link rel="shortcut icon" href="favicon.ico">
		<?php include("onesignal.php");?>
	</head>
	<body>
		<div class="lx-wrapper">	
			<header class="lx-header">
				<?php include('header.php');?>
			</header>
			<div class="lx-main">
				<aside class="lx-main-leftside">
					<?php include('mainmenu.php');?>
				</aside>
				<main class="lx-main-content">
					<div class="lx-page-header">
						<h1>Page non trouvée</h1>
					</div>
					<div class="lx-page-content">
						<div class="lx-cleaner">
							<h3>Désolé, la page que vous cherchez est introuvable ou il semble que vous n'avez pas l'autorisation pour y accéder</h3>
                            <a href="index.php" class="lx-btn">Retour au tableau de bord</a>
						</div>
					</div>
				</main>
			</div>
		</div>

		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/script.js"></script>
	</body>
</html>
