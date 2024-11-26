
<?php
require_once 'DAO.php';

class user
{
    public string $GakusekiNo;          //会員ID 
    public int $UserNo; 
    public string  $Comment;    
    public string $UserName;    
    public int $ToketuNo;
    public string $ToketuTxt;
}

class userDAO
{
    //DBからメールアドレスとパスワードが一致する会員データを取得する
    public function get_user(string $gakusekiNo, string $data)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得する
        $sql = "SELECT * FROM GakuseiUser WHERE GakusekiNo = :gakusekiNo";
        
        $Data = $_SESSION['student'];
        $GakusekiNo = array_column($Data, 'GakusekiNo');

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':gakusekiNo',$gakusekiNo,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //1件分のデータをMemberクラスのオブジェクトとして取得する
        $user = $stmt->fetchObject('user');
        print_r($user);
    }
    
    public function update(string $nickName, string $comment)
    {
        $dbh = DAO::get_db_connect();

        $sql = "UPDATE INTO GakuseiUser(ProfileComment,Username) 
                VALUES(:profilecomment,:username)";

        $stmt = $dbh->prepare($sql);


        $stmt->bindValue(":profilecomment", $user->profilecomment, PDO::PARAM_STR);
        $stmt->bindValue(":username", $user->username, PDO::PARAM_STR);

        $stmt->execute();
    }
}
