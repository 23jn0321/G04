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

    public function getMessagesByGroup($groupID) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT m.MessageID, m.GroupID, m.SendUserID, m.MessageDetail, m.SendTime, 
                         u.UserName AS SendUserName
                  FROM ChatMessage m
                  JOIN GakuseiUser u ON m.SendUserID = u.UserID
                  WHERE m.GroupID = :groupID
                  ORDER BY m.SendTime ASC";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':groupID', $groupID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // 結果をオブジェクト形式で返す
    }
    
}
?>
