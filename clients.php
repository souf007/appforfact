<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Clients#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Clients</title>
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

		<!-- Wrapper -->
		<div class="lx-wrapper">
			<!-- Header -->
			<header class="lx-header">
				<?php include('header.php');?>
			</header>
			<!-- Main -->
			<div class="lx-main">
				<aside class="lx-main-leftside">
					<?php include('mainmenu.php');?>
				</aside>
				<!-- Main Content -->
				<main class="lx-main-content">
					<div class="lx-page-header">
						<h1>Clients</h1>
					</div>
					<div class="lx-page-content">
                        <div class="lx-g1-f">
							<?php if(preg_match("#Ajouter Clients#",$_SESSION['easybm_roles'])): ?>
							<div class="lx-add-form">
								<button class="lx-btn" onclick="document.getElementById('client-modal').open()">+ Nouveau client</button>
								<button class="lx-btn" onclick="document.getElementById('import-modal').open()"><i class="fa fa-upload"></i> Importer</button>
							</div>
							<?php endif; ?>
                            <div class="lx-keyword">
                                <form>
                                    <input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Rechercher..." data-table="clients" />
                                </form>
                            </div>
                        </div>

						<div class="lx-table-container">
							<div class="lx-table lx-table-clients">
								<!-- Table content will be loaded by javascript -->
							</div>
						</div>
                        <div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
                            <?php
                            $nb = 50;
							if($parametres['nbrows'] != "" AND $parametres['nbrows'] != "0"){
								$nb = $parametres['nbrows'];
							}
                            $back = $bdd->query("SELECT COUNT(*) AS nb FROM clients WHERE trash='1'");
							$row = $back->fetch();
                            $nbpages = ceil($row['nb']/$nb);
                            ?>
                            <ul data-table="clients" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
                                <li><span>Page <ins>1</ins> sur <abbr><?php echo $nbpages;?></abbr></span></li>
                                <li><a href="javascript:;" class="previous disabled"><i class="fa fa-angle-left"></i></a></li>
                                <li>
                                    <select id="pgnumber">
                                        <?php for($i=1;$i<=$nbpages;$i++): ?>
                                        <option value="<?php echo ($i-1);?>"><?php echo $i;?></option>
                                        <?php endfor; ?>
                                    </select>
                                </li>
                                <li><a href="javascript:;" class="next <?php if($nbpages == 1){echo 'disabled';}?>"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div>
					</div>
				</main>
			</div>

			<custom-modal id="client-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Ajouter un nouveau client</h3>
                    </div>
                    <div class="lx-add-form">
                        <form autocomplete="off" action="#" method="post" id="clientsform">
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label>Client<sup>*</sup>:</label>
                                <input type="text" name="fullname" data-isnotempty="" data-message="Choisissez un client !!" class="todropdown">
                            </div>
                            <div class="lx-textfield lx-g2 lx-g lx-pb-0">
                                <label>Code client:</label>
                                <input type="text" autocomplete="off" name="codecl" />
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label>Société<sup>*</sup>:</label>
                                <select name="company" data-isnotempty="" data-message="Choisissez une société">
                                    <?php
                                    $back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
                                    while($row = $back->fetch()){
                                        echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['rs']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label>ICE:</label>
                                <input type="text" autocomplete="off" name="ice" />
                            </div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label>IF:</label>
                                <input type="text" autocomplete="off" name="iff" />
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label>Téléphone:</label>
                                <input type="text" autocomplete="off" name="phone" />
                            </div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label>Email:</label>
                                <input type="text" autocomplete="off" name="email" />
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label>Adresse:</label>
                                <input type="text" autocomplete="off" name="address" />
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label>Note:</label>
                                <textarea name="note"></textarea>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-submit lx-g1 lx-pb-0">
                                <input type="hidden" name="id" value="0" />
                                <button type="submit" class="lx-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
			</custom-modal>
			
			<custom-modal id="import-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Importation clients</h3>
                    </div>
                    <div class="lx-add-form">
                        <form autocomplete="off" action="#" method="post" id="importclientsform" enctype="multipart/form-data">
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <p><b>Important: </b>Veuillez impoter un fichier excel avec les colonnes suivantes en respectant l'ordre (Nom et prénom (Obligatoire) - ICE (Unique) - Téléphone - Adresse - Email - Note - IDs sociétés séparer par point virgule ";" (Obligatoire)) ou bien <a href="ExampleClients.xlsx">télécharger un modèle</a></p>
                                <br />
                                <p><b>NB: </b>Les clients existants seront automatiquement ignorés</p>
                                <br />
                                <div class="lx-importer">
                                    <input type="file" name="xlsfile" id="importclient" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                    <span>Choisissez un fichier (excel)</span>
                                </div>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-submit lx-g1 lx-pb-0">
                                <input type="hidden" name="id" value="0" />
                                <button type="submit" class="lx-btn">Importer</button>
                            </div>
                        </form>
                    </div>
                </div>
			</custom-modal>

			<custom-modal id="export-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Export clients</h3>
                    </div>
                    <div class="lx-add-form">
                        <form autocomplete="off" action="#" method="post" id="exportform">
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="code" data-title="Référence" checked /> Référence</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="ice" data-title="ICE" checked /> ICE</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="fullname" data-title="Nom et présnom" checked /> Nom et présnom</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="phone" data-title="Téléphone" checked /> Téléphone</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="address" data-title="Adresse" checked /> Adresse</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="email" data-title="Email" checked /> Email</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="note" data-title="Note"  checked /> Note</label>
                            </div>
                            <?php if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])): ?>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="ca" data-title="CA TTC" checked /> CA TTC</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="paid" data-title="Payé TTC" checked /> Payé TTC</label>
                            </div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><input type="checkbox" name="columns" value="encours" data-title="En cours TTC" checked /> En cours TTC</label>
                            </div>
                            <?php endif; ?>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-submit lx-g1 lx-pb-0">
                                <input type="hidden" name="table" value="clients" />
                                <button type="submit" class="lx-btn">Télécharger</button>
                            </div>
                        </form>
                    </div>
                </div>
			</custom-modal>

			<custom-modal id="delete-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Confirmation suppression</h3>
                    </div>
                    <div class="lx-add-form">
                        <div class="lx-delete-box">
                            <p>Voulez vous vraiment supprimer ce client?</p>
                            <a href="javascript:;" class="lx-delete-record" data-action="deleteclient" data-id="">Oui</a>
                            <a href="javascript:;" class="lx-cancel-delete" onclick="document.getElementById('delete-modal').close()">Non</a>
                        </div>
                    </div>
                </div>
			</custom-modal>

		</div>

		<script src="js/jquery-1.12.4.min.js"></script>
		<script src="js/jquery.popup.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="js/daterangepicker.js"></script>
		<script src="js/script.js"></script>
        <script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				loadClients($(".lx-pagination ul").attr("data-state"));
				toDropDown();

                // Open modals
                $('.lx-new-client').on('click', function() {
                    $('#client-modal').open();
                });
                $('.lx-open-popup[data-title="importer"]').on('click', function() {
                    $('#import-modal').open();
                });
                $('.lx-open-popup[data-title="export"]').on('click', function() {
                    $('#export-modal').open();
                });
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>