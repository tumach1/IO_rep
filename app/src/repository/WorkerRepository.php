<?php

use models\Worker;

require_once __DIR__ . "/Repository.php";
require_once __DIR__ . "/../models/Worker.php";

class WorkerRepository extends Repository
{
    public function getByEmail(string $email)
    {
        $stmt = $this->database->prepare('
            SELECT * FROM pracownicy WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $worker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$worker) {
            return null;
        }

        return new Worker(
            $worker['pracownik_id'],
            $worker['imie'],
            $worker['nazwisko'],
            $worker['email'],
            $worker['nr_tel'],
            $worker['pesel'],
            $worker['hash']
        );
    }

    public function getById(int $id)
    {
        $stmt = $this->database->prepare('
            SELECT * FROM pracownicy WHERE pracownik_id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $worker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$worker) {
            return null;
        }

        return new Worker(
            $worker['pracownik_id'],
            $worker['imie'],
            $worker['nazwisko'],
            $worker['email'],
            $worker['nr_tel'],
            $worker['pesel'],
            $worker['hash']
        );
    }

    static public function create($name, $surname, $email, $phone, $pesel, $password)
    {
        $db = Database::get();
        $stmt = $db->prepare('
            INSERT INTO pracownicy (imie, nazwisko, email, nr_tel, pesel, stanowisko_id, placowka_id, hash)
            VALUES (:imie, :nazwisko, :email, :nr_tel, :pesel, :hash);
        ');
        $stmt->bindParam(':imie', $name);
        $stmt->bindParam(':nazwisko', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nr_tel', $phone);
        $stmt->bindParam(':pesel', $pesel);

        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $stmt->bindParam(':hash', $hash);

        if (!$stmt->execute()) {
            return null;
        }

        $workerRepository = new WorkerRepository();
        return $workerRepository->getByEmail($email);
    }
}

