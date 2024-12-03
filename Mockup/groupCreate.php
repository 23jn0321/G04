<?php
  require_once './helpers/GruopCreateDAO.php';
  
  //セッションを開始
  session_start();

  //未ログインのとき
  /*if(empty($_SESSION['member'])){
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
    }*/

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





<script>
    let inputCount = 4;
    document.addEventListener('DOMContentLoaded', () => {
        const mainGenreSelect = document.getElementById('maingenreName');
        if (!mainGenreSelect) {
            console.error('Element with id "genreName" not found');
            return;
        }

        const subGenreContainer = document.getElementById('subGenreContainer');
        const genres = [
            ['ゲーム', ['RPG', 'シューティング', 'パズル', 'アクション', 'MMORPG', 'ホラー']],
            ['音楽', ['クラシック', 'ロック', 'ジャズ']],
            ['スポーツ', ['サッカー', 'バスケットボール', 'テニス', '野球', '水泳']],
            ['勉強', ['数学', '英語']]
        ];

        // メインジャンルが選ばれた際に処理を行う
        mainGenreSelect.addEventListener('change', (event) => {
            const genreName = event.target.value;
            console.log("Selected genre:", genreName); // デバッグ用

            // サブジャンル入力フォームをリセット
            const form = subGenreContainer.querySelector('form');
            if (!form) {
                console.error("Form element not found within subGenreContainer");
                return;
            }

            const existingInputs = form.querySelectorAll('input[type="text"]');
            existingInputs.forEach((input, index) => {
                if (index < 4) {
                    // Reset the first 4 inputs
                    input.value = '';
                    input.placeholder = '';
                } else {
                    // Remove additional inputs beyond the first 4
                    input.remove();
                }
            });

            // Reset input count
            inputCount = 4;

            // 選択されたジャンルに対応するサブジャンルを表示
            for (const [mainGenre, subGenres] of genres) {
                if (genreName === mainGenre) {
                    const inputs = form.querySelectorAll('input[type="text"]');
                    inputs.forEach((input, index) => {
                        if (index < subGenres.length) {
                            option.value = subGenres[index];
                        }
                    });
                    break; // 見つかったらループを抜ける
                }
            }
        });
    });
</script>

<body>


    <div class="dropdown-container">
        <label for="genreName">大ジャンル名</label>
        <select id="genreName">
            <option value="ゲーム"></option>
            <option value="音楽">音楽</option>
            <option value="スポーツ">スポーツ</option>
            <option value="勉強">勉強</option>
        </select>
    </div>
    <div id="subGenreContainer">
        <form onsubmit="subGenresAdd(event)">
            <label for="subGenreName">中ジャンル名</label>
            <input type="text" id="subGenreName1" name="subGenreName1">
            <input type="text" id="subGenreName2" name="subGenreName2">
            <input type="text" id="subGenreName3" name="subGenreName3">
            <input type="text" id="subGenreName4" name="subGenreName4">
    </div>
    </form>

</body>

</html>









<body>

    <header>
        <a href="admin.html">
            <img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <div class="dropdown-container">
        <label for="maingenreName">大ジャンル名</label>
        <select id="maingenreName">
            <option value="ゲーム">ゲーム</option>
            <option value="音楽">音楽</option>
            <option value="スポーツ">スポーツ</option>
            <option value="勉強">勉強</option>
        </select>


        <div class="dropdown-container">
        <label for="subgenreName">中ジャンル名</label>
        <select id="subgenreName">
            <option value="ゲーム"></option>
            <option value="音楽">音楽</option>
            <option value="スポーツ">スポーツ</option>
            <option value="勉強">勉強</option>
        </select>
    </div>
</body>

</html>

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