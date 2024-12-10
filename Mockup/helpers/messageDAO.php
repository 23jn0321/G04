<?php
require_once 'DAO.php';

class message{
public int $MessageID;
public int $GroupID;
public int $SendUserID;
public string $MessageDetail;
public DateTime $SendTime;
public int $ChatDeleteFlag;
}

class messageDAO{
    public function messageInsert(int $GroupID,int $UserID,string $MessageDetail)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO ChatMessage(GroupID,SendUserID,MessageDetail)
                VALUES(:GroupID,:SendUserID,:MessageDetail)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":GroupID", $GroupID, PDO::PARAM_INT);
        $stmt->bindValue(":SendUserID", $UserID, PDO::PARAM_INT);
        $stmt->bindValue(":MessageDetail", $MessageDetail, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function MyMessage(int $GroupID, int $UserID)
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT MessageDetail FROM ChatMessage WHERE GroupID = :groupID AND SendUserID = :userID";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":GroupID", $GroupID, PDO::PARAM_INT);
        $stmt->bindValue(":UserID", $UserID, PDO::PARAM_INT);
    }
    public function YouMessage(int $GroupID, int $UserID)
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT MessageDetail FROM ChatMessage WHERE GroupID = :groupID AND SendUserID = :userID";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":GroupID", $GroupID, PDO::PARAM_INT);
        $stmt->bindValue(":UserID", $UserID, PDO::PARAM_INT);
    }
}
?>