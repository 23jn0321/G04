<?php
class GenreSelectDAO
{
    public function get_Game_SubGenre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT SubGenreID, SubGenreName FROM SubGenre WHERE MainGenreID = 1";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

  
        $subGenre = $stmt->fetchAll();

        return $subGenre;


    }
    public function get_Music_SubGenre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT SubGenreID, SubGenreName FROM SubGenre WHERE MainGenreID = 2";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $subGenre = $stmt->fetchAll();

        return $subGenre;
    }
    public function get_Sports_SubGenre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT SubGenreID, SubGenreName FROM SubGenre WHERE MainGenreID = 3";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $subGenre = $stmt->fetchAll();

        return $subGenre;
    }
    public function get_Study_SubGenre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT SubGenreID, SubGenreName FROM SubGenre WHERE MainGenreID = 4";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $subGenre = $stmt->fetchAll();

        return $subGenre;
    }

    public function getGroupsByGenres($genreIDs, $userID) {
        // プレースホルダーをジャンル数に合わせて生成
        $dbh = DAO::get_db_connect();
        $placeholders = implode(',', array_fill(0, count($genreIDs), '?'));
        $sql = "
        SELECT 
            g.GroupID, 
            g.GroupName, 
            COUNT(DISTINCT gm.UserID) AS MemberCount,
            g.MaxMember AS MaxMember,
            COALESCE(FORMAT(MAX(cm.SendTime), 'MM/dd'), '情報なし') AS LastChatTime,
            mg.MainGenreName AS MainGenre, 
            sg.SubGenreName AS SubGenre
        FROM ChatGroup g
        LEFT JOIN GroupMember gm ON g.GroupID = gm.GroupID
        LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID
        LEFT JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
        LEFT JOIN MainGenre mg ON sg.MainGenreID = mg.MainGenreID
        WHERE sg.SubGenreID IN ($placeholders)
          AND g.GroupID NOT IN (
              SELECT GroupID
              FROM GroupMember
              WHERE UserID = ?
          )
        GROUP BY 
            g.GroupID, g.GroupName, mg.MainGenreName, sg.SubGenreName, g.MaxMember
        HAVING 
            COUNT(DISTINCT gm.UserID) < g.MaxMember
        ORDER BY 
            CASE 
                WHEN MAX(cm.SendTime) IS NULL THEN 0 
                ELSE 1
            END ASC,
            MAX(cm.SendTime) DESC";
    
        // パラメータを結合（ジャンルIDとユーザーID）
        $params = array_merge($genreIDs, [$userID]);
    
        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getAllSubGenres()
    {
        $dbh = DAO::get_db_connect();

        $sql = "
            SELECT 
                sg.SubGenreID, 
                sg.SubGenreName, 
                sg.MainGenreID, 
                mg.MainGenreName
            FROM SubGenre sg
            INNER JOIN MainGenre mg ON sg.MainGenreID = mg.MainGenreID
            ORDER BY sg.MainGenreID, sg.SubGenreID";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $subGenres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $subGenres;
    } 
}    
?>