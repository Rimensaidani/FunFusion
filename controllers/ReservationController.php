<?php
include_once '../models/Reservation.php';
include_once '../config/db.php';
require_once '../models/Payment.php';

class ReservationController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReservation($data) {
        // Create and save the reservation
        $reservation = new Reservation($this->db);
        $reservation->resource_id = $data['resource_id'];
        $reservation->location = $data['location'];
        $reservation->date_reservation = $data['date_reservation'];
        $reservation->date_retour = $data['date_retour'];
        $reservation->user = $data['user'];
        $reservation->etat = $data['etat'];
    
        // Save the reservation and get its ID
        if ($reservation->create()) {
            $reservationId = $this->db->lastInsertId(); // Get the last inserted reservation ID
    
            // Create the payment
            $payment = new Payment($this->db);
            $payment->reservation_id = $reservationId;
            $payment->amount = $data['amount'];
            $payment->user_id = 1; // Static user ID
            $payment->status = 'pending'; // Default status for payments
    
            // Save the payment record
            if ($payment->createPayment()) {
                return true; // Reservation and payment created successfully
            } else {
                // Handle payment failure (optional, you can implement your error handling here)
                return false;
            }
        }
        
        return false; // Reservation creation failed
    }

    public function getAllReservations() {
        $reservation = new Reservation($this->db);
        return $reservation->readAll();
    }

    public function getReservationById($id) {
        $reservation = new Reservation($this->db);
        return $reservation->find($id);
    }

    public function updateReservation($id, $resource_id, $location, $date_reservation, $date_retour, $user, $etat) {
        $reservation = new Reservation($this->db);
        $reservation->id = $id;
        $reservation->resource_id = $resource_id;
        $reservation->location = $location;
        $reservation->date_reservation = $date_reservation;
        $reservation->date_retour = $date_retour;
        $reservation->user = $user;
        $reservation->etat = $etat;
    
        return $reservation->update();
    }   
    public function deleteReservation($id) {
        $reservation = new Reservation($this->db);
        return $reservation->delete($id);
    }
}
?>