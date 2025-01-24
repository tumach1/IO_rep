<?php
require_once 'Repository.php';
require_once __DIR__.'/../models/City.php';

class CitiesRepository extends Repository
{
    public function getCities() : array
    {
        $stmt = Database::get()->prepare('
            SELECT miejscowosc_id, miejscowosc
            FROM miejscowosci
            ORDER BY miejscowosc;
        ');
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = array();
        foreach ($array as $row) {
            $result[] = new \models\City($row['miejscowosc_id'], $row['miejscowosc']);
        }
        return $result;
    }
}