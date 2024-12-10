<?php
require_once 'DAO.php';

class Student
{
    public string $GakusekiNo;          //会員ID 
    public int $GakunenCode;          //メールアドレス
    public string $GakkaCode;
    public string $GooglePass;       // パスワード
}
class Admin
{
    public int $AdminID;
    public string $AdminUserID;
    public string $AdminPass;
}
class User
{
    public int $UserID;
    public string $GakusekiNo;
    public string $ProfileComment;
    public string $UserName;
    public int $UserFreezeFlag;
    public string $FreezeReason;
}

class StudentDAO
{
    //DBからメールアドレスとパスワードが一致する会員データを取得する
    public function get_member(string $gakusekiNo, string $password)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        //学籍番号が一致する会員データを取得する
        $sql = "SELECT * FROM Gakusei WHERE GakusekiNo = :gakusekiNo";

        $sql2 = "SELECT * FROM GakuseiUser WHERE GakusekiNo = :gakuseiNo";
        
        $stmt = $dbh->prepare($sql);

        $stmt2 = $dbh->prepare($sql2);
       
        //SQLに変数の値を当てはめる
        $stmt->bindValue(':gakusekiNo',$gakusekiNo,PDO::PARAM_STR);
        $stmt2->bindValue(':gakuseiNo',$gakusekiNo,PDO::PARAM_STR);
       
        //SQLを実行する
        $stmt->execute();
        $stmt2->execute();
       
        
        //1件分のデータをStudentクラスのオブジェクトとして取得する
        $student = $stmt->fetchObject('Student');
        $user = $stmt2->fetchObject("User");
       

        //会員データが取得できたとき
        if($student !== false){ 
            //パスワードが一致するか検証
            if($password == $student->GooglePass){
                //会員データを返す
                return $user;
            }
        }
        return false;
    }
    public function get_admin(string $gakusekiNo, string $password)
    {
        $dbh = DAO::get_db_connect();

        //学籍番号が一致する会員データを取得する
        $sql = "SELECT * FROM Manager WHERE AdminUserID = :AdminUserID";
        
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':AdminUserID',$gakusekiNo,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //1件分のデータをStudentクラスのオブジェクトとして取得する
        $admin = $stmt->fetchObject('Admin');

        //会員データが取得できたとき
        if($admin !== false){ 
            //パスワードが一致するか検証
            if($password == $admin->AdminPass){
                //会員データを返す
                return $admin;
            }
        }
        return false;
    }
    public function get_newUserInfo(string $userID)
    {

        //DBに接続する
        $dbh = DAO::get_db_connect();

        //学籍番号が一致する会員データを取得する

        $sql = "SELECT * FROM GakuseiUser WHERE UserID = :userid";
        
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':userid',$userID,PDO::PARAM_INT);
       
        //SQLを実行する
        $stmt->execute();
       
        
        //1件分のデータをStudentクラスのオブジェクトとして取得する
        $user = $stmt->fetchAll();
        //会員データが取得できたとき
        if($user !== false){ 
                return $user;
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