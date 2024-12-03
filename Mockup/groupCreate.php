<?php
  require_once './helpers/GruopCreateDAO.php';
  
  //セッションを開始
  session_start();

  //未ログインのとき
  if(empty($_SESSION['member'])){
      //login.phpに移動
      header('Location:login.php');
      exit;
  }
  //ログイン中の会員情報を取得
  $member=$_SESSION['member'];

  //POSTメソッドでリクエストされたとき
  if($_SERVER['REQUEST_METHOD']==='POST'){
      //「カートに入れる」ボタンがクリックされたとき
      if(isset($_POST[''])){

      }
    }




    $array = array(
      array( "選択肢１", "選択肢１の値" ),
      array( "選択肢２", "選択肢２の値" ),
      array( "選択肢３", "選択肢３の値", "selected" ),
      array( "選択肢４", "選択肢４の値" ),
      array( "選択肢５", "選択肢５の値" ),
  );
  $sampleSelectBox = "<select name=\"selectBoxName\">\n";
  for ( $indexA = 0; $indexA < count( $array ); $indexA++ ) {
      $sampleSelectBox .= "\t<option value=\"{$array[$indexA][1]}\"";
      if ( $array[$indexA][2] ) {
          $sampleSelectBox .= " selected=\"selected\"";
      }
      $sampleSelectBox .= ">";
      $sampleSelectBox .= "{$array[$indexA][0]}";
      $sampleSelectBox .= "</option>\n";
  }
  $sampleSelectBox .= "</select>\n";
  echo "{$sampleSelectBox}";





?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSUser/GroupCreate.css">

  <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->
  <?php include "header.php"; ?>
</header>

<p>グループ名 ：<input type="text" id="groupName"></p>
<p>参加人数　：

  <label class="selectbox-6">
    <select>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </label>
</p>







<select name="selectBoxName">
    <option value="選択肢１の値">選択肢１</option>
    <option value="選択肢２の値">選択肢２</option>
    <option value="選択肢３の値" selected="selected">選択肢３</option>
    <option value="選択肢４の値">選択肢４</option>
    <option value="選択肢５の値">選択肢５</option>
</select>










<p>大ジャンル：
  <label class="selectbox-3">
    <select>

      <option value="1">ゲーム</option>
      <option value="2">音楽</option>
      <option value="3">スポーツ</option>
      <option value="4">勉強</option>
    </select>
</label>

  <script>
    $(document).ready(function() {
    if ($('#select option:selected').text() === '1') {
      中ジャンル：
      <label class="selectbox-3">
      <select>

      <option></option>
      <option>FPS</option>
      <option>ソシャゲ</option>
      <option>ボードゲーム</option>
      <option>RPG</option>
      <option>カードゲーム</option>

      </select>
  </label>
    }
    $('#select').on('change', function () {
    else if ($('#select option:selected').text() === '2') {
      中ジャンル：
      <label class="selectbox-3">
      <select>

      <option>J-POP</option>
      <option>K-POP</option>
      <option>ボーカロイド</option>
      <option>バンド活動</option>
      <option>DTM</option>
      <option>ボカロ</option>

    </select>
  </label>
    } 
    else if ($('#select option:selected').text() === '3'){
      中ジャンル：
        <label class="selectbox-3">
        <select>

      <option>サッカー</option>
      <option>バスケットボール</option>
      <option>野球</option>
      <option>バレー</option>
      <option>スキー・スノボ</option>
      <option>卓球</option>
      <option>テニス</option>
      <option>バトミントン</option>
      <option>フットサル</option>
      <option>スポーツ観戦</option>
    </select>
  </label>
    }
    else{
      中ジャンル：
      <label class="selectbox-3">
      <select>

      <option>試験勉強</option>
      <option>資格勉強</option>
      <option>プログラミング</option>
      <option>モデリング</option>
      <option>情報交換</option>
      <option>就職活動</option>

      </select>
  </label>
    }
  });

  </script>


  <!--中ジャンル：
  <label class="selectbox-3">
    <select>

      <option>サッカー</option>
      <option>テニス</option>
      <option>バスケットボール</option>
      <option>野球</option>
      <option>バレーボール</option>
      <option>ラグビー</option>
      <option>卓球</option>
      <option>バトミントン</option>
    </select>
  </label>-->

  <!--検索画面に戻る-->
  <a href="genreSelect.html"><input type="button" value="検索画面に戻る" id="modoru"></a>

  <br><br><br><br><br>

  <label>
    <span class="textbox-1-label">グループの説明：</span>
    <input type="text" class="textbox-1" id="textbox-2" />
  </label>



  <script src="./jquery-3.6.0.min.js"></script>


  <input type="button" id="btn08" value="作成">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

  <script>
    $("#btn08").click(function () {
      Swal.fire({
        title: 'グループを作成しますか？',
        html: 'グループ名：資格勉強の集い<br>グループ上限人数：５',
        showCancelButton: true,
        confirmButtonText: '作成',
        type: 'question'
      }).then((result) => {
        if (result.value) {
          
          
          window.location.href = 'message.html'
        }
      });
    });
    </script>