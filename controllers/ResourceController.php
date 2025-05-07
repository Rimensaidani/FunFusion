<?php
include_once '../models/Resource.php';
include_once '../config/db.php';

class ResourceController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createResource() {
        $resource = new Resource($this->db);
    
        // Get values directly from $_POST
        $resource->type = $_POST['type'] ?? null;
        $resource->nom = $_POST['nom'] ?? null;
        $resource->description = $_POST['description'] ?? null;
        $resource->status = $_POST['status'] ?? null;
    
       
    
        return $resource->create();
    }

    public function getAllResources() {
        $resource = new Resource($this->db);
        return $resource->readAll();
    }

    public function getResourceById($id) {
        $resource = new Resource($this->db);
        return $resource->find($id);
    }

    public function updateResource($id, $type, $nom, $description, $status) {
        $resource = new Resource($this->db);
        $resource->id = $id;
        $resource->type = $type;
        $resource->nom = $nom;
        $resource->description = $description;
        $resource->status = $status;
    
        return $resource->update();
    }

    public function deleteResource($id) {
        $resource = new Resource($this->db);
        $resource->id = $id;
        return $resource->delete();
    }
    public function getResourceNameById($id) {
        $sql = "SELECT nom FROM resources WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['nom'] : 'Unknown';
    }
    public function rateResource($resource_id, $rating, $user_id) {
        if ($this->hasRated($resource_id, $user_id)) {
            return false; // User has already rated
        }
    
        $sql = "INSERT INTO resource_ratings (resource_id, user_id, rating, created_at) 
                VALUES (:resource_id, :user_id, :rating, NOW())";
        $stmt = $this->db->prepare($sql); // 🔄 changed from $this->conn
        $stmt->bindParam(':resource_id', $resource_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
    
        return $stmt->execute();
    }
    
    public function hasRated($resource_id, $user_id) {
        $sql = "SELECT COUNT(*) FROM resource_ratings WHERE resource_id = :resource_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql); // 🔄 changed from $this->conn
        $stmt->bindParam(':resource_id', $resource_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        return $stmt->fetchColumn() > 0;
    }
    

    // Get like count for a resource
    public function getLikesCount($resource_id) {
        $query = "SELECT COUNT(*) FROM resource_ratings WHERE resource_id = :resource_id AND rating = 'like'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resource_id', $resource_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Get dislike count for a resource
    public function getDislikesCount($resource_id) {
        $query = "SELECT COUNT(*) FROM resource_ratings WHERE resource_id = :resource_id AND rating = 'dislike'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resource_id', $resource_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getTotalLikes() {
        $query = "SELECT COUNT(*) FROM resource_ratings WHERE rating = 'like'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function getTotalDislikes() {
        $query = "SELECT COUNT(*) FROM resource_ratings WHERE rating = 'dislike'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}
?>