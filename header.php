<link rel="stylesheet" href="css/modal.css">
<div class="lx-header-content">
	<a href="javascript:;" class="lx-mobile-menu"><i class="fas fa-bars"></i></a>
	<div class="lx-header-admin">
		<ul>
			<li>
				<?php if(preg_match("#Consulter Formation#",$_SESSION['easybm_roles'])): ?>
				<a href="formation.php" class="lx-btn lx-btn-primary"><i class="fab fa-youtube"></i> Formation</a>
				<?php endif; ?>
			</li>
			<li>
				<?php if(preg_match("#Consultation des notifications#",$_SESSION['easybm_roles'])): ?>
				<div class="lx-notifications-list-container">
					<a href="javascript:;" class="lx-show-notifications"><i class="far fa-bell"></i><ins></ins></a>
					<div class="lx-notifications-list">
						<i class="fa fa-caret-up"></i>
						<div></div>
						<?php if(preg_match("#Réglage des notifications#",$_SESSION['easybm_roles'])): ?>
						<a href="javascript:;" class="lx-edit-notifications" onclick="document.getElementById('editnotifications-modal').open()">Réglage notifications</a>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			</li>
			<li>
				<div class="lx-user-profile">
					<?php
					$picture = "images/avatar.png";
					if($_SESSION['easybm_picture'] != "avatar.png"){
						$picture = "uploads/cropped_".$_SESSION['easybm_picture'];
					}
					?>
					<img src="<?php echo $picture;?>" class="lx-avatar" />
					<div class="lx-account-settings">
						<div class="lx-user-info">
							<strong><?php echo $_SESSION['easybm_fullname'];?></strong>
							<p><?php echo $_SESSION['easybm_email'];?></p>
						</div>
						<a href="account.php"><i class="fa fa-user"></i> Mon profile</a>
						<a href="settings.php"><i class="fa fa-cog"></i> Paramètres</a>
						<?php if(preg_match("#Télécharger Backup#",$_SESSION['easybm_roles'])): ?>
						<a href="downloadbackup.php"><i class="fa fa-download"></i> Télécharger Backup</a>
						<?php endif; ?>
						<a href="disconnect.php"><i class="fa fa-power-off"></i> Déconnexion</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
<input type="hidden" id="appname" value="<?php echo htmlspecialchars($settings['store']);?>" />

