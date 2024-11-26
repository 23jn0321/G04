
<?php
require_once 'DAO.php';

class user
{
    public string $GakusekiNo;          //会員ID 
    public int $UserNo; 
    public string  $Comment;    
    public string $UserName;    
    public string $GakusekiNo;    
    public int $ToketuNo;
    public string $ToketuTxt;
}

class userDAO
{
    //DBからメールアドレスとパスワードが一致する会員データを取得する
    public function get_user(string $GakusekiNo, string $Data;)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得する
        $sql = "SELECT * FROM GakuseiUser WHERE GakusekiNo = :gakusekiNo";
        
        $Data = $_SESSION['student'];
        $GakusekiNo = array_column($Data, 'GakusekiNo')

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':gakusekiNo',$gakusekiNo,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //1件分のデータをMemberクラスのオブジェクトとして取得する
        $user = $stmt->fetchObject('user');
        print_r($user);
    }
    
    public function insert(Member $member)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO Member(email,membername,zipcode,address,tel,password) 
                VALUES(:email,:membername,:zipcode,:address,:tel,:password)";

        $stmt = $dbh->prepare($sql);

        $password = password_hash($member->password, PASSWORD_DEFAULT);

        $stmt->bindValue(":email", $member->email, PDO::PARAM_STR);
        $stmt->bindValue(":membername", $member->membername, PDO::PARAM_STR);
        $stmt->bindValue(":zipcode", $member->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(":address", $member->address, PDO::PARAM_STR);
        $stmt->bindValue(":tel", $member->tel, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function email_exists(string $email)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM Member WHERE email = :email";

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':email',$email,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        if($stmt->fetch() !== false){
            return true; //Emailがある  
        }
        else{
            return false; //Emailがない
        }
    }
}
?>