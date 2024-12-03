<?php
    require_once './helpers/studentDAO.php';

    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //ログイン中のとき
    if(!empty($_SESSION['userInfo'])){
        //セッション変数の会員情報を取得する
        $user = $_SESSION['userInfo'];
    }
?>
<header>
<a href="home.php"><img src="jecMatching/Jec.jpg" width="450px"></a>
<?php if (isset($user)) : ?>
    <?php
    $user = $_SESSION['userInfo'];
    $studentDAO = new StudentDAO();
    $userName = $studentDAO->get_newUserInfo($user->UserID);
    ?>
    
    <input type="text" id="name" value="<?= $userName->UserName ?>" placeholder="ニックネームを入力してください" readonly>
<?php else: ?>
    <input type="text" required name="nickName" class="input" value="<?= $user->UserName ?>" autofocus >
    <?php endif; ?>
  <a href="edit.php"><input type="button" value="編集" id="edit"></a>
  <a href="admin.html"><input type="button" value="管理者画面" id="admin"></a>
  <a href="login.php"><input type="button" value="ログイン画面遷移" id="admin"></a>
  <hr>
</header>