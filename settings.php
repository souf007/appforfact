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
		<title>Paramètres</title>
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
						<h1>Paramètres</h1>
						<p>Vous pouvez modifier vos paramètres d'affichage ici</p>
					</div>
					<div class="lx-page-content">
                        <div class="lx-g2">
                            <div class="lx-form">
                                <div class="lx-form-title">
                                    <h3>Personnaliser votre experience d'affichage</h3>
                                </div>
                                <div class="lx-add-form">
                                    <form autocomplete="off" action="#" method="post" id="settingsform">
                                        <div class="lx-textfield">
                                            <label>Logo</label>
                                            <input type="file" name="medias" id="medias" accept="image/x-png,image/jpeg" />
                                            <input type="hidden" name="picture" value="<?php echo $settings['logo'];?>" />
                                            <a href="javascript:;" class="lx-btn lx-upload-picture">Ajouter logo</a>
                                        </div>
                                        <div class="lx-textfield">
                                            <label>Couverture</label>
                                            <input type="file" name="mediascover" id="mediascover" accept="image/x-png,image/jpeg" />
                                            <input type="hidden" name="cover" value="<?php echo $settings['cover'];?>" />
                                            <a href="javascript:;" class="lx-btn lx-upload-picture">Ajouter couverture</a>
                                        </div>
                                        <div class="lx-textfield">
                                            <label>Nom de l'application</label>
                                            <input type="text" name="store" value="<?php echo htmlspecialchars($settings['store']);?>" />
                                        </div>
                                        <div class="lx-textfield">
                                            <label>Devise</label>
                                            <input type="text" name="currency" value="<?php echo htmlspecialchars($settings['currency']);?>" />
                                        </div>
                                        <div class="lx-textfield">
                                            <label>Nombre de ligne d'affichage sur les tableaux</label>
                                            <select name="nbrows">
                                                <option value="50" <?php if($parametres['nbrows']==50){echo "selected";}?>>50</option>
                                                <option value="100" <?php if($parametres['nbrows']==100){echo "selected";}?>>100</option>
                                                <option value="200" <?php if($parametres['nbrows']==200){echo "selected";}?>>200</option>
                                                <option value="500" <?php if($parametres['nbrows']==500){echo "selected";}?>>500</option>
                                            </select>
                                        </div>
                                        <div class="lx-submit">
                                            <button type="submit" class="lx-btn">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="lx-g2">
                            <div class="lx-form">
                                <div class="lx-form-title">
                                    <h3>TVA</h3>
                                </div>
                                <div class="lx-add-form">
                                    <a href="javascript:;" class="lx-btn" onclick="document.getElementById('tva-modal').open()">+ Nouvelle TVA</a>
                                </div>
                                <div class="lx-table-container">
                                    <div class="lx-table lx-table-tvas">
                                        <!-- Table content will be loaded by javascript -->
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</main>
			</div>

            <custom-modal id="tva-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Ajouter une nouvelle TVA</h3>
                    </div>
                    <div class="lx-add-form">
                        <form autocomplete="off" action="#" method="post" id="tvasform">
                            <div class="lx-textfield">
                                <label>TVA<sup>*</sup></label>
                                <input type="text" name="tva" data-isnotempty="" data-message="Saisissez une tva!!" />
                            </div>
                            <div class="lx-submit">
                                <input type="hidden" name="id" value="0" />
                                <button type="submit" class="lx-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </custom-modal>
		</div>

		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/jquery.popup.js"></script>
		<script src="js/script.js"></script>
        <script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				loadTVAs();
			});
		</script>
	</body>
</html>
