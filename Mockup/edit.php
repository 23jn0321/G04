<?php
    require_once 'helpers/userDAO.php';
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
    $nickName= '';
    $comment= '';
    $errs = [];
  
    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //入力された学籍番号とパスワードを受け取る
        $nickName = $_POST['nickName'];
        $comment = $_POST['comment'];

        if($nickName === ''){
            $errs[] = 'ニックネームを入力してください。';
        }
        if($comment === ''){
            $errs[] = 'ひとことコメントを入力してください';
        }

        if(empty($errs)){

            //DBから学籍番号・パスワードが一致する会員データを取り出す
            $userDAO = new UserDAO();
            $user = $userDAO->update($nickName,$comment);

            //会員データを取り出せたとき
            if($student !== false){
                //セッションIDを変更す
                //index.phpに移動
                header('Location: home.php');
                exit;
        }
    }
  }
?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  
</header>
<?php include "header.php"; ?>

    <!-- プロフィール編集 -->
    <form name="pro"action="" method="POST">
    <table id="profileTable" class="box">
        <tr>
            <th colspan="2">
                プロフィール編集
            </th>
        </tr>
        <tr>
            <td>ニックネーム</td>
            <td>
                <input type="text" required name="nickName" class="input" value="<?= $user->UserName ?>" autofocus >
            </td>
        </tr>
        <tr>
            <td>ひとことコメント</td>
            <td>
            <input type="text" required name="comment" class="input" value="<?= $user->ProfileComment ?>" autofocus >
            </td>
        </tr>
        <tr>
            <td>
            <input type="button" value="確定" id="submit">

            <script src="./jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
  $("#submit").click(function () {
    Swal.fire({
      html: '編集を確定してよろしいですか？',
      showCancelButton: true,
      confirmButtonText: 'OK',
      type: 'question'
    }).then((result) => {
      if (result.value) {
      <?
    $userDAO = new UserDAO();
    $editInfo = $userAO->update($nickName,$comment); ?>
        window.location.href = 'home.php'
        document.pro.submit();
      }
    });
  });
</script>
 
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                <?php foreach($errs as $e) : ?>
                    <span style="color:red"><?= $e ?></span>
                    <br>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
</form>



</html>