<?php
require_once __DIR__ . '/../config.php';
include(__DIR__ . '/../Model/ActiviteVirtuelle.php');

class ActiviteVirtuelleController
{
    public function listActiviteVirtuelle() {
        $sql = "SELECT * FROM activites_virtuelles";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC); // ← très important
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    

    public function listActivitesValidees()
{
    $db = config::getConnexion();
    $sql = "SELECT * FROM activites_virtuelles WHERE valide = 1";
    return $db->query($sql)->fetchAll();
}


    function deleteActiviteVirtuelle($id_activite)
    {
        $sql = "DELETE FROM activites_virtuelles WHERE id_activite = :id_activite";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_activite', $id_activite);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function addActiviteVirtuelle($activite)
    {   var_dump($activite);
        $sql = "INSERT INTO activites_virtuelles 
        VALUES (NULL, :titre,:type, :date,:plateforme, :lien, :id_createur, :valide , :image)";
        $db = config::getConnexion();
        try {
            
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $activite->getTitre(),
                'type' => $activite->getType(),
                'date' => $activite->getDate()->format('Y-m-d'), 
                'plateforme' => $activite->getPlateforme(),
                'lien' => $activite->getLien(),
                'id_createur' => $activite->getId_createur() ,
                'valide' => $activite->getValide() ? 1 : 0,
                'image' => $activite->getImage()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updateActiviteVirtuelle($activite, $id_activite)
{
    var_dump($activite);
    try {
        $db = config::getConnexion();

        $query = $db->prepare(
            'UPDATE activites_virtuelles SET 
                titre = :titre,
                type = :type,
                date = :date,
                plateforme = :plateforme,
                lien = :lien,
                id_createur = :id_createur,
                valide = :valide ,
                image = :image
            WHERE id_activite = :id_activite'
        );

        $query->execute([
            'id_activite' => $id_activite,
            'titre' => $activite->getTitre(),
            'type' => $activite->getType(),
            'date' => $activite->getDate()->format('Y-m-d H:i:s'), 
            'plateforme' => $activite->getPlateforme(),
            'lien' => $activite->getLien(),
            'id_createur' => $activite->getId_createur(),
            'valide' => $activite->getValide() ? 1 : 0,
            'image' => $activite->getImage()
        ]);

        echo $query->rowCount() . " records UPDATED successfully <br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); 
    }
}


    function showActiviteVirtuelle($id_activite)
    {
        $sql = "SELECT * from activites_virtuelles where id_activite = $id_activite";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();

            $activite = $query->fetch();
            return $activite;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function searchActivitesByTitre($mot)
{
    $sql = "SELECT * FROM activites_virtuelles WHERE titre LIKE :mot";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->execute(['mot' => '%' . $mot . '%']);
    return $stmt->fetchAll();
}

public function filterByType($type) {
    $sql = "SELECT * FROM activites_virtuelles WHERE valide = 1 AND type = :type";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function sortByDate($order = 'DESC') {
    $sql = "SELECT * FROM activites_virtuelles WHERE valide = 1 ORDER BY date $order";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 

public function getParticipationStats() {
    $sql = "SELECT a.id_activite, a.titre, COUNT(p.id_participation) AS nombre_participants
            FROM activites_virtuelles a
            LEFT JOIN participation p ON a.id_activite = p.id_activite
            GROUP BY a.id_activite, a.titre
            ORDER BY nombre_participants DESC";

    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}



}
?>
