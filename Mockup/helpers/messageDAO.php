<?php
require_once 'DAO.php';

class messageDAO {
    public function messageInsert(int $GroupID, int $UserID, string $MessageDetail) {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO ChatMessage (GroupID, SendUserID, MessageDetail) 
                VALUES (:GroupID, :SendUserID, :MessageDetail)";
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":GroupID", $GroupID, PDO::PARAM_INT);
        $stmt->bindValue(":SendUserID", $UserID, PDO::PARAM_INT);
        $stmt->bindValue(":MessageDetail", $MessageDetail, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function getMessages(int $GroupID, string $lastFetchTime = null) {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT cm.MessageDetail, cm.SendTime, cm.SendUserID, u.UserName 
                FROM ChatMessage cm
                JOIN GakuseiUser u ON cm.SendUserID = u.UserID
                WHERE cm.GroupID = :GroupID";

        if ($lastFetchTime) {
            $sql .= " AND cm.SendTime > :lastFetchTime";
        }

        $sql .= " ORDER BY cm.SendTime ASC";
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":GroupID", $GroupID, PDO::PARAM_INT);
        if ($lastFetchTime) {
            $stmt->bindValue(":lastFetchTime", $lastFetchTime, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
