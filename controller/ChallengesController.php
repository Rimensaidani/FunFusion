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

 
    public function searchChallenges($searchTerm) {
    try {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM challenges WHERE id_defi LIKE ? OR title LIKE ?");
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
        return [];
    }
}

// Replace the existing getChallengeOfferStats method in ChallengesController with this
public function getChallengeOfferStats() {
    try {
        $pdo = config::getConnexion();
        
        // Get all challenges
        $challengesStmt = $pdo->query("SELECT id_defi, title FROM challenges");
        $challenges = $challengesStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stats = [];
        foreach ($challenges as $challenge) {
            $id_defi = $challenge['id_defi'];
            
            // Get total offers for this challenge
            $totalStmt = $pdo->prepare("SELECT COUNT(*) as total FROM offres WHERE id_defi = ?");
            $totalStmt->execute([$id_defi]);
            $totalOffersForChallenge = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $challengeStats = [
                'id_defi' => $id_defi,
                'title' => $challenge['title'],
                'total_offers' => $totalOffersForChallenge,
                'offer_types' => []
            ];
            
            if ($totalOffersForChallenge > 0) {
                // Get breakdown of offer types for this challenge
                $stmt = $pdo->prepare("
                    SELECT type, COUNT(*) as count
                    FROM offres
                    WHERE id_defi = ?
                    GROUP BY type
                ");
                $stmt->execute([$id_defi]);
                $offerTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Calculate percentages for each offer type
                foreach ($offerTypes as $offer) {
                    $percentage = ($offer['count'] / $totalOffersForChallenge) * 100;
                    $challengeStats['offer_types'][] = [
                        'type' => $offer['type'],
                        'count' => $offer['count'],
                        'percentage' => $percentage
                    ];
                }
            }
            
            $stats[] = $challengeStats;
        }
        
        return $stats;
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
        return [];
    }
}
}
?>
