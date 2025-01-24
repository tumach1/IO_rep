<?php
require_once __DIR__.'/../../src/repository/BookRepository.php';

use models\Book;

require_once 'Repository.php';
require_once __DIR__.'/../models/Book.php';


class OrderRepository extends \Repository{
    public function addOrder($readerId, $bookIds = []) {
        $stmt = $this->database->prepare('
                INSERT INTO zamowienie (czytelnik_id)
                VALUES (:readerId)
            ');
            $stmt->bindParam(':readerId', $readerId);
            $stmt->execute();
            $orderId = $this->database->lastInsertId();
            
            foreach ($bookIds as $bookId) {
                $stmt = $this->database->prepare('
                    INSERT INTO zamowienie_ksiazki (zamowienie_id, ksiazka_id)
                    VALUES (:orderId, :bookId)
                ');
                $stmt->bindParam(':orderId', $orderId);
                $stmt->bindParam(':bookId', $bookId);
                $stmt->execute();
            }
    }

    public function getOrders($readerId) {

        $stmt = $this->database->prepare('
            SELECT zamowienie_id as id,
            status_id as statusId
            FROM zamowienie
            WHERE czytelnik_id = :readerId
        ');
        $stmt->bindParam(':readerId', $readerId);
        $stmt->execute();
        $zamowienie = $stmt->fetchAll(PDO::FETCH_ASSOC);

        for($i = 0; $i < count($zamowienie); $i++){
            $stmt = $this->database->prepare("
                SELECT zk.zamowienie_id as order_id,
				k.tytul as name,
				CONCAT(a.imie, ' ',a.nazwisko) as author,
				k.img_path as img_path
				from zamowienie_ksiazki zk
				JOIN ksiazki k on zk.ksiazka_id = k.ksiazka_id
				LEFT JOIN ksiazki_autorzy ka on k.ksiazka_id = ka.ksiazka_id
				JOIN autorzy a on ka.autor_id = a.autor_id
                WHERE zk.zamowienie_id = :orderId
            ");
            $stmt->bindParam(':orderId', $zamowienie[$i]['id']);
            $stmt->execute();
            $zamowienie[$i]['books'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $zamowienie;
    }

    public function updateOrderStatus($orderId, $statusId) {
        $stmt = $this->database->prepare('
            UPDATE zamowienie
            SET status_id = :statusId
            WHERE zamowienie_id = :orderId
        ');
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':statusId', $statusId);
        $stmt->execute();
    }

    public function deleteOrder($orderId) {
        $stmt = $this->database->prepare('
            DELETE FROM zamowienie
            WHERE zamowienie_id = :orderId
        ');
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
    }

    public function deleteBookFromOrder($orderId, $bookId) {
        $stmt = $this->database->prepare('
            DELETE FROM zamowienie_ksiazki
            WHERE zamowienie_id = :orderId AND ksiazka_id = :bookId
        ');
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':bookId', $bookId);
        $stmt->execute();
    }

    public function getOrdersData($readerId) {
        $stmt = $this->database->prepare('
            SELECT
                zamowienie_id as id,
                ksiazka_id as bookId
            FROM zamowienie
                     JOIN zamowienie_ksiazki
                          ON zamowienie.zamowienie_id = zamowienie_ksiazka.zamowienie_id
            WHERE czytelnik_id = :readerId
        ');
        $stmt->bindParam(':readerId', $readerId);
        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ordersData = [];
        foreach ($orders as $order) {
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
                 
                WHERE ksiazki.ksiazka_id = :bookId
                GROUP BY ksiazki.ksiazka_id
            ');
            $stmt->bindParam(':bookId', $order['bookId']);
            $stmt->execute();

            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            $book['authors'] = json_decode($book['authors']);
            $book['genres']
            = json_decode($book['genres']);
            $ordersData[] = $book;
        }
        return json_encode($ordersData);
    
    }


    public function getOrdersByStatus($status_id) {
        
        try{
            $this->database->beginTransaction();
            
        $stmt = $this->database->prepare('
            SELECT zamowienie_id as id,
                    czytelnik_id as user_id
            FROM zamowienie
            WHERE status_id = :status_id
        ');
        $stmt->bindParam(':status_id', $status_id);
        $stmt->execute();
        $zamowienie = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->database->prepare('
            Insert into test(error)
            VALUES(:status_id)
        ');
        $stmt->bindParam(':status_id', $zamowienie[0]['id']);
        $stmt->execute();
        

        for($i = 0; $i < count($zamowienie); $i++){
            $stmt = $this->database->prepare("
                SELECT zk.zamowienie_id as order_id,
                k.tytul as name,
                CONCAT(a.imie, ' ',a.nazwisko) as author,
                k.img_path as img_path
                from zamowienie_ksiazki zk
                JOIN ksiazki k on zk.ksiazka_id = k.ksiazka_id
                LEFT JOIN ksiazki_autorzy ka on k.ksiazka_id = ka.ksiazka_id
                JOIN autorzy a on ka.autor_id = a.autor_id
                WHERE zk.zamowienie_id = :orderId
            ");
            $stmt->bindParam(':orderId', $zamowienie[$i]['id']);
            $stmt->execute();
            $zamowienie[$i]['books'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        }
        $this->database->commit();
        return $zamowienie;
    } catch (Exception $e) {
        $this->database->rollBack();
        header('Content-Type: application/json');
        echo json_encode(array("success" => false, "message" => "Error while adding order: " . $e->getMessage()));
        throw new Exception("Error while adding order: " . $e->getMessage());
    }

        
    }
}
?>