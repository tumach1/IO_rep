<?php

namespace models;

class Author
{
    private int $id;
    private String $firstName;
    private String $surname;

    /**
     * @param int $id
     * @param String $firstName
     * @param String $surname
     */
    public function __construct(int $id, string $firstName, string $surname)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->surname = $surname;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }


}