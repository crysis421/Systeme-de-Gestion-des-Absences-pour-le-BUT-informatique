<?php
session_start();

// Vérifie que les données existent
if (!isset($_SESSION['formData'])) {
    die("Aucune donnée trouvée. Veuillez retourner au formulaire.");
}

$data = $_SESSION['formData'];

// Inclure la classe Database
require_once '../Model/Database.php';

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

// ======================
// Requête UPDATE sécurisée
// ======================
// Enumstatut : valeur directement dans la requête pour PostgreSQL
$sql = "UPDATE Absence
        SET statut = 'refus'::statut_absence,
            estRetard = false
        WHERE idSeance = :idSeance AND idEtudiant = :idEtudiant";

$stmt = $conn->prepare($sql);

try {
    // Exécution avec les identifiants
    $stmt->execute([
        ':idSeance' => 1,
        ':idEtudiant' => 42049956
    ]);

    echo "<h2>Absence mise à jour avec succès ✅</h2>";
    echo "<a href='formulaireAbsence.php'>Retour au formulaire</a>";

    // Supprimer les données de session pour éviter double envoi
    unset($_SESSION['formData']);

} catch (PDOException $e) {
    echo "Erreur lors de la mise à jour : " . $e->getMessage();
}
?>


42049956