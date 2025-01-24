<?php
require_once 'Repository.php';
require_once __DIR__.'/../models/Category.php';

class CategoryRepository extends Repository
{
    public function getCategories() : array
    {
        $stmt = Database::get()->prepare('
            SELECT kategoria_id, kategoria
            FROM kategorie
            ORDER BY kategoria;
        ');
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = array();
        foreach ($array as $row) {
            $result[] = new \models\Category($row['kategoria_id'], $row['kategoria']);
        }
        return $result;
    }
}