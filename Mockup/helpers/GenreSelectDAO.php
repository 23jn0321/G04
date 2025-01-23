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

    $sql = "SELECT					
                      g.GroupID, 
                      g.GroupName, 
                      CONCAT(
                          (SELECT COUNT(DISTINCT gm_sub.UserID) 
                          FROM GroupMember gm_sub 
                          WHERE gm_sub.GroupID = g.GroupID), 
                          ' / ', 
                          g.MaxMember
                      ) AS MemberInfo, 
                      MAX(cm.SendTime) AS  LastUpdatedTime, 
                      CONCAT(mg.MainGenreName, ' / ', sg.SubGenreName) AS Genre 
                  FROM GroupMember gm
                      INNER JOIN ChatGroup g ON gm.GroupID = g.GroupID
                      INNER JOIN MainGenre mg ON g.MainGenreID = mg.MainGenreID 
                      INNER JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
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
    g.GroupID, g.GroupName, g.MaxMember, mg.MainGenreName, sg.SubGenreName
HAVING 
    (SELECT COUNT(DISTINCT gm_sub.UserID) 
     FROM GroupMember gm_sub 
     WHERE gm_sub.GroupID = g.GroupID) < g.MaxMember -- メンバー数が最大人数未満
                      
                  ORDER BY 
                      CASE 
                          WHEN MAX(cm.SendTime) IS NULL THEN 0 
                          ELSE 1
                      END ASC,
                      MAX(cm.SendTime) DESC;
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
            $result['LastUpdatedTime'] = "情報なし";
        }else {
            $datetime = new DateTime($result['LastUpdatedTime']);
            $result['LastUpdatedTime'] = $datetime->format('m/d');
        }
    }

    return $results;
}

public function get_Select_Genres($selectedGenres)
{
    $dbh = DAO::get_db_connect();

    // プレースホルダーを動的に作成
    $placeholders = implode(',', array_fill(0, count($selectedGenres), '?'));

    $sql = "SELECT SubGenreName FROM SubGenre WHERE GenreDeleteFlag = 0 AND SubGenreID IN ($placeholders);";

    $stmt = $dbh->prepare($sql);

    // パラメータをバインド
    $index = 1;
    foreach ($selectedGenres as $genre) {
        $stmt->bindValue($index++, $genre, PDO::PARAM_INT);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

}
?>
