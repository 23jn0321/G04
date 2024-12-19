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

        
    }

?>