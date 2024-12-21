<?php
require_once 'DAO.php';

class Genre
{
    public int $mainGenreID;        //メインジャンルID
    public string $mainGenreName;   //メインジャンルネーム
}
class SubGenre
{
    public int $subGenreID;         //サブジャンルID
    public int $mainGenreID;        //メインジャンルID
    public string $subGenreName;    //サブジャンルネーム
    public bool $deleteFlag;         //サブジャンルのdeleteflag
}

class GenreDAO
{
    public function get_Genre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM MainGenre";

        $stmt = $dbh->query($sql);

        $genres = [];

        // 取得したデータをループして Genre オブジェクトに格納
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre = new Genre();
            $genre->mainGenreID = $row['MainGenreID'];
            $genre->mainGenreName = $row['MainGenreName'];
            $genres[] = $genre;
        }

        return $genres;
    }
    public function insert_Genre(string $mainGenreName, string $subGenreName)
    {

        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO MainGenre(mainGenreName) VALUES (:mainGenreName)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':mainGenreName', $mainGenreName, PDO::PARAM_STR);

        $stmt->execute();

        $mainGenreId = $dbh->lastInsertId();

        $sql = "INSERT INTO subgenre (subGenreName, mainGenreID) VALUES (:subGenreName, :mainGenreID)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':subGenreName', $subGenreName);
        $stmt->bindValue(':mainGenreID', $mainGenreId);

        $stmt->execute();
    }
}
class subGenreDAO
{
    public function get_subGenre()
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM SubGenre";

        $stmt = $dbh->query($sql);

        $subGenres = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $subGenre = new subGenre();
            $subGenre->subGenreID = $row['SubGenreID'];
            $subGenre->mainGenreID = $row['MainGenreID'];
            $subGenre->subGenreName = $row['SubGenreName'];
            $subGenre->deleteFlag = $row['GenreDeleteFlag'];
            $subGenres[] = $subGenre;
        }
        return $subGenres;
    }
    public function insert(int $mainGenreID, string $subGenreName)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO SubGenre (MainGenreID,SubGenreName) VALUES (:mainGenreID,:subGenreName)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':mainGenreID', $mainGenreID, PDO::PARAM_INT);
        $stmt->bindValue(':subGenreName', $subGenreName, PDO::PARAM_STR);

        $stmt->execute();
    }
}
