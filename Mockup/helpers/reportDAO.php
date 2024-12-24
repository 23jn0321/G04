<?php

    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }



    require_once 'DAO.php';

    class Report
    {
        public int $ReportID;           //通報ID 
        public int $ReportUserID;       //通報者ID
        public int $TargetUserID;       //被通報者ID
        public int $GroupID;            //通報があったグループID
        public string $ReportCategory;  //通報内容
    }

    class ReportDAO
    {
        public function reportUser(int $ReportUserID,int $TargetUserID, int $GroupID, string $ReportCategory)
        {
            //DBに接続
            $dbh = DAO::get_db_connect();

            //Reportテーブルに通報情報をINSERTするSQL文
            $sql="INSERT INTO Report(ReportUserID,TargetUserID,GroupID,ReportCategory) 
	                VALUES(:reportUserID,:targetUserID,:groupID,:reportCategory)";

            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':reportUserID', $ReportUserID, PDO::PARAM_INT);
            $stmt->bindValue(':targetUserID', $TargetUserID, PDO::PARAM_INT);
            $stmt->bindValue(':groupID', $GroupID, PDO::PARAM_INT);
            $stmt->bindValue(':reportCategory', $ReportCategory, PDO::PARAM_STR);
            $stmt->execute();
        }

        function getUserChats()
        {
            $dbh = DAO::get_db_connect();
            $sql = "
                SELECT 
                    u.UserID, u.UserName,  g.GroupName, m.MessageDetail 
                FROM 
                    GakuseiUser u
                INNER JOIN 
                    GroupMember gm ON u.UserID = gm.UserID
                INNER JOIN 
                    ChatGroup g ON gm.GroupID = g.GroupID
                INNER JOIN 
                    ChatMessage m ON g.GroupID = m.GroupID
                ORDER BY 
                    u.UserID, g.GroupID, m.SendTime ASC";
                        $stmt = $dbh->prepare($sql);
                $userChats = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $userID = $row['UserID'];
                    if (!isset($userChats[$userID])) {
            $userChats[$userID] = [
                'userName' => $row['UserName'],
                'ChatGroup' => []
            ];
        }
        $groupName = $row['GroupName'];
        if (!isset($userChats[$userID]['ChatGroup'][$groupName])) {
            $userChats[$userID]['ChatGroup'][$groupName] = [];
        }
        $userChats[$userID]['ChatGroup'][$groupName][] = $row['MessageDetail'];
    }
    return $userChats;
}

        
    }

?>