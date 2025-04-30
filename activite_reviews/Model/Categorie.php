<?php
include_once '../../config/db.php';

class Categorie {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($nom) {
        $stmt = $this->pdo->prepare("INSERT INTO Categorie (nom) VALUES (?)");
        return $stmt->execute([$nom]);
    }

    public function readAll() {
        $stmt = $this->pdo->query("SELECT * FROM Categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Categorie WHERE id_categorie = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Categorie WHERE id_categorie = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $nom) {
        $stmt = $this->pdo->prepare("UPDATE Categorie SET nom = ? WHERE id_categorie = ?");
        return $stmt->execute([$nom, $id]);
    }
    public function search($keyword) {
        $stmt = $this->pdo->prepare("SELECT * FROM Categorie WHERE nom LIKE ?");
        $stmt->execute(['%' . $keyword . '%']); // Use wildcards for a partial match
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>