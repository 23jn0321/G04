<?php
require_once 'DAO.php';

class GruopCeateDAO
{
    public int $GroupID;             //グループID 
    public string $GroupName;        //グループ名
    public int $MaxMember;           // 最大人数
    public string $Groupdetail;      //グループ詳細
    public string $GroupDeleteFlag;  //グループ削除フラグ
    public int $GroupAdminID;        //グループ管理者ID
    public int $SubGenreID;          //中ジャンルID
    public int $MainGenreID;         //大ジャンルID
}

class GruopCreateDAO
{
    //DBからグループ内容(グループ名、最大人数、中ジャンルID、大ジャンルID、グループ詳細)をSQLに送信するメソッド
    public function insert(string $GroupName,int $MaxMember,int $MainGenreID,int $SubGenreID, string $Groupdetail)
    {
        $dbh=DAO::get_db_connect();


            //ChatGruopテーブルにグループ内容()を追加する
            $sql="INSERT INTO ChatGruop(GroupName, MaxMember, MainGenreID, SubGenreID, Groupdetail)
            VALUES (:GroupName, :MaxMember, :MainGenreID, :SubGenreID, :Groupdetail)";

            $stmt=$dbh->prepare($sql);
            
            //SQLに変数の値を当てはめる
            $stmt->bindValue(':GroupName',$GroupName,PDO::PARAM_STR);
            $stmt->bindValue(':MaxMember',$MaxMember,PDO::PARAM_STR); 
            $stmt->bindValue(':MainGenreID',$SubGenreID,PDO::PARAM_STR);
            $stmt->bindValue(':SubGenreID',$SubGenreID,PDO::PARAM_INT);
            $stmt->bindValue(':Groupdetail',$SubGenreID,PDO::PARAM_STR);

            //実行
            $stmt->execute();
    }
}
?>