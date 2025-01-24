<?php

use models\Reader;
require_once __DIR__."/Repository.php";
require_once __DIR__."/../models/Reader.php";
class ReaderRepository extends Repository
{
    public function getByEmail(string $email) {
        $stmt = $this->database->prepare('
            SELECT * FROM czytelnicy WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $reader = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$reader) {
            return null;
        }

        return new Reader(
            $reader['czytelnik_id'],
            $reader['imie'],
            $reader['nazwisko'],
            $reader['email'],
            $reader['hash'],
            $reader['pesel']
        );
    }

    public function getById(int $id) {
        $stmt = $this->database->prepare('
            SELECT * FROM czytelnicy WHERE czytelnik_id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $reader = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$reader) {
            return null;
        }

        return new Reader(
            $reader['czytelnik_id'],
            $reader['imie'],
            $reader['nazwisko'],
            $reader['email'],
            $reader['hash'],
            $reader['pesel']
        );
    }

    static public function create($name, $surname, $email, $pesel, $street, $town, $zip, $password) {
        $db = Database::get();
        $stmt = $db->prepare('
            INSERT INTO czytelnicy (imie, nazwisko, email, pesel, ulica, miejscowosc_id, kod_pocztowy, hash)
            VALUES (:imie, :nazwisko, :email, :pesel, :ulica, :miejscowosc_id, :kod_pocztowy, :hash);
        ');
        $stmt->bindParam(':imie', $name);
        $stmt->bindParam(':nazwisko', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pesel', $pesel);
        $stmt->bindParam(':ulica', $street);
        $stmt->bindParam(':miejscowosc_id', $town);
        $stmt->bindParam(':kod_pocztowy', $zip);

        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $stmt->bindParam(':hash', $hash);

        if(!$stmt->execute()) {
            return null;
        }

        $readerRepository = new ReaderRepository();
        return $readerRepository->getByEmail($email);
    }

    public function existsByEmailOrPesel(string $email, string $pesel): bool {
        $stmt = $this->database->prepare('
            SELECT 1 FROM czytelnicy WHERE email = :email OR pesel = :pesel
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pesel', $pesel, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() !== false;
    }
}