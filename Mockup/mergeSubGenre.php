<?php
require_once 'helpers/GenreDAO.php';

$genreDAO = new GenreDAO();
$genres = $genreDAO->get_Genre();

$subGenreDAO = new subGenreDAO();
$subGenres = $subGenreDAO->get_subGenre();

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
//配列表示.
//echo '<pre>';
//print_r($genreWithSubGenres);
//echo '<pre>';

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
        event.preventDefault();

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
            reverseButtons: true
        }).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
                Swal.fire("ジャンルを追加しました", {
                    icon: "success",
                });
                document.querySelector("form").submit(); // ここで実際にフォーム送信を行う
            } else {
                Swal.fire("ジャンル追加がキャンセルされました。", {
                    icon: "info",
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const genreSelect = document.getElementById("genreName");
        const sourceGenreSelect = document.getElementById("sourceGenre");
        const targetGenreSelect = document.getElementById("targetGenre");

        // PHPからJSON形式でデータを渡す
        let genre = <?php echo json_encode($genreWithSubGenres); ?>;

        // 初期状態で「ゲーム」を選択
        genreSelect.value = "ゲーム"; // ここで初期選択を「ゲーム」に設定

        // 「ゲーム」を選んだときのサブジャンルを表示
        const selectedGenre = genreSelect.value;
        sourceGenreSelect.innerHTML = '<option value="">選択してください</option>';
        targetGenreSelect.innerHTML = '<option value="">選択してください</option>';

        if (selectedGenre) {
            const selectedGenreData = genre.find(g => g.mainGenreName === selectedGenre);

            if (selectedGenreData) {
                selectedGenreData.subGenreNames.forEach(function(subGenre) {
                    // sourceGenreSelectのオプション追加
                    const option1 = document.createElement("option");
                    option1.value = subGenre; // subGenreの値を設定
                    option1.textContent = subGenre; // 表示されるテキストを設定
                    sourceGenreSelect.appendChild(option1);

                    // targetGenreSelectのオプション追加
                    const option2 = document.createElement("option");
                    option2.value = subGenre; // subGenreの値を設定
                    option2.textContent = subGenre; // 表示されるテキストを設定
                    targetGenreSelect.appendChild(option2);
                });
                sourceGenreSelect.disabled = false;
                targetGenreSelect.disabled = false;
            }
        }

        // ジャンル変更時のイベント
        genreSelect.addEventListener("change", function() {
            const selectedGenre = genreSelect.value; // 選択されたジャンル
            sourceGenreSelect.innerHTML = '<option value="">選択してください</option>';
            targetGenreSelect.innerHTML = '<option value="">選択してください</option>';

            if (selectedGenre) {
                const selectedGenreData = genre.find(g => g.mainGenreName === selectedGenre);

                if (selectedGenreData) {
                    selectedGenreData.subGenreNames.forEach(function(subGenre) {
                        // sourceGenreSelectのオプション追加
                        const option1 = document.createElement("option");
                        option1.value = subGenre; // subGenreの値を設定
                        option1.textContent = subGenre; // 表示されるテキストを設定
                        sourceGenreSelect.appendChild(option1);

                        // targetGenreSelectのオプション追加
                        const option2 = document.createElement("option");
                        option2.value = subGenre; // subGenreの値を設定
                        option2.textContent = subGenre; // 表示されるテキストを設定
                        targetGenreSelect.appendChild(option2);
                    });
                    sourceGenreSelect.disabled = false;
                    targetGenreSelect.disabled = false;
                }
            } else {
                sourceGenreSelect.disabled = true;
                targetGenreSelect.disabled = true;
            }
        });
    });
</script>


<body>

    <header>
        <a href="admin.html">
            <img src="jecMatching/Jec.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <form onsubmit="margeGenre(event)">
        <div class="form-group">
            <label for="genreName">大ジャンル名</label>
            <br>
            <select id="genreName" class="genre" name="genreName">
                <?php $loopCounter = 1; // 1からカウント開始 
                ?>
                <?php foreach ($genres as $genre): ?>
                    <option id="<?php echo $loopCounter; ?>" value="<?php echo htmlspecialchars($genre->mainGenreName, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($genre->mainGenreName, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php $loopCounter++; // カウンターをインクリメント 
                    ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subGenreName">統合元ジャンルㅤㅤㅤㅤ統合先ジャンル</label>
            <div class="merge-genre">
                <select name="sourceGenre" class="genre-select" id="sourceGenre">
                    <option></option>
                    <option></option>
                    <option></option>
                    <option></option>
                </select>

                <span class="arrow">➡</span>

                <select name="targetGenre" class="genre-select" id="targetGenre">
                    <option></option>
                    <option></option>
                    <option></option>
                    <option></option>
                </select>
            </div>
        </div>

        <button type="submit">中ジャンルを統合</button>
    </form>

</body>

</html>