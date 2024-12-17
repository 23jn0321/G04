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

    public function getGroupsByGenres($genreIDs) {
        // プレースホルダーをジャンル数に合わせて生成
        $dbh = DAO::get_db_connect();
        $placeholders = implode(',', array_fill(0, count($genreIDs), '?'));
        $sql = "
        SELECT 
    g.GroupID, 
    g.GroupName, 
    COUNT(DISTINCT gm.UserID) AS MemberCount,
	g.MaxMember AS MaxMember,
    FORMAT(MAX(cm.SendTime), 'MM/dd') AS LastChatTime ,
    mg.MainGenreName AS MainGenre , 
    sg.SubGenreName AS SubGenre
FROM ChatGroup g
LEFT JOIN GroupMember gm ON g.GroupID = gm.GroupID
LEFT JOIN ChatMessage cm ON g.GroupID = cm.GroupID
LEFT JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
LEFT JOIN MainGenre mg ON sg.MainGenreID = mg.MainGenreID
WHERE sg.SubGenreID IN ($placeholders)
GROUP BY 
    g.GroupID, g.GroupName, mg.MainGenreName, sg.SubGenreName,g.MaxMember;
";
    
        $stmt = $dbh->prepare($sql);
        $stmt->execute($genreIDs);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}    
?>