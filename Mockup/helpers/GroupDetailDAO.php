<?php
require_once 'DAO.php';

class ChatGroup
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
    public function get_GroupDetail1(int $GroupID)
    {
        //DBに接続
        $dbh = DAO::get_db_connect();

        //グループの内容(グループの名前、人数、ジャンル)を取得するSQL
        $sql="SELECT		
                        g.GroupID, 
                        g.GroupName, 
                        CONCAT(
                            (SELECT COUNT(DISTINCT gm_sub.UserID) 
                            FROM GroupMember gm_sub 
                            WHERE gm_sub.GroupID = g.GroupID), 
                            ' / ', 
                            g.MaxMember
                        ) AS MemberInfo, 
                        COALESCE(FORMAT(MAX(cm.SendTime), 'MM/dd'), '情報なし') AS LastUpdated, 
                        CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre ,
						g.GroupDetail
                    FROM GroupMember gm
                        INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID
                        INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID 
                        INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
                        LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID 
                    WHERE g.GroupID = :groupid AND g.GroupDeleteFlag = 0 
                    GROUP BY 
                        g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName,g.GroupDetail
                    ORDER BY g.GroupName";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':groupid', $GroupID, PDO::PARAM_INT);

        //実行
        $stmt->execute();

        $chatgroup = $stmt -> fetchAll();
        return $chatgroup;


    }

    //参加者の情報を
    public function get_groupDetail2()
    {
        $dbh = DAO::get_db_connect();
    }

    public function insert()
    {
        $dbh = DAO::get_db_connect();

        $sql="INSERT INTO GroupMember
                VALUES(:UserID,:GroupID)";

        
    }

}
