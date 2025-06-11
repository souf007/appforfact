<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Trésorerie</title>
		<meta name="description" content="<?php echo htmlspecialchars($settings['store']);?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="css/modern_theme.css">
		<link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
		<link rel="stylesheet" href="css/ion.rangeSlider.min.css"/>
		<link rel="shortcut icon" href="favicon.ico">
		<?php include("onesignal.php");?>
		<script src="js/tinymce/tinymce.min.js"></script>
		<script>
			tinymce.init({
			  selector: 'textarea',
			  height: 200,
			  menubar: false,
			  plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table paste code help wordcount'
			  ],
			  toolbar: 'undo redo | ' +
			  'bold italic underline | alignleft aligncenter ' +
			  'alignright alignjustify | bullist numlist outdent indent | ' +
			  'removeformat',
			  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
			});
		</script>
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
						<h1>Trésorerie</h1>
					</div>
					<div class="lx-page-content">
						<div class="lx-g1-f">
                            <div class="lx-add-form">
								<button class="lx-btn" onclick="document.getElementById('expense-modal').open()">+ Nouvelle dépense / encaissement</button>
							</div>
							<div class="lx-keyword">
								<form>
                                    <input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Rechercher..." data-table="caisse" />
                                </form>
							</div>
						</div>
						<div class="lx-table-container">
							<div class="lx-table lx-table-caisse">
								<!-- Table content will be loaded by javascript -->
							</div>
						</div>
                        <div class="lx-pagination">
                            <!-- Pagination will be loaded by javascript -->
                        </div>
					</div>
				</main>
			</div>
			
			<custom-modal id="expense-modal">
                <div class="lx-form">
                    <div class="lx-form-title">
                        <h3>Ajouter une nouvelle dépense / encaissement</h3>
                    </div>
                    <div class="lx-add-form">
                        <form autocomplete="off" action="#" method="post" id="expenseform">
                            <div class="lx-textfield lx-g1 lx-pb-0" style="<?php echo !preg_match("#Modification date opération#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
                                <label><span>Date de création: </span><input type="text" name="dateaddcommand" /></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><span>Affectée à la société<sup>*</sup>:</span>
                                    <select name="company" class="lx-companies-list" data-isnumber="" data-message="Choisissez une société!!">
                                        <?php
                                        $back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
                                        if($back->rowCount() > 1){
                                        ?>
                                        <option value="">Choisissez une société</option>
                                        <?php
                                        }
                                        while($row = $back->fetch()){
                                            ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row['rs'];?></option>
                                            <?php 
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>											
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label><span>Nature<sup>*</sup>:</span>
                                    <select name="type" data-isnotempty="" data-message="Choisissez un nature!!">
                                        <option value="">Choisissez un type</option>
                                        <option value="Entrée">Encaissement</option>
                                        <option value="Sortie">Dépense</option>
                                    </select>
                                </label>
                            </div>	
                            <div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yesno">
                                <label><span>Facturé<sup>*</sup>:</span>
                                    <select name="invoiced" data-isnotempty="" data-message="Choisissez une option!!">
                                        <option value=""></option>
                                        <option value="Oui">Oui</option>
                                        <option value="Non">Non</option>
                                    </select>
                                </label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-clientsupplier">
                                <div class="lx-textfield lx-g1 lx-pb-0 lx-client-expense" style="display:none;">
                                    <label><span>Client:</span>
                                        <select name="client" class="todropdown" data-isnotempty="" data-message="Veuillez choisir un client de la liste!!">
                                            <option value="">Choisissez un client</option>
                                            <?php
                                            $back = $bdd->query("SELECT id,fullname,company FROM clients WHERE trash='1' AND fullname<>'' ORDER BY fullname");
                                            while($row = $back->fetch()){
                                                ?>
                                            <option value="<?php echo $row['id'];?>" data-company="<?php echo $row['company'];?>"><?php echo $row['fullname'];?></option>
                                                <?php 
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </div>	
                                <div class="lx-textfield lx-g1 lx-pb-0 lx-supplier-expense" style="display:none;">
                                    <label><span>Fournisseur:</span>
                                        <select name="supplier" class="todropdown" data-isnotempty="" data-message="Veuillez choisir un fournisseur de la liste!!">
                                            <option value="">Choisissez un fournisseur</option>
                                            <?php
                                            $back = $bdd->query("SELECT id,title,company FROM suppliers WHERE trash='1' AND title<>'' ORDER BY title");
                                            while($row = $back->fetch()){
                                                ?>
                                            <option value="<?php echo $row['id'];?>" data-company="<?php echo $row['company'];?>"><?php echo $row['title'];?></option>
                                                <?php 
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </div>	
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label><span>Catégorie:</span>
                                    <select name="nature" class="todropdown">
                                        <option value="">Choisissez une catégorie</option>
                                        <?php
                                        $back = $bdd->query("SELECT DISTINCT nature,company FROM payments WHERE nature<>''".$multicompanies." ORDER BY nature");
                                        while($row = $back->fetch()){
                                            ?>
                                        <option value="<?php echo $row['nature'];?>" data-company="<?php echo $row['company'];?>"><?php echo $row['nature'];?></option>
                                            <?php 
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>	
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label><span>Libellé<sup>*</sup>: </span><input type="text" autocomplete="off" name="title" data-isnotempty="" data-message="Saisissez un libellé!!" /></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label><span>Montant TTC<sup>*</sup>: </span><input type="text" autocomplete="off" name="price" data-isnumber="" data-message="Saisissez un montant!!" /></label>
                            </div>
                            <div class="lx-textfield lx-g2 lx-pb-0">
                                <label><span>Mode de paiement<sup>*</sup>:</span>
                                    <select name="modepayment" data-isnotempty="" data-message="Choisissez un mode de paiement!">
                                        <option value="">Choisissez un mode de paiement</option>
                                        <option value="Espèce">Espèce</option>
                                        <option value="Chèque">Chèque</option>
                                        <option value="Effet">Effet</option>
                                        <option value="Virement">Virement</option>
                                        <option value="TPE">TPE</option>
                                    </select>
                                    <input type="hidden" name="modepayment" />
                                </label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0 countinprofit">
                                <label><span>Taux de TVA<sup>*</sup>:</span>
                                    <select name="tva" data-isnotempty="" data-message="Choisissez un taux de TVA!">
                                        <option value=""></option>
                                        <?php
                                        $back = $bdd->query("SELECT * FROM tvas ORDER BY tva");
                                        while($row = $back->fetch()){
                                            ?>
                                        <option value="<?php echo $row['tva'];?>%"><?php echo $row['tva'];?>%</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>	
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
                                <label><span style="color:#FFFFFF;">Remis: </span></label>
                                <label style="padding:10px 10px;font-weight:bold;border:1px solid #39add1;color:#39add1;border-radius:6px;"><input type="checkbox" name="remis" value="0" /> Marquer comme rémis<del class="checkmark" style="top:8px;left:8px;"></del></label>
                            </div>
                            <div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
                                <label><span>Date remis: </span><input type="text" name="dateremiscommand" data-isnotempty="" data-message="Choisissez une date!!" /></label>
                            </div>
                            <div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
                                <label><span>N° de remise: </span><input type="text" autocomplete="off" name="nremise" /></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0 lx-umpaid" style="display:none;">
                                <label style="padding:10px 10px;font-weight:bold;border:1px solid #FF0000;color:#FF0000;border-radius:6px;"><input type="checkbox" name="paid" value="0" /> Marquer comme impayé<del class="checkmark" style="top:8px;left:8px;"></del></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
                                <label><span>Date d'écheance: </span><input type="text" name="dateduecommand" /></label>
                            </div>
                            <div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
                                <label><span>Date d'encaissement / décaissement (Rapprochement): </span><input type="text" name="datepaidcommand" /></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
                                <label><span>Compte banquaire (encaissement / décaissement):</span>
                                    <select name="rib" class="lx-bankaccounts-list">
                                        <option value="0">Choisissez un compte banquaire</option>
                                    </select>
                                </label>
                            </div>
                            <div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
                                <label><span>Imputation comptable:</span>
                                    <select name="imputation" class="todropdown">
                                        <option value="">Saisissez une imputation comptable</option>
                                        <?php
                                        $back = $bdd->query("SELECT DISTINCT imputation FROM payments WHERE imputation<>'' ORDER BY imputation");
                                        while($row = $back->fetch()){
                                            ?>
                                        <option value="<?php echo $row['imputation'];?>"><?php echo $row['imputation'];?></option>
                                            <?php 
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-textfield lx-g1 lx-pb-0">
                                <label><span>Description: </span><textarea type="text" name="description" id="description"></textarea></label>
                            </div>
                            <div class="lx-clear-fix"></div>
                            <div class="lx-submit lx-g1 lx-pb-0">
                                <input type="hidden" name="page1" value="expense" />
                                <input type="hidden" name="page" value="payments" />
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
		<script src="js/moment.min.js"></script>
		<script src="js/daterangepicker.js"></script>
		<script src="js/ion.rangeSlider.min.js"></script>
		<script src="js/script.js"></script>
        <script src="js/modal.js"></script>
		<script>
			$(document).ready(function(){
				loadCaisse(1);
				loadPriceRange('payments');
				toDropDown();
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>