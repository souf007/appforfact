<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
	exit;
}

if(!preg_match("#Consulter Tableau de bord#",$_SESSION['easybm_roles'])){
    $page_map = [
        "Consulter Historique des paiements" => "payments.php",
        "Consulter Factures," => "documents.php?type=factures",
        "Consulter Devis" => "documents.php?type=devis",
        "Consulter Factures proforma" => "documents.php?type=facturesproforma",
        "Consulter Bons de livraison" => "documents.php?type=bl",
        "Consulter Bons de sortie" => "documents.php?type=bs",
        "Consulter Bons de retour" => "documents.php?type=br",
        "Consulter Factures avoir" => "documents.php?type=avoirs",
        "Consulter Clients" => "clients.php",
        "Consulter Bons de commande" => "documents.php?type=bc",
        "Consulter Bons de récéption" => "documents.php?type=bre",
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
				<main class="lx-main-content-inner">
					<div class="lx-page-header">
						<h1>Tableau de bord</h1>
						<p>Aperçu des performances de votre entreprise</p>
					</div>

					<div class="lx-page-content">
                        <div class="lx-g1-f">
                            <div class="lx-keyword lx-g1">
                                <div class="filter-grid">
                                    <div class="lx-textfield">
                                        <label for="dateadd">Date création:</label>
                                        <input type="text" autocomplete="off" name="dateadd" id="dateadd" title="Date création" value="<?php echo gmdate("d/m/Y",time()-(60*60*24*29))." - ".gmdate("d/m/Y");?>" placeholder="Date création" />
                                    </div>
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