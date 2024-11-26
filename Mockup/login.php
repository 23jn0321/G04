<?php
    require_once './helpers/studentDAO.php';

    $email = '';
    $errs = [];

    //セッションの開始
    session_start();

    //ログイン済みのとき
    if(!empty($_SESSION['member'])){

        header('Location: home.html');//htmlファイルからphpファイルに変更
        exit;
    }

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //入力されたメールアドレスとパスワードを受け取る
        $email = $_POST['email'];
        $password = $_POST['password'];

        if($email === ''){
            $errs[] = 'メールアドレスを入力してください。';
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errs[] = 'メールアドレスの形式に誤りがあります。';
        }
        if($password === ''){
            $errs[] = 'パスワードを入力してください。';
        }

        if(empty($errs)){

            //DBからメールアドレス・パスワードが一致する会員データを取り出す
            $memberDAO = new MemberDAO();
            $member = $memberDAO->get_member($email,$password);

            //会員データを取り出せたとき
            if($member !== false){
                //セッションIDを変更する
                session_regenerate_id(true);

                //セッション変数に会員データを保存する
                 $_SESSION['member'] = $member;

                //index.phpに移動
                header('Location: index.php');
                exit;
            }
            //会員データが取り出せなかった時
            else{
                $errs[] = 'メールアドレスまたはパスワードに誤りがあります。';
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="css/LoginStyle.css" rel="stylesheet">
    <title>ログイン</title>
</head>
<body>
<?php include "header2.php"; ?>
<form action="" method="POST">
    <table id="LoginTable" class="box">
        <tr>
            <th colspan="2">
                ログイン
            </th>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td>
                <input type="email"required name="email" class="input" value="<?= $email ?>" autofocus>
            </td>
        </tr>
        <tr>
            <td>パスワード</td>
            <td>
                <input type="password"required class="input" name="password">
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="ログイン">
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