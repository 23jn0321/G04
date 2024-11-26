<?php 
    require_once 'DAO.php';

class Genre{
    public int $mainGenreID;
    public string $mainGenreName;
}
class SubGenre{
    public int $subGenreID;
    public int $mainGenreID;
    public string $subGenreName;
    public int $deleteFlag;
}

class GenreDAO{
    public function get_Genre(){
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM MainGenre";

        $stmt = $dbh->query($sql);

        
    }
}
?>