<?php

namespace models;

class Copy
{
    private int $id;
    private int $bookId;

    /**
     * @param int $id
     * @param int $bookId
     */
    public function __construct(int $id, int $bookId)
    {
        $this->id = $id;
        $this->bookId = $bookId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

}