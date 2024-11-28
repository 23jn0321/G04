<?php
require_once 'DAO.php';

class Student
{
    public int $GroupID;          //会員ID 
    public string $GroupName;          //メールアドレス
    public int $MaxMember;       // パスワード
    public string $Groupdetail;
    public string $GroupDeleteFlag;
    public int $SubGenreID;
    public int $GroupAdminID;

    public string $MessageDtail;

}

class MessageDAO
{
    //DBからメッセージ内容と送信ユーザーIDを取得するメソッド
    public function get_message()
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得する
        $sql = "SELECT MessaageDetail FROM ChatMessage WHERE ";
        
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':GakusekiNo',$GakusekiNo,PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //取得したデータをクラスの配列にする
        $data=[];






        //1件分のデータをMemberクラスのオブジェクトとして取得する
        $member = $stmt->fetchObject('Student');

        //会員データが取得できたとき
        if($member !== false){
            //パスワードが一致するか検証
            if(password_verify($password,$member->password)){
                //会員データを返す
                return $member;
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