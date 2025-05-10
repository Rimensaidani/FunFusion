<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Participation.php';

class ParticipationController {
    public function addParticipation(Participation $participation) {
        $pdo = config::getConnexion();
        $sql = "INSERT INTO participation (username, age,  id_activite, id_utilisateur)
                VALUES (:username, :age,  :id_activite, :id_utilisateur)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'username' => $participation->getUsername(),
                'age' => $participation->getAge(),
                'id_activite' => $participation->getIdActivite(),
                'id_utilisateur' => $participation->getIdUtilisateur(),
            ]);
        } catch (PDOException $e) {
            echo "Erreur ajout participation : " . $e->getMessage();
        }
    }

    public function getParticipationByUserAndActivity($idUtilisateur, $idActivite) {
        $sql = "SELECT * FROM participation WHERE id_utilisateur = :idUtilisateur AND id_activite = :idActivite";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':idUtilisateur', $idUtilisateur);
            $query->bindParam(':idActivite', $idActivite);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); // retournera null si aucune participation
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    

    public function deleteParticipation($id_participation) {
        $sql = "DELETE FROM participation 
                WHERE id_participation = :id_participation ";
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_participation' => $id_participation
                
            ]);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    

    public function updateParticipation(Participation $p) {
        $pdo = config::getConnexion();
        $sql = "UPDATE participation SET 
                username = :username, 
                age = :age, 
                id_activite = :id_activite,
                id_utilisateur = :id_utilisateur
                WHERE id_participation = :id_participation";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'username' => $p->getUsername(),
                'age' => $p->getAge(),
                'id_activite' => $p->getIdActivite(),
                'id_utilisateur' => $p->getIdUtilisateur(),
                'id_participation' => $p->getIdParticipation()
            ]);
            
            return $stmt->rowCount() > 0; // Retourne true si au moins une ligne a été modifiée
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la participation: " . $e->getMessage());
            return false;
        }
    }

    public function listParticipationsAvecActivite() {
        $sql = "SELECT p.*, a.titre 
                FROM participation p 
                JOIN activites_virtuelles a ON p.id_activite = a.id_activite";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getParticipationsByActivite($id_activite) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM participation WHERE id_activite = ?");
        $stmt->execute([$id_activite]);
        return $stmt->fetchAll();
    }

    public function checkUserParticipation($id_utilisateur, $id_activite) {
    
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT COUNT(*) FROM participation WHERE id_utilisateur = ? AND id_activite = ?");
        $stmt->execute([$id_utilisateur, $id_activite]);
        return $stmt->fetchColumn() > 0;
    }
    public function countParticipations($id_activite) {
        $db = config::getConnexion();
        $sql = "SELECT COUNT(*) FROM participation WHERE id_activite = ?"; // Changé de 'participations' à 'participation'
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_activite]);
        return $stmt->fetchColumn();
    } 

}    

?>
