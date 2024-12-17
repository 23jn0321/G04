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
            SELECT g.GroupID, g.GroupName, COUNT(m.UserID) AS MemberCount, sg.SubGenreName AS Genre
            FROM ChatGroup g
            JOIN SubGenre sg ON g.SubGenreID = sg.SubGenreID
            LEFT JOIN GroupMember m ON g.GroupID = m.GroupID
            WHERE g.SubGenreID IN ($placeholders) AND g.GroupDeleteFlag = 0
            GROUP BY g.GroupID, g.GroupName, sg.SubGenreName
        ";
    
        $stmt = $dbh->prepare($sql);
        $stmt->execute($genreIDs);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}    
?>