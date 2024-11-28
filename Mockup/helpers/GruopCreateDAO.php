<?php
require_once 'DAO.php';

class GruopCeate
{
    public int $GroupID;             //グループID 
    public string $GroupName;        //グループ名
    public int $MaxMember;           // 最大人数
    public string $Groupdetail;      //グループ詳細
    public string $GroupDeleteFlag;  //グループ削除フラグ
    public int $GroupAdminID;        //グループ管理者ID
    public int $SubGenreID;          //中ジャンルID
}

class GruopDetailDAO
{
    //DBからグループ内容(グループ名、最大人数、中ジャンルID、グループ詳細)をSQLに送信するメソッド
    public function insert(string $GroupName,int $MaxMember,int $SubGenreID,string $Groupdetail){
        $dbh=DAO::get_db_connect();


            //ChatGruopテーブルにグループ内容()を登録する
            $sql="INSERT INTO ChatGruop(GroupName, MaxMember, SubGenreID, Groupdetail)
            VALUES (:GroupName, :MaxMember, :SubGenreID, :Groupdetail)";

            $stmt=$dbh->prepare($sql);

            $stmt->bindValue(':GroupName',$GroupName,PDO::PARAM_STR);
            $stmt->bindValue(':MaxMember',$MaxMember,PDO::PARAM_STR); 
            $stmt->bindValue(':SubGenreID',$SubGenreID,PDO::PARAM_INT);
            $stmt->bindValue(':Groupdetail',$SubGenreID,PDO::PARAM_STR);
            $stmt->execute();
    }
}
?>