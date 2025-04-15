<?php

class Participation {
    private ?int $id_participation;
    private int $id_utilisateur;
    private int $id_activite;
    private DateTime $date_participation;

    public function __construct(int $id_utilisateur,int $id_activite,?DateTime $date_participation = null,?int $id_participation = null) {
        $this->id_utilisateur = $id_utilisateur;
        $this->id_activite = $id_activite;
        $this->date_participation = $date_participation ?? new DateTime();
        $this->id_participation = $id_participation;
    }

    public function getIdParticipation(): ?int {
        return $this->id_participation;
    }

    public function getIdUtilisateur(): int {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getIdActivite(): int {
        return $this->id_activite;
    }

    public function setIdActivite(int $id_activite): void {
        $this->id_activite = $id_activite;
    }

    public function getDateParticipation(): DateTime {
        return $this->date_participation;
    }

    public function setDateParticipation(DateTime $date): void {
        $this->date_participation = $date;
    }
}
