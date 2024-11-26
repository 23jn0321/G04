<?php
    require_once './helpers/studentDAO.php';

    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //ログイン中のとき
    if(!empty($_SESSION['member'])){
        //セッション変数の会員情報を取得する
        $member = $_SESSION['member'];
    }
?>
<header>
<a href="home.php"><img src="jecMatching/Jec.jpg" width="450px"></a>
  <input type="text" id="name" value="<??>" placeholder="ニックネームを入力してください" readonly>
  <a href="edit.php"><input type="button" value="編集" id="edit"></a>
  <a href="admin.html"><input type="button" value="管理者画面" id="admin"></a>
  <a href="login.php"><input type="button" value="ログイン画面遷移" id="admin"></a>
  <hr>
</header>