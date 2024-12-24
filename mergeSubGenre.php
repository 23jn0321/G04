<?php
require_once 'DAO.php';

class Genre
{
    public int $mainGenreID;        //メインジャンルID
    public string $mainGenreName;   //メインジャンルネーム
}
class SubGenre
{
    public int $subGenreID;         //サブジャンルID
    public int $mainGenreID;        //メインジャンルID
    public string $subGenreName;    //サブジャンルネーム
    public bool $deleteFlag;         //サブジャンルのdeleteflag
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>中ジャンル統合</title>
    <link rel="stylesheet" href="CSSAdmin/mergeSubGenre.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

<script>
    function margeGenre(event) {
        event.preventDefault()

        const genreName = document.getElementById('genreName').value;
        const sourceGenre = document.getElementById('sourceGenre').value;
        const targetGenre = document.getElementById('targetGenre').value;
        Swal.fire({
            html: `<h2>以下のジャンルを統合します</h2>
<p>
  <strong>大ジャンル:</strong> ${genreName}<br>
  <strong>統合元の中ジャンル:</strong> ${sourceGenre}<br>
  <strong>統合先の中ジャンル:</strong> ${targetGenre}
</p>
<p>この操作を実行すると、${sourceGenre} に関連するすべてのデータが ${targetGenre} に統合されます。</p>
<p><strong>本当に実行してよろしいですか？</strong></p>`,
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

            } else {
                // キャンセルボタンが押された時
                Swal.fire("ジャンル追加がキャンセルされました。", {
                    icon: "info",
                });
            }
        });
    }
</Script>

<body>

    <header>
        <a href="admin.html">
            <img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <form onsubmit="margeGenre(event)">
        <div class="form-group">
            <label for="genreName">大ジャンル名</label>
            <br>
            <select id="genreName" class="genre">
                <option>ゲーム</option>
                <option>音楽</option>
                <option>スポーツ</option>
                <option>勉強</option>
            </select>
        </div>

        <div class="form-group">
            <label for="subGenreName">統合元ジャンルㅤㅤㅤㅤ統合先ジャンル</label>
            <div class="merge-genre">
                <select name="sourceGenre" class="genre-select" id="sourceGenre">
                    <option>a</option>
                    <option>b</option>
                    <option>c</option>
                    <option>d</option>
                </select>

                <span class="arrow">➡</span>

                <select name="targetGenre" class="genre-select" id="targetGenre">
                    <option>a</option>
                    <option>b</option>
                    <option>c</option>
                    <option>d</option>
                </select>
            </div>
        </div>

        <button type="submit">中ジャンルを統合</button>
    </form>

</body>

</html>