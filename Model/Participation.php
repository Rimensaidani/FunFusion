<?php
class Participation {
    private ?int $id_participation;
    private string $username;
    private int $age;
    private int $id_activite;
    private int $id_utilisateur;

    public function __construct(
        string $username,
        int $age,
        int $id_activite,
        int $id_utilisateur,
        ?int $id_participation = null
    ) {
        $this->id_participation = $id_participation;
        $this->username = $username;
        $this->age = $age;
        $this->id_activite = $id_activite;
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getIdParticipation(): ?int {
        return $this->id_participation;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getAge(): int {
        return $this->age;
    }

    public function getIdActivite(): int {
        return $this->id_activite;
    }

    public function getIdUtilisateur(): int {
        return $this->id_utilisateur;
    }
}
