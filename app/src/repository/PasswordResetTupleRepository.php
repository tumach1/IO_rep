<?php

use models\PasswordResetTuple;

require_once 'Repository.php';
require_once __DIR__.'/../models/PasswordResetTuple.php';

class PasswordResetTupleRepository extends Repository
{
    private const int tupleExpiryTime = 86400;
    public function getByReaderId($readerId){
        $stmt = Database::get()->prepare('
        SELECT * FROM resetowanie_hasel
        WHERE czytelnik_id = :id
        ');
        $stmt->bindParam(':id', $readerId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return new PasswordResetTuple($result['czytelnik_id'], $result['token'], $result['token_sesja'], $result['termin_wygasniecia']);
    }

    public function getByToken($token)
    {
        $stmt = Database::get()->prepare('
        SELECT * FROM resetowanie_hasel
        WHERE token = :token
        ');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result == null){
            return null;
        }
        return new PasswordResetTuple($result['czytelnik_id'], $result['token'], $result['token_sesja'], $result['termin_wygasniecia']);
    }

    public static function create($readerId)
    {
        $stmt = Database::get()->prepare('
            INSERT INTO resetowanie_hasel(czytelnik_id, token, token_sesja, termin_wygasniecia)
            VALUES (:readerId, :token, :sessionToken, :date)
            ON DUPLICATE KEY
            UPDATE token = :token, token_sesja = :sessionToken, termin_wygasniecia = :date
        ');
        $stmt->bindParam(':readerId', $readerId);
        try {
            $token = bin2hex(random_bytes(25));
        } catch (\Random\RandomException $e) {
        }
        $stmt->bindParam(':token', $token);
        try {
            $sessionToken = bin2hex(random_bytes(25));
        } catch (\Random\RandomException $e) {
        }
        $stmt->bindParam(':sessionToken', $sessionToken);
        $timestamp = time() + self::tupleExpiryTime;
        $stmt->bindParam(':date', $timestamp);
        $stmt->execute();
        setcookie('resetPasswordToken', $sessionToken, time() + 86400, '/', 'localhost', true);
    }
}