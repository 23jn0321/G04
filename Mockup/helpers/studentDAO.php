<?php
require_once 'DAO.php';

class Student
{
    public string $GakusekiNo;          //会員ID 
    public int $GakunenCode;          //メールアドレス
    public string $GakkaCode;
    public string $GooglePass;       // パスワード
}

class StudentDAO
{
    //DBからメールアドレスとパスワードが一致する会員データを取得する
    public function get_member(string $gakusekiNo, string $password)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得する
        $sql = "SELECT * FROM Gakusei WHERE GakusekiNo = :gakusekiNo";
        
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':gakusekiNo',$gakusekiNo,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //1件分のデータをStudentクラスのオブジェクトとして取得する
        $student = $stmt->fetchObject('Student');

        //会員データが取得できたとき
        if($student !== false){ 
            //パスワードが一致するか検証
            if($password == $student->GooglePass){
                //会員データを返す
                return $student;
            }else{
                echo "Noooooooooooooo";
            }
        }
        return false;
    }
    
    public function insert(Member $member)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO Member(email,membername,zipcode,address,tel,password) 
                VALUES(:email,:membername,:zipcode,:address,:tel,:password)";

        $stmt = $dbh->prepare($sql);

        $password = password_hash($member->password, PASSWORD_DEFAULT);

        $stmt->bindValue(":GakusekiNo", $member->GakusekiNo, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function email_exists(string $studentNo)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM Member WHERE GakusekiNo = :GakusekiNo";

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':GakusekiNo',$GakusekiNo,PDO::PARAM_STR);

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