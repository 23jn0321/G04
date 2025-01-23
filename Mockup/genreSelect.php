<?php
require_once 'helpers/userDAO.php';
require_once 'helpers/GroupDAO.php';
require_once 'helpers/GenreSelectDAO.php';

include "header.php"; 

$loggedInUser = $_SESSION['userInfo'] ?? null;

$groupDAO = new NewGroupDAO();
$groupInfo = $groupDAO->getNewGroup($loggedInUser->UserID);

$genreSelectDAO = new GenreSelectDAO();
// 大ジャンルを取得
$mainGenres = $genreSelectDAO->getAllMainGenres();
?>

<!DOCTYPE html>
<html>
    <body>
        <meta charset="utf-8">
        <header>
            <link rel="stylesheet" href="CSSUser/GenreSelect.css">
            <div>
                <p id="title">最新のグループ</p>
            </div>
        </header>

        <nav class="newGroup">
            <ul>
                <?php if (empty($groupInfo)) : ?>
                    <li>最新グループがありません</li>
                <?php else : ?>
                    <?php foreach ($groupInfo as $var) : ?>
                        <li>
                            <a href="groupDetail.php?GroupID=<?= urlencode($var->GroupID) ?>">
                                グループ名：<?= htmlspecialchars($var->GroupName) ?><br>
                                所属人数：<?= htmlspecialchars($var->MemberInfo) ?><br>
                                最終更新日：<?= htmlspecialchars($var->LastUpdated) ?><br>
                                ジャンル：<?= htmlspecialchars($var->Genre) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </nav>

        <button id="GroupSakusei" onclick="location.href='groupCreate.php'">グループ作成</button>

        <div id="container">
            <div id="mainGenres">
                <h2>大ジャンル</h2>
                <?php foreach ($mainGenres as $mainGenre) : ?>
                    <div class="genre-button" data-genre-id="<?= htmlspecialchars($mainGenre['MainGenreID']) ?>">
                        <?= htmlspecialchars($mainGenre['MainGenreName']) ?>
                    </div>
                    <br>
                <?php endforeach; ?>
            </div>

            <div class="box">
            <div id="subGenres">
                <h2>中ジャンル</h2>
                    <form id="subGenreForm" method="GET" action="search.php">
                        <div id="subGenreList">
                            大ジャンルを選択してください。
                        </div>
                    <button type="submit" id="Search">検索</button>
                </form>
            </div>
        </div>

        <script>
document.addEventListener("DOMContentLoaded", function () {
    const genreButtons = document.querySelectorAll(".genre-button");
    const subGenreList = document.getElementById("subGenreList");
    const subGenreForm = document.getElementById("subGenreForm");

    // すべての選択された中ジャンルを保持するマップ (大ジャンルごとに分ける)
    const selectedSubGenres = new Map();

    genreButtons.forEach(button => {
        button.addEventListener("click", function () {
            const genreId = this.getAttribute("data-genre-id");

            // サブジャンルを取得
            fetch(`getSubGenre.php?MainGenreID=${genreId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
    subGenreList.innerHTML = "";

    if (data.error) {
        subGenreList.innerHTML = data.error;
        return;
    }

    if (data.length === 0) {
        subGenreList.innerHTML = "中ジャンルが見つかりません。";
        return;
    }

    // サブジャンルリストを生成
    data.forEach((subGenre, index) => {
        const wrapperDiv = document.createElement("div");

        // 9件ごとに異なるクラスを割り当て
        const groupIndex = Math.floor(index / 9) + 1; // 1から始まるグループ番号
        wrapperDiv.className = `subGenreGroup group-${groupIndex}`;

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = `genre[${genreId}][]`;
        checkbox.value = subGenre.SubGenreID;

        // チェック状態の復元
        if (selectedSubGenres.has(genreId) && selectedSubGenres.get(genreId).has(subGenre.SubGenreID)) {
            checkbox.checked = true;
        }

        // チェック状態の更新
        checkbox.addEventListener("change", function () {
            if (!selectedSubGenres.has(genreId)) {
                selectedSubGenres.set(genreId, new Set());
            }

            const subGenreSet = selectedSubGenres.get(genreId);
            if (this.checked) {
                subGenreSet.add(subGenre.SubGenreID);
            } else {
                subGenreSet.delete(subGenre.SubGenreID);
            }
        });

        const label = document.createElement("label");
        label.textContent = subGenre.SubGenreName;
        label.prepend(checkbox);

        wrapperDiv.appendChild(label);

        subGenreList.appendChild(wrapperDiv);
    });
});


        });
    });

    // フォーム送信時に選択内容を更新
    subGenreForm.addEventListener("submit", function () {
    const hiddenInputsContainer = document.createElement("div");
    hiddenInputsContainer.style.display = "none";

    // Map から選択された中ジャンルを hidden input として追加
    selectedSubGenres.forEach((subGenreSet, mainGenreId) => {
        subGenreSet.forEach(subGenreId => {
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = `genre[]`;
            hiddenInput.value = subGenreId;
            hiddenInputsContainer.appendChild(hiddenInput);
        });
    });

    subGenreForm.appendChild(hiddenInputsContainer);
});

});

document.getElementById("selectAll").addEventListener("click", function () {
    const checkboxes = subGenreList.querySelectorAll("input[type='checkbox']");
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;

        const genreId = checkbox.name.match(/\d+/)[0]; // 大ジャンルID
        const subGenreId = checkbox.value;

        // Map に反映
        if (!selectedSubGenres.has(genreId)) {
            selectedSubGenres.set(genreId, new Set());
        }
        selectedSubGenres.get(genreId).add(parseInt(subGenreId));
    });
});


</script>



    </body>
</html>
