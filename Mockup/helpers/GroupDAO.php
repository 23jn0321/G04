<?php 
require_once 'DAO.php'; 

class GruopDAO
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
                          WHERE gm_sub.GroupID = g.GroupID)) AS MemberInfo, -- サブクエリでユニークな参加人数をカウント
                            COALESCE(FORMAT(MAX(cm.SendTime), 'MM/dd'), '情報なし') AS LastUpdated, -- 日付をmm/dd形式でフォーマット
                              CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre -- メインジャンルとサブジャンルを/で結合
                        FROM GroupMember gm
                          INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID -- グループ情報を結合
                          INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID -- メインジャンルを結合
                          INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID -- サブジャンルを結合
                          LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID -- 最新メッセージを取得
                            WHERE gm.UserID = :UserID AND g.GroupDeleteFlag = 0 -- ユーザーIDと削除フラグでフィルタリング
                              GROUP BY 
                                  g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName
                              ORDER BY g.GroupName";

              //
              $stmt = $dbh->prepare($sql);

              //
              $stmt->bindValue(':UserID', $UserID, PDO::PARAM_STR);

              //実行
              $stmt->execute();
             }
            }
?>
