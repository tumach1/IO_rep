<?php

namespace models;

use Database;

require_once __DIR__.'/../../Database.php';

class Reader
{
    private $id;
    private $name;
    private $surname;
    private $email;
    private $hash;
    private $pesel;

    /**
     * @param mixed $hash
     */
    public function setHash(mixed $hash): void
    {
        $this->hash = $hash;
    }

    public function updateHash(String $password): void
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $this->setHash($hash);

        $stmt = Database::get()->prepare('
        UPDATE czytelnicy
        SET hash = :newHash
        WHERE czytelnik_id = :id;
        ');
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':newHash', $hash);
        $stmt->execute();
    }

    public function __construct($id, $name, $surname, $email, $hash, $pesel)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->hash = $hash;
        $this->pesel = $pesel;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHash()
    {
        return $this->hash;
    }
    public function getPesel()
    {
        return $this->pesel;
    }
}