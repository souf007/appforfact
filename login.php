<?php
session_start();
include("config.php");

if(isset($_POST['username'])){
	$back = $bdd->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $back->execute([$_POST['username'], $_POST['password']]);
	if($back->rowCount() == 1){
		$back = $bdd->prepare("SELECT * FROM users WHERE email=? AND password=? AND trash='1'");
        $back->execute([$_POST['username'], $_POST['password']]);
		if($back->rowCount() == 1){
			$row = $back->fetch(PDO::FETCH_ASSOC);
			$_SESSION['easybm_id'] = $row['id'];
			$_SESSION['easybm_fullname'] = $row['fullname'];
			$_SESSION['easybm_picture'] = $row['picture'];
			$_SESSION['easybm_phone'] = $row['phone'];
			$_SESSION['easybm_email'] = $row['email'];
			$_SESSION['easybm_password'] = $row['password'];
			$_SESSION['easybm_roles'] = $row['roles'];
			$_SESSION['easybm_companies'] = "0,".($row['companies'] ?? '0');
			$_SESSION['easybm_type'] = $row['type'];
			$_SESSION['easybm_superadmin'] = $row['superadmin'];

            if(isset($_POST['rememberme'])){
                $expiry = time() + (86400 * 30); // 30 days
				setcookie('rememberme', 'yes', $expiry);
				setcookie('id', $_SESSION['easybm_id'], $expiry);
				setcookie('fullname', $_SESSION['easybm_fullname'], $expiry);
			} else {
                $expiry = time() - 3600;
				setcookie('rememberme', "", $expiry);
				setcookie('id', "", $expiry);
				setcookie('fullname', "", $expiry);
			}
						
			$page_map = [
                "Consulter Tableau de bord" => "index.php",
                "Consulter Historique des paiements" => "payments.php",
                "Consulter Factures," => "factures.php",
                "Consulter Devis" => "devis.php",
                "Consulter Factures proforma" => "facturesproforma.php",
                "Consulter Bons de livraison" => "bl.php",
                "Consulter Bons de sortie" => "bs.php",
                "Consulter Bons de retour" => "br.php",
                "Consulter Factures avoir" => "avoirs.php",
                "Consulter Clients" => "clients.php",
                "Consulter Bons de commande" => "bc.php",
                "Consulter Bons de récéption" => "bre.php",
                "Consulter Fournisseurs" => "suppliers.php",
                "Consulter Utilisateurs" => "users.php"
            ];

            $redirect_page = "login.php";
            foreach($page_map as $role => $page){
                if(preg_match("#$role#", $_SESSION['easybm_roles'])){
                    $redirect_page = $page;
                    break;
                }
            }
			header('location: '.$redirect_page);
            exit;
		}
	}
	else{
		$error = 'Login ou mot de passe est incorrect';
	}
}

if(file_exists("configdb.data")){
	unlink("configdb.data");
}
if(file_exists("installer.php")){
	unlink("installer.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Login</title>
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
		<div class="lx-login-wrapper">
            <div class="lx-login-left">
                <div class="lx-login-form-container">
                    <div class="lx-login-logo">
                        <img src="<?php echo $settings['logo']=="logo.png"?"images/".$settings['logo']:"uploads/".$settings['logo'];?>" alt="Logo">
                    </div>
                    <div class="lx-login-form">
                        <h2>Se connecter</h2>
                        <p>Bon retour! Veuillez saisir vos coordonnées.</p>
                         <form action="login.php" method="post" id="loginForm">
                            <?php if(isset($error)): ?>
                                <div class="lx-login-error"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <div class="lx-textfield">
                                <label for="username">Email</label>
                                <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : "";?>" placeholder="vous@exemple.com" required>
                            </div>
                            <div class="lx-textfield">
                                <label for="password">Mot de passe</label>
                                <input type="password" id="password" name="password" value="<?php echo isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : "";?>" placeholder="********" required>
                            </div>
                            <div class="lx-form-options">
                                <label class="lx-checkbox"><input type="checkbox" name="rememberme" value="yes" <?php echo isset($_COOKIE['rememberme'])?"checked":"";?>> Se souvenir de moi</label>
                                <a href="#" class="lx-password-forgotten">Mot de passe oublié?</a>
                            </div>
                            <div class="lx-submit">
                                <button type="submit" class="lx-btn">Se connecter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="lx-login-right">
                 <div class="lx-login-right-overlay">
                    <h3>EasyDoc</h3>
                    <p>Votre créateur de documents commerciaux!</p>
                </div>
            </div>
		</div>
		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/script.js"></script>
        <script>
            $(document).ready(function() {
                $('#loginForm').on('submit', function(e) {
                    e.preventDefault();
                    // Add modern form validation here if needed
                    this.submit();
                });
            });
        </script>
	</body>
</html>