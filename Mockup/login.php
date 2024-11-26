<?php
    require_once 'helpers/studentDAO.php';

    $gakusekiNo = '';
    $errs = [];

    //セッションの開始
    session_start();

    //ログイン済みのとき
    if(!empty($_SESSION['student'])){

        header('Location: home.html');//htmlファイルからphpファイルに変更
        exit;
    }

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //入力された学籍番号とパスワードを受け取る
        $gakusekiNo = $_POST['gakusekiNo'];
        $password = $_POST['password'];

        if($gakusekiNo === ''){
            $errs[] = '学籍番号を入力してください。';
        }
        if($password === ''){
            $errs[] = 'パスワードを入力してください。';
        }

        if(empty($errs)){

            //DBから学籍番号・パスワードが一致する会員データを取り出す
            $studentDAO = new StudentDAO();
            $student = $studentDAO->get_member($gakusekiNo,$password);

            //会員データを取り出せたとき
            if($student !== false){
                //セッションIDを変更する
                session_regenerate_id(true);

                //セッション変数に会員データを保存する
                 $_SESSION['student'] = $student;

                //index.phpに移動
                header('Location: home.html');
                exit;
            }
            //会員データが取り出せなかった時
            else{
                $errs[] = '学籍番号またはパスワードに誤りがあります。';
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
            <td>学籍番号</td>
            <td>
                <input type="text" required name="gakusekiNo" class="input" value="<?= $gakusekiNo ?>" autofocus >
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