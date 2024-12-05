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
            $sql="INSERT INTO ChatGruop(GroupName, MaxMember, MainGenreID, SubGenreID, Groupdetail)
            VALUES (:GroupName, :MaxMember, :MainGenreID, :SubGenreID, :Groupdetail)";

            $stmt=$dbh->prepare($sql);

            $stmt->bindValue(':GroupName',$GroupName,PDO::PARAM_STR);
            $stmt->bindValue(':MaxMember',$MaxMember,PDO::PARAM_STR); 
            $stmt->bindValue(':MainGenreID',$SubGenreID,PDO::PARAM_STR);
            $stmt->bindValue(':SubGenreID',$SubGenreID,PDO::PARAM_INT);
            $stmt->bindValue(':Groupdetail',$SubGenreID,PDO::PARAM_STR);
            $stmt->execute();
    }
    public function groupSelect(){
        $dbh = DAO::get_db_connect();

        $sql="SELECT * FROM MainGenre";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $mainGenre = $stmt->fetchAll();

        $genres = [];

        foreach($mainGenre as $genre){
            $genreID = $genre['MainGenreID'];
            $genreName = $genre['MainGenreName'];

            $sql2 = "SELECT SubGenreName FROM SubGenre WHERE MainGenre = :mainGenre";

            $stmt2 = $dbh->prepare($sql2);

            $stmt2->bindValue(':mainGenre', $genreID, PDO::PARAM_INT);
            $stmt2->execute();

            $subGenre = $stmt2->fetchAll(PDO::FETCH_COLUMN);

            $genres[] = [$genreName,$subGenre];
        }


        return $genres;
        
    }


}
?>