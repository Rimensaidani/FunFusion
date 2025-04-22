
<?php
class Offre {
    private $id_offre;
    private $type;
    private $date_expiration;
    private $id_defi;
    private $etat;

    // Constructeur paramétré
    public function __construct($id_offre, $type, $date_expiration, $id_defi, $etat) {
        $this->id_offre = $id_offre;
        $this->type = $type;
        $this->date_expiration = $date_expiration;
        $this->id_defi = $id_defi;
        $this->etat = $etat;
    }

    // Getters
    public function getIdOffre() {
        return $this->id_offre;
    }

    public function getType() {
        return $this->type;
    }

    public function getDateExpiration() {
        return $this->date_expiration;
    }

    public function getIdDefi() {
        return $this->id_defi;
    }

    public function getEtat() {
        return $this->etat;
    }

    // Setters
    public function setIdOffre($id_offre) {
        $this->id_offre = $id_offre;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setDateExpiration($date_expiration) {
        $this->date_expiration = $date_expiration;
    }

    public function setIdDefi($id_defi) {
        $this->id_defi = $id_defi;
    }

    public function setEtat($etat) {
        $this->etat = $etat;
    }
}
?>
