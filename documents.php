<?php
session_start();
include("config.php");

// Determine the document type from the URL, default to 'factures'
$type = isset($_GET['type']) ? $_GET['type'] : 'factures';

// Define properties for each document type
$doc_types = [
    'factures' => ['title' => 'Factures', 'singular' => 'Facture', 'prefix' => 'FA', 'role' => 'Consulter Factures,'],
    'devis' => ['title' => 'Devis', 'singular' => 'Devis', 'prefix' => 'DE', 'role' => 'Consulter Devis'],
    'avoirs' => ['title' => 'Factures avoir', 'singular' => 'Facture avoir', 'prefix' => 'AV', 'role' => 'Consulter Factures avoir'],
    'br' => ['title' => 'Bons de retour', 'singular' => 'Bon de retour', 'prefix' => 'BR', 'role' => 'Consulter Bons de retour'],
    'facturesproforma' => ['title' => 'Factures Proforma', 'singular' => 'Facture Proforma', 'prefix' => 'FP', 'role' => 'Consulter Factures proforma'],
    'bl' => ['title' => 'Bons de livraison', 'singular' => 'Bon de livraison', 'prefix' => 'BL', 'role' => 'Consulter Bons de livraison'],
    'bs' => ['title' => 'Bons de sortie', 'singular' => 'Bon de sortie', 'prefix' => 'BS', 'role' => 'Consulter Bons de sortie'],
    'bc' => ['title' => 'Bons de commande', 'singular' => 'Bon de commande', 'prefix' => 'BC', 'role' => 'Consulter Bons de commande'],
    'bre' => ['title' => 'Bons de récéption', 'singular' => 'Bon de récéption', 'prefix' => 'BRE', 'role' => 'Consulter Bons de récéption'],
];

// Check if the document type exists and the user has the required role
if (!isset($doc_types[$type]) || !preg_match("#" . $doc_types[$type]['role'] . "#", $_SESSION['easybm_roles'])) {
    header('location: 404.php');
    exit;
}

$current_doc = $doc_types[$type];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($current_doc['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($settings['store']); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/modern_theme.css">
    <link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
    <link rel="stylesheet" href="css/ion.rangeSlider.min.css" />
    <link rel="shortcut icon" href="favicon.ico">
    <?php include("onesignal.php"); ?>
    <script src="js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 150,
            menubar: false,
            plugins: 'advlist autolink lists link charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
            content_style: 'body { font-family:Inter, sans-serif; font-size:14px }'
        });
    </script>
</head>
<body>
    <div class="lx-wrapper">
        <header class="lx-header">
            <?php include('header.php'); ?>
        </header>
        <div class="lx-main">
            <aside class="lx-main-leftside">
                <?php include('mainmenu.php'); ?>
            </aside>
            <main class="lx-main-content">
                <div class="lx-page-header">
                    <h1><?php echo htmlspecialchars($current_doc['title']); ?></h1>
                </div>
                <div class="lx-tabs">
                    <?php foreach ($doc_types as $key => $doc): ?>
                        <?php if (preg_match("#" . $doc['role'] . "#", $_SESSION['easybm_roles'])): ?>
                            <a href="documents.php?type=<?php echo $key; ?>" class="<?php echo $type == $key ? 'active' : ''; ?>"><?php echo htmlspecialchars($doc['title']); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="lx-page-content">
                    <div class="lx-g1-f">
                        <div class="lx-add-form">
                            <button class="lx-btn" onclick="openCommandModal()">+ Nouveau <?php echo htmlspecialchars($current_doc['singular']); ?></button>
                        </div>
                        <div class="lx-keyword">
                            <form onsubmit="return false;">
                                <input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Rechercher..." data-table="<?php echo $type; ?>" />
                            </form>
                        </div>
                    </div>
                    <div class="lx-table-container">
                        <div class="lx-table lx-table-<?php echo $type; ?>">
                            <!-- Table content will be loaded by JavaScript -->
                        </div>
                    </div>
                    <div class="lx-pagination">
                        <!-- Pagination will be loaded by JavaScript -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Document Modal -->
    <custom-modal id="command-modal">
        <div class="lx-form">
            <div class="lx-form-title">
                <h3><span id="command-modal-title-action">Ajouter</span> un(e) nouveau(elle) <?php echo htmlspecialchars($current_doc['singular']); ?></h3>
            </div>
            <div class="lx-add-form">
                <form autocomplete="off" action="#" method="post" id="commandsform">
                    <!-- Dynamic content will be injected here by JS -->
                </form>
            </div>
        </div>
    </custom-modal>
    
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/jquery.popup.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.js"></script>
    <script src="js/ion.rangeSlider.min.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/script.js"></script>
    <script>
        function openCommandModal(id = 0) {
            const docType = '<?php echo $type; ?>';
            const modal = document.getElementById('command-modal');
            const form = document.getElementById('commandsform');
            const titleAction = document.getElementById('command-modal-title-action');

            if (id) {
                titleAction.textContent = 'Modifier';
                // AJAX call to get document data and populate form
                // $.post('ajax.php', { action: 'get_document', id: id, type: docType }, function(data) {
                //     // Populate form fields with data
                // });
            } else {
                titleAction.textContent = 'Ajouter';
                form.reset();
            }

            // You can load the specific form fields for the document type here
            // For now, it's a placeholder.
            form.innerHTML = `
                <input type="hidden" name="id" value="${id}">
                <input type="hidden" name="type" value="${docType}">
                <div class="lx-textfield">
                    <label>Client</label>
                    <input type="text" name="client_name" placeholder="Nom du client">
                </div>
                <div class="lx-textfield">
                    <label>Montant</label>
                    <input type="number" name="amount" placeholder="0.00">
                </div>
                <div class="lx-submit">
                    <button type="submit" class="lx-btn">Enregistrer</button>
                </div>
            `;

            modal.open();
        }

        $(document).ready(function() {
            // Initial load
            loadDocuments('<?php echo $type; ?>');
        });

        function loadDocuments(type) {
            // This function would use AJAX to load the table content
            console.log(`Loading documents of type: ${type}`);
            // Placeholder for the AJAX call
            $('.lx-table-' + type).html('<p style="text-align:center; padding: 2rem;">Chargement des données...</p>');
        }
    </script>
</body>
</html>