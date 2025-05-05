<?php
// Inclusion du fichier de configuration
include 'C:\xampp\htdocs\Projetttttt\Config.php';

class OffresController {

    // Ajouter une offre
    public function ajouterOffre($type, $date_expiration, $id_defi, $etat) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO offres (type, date_expiration, id_defi, etat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$type, $date_expiration, $id_defi, $etat]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // Afficher toutes les offres
    public function afficherOffres() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM offres");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }
    public function afficherChallenges() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT id_defi, nom FROM challenges");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }
    // Supprimer une offre
    public function supprimerOffres($id_offre) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM offres WHERE id_offre = ?");
            $stmt->execute([$id_offre]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // Modifier une offre
    public function modifierOffres($id_offre, $type, $date_expiration, $id_defi, $etat) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE offres SET type = ?, date_expiration = ?, id_defi = ?, etat = ? WHERE id_offre = ?");
            $stmt->execute([$type, $date_expiration, $id_defi, $etat, $id_offre]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

        public function searchOffres($searchTerm) {
            try {
                $pdo = config::getConnexion();
                $stmt = $pdo->prepare("SELECT o.*, c.score 
                                       FROM offres o 
                                       LEFT JOIN challenges c ON o.id_defi = c.id_defi 
                                       WHERE o.id_offre LIKE ? OR o.type LIKE ?");
                $searchTerm = "%" . $searchTerm . "%";
                $stmt->execute([$searchTerm, $searchTerm]);
                $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                foreach ($offres as &$offre) {
                    $score = (int)($offre['score'] ?? 0);
                    $offre['etat'] = ($score > 500) ? 'debloque' : 'bloque';
    
                    $stmt_update = $pdo->prepare("UPDATE offres SET etat = ? WHERE id_offre = ?");
                    $stmt_update->execute([$offre['etat'], $offre['id_offre']]);
                }
    
                return $offres;
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
                return [];
            }
        }
    }
    