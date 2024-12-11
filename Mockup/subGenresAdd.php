<?php
require_once 'helpers/GenreDAO.php';

$genreDAO = new GenreDAO();
$genres = $genreDAO->get_Genre();

$subGenreDAO=new subGenreDAO();
$subGenres=$subGenreDAO->get_subGenre();

$genreWithSubGenres = [];

foreach ($genres as $genre) {
    $subGenreNames = [];
    
    foreach ($subGenres as $subGenre) {
        if ($subGenre->mainGenreID == $genre->mainGenreID && !$subGenre->deleteFlag) {
            $subGenreNames[] = $subGenre->subGenreName;
        }
    }
    
    $genreWithSubGenres[] = [
        'mainGenreName' => $genre->mainGenreName,
        'subGenreNames' => $subGenreNames
    ];
}


foreach ($genreWithSubGenres as $item) {
    echo "メインジャンル名: " . $item['mainGenreName'] . "<br>";
    echo "サブジャンル: " . implode(", ", $item['subGenreNames']) . "<br><br>";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>サブジャンル追加</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSSAdmin/subGenresAdd.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" defer></script>
</head>

<script>
    let inputCount = 4;

    document.addEventListener('DOMContentLoaded', () => {
        const mainGenreSelect = document.getElementById('genreName');
        const subGenreContainer = document.getElementById('subGenreContainer');
        const genres = [
            ['ゲーム', ['RPG', 'シューティング', 'パズル', 'アクション', 'MMORPG', 'ホラー']],
            ['音楽', ['クラシック', 'ロック', 'ジャズ', 'ボカロ']],
            ['スポーツ', ['サッカー', 'バスケットボール', 'テニス', '野球', '水泳']],
            ['勉強', ['数学', '英語', 'プログラミング', 'Linux', '資格勉強']]
        ];

        // 初期状態でのサブジャンル表示処理
        const genreName = mainGenreSelect.value;
        updateSubGenreFields(genreName);

        // メインジャンル変更時の処理
        mainGenreSelect.addEventListener('change', (event) => {
            const selectedGenre = event.target.value;
            updateSubGenreFields(selectedGenre);
        });

        function updateSubGenreFields(genreName) {
            // フィールドをリセット
            resetSubGenreInputs();

            // 選択されたジャンルに応じたフィールドを追加
            for (const [mainGenre, subGenres] of genres) {
                if (genreName === mainGenre) {
                    subGenres.forEach((subGenre, index) => {
                        if (index >= 4) {
                            addSubGenreField();
                        }
                        // 入力値を設定
                        const input = document.querySelector(`#subGenreName${index + 1}`);
                        if (input) {
                            input.value = subGenre;
                            input.setAttribute('readonly', 'true');
                        }
                    });
                    break;
                }
            }
        }

        function resetSubGenreInputs() {
            const form = subGenreContainer.querySelector('form');
            if (!form) {
                console.error('Form element not found within subGenreContainer');
                return;
            }

            const existingInputs = form.querySelectorAll('input[type="text"]');
            existingInputs.forEach((input, index) => {
                if (index < 4) {
                    input.value = '';
                    input.placeholder = '';
                    input.removeAttribute('readonly');
                } else {
                    input.remove();
                }
            });

            inputCount = 4; // 入力カウントをリセット
            console.log('Inputs have been reset.');
        }

        function addSubGenreField() {
            // 新しいinput要素を作成
            if (inputCount >= 10) {
                showAlert("一度に追加できる中ジャンルは10個までです！");
            } else {
                inputCount += 1;
                var newInput = document.createElement("input");
                newInput.type = "text";
                newInput.id = "subGenreName" + inputCount;
                newInput.name = "subGenreName" + inputCount;
                // 新しいinput要素をフォームに追加
                var form = document.querySelector("form");
                form.insertBefore(newInput, form.querySelector("button"));
                console.log("add呼び出し");
            }
        }

        function showAlert(message) {
            const existingAlert = document.querySelector('.alert-fixed');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-primary alert-fixed';
            alertDiv.role = 'alert';
            alertDiv.textContent = message;

            document.body.insertBefore(alertDiv, document.body.firstChild);

            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 3000);
        }

        const addButton = document.getElementById('addSubGenreButton');
        addButton.addEventListener('click', addSubGenreField);
    });

    function subGenresAdd(event) {
        event.preventDefault()

        const genreName = document.getElementById('genreName').value;
        const subGenreNames = [];
        const textInputs = document.querySelectorAll('input[type="text"]');
        const textInputCount = textInputs.length;
        for (let i = 0; i < textInputCount; i++) {
            const subGenre = textInputs[i].value;
            if (subGenre) {
                subGenreNames.push(subGenre);
            }
        }
        if (subGenreNames.length === 0) {
            showAlert("サブジャンル名を入力してください！");
            return;
        }
        Swal.fire({
            html: `<h1>大ジャンル: <b>${genreName}</h1></b><br><h2>中ジャンル: <b>${subGenreNames.join(', ')}</h2></b><br>これで追加しますか？`,
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
</script>

<body>

    <header>
        <a href="admin.html">
            <img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <div class="dropdown-container">
        <label for="genreName">大ジャンル名</label>
        <select id="genreName">
            <option value="ゲーム">ゲーム</option>
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
            <!-- 中ジャンル追加ボタン -->
            <button type="button" id="addSubGenreButton">+中ジャンルを追加</button>
            <br>

            <button type="submit">ジャンルを追加する</button>
        </form>
    </div>

</body>

</html>