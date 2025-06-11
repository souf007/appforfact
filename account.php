<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
	exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon Profil</title>
		<meta name="description" content="<?php echo htmlspecialchars($settings['store']);?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="css/new_main_style.php">
		<link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
		<link rel="shortcut icon" href="favicon.ico">
		<?php include("onesignal.php");?>
	</head>
	<body>
		<div class="lx-wrapper">
			<aside class="lx-main-leftside">
				<?php include('mainmenu.php');?>
			</aside>
			<div class="lx-main-content">
				<header class="lx-header">
					<?php include('header.php');?>
				</header>
				<main>
					<div class="lx-page-header">
						<h1>Mon Compte</h1>
						<p>Gérez vos informations personnelles et de sécurité.</p>
					</div>

                    <?php if($settings['inventaire'] == "1"): ?>
						<div class="lx-notices-item">
							<i class="fa fa-exclamation-triangle"></i>
							<p>Un inventaire de stock est lancé, vous ne pouvez pas effectuer aucune action sur l'application avant de le terminer</p>
						</div>
					<?php endif; ?>

					<div class="lx-g2-f">
						<div class="lx-page-content">
							<h3>Informations Personnelles</h3>
							<form autocomplete="off" action="#" method="post" id="accountform">
								<?php
								$user_query = $bdd->prepare("SELECT * FROM users WHERE id = ?");
								$user_query->execute([$_SESSION['easybm_id']]);
								$user = $user_query->fetch(PDO::FETCH_ASSOC);
								?>
								<div class="lx-profile-picture">
									<img src="<?php echo ($user['picture'] == "avatar.png") ? "images/avatar.png" : "uploads/".$user['picture']; ?>" alt="Photo de profil" class="picture">
									<div class="lx-picture-actions">
										<input type="file" name="medias" id="medias" accept="image/x-png,image/jpeg" class="lx-hidden-input">
										<button type="button" class="lx-btn lx-btn-primary lx-upload-trigger">Changer</button>
										<button type="button" class="lx-btn lx-btn-secondary lx-reset-photo" data-file="medias" data-input="picture" data-default="avatar.png">Réinitialiser</button>
									</div>
                                    <input type="hidden" name="picture" value="<?php echo htmlspecialchars($user['picture']);?>" />
								</div>
								<div class="lx-textfield">
									<label for="email">Login (Email)</label>
									<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']);?>" readonly disabled>
								</div>
								<div class="lx-textfield">
									<label for="fullname">Nom complet</label>
									<input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']);?>" required>
								</div>
								<div class="lx-textfield">
									<label for="phone">Téléphone</label>
									<input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']);?>" pattern="0[67][0-9]{8}">
								</div>
								<div class="lx-form-actions">
                                    <input type="hidden" name="id" value="<?php echo $user['id'];?>" />
									<button type="submit" class="lx-btn lx-btn-primary">Enregistrer les modifications</button>
								</div>
							</form>
						</div>

						<div class="lx-page-content">
							<h3>Changer le mot de passe</h3>
							<form autocomplete="off" action="#" method="post" id="passwordform">
								<div class="lx-textfield">
									<label for="oldpassword">Ancien mot de passe</label>
									<input type="password" id="oldpassword" name="oldpassword" required>
								</div>
								<div class="lx-textfield">
									<label for="newpassword1">Nouveau mot de passe</label>
									<input type="password" id="newpassword1" name="newpassword1" required minlength="6">
								</div>
								<div class="lx-textfield">
									<label for="newpassword2">Confirmer le nouveau mot de passe</label>
									<input type="password" id="newpassword2" name="newpassword2" required minlength="6">
								</div>
								<div class="lx-form-actions">
                                    <input type="hidden" name="id" value="<?php echo $user['id'];?>" />
									<button type="submit" class="lx-btn lx-btn-primary">Changer le mot de passe</button>
								</div>
							</form>
						</div>
					</div>
				</main>
			</div>
		</div>

		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/jquery.popup.js"></script>
		<script src="js/script.js"></script>
        <script>
            // JS for the profile picture upload trigger
            $('.lx-upload-trigger').on('click', function() {
                $('#medias').click();
            });
        </script>
	</body>
</html>
<?php
DB_Sanitize();
?>