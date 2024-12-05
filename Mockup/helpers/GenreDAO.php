<?php 
    require_once 'DAO.php';

class Genre{
    public int $mainGenreID;        //メインジャンルID
    public string $mainGenreName;   //メインジャンルネーム
}
class SubGenre{
    public int $subGenreID;         //サブジャンルID
    public int $mainGenreID;        //メインジャンルID
    public string $subGenreName;    //サブジャンルネーム
    public bool $deleteFlag;         //サブジャンルのdeleteflag
}

class GenreDAO{
    public function get_Genre(){
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM MainGenre";

        $stmt = $dbh->query($sql);

        $genres = [];
        
        // 取得したデータをループして Genre オブジェクトに格納
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre = new Genre();
            $genre->mainGenreID = $row['mainGenreID'];
            $genre->mainGenreName = $row['mainGenreName'];
            $genres[] = $genre;
        }

        return $genres;
        
    }
}
class subGenreDAO{
    public function get_subGenre(){
        $dbh=DAO::get_db_connect();

        $sql="SELECT * FROM SubGenre";
        
        $stmt=$dbh->query($sql);

        $subGenres=[];

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $subGenre=new subGenre();
            $subGenre->subGenreID=$row['SubGeneID'];
            $subGenre->mainGenreID=$row['MainGenre'];
            $subGenre->subGenreName=$row['SubGenreName'];
            $subGenre->deleteFlag=$row['DeleteFlag'];
            $subGenres[]=$subGenre;
        }
        return $subGenres;
    }
}
?>