<?php 
require_once 'DAO.php'; 

class GruopDetailDAO
{
          //DBからグループ内容(グループID、グループ名、グループ人数、最終更新日、大ジャンル、中ジャンル、学籍番号)を取得するメソッド
          public function getGroup(int $GroupID, string $GroupName, string $MemberInfo, string $LastUpdated ,string $Genre ,string $gakusekiNo)
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

              $stmt = $dbh->prepare($sql);

              $stmt->bindValue(':GroupID', $GroupID, PDO::PARAM_INT);
              $stmt->bindValue(':GroupName', $GroupName, PDO::PARAM_STR);
              $stmt->bindValue(':MemberInfo', $MemberInfo, PDO::PARAM_STR);
              $stmt->bindValue(':LastUpdated', $LastUpdated, PDO::PARAM_STR);
              $stmt->bindValue(':Genre', $Genre, PDO::PARAM_STR);
              $stmt->bindValue(':UserID', $UserID, PDO::PARAM_STR);
              $stmt->bindValue(':gakusekiNo',$gakusekiNo,PDO::PARAM_STR);

              

              //実行
              $stmt->execute();


              print_r($MemberInfo);


             }
            }
?>
<?php
require_once 'DAO.php'; // DAOの基本設定をインクルード

class GroupDAO {
    // ユーザーが所属するグループを取得するメソッド
    public static function getUserGroups($userId) {
        // DB接続オブジェクトを取得
        $dbh = DAO::get_db_connect();

        // クエリ: 所属グループの情報を取得
        $sql = "
            SELECT g.GroupID, g.GroupName, g.MaxMember, COUNT(gm.UserID) AS CurrentMemberCount, ISNULL(MAX(cm.SendTime), '情報なし') AS LastUpdated, mg.MainGenreName, sg.SubGenreName
              FROM GroupMember gm
                INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID -- グループ情報を結合
                  INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID -- メインジャンルを結合
                    INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID -- サブジャンルを結合
                      LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID -- 最新メッセージを取得
                        WHERE gm.UserID = :userId AND g.GroupDeleteFlag = 0 -- ユーザーIDと削除フラグでフィルタリング
                          GROUP BY 
                            g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName
                              ORDER BY g.GroupName"; // グループ名の昇順でソート

        // パラメータ化クエリを準備し実行
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); // ユーザーIDをバインド
        $stmt->execute();

        // 結果を取得
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
