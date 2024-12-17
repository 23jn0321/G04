<?php
require_once 'DAO.php';


class joinMember
{
    public int $GroupID;             
    public string $UserName;        
    public string $GakkaName;     
}

class GroupDetailDAO
{
    //
    public function get_GroupDetail1(int $GroupID)
    {
        //DBに接続
        $dbh = DAO::get_db_connect();

        //グループの内容(グループ名、グループ人数)を取得するSQL
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
                    WHERE g.GroupID = :GroupID AND g.GroupDeleteFlag = 0 
                    GROUP BY 
                        g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName,g.GroupDetail
                    ORDER BY g.GroupName";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':GroupID', $GroupID, PDO::PARAM_INT);

        //実行
        $stmt->execute();

        $chatgroup = $stmt -> fetchAll();
        return $chatgroup;


    }

    //参加者の情報を取得するメソッド
    public function get_groupDetail2(int $GroupID)
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT 
	                GroupID,
                    UserName,
                    G.GakkaName
                FROM 
                    GroupMember GM
                JOIN 
                    GakuseiUser U ON GM.UserID = U.UserID
                JOIN 
                    Gakka G ON SUBSTRING(U.GakusekiNo, 3, 2) = G.GakkaCode
                WHERE GroupID=:GroupID";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':GroupID',$GroupID,PDO::PARAM_INT);

        $stmt->execute();

        $data = [];

        while ($row = $stmt->fetchObject('joinMember')) {
            $data[] = $row;
        }

        return $data;



    }



    public function insert(int $userid, int $Groupid)
    {
        $dbh = DAO::get_db_connect();

        $sql="INSERT INTO GroupMember
              VALUES(:UserID,:GroupID)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':UserID', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':GroupID', $Groupid, PDO::PARAM_INT);

        $stmt ->execute();
    }

}
