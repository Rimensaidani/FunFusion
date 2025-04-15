<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Participation.php';

class ParticipationController
{
    public function addParticipation(Participation $participation): void
    {
        $db = config::getConnexion();
        try {
            $query = $db->prepare(
                "INSERT INTO participation (id_utilisateur, id_activite, date_participation)
                 VALUES (:id_utilisateur, :id_activite, :date_participation)"
            );

            $query->execute([
                'id_utilisateur' => $participation->getIdUtilisateur(),
                'id_activite' => $participation->getIdActivite(),
                'date_participation' => $participation->getDateParticipation()->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateParticipation(Participation $participation): void
    {
        $db = config::getConnexion();
        try {
            $query = $db->prepare(
                "UPDATE participation SET 
                    id_utilisateur = :id_utilisateur,
                    id_activite = :id_activite,
                    date_participation = :date_participation
                 WHERE id_participation = :id_participation"
            );

            $query->execute([
                'id_utilisateur' => $participation->getIdUtilisateur(),
                'id_activite' => $participation->getIdActivite(),
                'date_participation' => $participation->getDateParticipation()->format('Y-m-d H:i:s'),
                'id_participation' => $participation->getIdParticipation()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteParticipation(int $id): void
    {
        $db = config::getConnexion();
        try {
            $query = $db->prepare("DELETE FROM participation WHERE id_participation = :id");
            $query->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function listParticipationsWithJoin(): array
    {
        $db = config::getConnexion();
        try {
            $query = $db->query("
                SELECT p.id_participation, p.date_participation, 
                       u.nom AS nom_utilisateur, u.email,
                       a.titre AS activite, a.type
                FROM participation p
                JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                JOIN activites_virtuelles a ON p.id_activite = a.id_activite
                ORDER BY p.date_participation DESC
            ");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getParticipationById(int $id): ?array
    {
        $db = config::getConnexion();
        try {
            $query = $db->prepare("SELECT * FROM participation WHERE id_participation = :id");
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
