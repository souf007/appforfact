<?php
session_start();
include("config.php");

$_SESSION['easybm_errorimport'] = "";

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
	exit;
}

if(!preg_match("#Consulter Tableau de bord#",$_SESSION['easybm_roles'])){
    $page_map = [
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
    header("location: $redirect_page");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Tableau de bord</title>
		<meta name="description" content="<?php echo htmlspecialchars($settings['store']);?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="css/modern_theme.css">
		<link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
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
						<h1>Tableau de bord</h1>
						<p>Aperçu des performances de votre entreprise</p>
					</div>

					<div class="lx-page-content">
						<div class="lx-g1-f">
							<div class="lx-keyword lx-g1">
								<h3>Filtre global</h3>
								<div class="filter-grid">
									<?php
									$styleday = "";
									$rangedate = gmdate("d/m/Y",time()-(60*60*24*29))." - ".gmdate("d/m/Y");
									$rangedateplaceholder = "Date création";
									$startdate = gmdate("d/m/Y",time()-(60*60*24*29));
									$enddate = gmdate("d/m/Y");
									if(preg_match("#Consultation de la journée en cours seulement Tableau de bord#",$_SESSION['easybm_roles'])){
										$styleday = "display:none;";
										$rangedate = gmdate("d/m/Y")." - ".gmdate("d/m/Y");
										$startdate = gmdate("d/m/Y");
										$enddate = gmdate("d/m/Y");
									}
									?>
									<div style="<?php echo $styleday;?>" class="lx-textfield">
										<label for="dateadd">Date création:</label>
										<input type="text" autocomplete="off" name="dateadd" id="dateadd" title="Date création" value="<?php echo $rangedate;?>" placeholder="<?php echo $rangedateplaceholder;?>" />
									</div>
									<input type="hidden" name="datestart" id="datestart" value="<?php echo $startdate;?>" />
									<input type="hidden" name="dateend" id="dateend" value="<?php echo $enddate;?>" />

									<div class="lx-textfield">
										<label for="company">Sociétés</label>
										<select name="company" id="company" multiple>
											<?php
											$back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
											while($row = $back->fetch()){
												echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['rs']).'</option>';
											}
											?>
										</select>
									</div>
									<div class="lx-textfield" style="<?php echo preg_match("#Consulter Clients#",$_SESSION['easybm_roles'])?"":"display:none;";?>">
										<label for="client">Clients</label>
										<select name="client" id="client" multiple>
											<?php
											$back = $bdd->query("SELECT id,code,fullname FROM clients WHERE fullname<>''".$multicompanies." ORDER BY fullname");
											while($row = $back->fetch()){
												echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['fullname']." (".$row['code'].")").'</option>';
											}
											?>
										</select>
									</div>
									<div class="lx-textfield" style="<?php echo preg_match("#Consulter Fournisseurs#",$_SESSION['easybm_roles'])?"":"display:none;";?>">
										<label for="supplier">Fournisseurs</label>
										<select name="supplier" id="supplier" multiple>
											 <?php
											$back = $bdd->query("SELECT id,code,title FROM suppliers WHERE title<>''".$multicompanies." ORDER BY title");
											while($row = $back->fetch()){
												echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['title']." (".$row['code'].")").'</option>';
											}
											?>
										</select>
									</div>
									<div class="lx-textfield">
										<a href="index.php" class="lx-btn lx-btn-secondary"><i class="fa fa-sync-alt"></i> Réinitialiser</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="lx-page-content">
						<h3>KPIs Paiements</h3>
						<div id="kpi" class="kpi-container"></div>
					</div>

					<div class="lx-page-content">
						<h3>KPIs Documents</h3>
						<div id="documents" class="kpi-container"></div>
					</div>

					<div class="lx-page-content">
                         <h3>Analyse Approfondie</h3>
                         <div id="ca" class="chart-container"></div>
                         <div id="topca" class="chart-container"></div>
					</div>
				</main>
			</div>
		</div>

		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/jquery.popup.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="js/daterangepicker.js"></script>
		<script src="js/highcharts.js"></script>
		<script src="js/exporting.js"></script>
		<script src="js/export-data.js"></script>
		<script src="js/annotations.js"></script>
		<script src="js/script.js"></script>

		<script>
			$(document).ready(function(){
				function loadDashboardData(){
					loadKPI();
					loadKPI1();
					loadCA();
					loadTop();
				}

				loadDashboardData();

				$('input[name="dateadd"]').daterangepicker({
					locale: { format: 'DD/MM/YYYY', separator: " - " },
					startDate: moment().subtract(29,'day'),
					endDate: moment(),
					ranges: {
						'Aujourd'hui': [moment(), moment()],
						'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'7 derniers jours': [moment().subtract(6, 'days'), moment()],
						'30 derniers jours': [moment().subtract(29, 'days'), moment()],
						'Ce mois': [moment().startOf('month'), moment().endOf('month')],
						'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					}
				}, function(start, end) {
					$('input[name="datestart"]').val(start.format('DD/MM/YYYY'));
					$('input[name="dateend"]').val(end.format('DD/MM/YYYY'));
					loadDashboardData();
				});

				$('#company, #client, #supplier').on('change', function(){
					loadDashboardData();
				});
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
?>