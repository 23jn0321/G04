<?php
require_once 'DAO.php';
class ChatGroup
{
  public int $GroupID;
  public string $GroupName;
  public string $GroupAdminID;
  public string $MemberInfo;
  public string $LastUpdated;
  public string $Genre;
}

class GroupDAO
{
  //DBからグループ内容(グループID、グループ名、グループ人数、最終更新日、大ジャンル、中ジャンル、学籍番号)を取得するメソッド
  public function getGroup($UserID)
  {
    //DBに接続
    $dbh = DAO::get_db_connect();
		
              //DBからグループ内容を取得くするSQL
              $sql = "SELECT					
                      g.GroupID, 
                      g.GroupName, 
                      g.GroupAdminID, -- GroupAdminIDを追加
                      CONCAT(
                          (SELECT COUNT(DISTINCT gm_sub.UserID) 
                          FROM GroupMember gm_sub 
                          WHERE gm_sub.GroupID = g.GroupID), 
                          ' / ', 
                          g.MaxMember
                      ) AS MemberInfo, 
                      COALESCE(FORMAT(MAX(cm.SendTime), 'MM/dd'), '情報なし') AS LastUpdated, 
                      CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre 
                  FROM GroupMember gm
                      INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID
                      INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID 
                      INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
                      LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID 
                  WHERE gm.UserID = :UserID AND g.GroupDeleteFlag = 0 
                  GROUP BY 
                      g.GroupID, g.GroupName, g.GroupAdminID, g.MaxMember, mg.MainGenreName, sg.SubGenreName -- GroupAdminIDを追加
                  ORDER BY 
                      CASE 
                          WHEN MAX(cm.SendTime) IS NULL THEN 0 
                          ELSE 1
                      END ASC,
                      MAX(cm.SendTime) DESC";

              
             
    //
    $stmt = $dbh->prepare($sql);

    //
    $stmt->bindValue(':UserID', $UserID, PDO::PARAM_INT);

    //実行
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetchObject('ChatGroup')) {
      $data[] = $row;
    }
    return $data;
  }

  public function get_My_Group(int $GroupID)
  {
    $dbh = DAO::get_db_connect();

    $sql = "SELECT 
                    g.GroupName, 
                    g.MaxMember, 
                    g.GroupDetail, 
                    mg.MainGenreName, 
                    sg.SubGenreName
                FROM 
                    ChatGroup g
                INNER JOIN 
                    MainGenre mg ON g.MainGenreID = mg.MainGenreID
                INNER JOIN 
                    SubGenre sg ON g.SubGenreID = sg.SubGenreID
                WHERE 
                    g.GroupID = :groupID
                    AND g.GroupDeleteFlag = 0";
                    

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(':groupID', $GroupID, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  public function groupInfoUpdate(int $groupID,string $newGroupName,string $newGroupDetail)
  {
      $dbh = DAO::get_db_connect();
      $sql = "UPDATE ChatGroup SET GroupName = :newGroupName, GroupDetail = :newGroupDetail WHERE GroupID = :groupID";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":newGroupName", $newGroupName, PDO::PARAM_STR);
      $stmt->bindValue(":newGroupDetail", $newGroupDetail, PDO::PARAM_STR);
      $stmt->bindValue("groupID", $groupID, PDO::PARAM_INT);

      $stmt->execute();
  }
  public function deleteGroup(int $groupID)
  {
    $dbh = DAO::get_db_connect();
    $sql = "UPDATE ChatGroup SET GroupDeleteFlag = 1 WHERE GroupID = :groupID";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":groupID", $groupID, PDO::PARAM_INT);
    $stmt->execute();
  }





}
class NewGroupDAO
{

  public function getNewGroup()
  {
    //DBに接続
    $dbh = DAO::get_db_connect();

    //DBからグループ内容を取得くするSQL
    $sql = "SELECT TOP 8
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
                    CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre 
                FROM GroupMember gm
                    INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID
                    INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID 
                    INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
                    LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID 
                WHERE g.GroupDeleteFlag = 0
                GROUP BY 
                    g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName
                HAVING 
                    (SELECT COUNT(DISTINCT gm_sub.UserID) 
                     FROM GroupMember gm_sub 
                     WHERE gm_sub.GroupID = g.GroupID) < g.MaxMember -- メンバー数が最大人数未満
                ORDER BY g.GroupID DESC
                ";

    //
    $stmt = $dbh->prepare($sql);
    //実行
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetchObject('ChatGroup')) {
      $data[] = $row;
    }
    return $data;
  }
}
