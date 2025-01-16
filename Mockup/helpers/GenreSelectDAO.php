<?php
require_once 'DAO.php';

class GenreSelectDAO
{
    // 特定のメインジャンルIDに関連するサブジャンルを取得
    public function getSubGenresByMainGenreID($mainGenreId)
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT SubGenreID, SubGenreName FROM SubGenre WHERE MainGenreID = :mainGenreID AND GenreDeleteFlag = 0";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':mainGenreID', $mainGenreId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 全メインジャンルを取得
    public function getAllMainGenres()
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT MainGenreID, MainGenreName FROM MainGenre";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGroupsByGenres($selectedGenres, $userId)
{
    $dbh = DAO::get_db_connect();

    // プレースホルダーを動的に作成
    $placeholders = implode(',', array_fill(0, count($selectedGenres), '?'));

    $sql = "
        SELECT 
            g.GroupID, 
            g.GroupName, 
            g.MaxMember, 
            ISNULL(COUNT(m.UserID), 0) AS MemberCount, 
            ma.MainGenreName, 
            su.SubGenreName, 
            MAX(cm.SendTime) AS LastUpdatedTime
        FROM ChatGroup g
        LEFT JOIN GroupMember m ON g.GroupID = m.GroupID
        LEFT JOIN MainGenre ma ON g.MainGenreID = ma.MainGenreID
        LEFT JOIN SubGenre su ON g.SubGenreID = su.SubGenreID
        LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID
        WHERE g.GroupDeleteFlag = 0
          AND g.SubGenreID IN ($placeholders)
          AND NOT EXISTS (
              SELECT 1
              FROM GroupMember gm
              WHERE gm.GroupID = g.GroupID
                AND gm.UserID = ?
          )
        GROUP BY 
            g.GroupID, 
            g.GroupName, 
            g.MaxMember, 
            ma.MainGenreName, 
            su.SubGenreName
        ORDER BY LastUpdatedTime DESC;
    ";

    $stmt = $dbh->prepare($sql);

    // パラメータをバインド
    $index = 1;
    foreach ($selectedGenres as $genre) {
        $stmt->bindValue($index++, $genre, PDO::PARAM_INT);
    }
    $stmt->bindValue($index, $userId, PDO::PARAM_INT);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // PHPでNULLのLastUpdatedTimeを "N/A" に置き換え
    foreach ($results as &$result) {
        if ($result['LastUpdatedTime'] === null) {
            $result['LastUpdatedTime'] = "N/A";
        }
    }

    return $results;
}



}
?>
