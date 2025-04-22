<?php
// Inclusion du fichier de configuration
include 'C:\xampp\htdocs\Projetttttt\Config.php';
// === CONTROLLER ===
class ChallengesController {
    // Fonction pour ajouter un challenge
    public function ajouterchallenges($title, $type, $creation_date, $score) {
        try {
            // Connexion à la base de données
            $pdo = config::getConnexion();

            // Préparation de la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO challenges (title, type, creation_date, score) VALUES (?, ?, ?, ?)");

            // Exécution de la requête avec les données
            $stmt->execute([$title, $type, $creation_date, $score]);

            return true;  // Indiquer que l'ajout a réussi
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;  // Indiquer que l'ajout a échoué
        }
    }
    public function afficherChallenges() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM challenges");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }
    public function deleteChallenge($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM challenges WHERE id_defi = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
    public function modifierChallenge($id, $title, $type, $creation_date, $score) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE challenges SET title = ?, type = ?, creation_date = ?, score = ? WHERE id_defi = ?");
            $stmt->execute([$title, $type, $creation_date, $score, $id]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}


?>
