<?php
    require_once 'helpers/userDAO.php';
    require_once 'helpers/studentDAO.php';

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
        $userDAO = new UserDAO();
        $editInfo = $userDAO->update($nickName,$comment,$user->UserID);
       

        if($nickName === ''){
            $errs[] = 'ニックネームを入力してください。';
        }
        if($comment === ''){
            $errs[] = 'ひとことコメントを入力してください';
        }

        if(empty($errs)){

            //DBから学籍番号・パスワードが一致する会員データを取り出す
            $userDAO = new UserDAO();
            $user = $userDAO->update($nickName,$comment,$user->UserID);

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
    <form id="myForm" action="" method="POST">
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
            <button type="submit" id="submitButton">送信</button>
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

<script src="./jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

       
$(document).ready(function() {
            // フォームの送信処理をカスタマイズ
            $('#myForm').on('submit', function(e) {
                e.preventDefault(); // 通常の送信を防ぐ

                // SweetAlert2を使って確認ダイアログを表示
                Swal.fire({
                    title: '編集確認',
                    text: '編集を確定しますか？',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: 'キャンセル',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 「送信」ボタンが押された場合、フォームを送信
                        this.submit();
                    }
                });
            });
        });
    

</script>


</html>