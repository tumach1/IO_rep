<?php

namespace models;

class Worker
{
    private $id;
    private $name;
    private $surname;
    private $email;
    private $phoneNumber;
    private $pesel;
    private $hash;

    public function __construct($id, $name, $surname, $email, $phoneNumber, $pesel, $hash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->pesel = $pesel;
        $this->hash = $hash;
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

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getPesel()
    {
        return $this->pesel;
    }

    public function getHash()
    {
        return $this->hash;
    }
}
?>
