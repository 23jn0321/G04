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

    class GakuseiUser
    {
        public string $UserName;  //ニックネーム
    }

    class aaa
    {
        public function insert(int $ReportID, int $ReportUserID,int $TargetUserID, int $GroupID, string $ReportCategory, string $UserName)
        {
            $dbh = DAO::get_db_connect();

            $sql="INSERT INTO Report(ReportID, ReportUserID, TargetUserID, GroupID,ReportCategory,UserName)
                  VALUES (:ReportID, :ReportUserID, :TargetUserID, GroupID, :ReportCategory, :UserName)";
        }

        
    }

?>