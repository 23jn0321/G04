<?php
    require_once 'helpers/studentDAO.php';

    $gakusekiNo = '';
    $errs = [];

    //セッションの開始
    session_start();

?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <div>
    <!-- CSS適応 -->
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSUser/Edit.css">

    <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->
    <a href="home.html"><img src="jecMatching/Jec.jpg" width="450px"></a>
    <input type="text" id="name" value="電子太郎 さん" placeholder="ニックネームを入力してください" readonly>
    <input type="button" value="編集" id="edit">
    <hr>

    <!-- プロフィール編集 -->
    <br>
    <p>ニックネーム</p>
    <input type="text" id="nickname"><br>
    <p>一言コメント</p>
    <textarea id="my" name="my" rows="5" cols="33"></textarea><br>
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
            window.location.href = 'home.php'
          }
        });
      });
    </script>


</html>