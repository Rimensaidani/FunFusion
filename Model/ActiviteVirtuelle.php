<?php
if (!class_exists('ActiviteVirtuelle')) {
class ActiviteVirtuelle {
    private ?int $id_activite;
    private ?string $titre;
    private ?string $type;
    private ?DateTime $date;
    private ?string $plateforme;
    private ?string $lien;
    private ?int $id_createur;
    private ?bool $valide;
    private ?string $image;

    public function __construct(?string $titre, ?string $type, ?DateTime $date, ?string $plateforme, ?string $lien, ?int $id_createur, ?bool $valide = false, ?int $id_activite = null,?string $image = "") {
        
        $this->titre = $titre;
        $this->type = $type;
        $this->date = $date;
        $this->plateforme = $plateforme;
        $this->lien = $lien;
        $this->id_createur = $id_createur;
        $this->valide = $valide;
        $this->id_activite = $id_activite;
        $this->image = $image;
    }
    
    public function getId() {
        return $this->id_activite;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getType() {
        return $this->type;
    }

    public function getDate() {
        return $this->date;
    }

    public function getPlateforme() {
        return $this->plateforme;
    }

    public function getLien() {
        return $this->lien;
    }

    public function getId_createur() {
        return $this->id_createur;
    }

    public function getValide() {
        return $this->valide;
    }

    public function getImage(){
        return $this->image;
    }
    

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setPlateforme($plateforme) {
        $this->plateforme = $plateforme;
    }

    public function setLien($lien) {
        $this->lien = $lien;
    }

    public function setId_createur($id_createur) {
        $this->id_createur = $id_createur;
    }

    public function setValide($valide) {
        $this->valide = $valide;
    }
}
}
?>
