<div class="lx-main-menu">
    <div class="lx-logo">
        <a href="index.php">
            <img src="<?php echo $settings['logo']=="logo.png"?"images/".$settings['logo']:"uploads/".$settings['logo'];?>" alt="Logo">
        </a>
    </div>
    <div class="lx-main-menu-scroll">
        <nav>
            <ul>
                <?php if(preg_match("#Consulter Tableau de bord#",$_SESSION['easybm_roles'])): ?>
                    <li><a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "index.php"){echo 'active';}?>"><i class="fa fa-chart-pie"></i><span>Tableau de bord</span></a></li>
                <?php endif; ?>

                <?php if(preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])): ?>
                    <li><a href="payments.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "payments.php"){echo 'active';}?>"><i class="fa fa-cash-register"></i><span>Trésorerie</span></a></li>
                <?php endif; ?>

                <?php
                $show_docs_clients = preg_match("#Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles']);
                $show_clients = preg_match("#Consulter Clients#",$_SESSION['easybm_roles']);
                if($show_docs_clients || $show_clients):
                ?>
                    <li class="lx-menu-separator"><span>Ventes</span></li>
                    <?php if($show_docs_clients): ?>
                         <li>
                            <a href="documents.php?type=factures" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'factures'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Factures</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=devis" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'devis'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Devis</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=avoirs" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'avoirs'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Factures Avoir</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=br" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'br'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Bons de Retour</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=facturesproforma" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'facturesproforma'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Factures Proforma</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=bl" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'bl'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Bons de Livraison</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=bs" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'bs'){echo 'active';}?>"><i class="fa fa-file-alt"></i><span>Bons de Sortie</span></a>
                        </li>
                    <?php endif; ?>
                     <?php if($show_clients): ?>
                        <li><a href="clients.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "clients.php"){echo 'active';}?>"><i class="fa fa-users"></i><span>Clients</span></a></li>
                    <?php endif; ?>
                <?php endif; ?>


                <?php
                $show_docs_fournisseurs = preg_match("#Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles']);
                $show_fournisseurs = preg_match("#Consulter Fournisseurs#",$_SESSION['easybm_roles']);
                if($show_docs_fournisseurs || $show_fournisseurs):
                ?>
                    <li class="lx-menu-separator"><span>Achats</span></li>
                    <?php if($show_docs_fournisseurs): ?>
                        <li>
                            <a href="documents.php?type=bc" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'bc'){echo 'active';}?>"><i class="fa fa-file-invoice-dollar"></i><span>Bons de commande</span></a>
                        </li>
                        <li>
                            <a href="documents.php?type=bre" class="<?php if(basename($_SERVER['PHP_SELF']) == "documents.php" && $_GET['type'] == 'bre'){echo 'active';}?>"><i class="fa fa-file-invoice-dollar"></i><span>Bons de récéption</span></a>
                        </li>
                    <?php endif; ?>
                    <?php if($show_fournisseurs): ?>
                        <li><a href="suppliers.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "suppliers.php"){echo 'active';}?>"><i class="fa fa-truck"></i><span>Fournisseurs</span></a></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(preg_match("#Consulter Autres dépences et recettes#",$_SESSION['easybm_roles'])): ?>
                     <li class="lx-menu-separator"><span>Comptabilité</span></li>
                    <li><a href="expenses.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "expenses.php"){echo 'active';}?>"><i class="fa fa-balance-scale"></i><span>Autres dépences et recettes</span></a></li>
                <?php endif; ?>


                <?php
                $show_societes = preg_match("#Consulter Sociétés#",$_SESSION['easybm_roles']);
                $show_users = preg_match("#Consulter Utilisateurs#",$_SESSION['easybm_roles']);
                if($show_societes || $show_users):
                ?>
                    <li class="lx-menu-separator"><span>Paramètres</span></li>
                    <?php if($show_societes): ?>
                        <li><a href="companies.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "companies.php"){echo 'active';}?>"><i class="far fa-building"></i><span>Sociétés</span></a></li>
                    <?php endif; ?>
                    <?php if($show_users): ?>
                        <li><a href="users.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "users.php"){echo 'active';}?>"><i class="fa fa-users-cog"></i><span>Utilisateurs</span></a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
