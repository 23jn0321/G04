<?php 
require_once 'DAO.php'; 
class ChatGroup
{
  public int $GroupID;
  public string $GroupName;
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
              $sql ="SELECT 
                      g.GroupID, 
                      g.GroupName, 
                      CONCAT(g.MaxMember, ' / ', 
                      (SELECT COUNT(DISTINCT gm_sub.UserID) 
                        FROM GroupMember gm_sub 
                          WHERE gm_sub.GroupID = g.GroupID)) AS MemberInfo, 
                            COALESCE(FORMAT(MAX(cm.SendTime), 'MM/dd'), '情報なし') AS LastUpdated, 
                              CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre 
                        FROM GroupMember gm
                          INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID
                          INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID 
                          INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
                          LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID 
                            WHERE gm.UserID = :UserID AND g.GroupDeleteFlag = 0 
                              GROUP BY 
                                  g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName
                              ORDER BY g.GroupName";

              //
              $stmt = $dbh->prepare($sql);

              //
              $stmt->bindValue(':UserID', $UserID, PDO::PARAM_INT);

              //実行
              $stmt->execute();
              
              $data = [];
              while($row = $stmt->fetchObject('ChatGroup')){
                $data[] = $row;
              }
              return $data;
             }
            }
?>
