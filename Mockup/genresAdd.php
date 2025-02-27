<?php
require_once 'helpers/GenreDAO.php';
// $_GET['genreName'] と $_GET['subGenreName'] が存在するか確認
if (isset($_GET['genreName']) && isset($_GET['subGenreName'])) {
  $genreName = $_GET['genreName'];  // genreNameを取得
  $subGenreNames = (array) $_GET['subGenreName']; // subGenreName[] を配列として取得（配列でない場合も配列に変換）
  $genreDAO = new GenreDAO();

  $mainGenreID = $genreDAO->insert_Genre($genreName);  //メインジャンルを挿入
  // サブジャンルを挿入
  //echo $mainGenreID;
  $subGenreDAO = new SubGenreDAO();
  foreach ($subGenreNames as $subGenreName) {
    //echo $subGenreName;  // サブジャンル名を表示
    $subGenreDAO->insert_SubGenre($mainGenreID, $subGenreName);  // サブジャンルを挿入

  }

  // 確認のため、genreNameとsubGenreNameを表示
  //echo '<br>大ジャンル名: ' . $genreName . '<br>';
  //echo '<pre>';
  //print_r($subGenreNames);  // 配列を表示
  //echo '</pre>';
} 
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>ジャンル追加</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="CSSAdmin/genresAdd.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" defer></script>

</head>
<script>
  let inputCount = 4;

  function addSubGenreField() {
    // 新しいinput要素を作成
    if (inputCount >= 10) {
      showAlert("一度に追加できる中ジャンルは10個までです！")
    } else {
      inputCount += 1
      var newInput = document.createElement("input");
      newInput.type = "text";
      newInput.id = "subGenreName" + inputCount;
      newInput.name = "subGenreName[]";
      // 新しいinput要素をフォームに追加
      var form = document.querySelector("form");
      form.insertBefore(newInput, form.querySelector("button"));
    }
  }

  function showAlert(message) {
    var existingAlert = document.querySelector(".alert-fixed");
    if (existingAlert) {
      existingAlert.remove();
    }
    // アラート用のdivを作成
    var alertDiv = document.createElement("div");
    alertDiv.className = "alert alert-primary alert-fixed"; // Bootstrapのアラートクラス
    alertDiv.role = "alert"; // アラートとしての役割を設定
    alertDiv.textContent = message; // メッセージを設定

    // アラートをページに追加（フォームの前に追加）
    document.body.insertBefore(alertDiv, document.body.firstChild);

    // 3秒後にアラートを非表示にする
    setTimeout(function() {
      alertDiv.style.display = "none";
    }, 3000);
  }

  function genresAdd(event) {
    event.preventDefault()

    const genreName = document.getElementById('genreName').value;
    const subGenreNames = [];
    const textInputs = document.querySelectorAll('input[type="text"]');
    const textInputCount = textInputs.length;
    for (let i = 1; i < textInputCount; i++) {
      const subGenre = textInputs[i].value;
      if (subGenre) {
        subGenreNames.push(subGenre);
      }
    }
    if (!genreName) {
      showAlert("大ジャンル名を入力してください！");
      return;
    }
    Swal.fire({
      html: `<h1>大ジャンル: <b>${genreName}</h1></b><br><h2>中ジャンル: <b>${subGenreNames.join(', ')}</h2></b><br>これで追加しますか？<br>※空文字列の中ジャンルは追加されません`,
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'OK',
      reverseButtons: true,
      icon: 'info'
    }).then((result) => {
      console.log(result);
      if (result.isConfirmed) {
        // OKボタンが押された時
        Swal.fire("ジャンルを追加しました", {
          icon: "success",
        });
        document.querySelector("form").submit(); // ここで実際にフォーム送信を行う
        header('Location: "genresAdd.php"');

      } else {
        // キャンセルボタンが押された時
        Swal.fire("ジャンル追加がキャンセルされました。", {
          icon: "info",
        });
      }
    });
  }
</script>

<body>

  <header>
    <a href="admin.html">
      <img src="jecMatching/Jec.jpg" width="450px" alt="Jec Logo">
    </a>
    <hr>
  </header>

  <form onsubmit="genresAdd(event)" method="GET" action="">
    <label for="genreName">大ジャンル名</label>
    <input type="text" id="genreName" name="genreName">

    <label for="subGenreName">中ジャンル名</label>
    <input type="text" id="subGenreName1" name="subGenreName[]">
    <input type="text" id="subGenreName2" name="subGenreName[]">
    <input type="text" id="subGenreName3" name="subGenreName[]">
    <input type="text" id="subGenreName4" name="subGenreName[]">
    <!-- 中ジャンル追加ボタン -->
    <button type="button" onclick="addSubGenreField()">+中ジャンルを追加</button>
    <br>

    <button type="submit">ジャンルを追加する</button>
  </form>

</body>

</html>