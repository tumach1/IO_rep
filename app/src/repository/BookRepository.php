<?php

use models\Author;
use models\Book;

require_once 'Repository.php';
require_once __DIR__.'/../models/Book.php';
require_once __DIR__.'/../models/Author.php';

class BookRepository extends \Repository
{
    public function getJsonData($id) {
        $stmt = $this->database->prepare('
            SELECT
                ksiazki.ksiazka_id as id,
                tytul as title,
                podtytul as subtitle,
                tytul_oryg as title_original,
                podtytul_oryg as subtitle_original,
                isbn as isbn,
                liczba_str as pages,
                oprawki.typ as binding,
                JSON_ARRAYAGG(autorzy.imie) as authors,
                JSON_ARRAYAGG(DISTINCT kategorie.kategoria) as genres,
                img_path as imageUrl,
                opis as description
            FROM ksiazki
                     LEFT JOIN oprawki
                               ON ksiazki.oprawka_id = oprawki.oprawka_id
            
                     LEFT JOIN ksiazki_kategorie
                                ON ksiazki.ksiazka_id = ksiazki_kategorie.ksiazka_id
                     LEFT JOIN kategorie
                                ON ksiazki_kategorie.kategoria_id = kategorie.kategoria_id
            
                     INNER JOIN ksiazki_autorzy
                                ON ksiazki.ksiazka_id = ksiazki_autorzy.ksiazka_id
                     INNER JOIN autorzy
                                ON ksiazki_autorzy.autor_id = autorzy.autor_id
            
            WHERE ksiazki.ksiazka_id = :id
            GROUP BY ksiazki.ksiazka_id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$book) {
            return null;
        }

        $book['authors'] = json_decode($book['authors']);
        $book['genres'] = json_decode($book['genres']);

        return json_encode($book);
    }

    public function getAllJsonData() {
        $stmt = $this->database->prepare('
            SELECT
                ksiazki.ksiazka_id as id,
                tytul as title,
                podtytul as subtitle,
                tytul_oryg as title_original,
                podtytul_oryg as subtitle_original,
                isbn as isbn,
                liczba_str as pages,
                oprawki.typ as binding,
                JSON_ARRAYAGG(CONCAT(autorzy.imie, \' \', autorzy.nazwisko)) as authors,
                JSON_ARRAYAGG(DISTINCT kategorie.kategoria) as genres,
                img_path as imageUrl,
                opis as description
            FROM ksiazki
                     LEFT JOIN oprawki
                               ON ksiazki.oprawka_id = oprawki.oprawka_id
            
                     LEFT JOIN ksiazki_kategorie
                                ON ksiazki.ksiazka_id = ksiazki_kategorie.ksiazka_id
                     LEFT JOIN kategorie
                                ON ksiazki_kategorie.kategoria_id = kategorie.kategoria_id
            
                     INNER JOIN ksiazki_autorzy
                                ON ksiazki.ksiazka_id = ksiazki_autorzy.ksiazka_id
                     INNER JOIN autorzy
                                ON ksiazki_autorzy.autor_id = autorzy.autor_id
            
            GROUP BY ksiazki.ksiazka_id
        ');
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$books) {
            return null;
        }

        for($i = 0; $i < sizeof($books); $i++) {
            $books[$i]['authors'] = json_decode($books[$i]['authors']);
            $books[$i]['genres'] = json_decode($books[$i]['genres']);
        }

        return json_encode($books);
    }

    function borrowBook($bookId, $readerId): void
    {
        $stmt = Database::get()->prepare('
        CALL wypozycz_ksiazke(:bookId, :readerId);
        ');
        $stmt->bindParam(':bookId', $bookId);
        $stmt->bindParam(':readerId', $readerId);
        $stmt->execute();
    }

    function getById($bookId){
        $stmt = $stmt = $this->database->prepare('
        SELECT ksiazka_id AS id, tytul AS title
        FROM ksiazki
        WHERE ksiazka_id = :id
        ');
        $stmt->bindParam(':id', $bookId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            return null;
        }

        $stmt = $this->database->prepare('
        SELECT autorzy.autor_id AS id, imie AS name, nazwisko AS surname
        FROM ksiazki_autorzy
        INNER JOIN autorzy ON ksiazki_autorzy.autor_id = autorzy.autor_id
        WHERE ksiazki_autorzy.ksiazka_id = :id;
        ');
        $stmt->bindParam(':id', $bookId);
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $authors = array();
        foreach ($result2 as $row){
            $authors[] = new Author($row['id'], $row['name'], $row['surname']);
        }

        return new Book($result['id'], $result['title'], $authors);
    }

}