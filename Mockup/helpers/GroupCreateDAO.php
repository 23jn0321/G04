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
    public int $MainGenreID;         //大ジャンルID
}

class GruopDetailDAO
{
    //DBからグループ内容(グループ名、最大人数、中ジャンル名、大ジャンル名、グループ詳細)をSQLに送信するメソッド
    public function insert(string $GroupName,int $MaxMember,string $mainGenreName,string $SubGenreName, string $Groupdetail, int $userID)
    {

        $dbh = DAO::get_db_connect();

        //大ジャンルの型変換
        $sql1="SELECT MainGenreID
	           FROM MainGenre
		       WHERE MainGenreName = :mainGenreName";

        $stmt = $dbh->prepare($sql1);

        $stmt->bindValue(":mainGenreName",$mainGenreName,PDO::PARAM_STR);

        //実行
        $stmt->execute();

        $mainGenreID = $stmt->fetchAll();
        var_dump($mainGenreID[0]["MainGenreID"]);
        //中ジャンルの型変換
        $sql2="SELECT SubGenreID
	           FROM SubGenre
		       WHERE SubGenreName = :SubGenreName";

        $stmt2 = $dbh->prepare($sql2);
        var_dump($SubGenreName);

        $stmt2->bindValue(":SubGenreName",$SubGenreName,PDO::PARAM_STR);

        //実行
        $stmt2->execute();

        $subGenreID = $stmt2->fetchAll();
        var_dump($subGenreID[0]["SubGenreID"]);



            //ChatGruopテーブルにグループ内容()を追加する
            $sql3="INSERT INTO ChatGroup(GroupName, MaxMember, Groupdetail, GroupDeleteFlag,MainGenreID, SubGenreID,GroupAdminID)
                                 VALUES (:GroupName, :MaxMember, :Groupdetail, 0, :MainGenreID, :SubGenreID, :GroupAdminID)";

            $stmt3=$dbh->prepare($sql3);
            
            //SQLに変数の値を当てはめる
            $stmt3->bindValue(':GroupName',$GroupName,PDO::PARAM_STR);
            $stmt3->bindValue(':MaxMember',$MaxMember,PDO::PARAM_INT); 
            $stmt3->bindValue(':MainGenreID',$mainGenreID[0]["MainGenreID"],PDO::PARAM_INT);
            $stmt3->bindValue(':SubGenreID',$subGenreID[0]["SubGenreID"],PDO::PARAM_INT);
            $stmt3->bindValue(':Groupdetail',$Groupdetail,PDO::PARAM_STR);
            $stmt3->bindValue(':GroupAdminID',$userID,PDO::PARAM_INT);

            //実行
            $stmt3->execute();

             // 挿入したグループのGroupIDを取得
             $groupID = $dbh->lastInsertId();

             // GroupMemberテーブルに作成者を追加
             $sql4 = "INSERT INTO GroupMember(UserID, GroupID) VALUES (:UserID, :GroupID)";
             $stmt4 = $dbh->prepare($sql4);
             $stmt4->bindValue(':UserID', $userID, PDO::PARAM_INT);
             $stmt4->bindValue(':GroupID', $groupID, PDO::PARAM_INT);
             $stmt4->execute();
    }

    //大ジャンルと中ジャンルの同期
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

            $sql2 = "SELECT SubGenreName FROM SubGenre WHERE MainGenreID = :mainGenreID";

            $stmt2 = $dbh->prepare($sql2);

            $stmt2->bindValue(':mainGenreID', $genreID, PDO::PARAM_INT);
            $stmt2->execute();

            $subGenre = $stmt2->fetchAll(PDO::FETCH_COLUMN);

            $genres[] = [$genreName,$subGenre];
        }


        return $genres;
        
    }
}
?>