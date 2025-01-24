<?php

namespace repository;
use models\Book;

require_once 'Repository.php';

class CopyRepository extends \Repository
{
    public function getBookId($copyId){
        $stmt = $this->database->prepare('
        SELECT ksiazka_id AS bookId FROM egzemplarze WHERE egzemplarz_id = :copyId;
        ');
        $stmt->bindParam(':copyId', $copyId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(empty($result)){
            return null;
        }
        return $result['bookId'];
    }

}