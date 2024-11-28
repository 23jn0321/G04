<?php
  $host = 'localhost';
  $dbname= 'JNSV01\sotsu';
  $username= '23jn03_G04';
  $password= '23jn03_G04';
?>


<!DOCTYPE html>
<html>
  <meta charset="utf-8">
  <header>
<!-- CSS適応 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSUser/Message.css">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    
<!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->
      <div class="logo">
        <a href="home.html"><img src="jecMatching/Jec.jpg" width="450px" id="jecIMG"></a>
        <input type="text" id="name" value="電子太郎 さん" placeholder="ニックネームを入力してください" readonly>
        <a href="edit.html"><input type="button" value="編集" id="edit"></a>
        <hr>
      </div>
      
    
  </header>
  <div>
  <p id="group">所属グループ一覧</p>
  <a href="groupEdit.html"><input type="button" value="グループ編集" id="groupEdit"></a>
</div>
<a href="message.html">
<p>
  <!-- グループ表示 -->
 <div class="group">
  <ul>
  <li><p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p></li>
  <li><p>テスト期間がち勉強(4/5)<br>最終更新日：10/8<br>ジャンル：勉強 / テスト勉強</p></li>
  <li><p>プログラミング愛好家(3/4)<br>最終更新日：10/3<br>ジャンル：勉強 / プログラミング</p></li>
  <li><p>テスト勉強(4/4)<br>最終更新日：9/30<br>ジャンル：勉強 / テスト勉強</p></li>
  </ul>
 </div>
</a>
</p>


<header class="header"></header>
  <div class="header__inner">
 
    <buttom class="drawer__button">
      <span></span>
      <span></span>
      <span></span>
    </buttom>
    <nav class="drawer__nav">
      <div class="drawer__nav__inner">
        <ul class="drawer__nav__menu">
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><nobr><p>所属グループ一覧　　<button onclick="location.href='groupEdit.html'">グループ編集</button></p></nobr></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト期間がち勉強(4/5)<br>最終更新日：10/8<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>プログラミング愛好家(3/4)<br>最終更新日：10/3<br>ジャンル：勉強 / プログラミング</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト勉強(4/4)<br>最終更新日：9/30<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p></li></ul></a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>

<script src="./jquery-3.6.0.min.js"></script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>



<input type="button" id="btn08" class="secret" value="">
<input type="button" id="btn09" class="secret" value="">
<!-- メッセージ機能 (https://naruweb.com/coding/linechat/)から引用 -->
<div class="room">
    <ul>
      <li class="chat you" id="btn08">   <!-- 相手のメッセージにはclass「you」をつける。 -->
        <label for="btn08"class="mes" id="btn08">相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ</label>
        <div class="status">電子花子<br>17:00</div>
      </li>
      <li class="chat me" id="btn09">    <!-- 相手のメッセージにはclass「me」をつける。 -->
        <label for="btn09" class="mes" >自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ</label>
        <div class="status">電子太郎<br>17:05</div>
      </li>
    </ul>
  </div>

<div class="send" >
  <input type="text" id="message" placeholder="メッセージを入力してください">








  <!--<body>
    <form action="">
  </body>
      <td>
        <input type="text"required  class="input" value="<?= $send ?>" autofocus>
      </td>

     <body>
          <form action="" method="post">
              <label for="inputText"></label>
              <input type="text" id="inputText" name="inputText" required>
              <button type="submit">送信</button>
          </form>
    </body> -->









    <!-- ブートストラップ -->
 
    <input type="button" value="Send" id="submit">
    <a href="groupDetailAfter.html"><input type="button" value="グループ詳細" id="groupDetail"></a>
  </div> 
  <script>

    $("#btn08").click(function () {
      Swal.fire({
        
        html: '<br>電子花子<br>高度情報処理科<br><br>勉強が好きです。よろしくお願いします<br><br>',
     
        confirmButtonText: '通報',
        showCloseButton : true,
        confirmButtonColor : '#990606'
      }).then((result) => {
        if (result.value) {
          window.location.href = 'report.html'
        }
      });
    });
</script>

<script>
  $("#btn09").click(function () {
    Swal.fire({
      html: '<br>電子太郎<br>情報処理科<br><br>よろしくお願いします<br><br>',
      showCloseButton : true
    })
  });
</script>




</html> 

<script>$(function () {
  // ハンバーガーボタンクリックで実行
  $(".drawer__button").click(function () {
    $(this).toggleClass("active");
    $(".drawer__nav").toggleClass("active");
  });
  // function
});</script>
