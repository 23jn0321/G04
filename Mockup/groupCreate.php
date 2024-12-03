<?php
  require_once './helpers/GruopCreateDAO.php';
  
  //セッションの開始
  if(session_status() === PHP_SESSION_NONE){
    session_start();
}

//を取得
$groupCreateDAO=new groupCreateDAO();
$cart_list=$groupCreateDAO->get_cart_by_memberid($member->memberid);

//ログイン中のとき
if(!empty($_SESSION['userInfo'])){
    //セッション変数の会員情報を取得する
    $user = $_SESSION['userInfo'];
}


$cartDAO=new CartDAO();
$cart_list=$cartDAO->get_cart_by_memberid($member->memberid);


$prefecture_cities = [
    'ゲーム' => ['FPS', '', '品川区', '港区'],
    '音楽' => ['横浜市', '川崎市', '相模原市', '藤沢市'],
    'スポーツ' => ['さいたま市', '川越市', '所沢市', '越谷市']
];

?>


<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<header>
    <!-- CSS適応 -->
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSUser/GroupCreate.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->
    <?php include "header.php"; ?>
</header>

<body>
    <!-- グループ名 -->
    <p>グループ名 ：<input type="text" id="groupName"></p>

    <!-- 参加人数 -->
    <p>参加人数　：
        <label class="selectbox-6">
            <select>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </label>
    </p>

    <!-- メインジャンルとサブジャンル -->
    <div class="dropdown-container">
        <label for="maingenreName">大ジャンル名</label>
        <select id="maingenreName">
            <option value="ゲーム">ゲーム</option>
            <option value="音楽">音楽</option>
            <option value="スポーツ">スポーツ</option>
            <option value="勉強">勉強</option>
        </select>

    <!-- サブジャンル選択 -->
    <a>
        <label for="subGenreName" >中ジャンル名</label>
        <select id="subGenreName">
            <option hidden>選択してください</option>
        </select>
        </div>
    
    </a>

    <!-- 作成ボタン -->
    <input type="button" id="btn08" value="作成">

    <script src="jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainGenreSelect = document.getElementById('maingenreName');
            const subGenreSelect = document.getElementById('subGenreName');
            const genres = [
                ['ゲーム', ['RPG', 'シューティング', 'パズル', 'アクション', 'MMORPG', 'ホラー']],
                ['音楽', ['クラシック', 'ロック', 'ジャズ']],
                ['スポーツ', ['サッカー', 'バスケットボール', 'テニス', '野球', '水泳']],
                ['勉強', ['数学', '英語']]
            ];
            

            // 初期選択のゲームに対応するサブジャンルを設定
            const selectedGenre = mainGenreSelect.value; // 初期状態で選ばれているジャンル
            subGenreSelect.innerHTML = '<option hidden>選択してください</option>';  // サブジャンルセレクトボックスをリセット

            // 選ばれたジャンルに対応するサブジャンルを表示
            for (const [mainGenre, subGenres] of genres) {
                if (selectedGenre === mainGenre) {
                    subGenres.forEach(subGenre => {
                        const option = document.createElement('option');
                        option.value = subGenre;
                        option.textContent = subGenre;
                        subGenreSelect.appendChild(option);
                    });
                    break; // 見つかったらループを抜ける
                }
            }

            // メインジャンルが変更された際にサブジャンルを更新
            mainGenreSelect.addEventListener('change', (event) => {
                const genreName = event.target.value;
                console.log("Selected genre:", genreName); // デバッグ用

                // サブジャンルセレクトボックスをリセット
                subGenreSelect.innerHTML = '<option hidden>選択してください</option>';

                // 選択されたジャンルに対応するサブジャンルを表示
                for (const [mainGenre, subGenres] of genres) {
                    if (genreName === mainGenre) {
                        subGenres.forEach(subGenre => {
                            const option = document.createElement('option');
                            option.value = subGenre;
                            option.textContent = subGenre;
                            subGenreSelect.appendChild(option);
                        });
                        break; // 見つかったらループを抜ける
                    }
                }
            });
        });

        // 作成ボタンがクリックされたとき
        $("#btn08").click(function () {
            Swal.fire({
                title: 'グループを作成しますか？',
                html: 'グループ名：<span id="groupNameText">資格勉強の集い</span><br>グループ上限人数：5',
                showCancelButton: true,
                confirmButtonText: '作成',
                type: 'question'
            }).then((result) => {
                if (result.value) {
                    window.location.href = 'message.html'; // 作成後、別のページに遷移
                }
            });
        });
    </script>

    <!--検索画面に戻る-->
    <a href="genreSelect.html"><input type="button" value="検索画面に戻る" id="back"></a>

    <br><br><br><br><br>

    <label>
        <span class="textbox-1-label">グループの説明：</span>
        <input type="text" class="textbox-1" id="textbox-2" />
    </label>

</body>

</html>