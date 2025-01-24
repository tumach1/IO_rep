<?php

namespace models;

require_once 'Author.php';

class Book
{
    private int $id;
    private String $title;
    private String $subTitle;
    private array $authors;

    /**
     * @param int $id
     * @param String $title
     * @param array $authors
     */
    public function __construct(int $id, string $title, array $authors)
    {
        $this->id = $id;
        $this->title = $title;
        $this->authors = $authors;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

}