<custom-modal id="editnotifications-modal">
    <div class="lx-form">
        <div class="lx-form-title">
            <h3>Règlage notifications</h3>
        </div>
        <div class="lx-add-form">
            <form autocomplete="off" action="#" method="post" id="editnotificationsform">
                <div class="lx-table lx-table-notifications lx-g1">
                    <table>
                        <tr>
                            <td class="lx-wrap" style="width:auto;">Délai de déclenchement en jours passés</td>
                            <td style="width:1%;">Activer</td>
                        </tr>
                        <tr>
                            <?php
                            $value = explode("-",$notifications['facture'])[1];
                            $checked = explode("-",$notifications['facture'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['facture'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#ffe5e5;width:auto;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;">Factures non payées</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="facture" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#ffe5e5;">
                                <label><input type="checkbox" name="onfacture" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>
                        <tr>
                            <?php
                            $value = explode("-",$notifications['avoir'])[1];
                            $checked = explode("-",$notifications['avoir'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['avoir'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#ffe5e5;width:auto;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;">Avoirs non remboursés</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="avoir" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#ffe5e5;">
                                <label><input type="checkbox" name="onavoir" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table>
                        <tr>
                            <td class="lx-wrap">Délai de déclenchement en jours restants</td>
                            <td>Activer</td>
                            <td class="lx-wrap">Délai de déclenchement en jours passés</td>
                            <td>Activer</td>
                        </tr>
                        <tr>
                            <?php
                            $value = explode("-",$notifications['rcaissein'])[1];
                            $checked = explode("-",$notifications['rcaissein'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['rcaissein'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#fff6e5;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;white-space:nowrap;">Réglements reçus  seront échues dans</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="rcaissein" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#fff6e5;">
                                <label><input type="checkbox" name="roncaissein" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                            <?php
                            $value = explode("-",$notifications['caissein'])[1];
                            $checked = explode("-",$notifications['caissein'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['caissein'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#ffe5e5;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;white-space:nowrap;">Réglements reçus échus non encaissés</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="caissein" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#ffe5e5;">
                                <label><input type="checkbox" name="oncaissein" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>									
                        <tr>
                            <?php
                            $value = explode("-",$notifications['rcaisseout'])[1];
                            $checked = explode("-",$notifications['rcaisseout'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['rcaisseout'])[0]=="on"?"":"readonly";
                            ?>	
                            <td style="background:#fff6e5;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;white-space:nowrap;">Réglements émis seront échues dans</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="rcaisseout" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#fff6e5;">
                                <label><input type="checkbox" name="roncaisseout" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                            <?php
                            $value = explode("-",$notifications['caisseout'])[1];
                            $checked = explode("-",$notifications['caisseout'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['caisseout'])[0]=="on"?"":"readonly";
                            ?>	
                            <td style="background:#ffe5e5;">
                                <div class="lx-textfield lx-g1-f">
                                    <span style="font-weight:500;white-space:nowrap;">Réglements émis échus non décaissés</span>
                                    <label style="display:block;"><input style="display:block;" type="text" autocomplete="off" name="caisseout" value="<?php echo $readonly!="readonly"?$value:"";?>" data-isnumber="" data-message="Saisissez un numéro !!" <?php echo $readonly;?> /></label>
                                </div>															
                            </td>
                            <td style="background:#ffe5e5;">
                                <label><input type="checkbox" name="oncaisseout" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>
                    </table> 
                    <br />
                    <table>
                        <tr>
                            <td style="width:auto;">Alerte</td>
                            <td style="width:1%">Activer</td>
                        </tr>
                        <tr>
                            <td class="lx-wrap" style="background:#ffe5e5;width:auto;"><span style="font-weight:500;">Chèques / effets échus non remis</span></td>
                            <?php
                            $value = explode("-",$notifications['remis'])[1];
                            $checked = explode("-",$notifications['remis'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['remis'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#ffe5e5;">
                                <label><input type="checkbox" name="onremis" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>	
                        <tr>
                            <td class="lx-wrap" style="background:#ffe5e5;width:auto;"><span style="font-weight:500;">Chèques / effets impayés</span></td>
                            <?php
                            $value = explode("-",$notifications['unpaid'])[1];
                            $checked = explode("-",$notifications['unpaid'])[0]=="on"?"checked":"";
                            $readonly = explode("-",$notifications['unpaid'])[0]=="on"?"":"readonly";
                            ?>
                            <td style="background:#ffe5e5;width:1%">
                                <label><input type="checkbox" name="onunpaid" value="1" <?php echo $checked;?> /><del class="checkmark"></del></label>													
                            </td>
                        </tr>
                    </table>
                </div>			
                <div class="lx-clear-fix"></div>
                <div class="lx-submit lx-g1 lx-pb-0">
                    <input type="hidden" name="id" value="0" />
                    <a href="javascript:;" class="">Enregistrer</a>
                </div>
            </form>
        </div>
    </div>
</custom-modal>

<custom-modal id="signature-modal">
    <div class="lx-form">
        <div class="lx-form-title">
            <h3>Confirmation téléchargement</h3>
        </div>
        <div class="lx-add-form">
            <form autocomplete="off" action="#" method="post" id="printform">
                <h3 style="text-align:center;margin-bottom:0px;margin-top:20px;font-size:16px;">Télécharger le document:</h3>
                <div class="lx-textfield lx-g1 lx-pb-0">
                    <label>
                        <span>Cachet et signature:</span>
                        <select name="signature" data-isnotempty="" data-message="Choisissez une option !!">
                            <option value=""></option>
                            <option value="&signature=1">Avec</option>
                            <option value="&signature=0">Sans</option>
                        </select>
                    </label>
                </div>
                <div class="lx-textfield lx-g1 lx-pb-0">
                    <label>
                        <span>En-tête et pied de page:</span>
                        <select name="header" data-isnotempty="" data-message="Choisissez une option !!">
                            <option value=""></option>
                            <option value="&header=1">Avec</option>
                            <option value="&header=0">Sans</option>
                        </select>
                    </label>
                </div>
                <div class="lx-submit lx-g1 lx-pb-0">
                    <input type="hidden" name="href" value="" />
                    <a href="javascript:;" class="">Imprimer</a>
                </div>
            </form>
        </div>
    </div>
</custom-modal>
<script src="js/modal.js"></script>
<script>
    // Handle opening the signature modal
    const printLinks = document.querySelectorAll('a[href*="print"]');
    printLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.getElementById('signature-modal');
            modal.querySelector('input[name="href"]').value = this.href;
            modal.open();
        });
    });
</script>
