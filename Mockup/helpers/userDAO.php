
<?php
require_once 'DAO.php';



class GroupAffiliation
{
    public int $GroupID; //グループID
    public int $UserID;  //ユーザーID
    public string $GroupName; //グループ名
    public int $MaxMember; //グループ最大メンバー数
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

        $sql = "UPDATE INTO GakuseiUser(ProfileComment,UserName) 
                VALUES(:profilecomment,:username)";

        $stmt = $dbh->prepare($sql);


        $stmt->bindValue(":profilecomment", $comment PDO::PARAM_STR);
        $stmt->bindValue(":username", $nickName, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function imGroup(string $userNo)
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT ChatGroup.GroupID, UserID,GroupName, MaxMember
	        FROM ChatGroup INNER JOIN GroupMember
		        ON ChatGroup.GroupID = GroupMember.GroupID
			        WHERE UserID = :userid";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":userid", $userNo, PDO::PARAM_INT);

        $stmt->execute();

        $groupAffiliation = $stmt->fetchObject('GroupAffiliation');

        if($groupAffiliation !== false){ 
            return $groupAffiliation;
        }
        return false;
    }
}
