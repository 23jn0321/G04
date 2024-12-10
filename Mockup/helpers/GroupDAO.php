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
