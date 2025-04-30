<?php
include_once '../../config/db.php';

class Activite_Reelle {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($titre, $lieu, $date, $categorie_id) {
        $stmt = $this->pdo->prepare("INSERT INTO Activite_Reelle (titre, lieu, date, categorie_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$titre, $lieu, $date, $categorie_id]);
    }
    
    public function readAll() {
        $stmt = $this->pdo->query("SELECT a.*, c.nom as categorie_nom FROM Activite_Reelle a LEFT JOIN Categorie c ON a.categorie_id = c.id_categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Activite_Reelle WHERE id_activite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Activite_Reelle WHERE id_activite = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $titre, $lieu, $date, $categorie_id) {
        $stmt = $this->pdo->prepare("UPDATE Activite_Reelle SET titre = ?, lieu = ?, date = ?, categorie_id = ? WHERE id_activite = ?");
        return $stmt->execute([$titre, $lieu, $date, $categorie_id, $id]);
    }

    public function search($keyword) {
        $stmt = $this->pdo->prepare("SELECT * FROM Activite_Reelle WHERE titre LIKE ?");
        $searchTerm = "%" . $keyword . "%";
        $stmt->execute([$searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>