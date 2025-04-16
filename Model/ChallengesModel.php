<?php
class ChallengesModel {
    private $id_defi;
    private $title;
    private $type;
    private $creation_date;
    private $score;
    

    // Constructeur paramétré
    public function __construct($id_defi, $title, $type, $creation_date, $score) {
        $this->id_defi = $id_defi;
        $this->title = $title;
        $this->type = $type;
        $this->creation_date = $creation_date;
        $this->score = $score;
        
    }

    // Getters
    public function getIdDefi() {
        return $this->id_defi;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getType() {
        return $this->type;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function getScore() {
        return $this->score;
    }

    

    // Setters
    public function setIdDefi($id_defi) {
        $this->id_defi = $id_defi;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setType_c($type) {
        $this->type = $type;
    }

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function setScore($score) {
        $this->score = $score;
    }

   
}
?>
