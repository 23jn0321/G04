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
if (isset($_GET['newSubGenreName'])) {
    $newSubGenreNames = $_GET['newSubGenreName'];  // ここでnewSubGenreName[]が配列として取得される
    $genreID=$_GET["selectedOptionId"];
    $subGenreDAO = new subGenreDAO();
    echo "Genre ID: " . htmlspecialchars($genreID, ENT_QUOTES, 'UTF-8') . "<br>";
    foreach ($newSubGenreNames as $subGenre) {
        echo htmlspecialchars($subGenre, ENT_QUOTES, 'UTF-8') . "<br>";
        $subGenreDAO->insert($genreID,$subGenre);
        echo htmlspecialchars($subGenre, ENT_QUOTES, 'UTF-8') . "<br>";
    }
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
    let newGenreCount = 0;
    let genres = [
        [],
        []
    ]
    document.addEventListener('DOMContentLoaded', () => {
        const mainGenreSelect = document.getElementById('genreName');
        const subGenreContainer = document.getElementById('subGenreContainer');
        genres = <?php echo json_encode($genreWithSubGenres); ?>;
        console.log(genres);
        const selectedOption = mainGenreSelect.options[mainGenreSelect.selectedIndex];
        const initialOptionId = selectedOption.id;
        hiddenLabelDiv.value = initialOptionId; // 隠し入力にoptionIdを設定
        // 初期状態でのサブジャンル表示処理
        const genreName = mainGenreSelect.value;
        updateSubGenreFields(genreName);

        // メインジャンル変更時の処理
        mainGenreSelect.addEventListener('change', (event) => {
            const selectedGenre = event.target.value;
            updateSubGenreFields(selectedGenre);
            //変更されたジャンルIDにoptionIDを変更
            const selectedOption = event.target.options[event.target.selectedIndex];
            hiddenLabelDiv.value = selectedOption.id; // 隠し入力に新しいoptionIdを設定
        });

        function updateSubGenreFields(genreName) {
            resetSubGenreInputs();
            // genres をループして対応する大ジャンルを検索
            const genreData = genres.find(genre => genre.mainGenreName === genreName);
            if (genreData) {
                // サブジャンルの入力フィールドを追加
                genreData.subGenreNames.forEach((subGenre, index) => {
                    if (index >= 4) {
                        addSubGenreField(); // 必要な数だけフィールドを追加
                    }
                    const input = document.querySelector(`#subGenreName${index + 1}`);
                    if (input) {
                        input.value = subGenre;
                        input.setAttribute('readonly', 'true');
                    }
                });
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
                console.log("自動追加");
            }
        }

        function addNewSubGenreField() {
            // 新しいinput要素を作成
            if (inputCount >= 10) {
                showAlert("一度に追加できる中ジャンルは10個までです！");
            } else {
                newGenreCount += 1;
                inputCount += 1;
                var newInput = document.createElement("input");
                newInput.type = "text";
                newInput.id = "newSubGenreName" + newGenreCount;
                newInput.name = name = "newSubGenreName[]"
                // 新しいinput要素をフォームに追加
                var form = document.querySelector("form");
                form.insertBefore(newInput, form.querySelector("button"));
                console.log("ジャンル追加呼び出し");
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

        const addButton = document.getElementById('addNewSubGenreButton');
        addButton.addEventListener('click', addNewSubGenreField);
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
                const genreData = genres.find(genre => genre.mainGenreName === genreName);
                if (genreData) {
                    const existingInputs = document.querySelectorAll('input[type="text"]');
                    console.log(existingInputs.length);
                    console.log(document.getElementById('subGenreName' + i).value);
                    for (let i = genreData.subGenreNames.length + 1; i < existingInputs.length + 1; i++) {

                        //insert
                    }


                }



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
        <select id="genreName" name="genreName">
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

    <div id="subGenreContainer">
        <form onsubmit="newSubGenresAdd(event)" method="GET" action="">
            <input type="hidden" id="hiddenLabelDiv" name="selectedOptionId" value="">
            <label for="subGenreName">中ジャンル名</label>
            <input type="text" id="subGenreName1" name="subGenreName1">
            <input type="text" id="subGenreName2" name="subGenreName2">
            <input type="text" id="subGenreName3" name="subGenreName3">
            <input type="text" id="subGenreName4" name="subGenreName4">
            <!-- 中ジャンル追加ボタン -->
            <button type="button" id="addNewSubGenreButton">+中ジャンルを追加</button>
            <br>

            <button type="submit">ジャンルを追加する</button>
        </form>
    </div>

</body>

</html>