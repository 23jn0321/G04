<?php
  require_once './helpers/GroupCreateDAO.php';
  require_once 'helpers/userDAO.php';
  
  
  //セッションの開始
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!empty($_SESSION['userInfo'])){
    //セッション変数の会員情報を取得する
    $user = $_SESSION['userInfo'];
}
//を取得
$groupCreateDAO=new GruopDetailDAO();
$genreList = $groupCreateDAO->groupSelect();
$genre_json = json_encode($genreList); //JSONエンコード


//POSTメソッドでリクエストされたとき
if($_SERVER["REQUEST_METHOD"] === "POST"){
        //作成ボタンが押されたとき
          //グループの内容が空ではなければ
         
    
            //入力されたグループの内容を受け取る
            $GroupName = $_POST['groupName'];
            $MaxMember = $_POST['joinNum'];
            $GroupDetial = $_POST['groupDetail'];
            $MainGenreName = $_POST['maingenreName'];
            $SubGenreName = $_POST['subGenreName'];


            $GroupCreateDAO = new GruopDetailDAO();
            $sss = 101;
            $GroupCreateDAO->insert($GroupName,$MaxMember,$MainGenreName,$SubGenreName,$GroupDetial,$sss);

            header('Location: message.php');
            exit;
            
            
    }

?>


<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<header>
    <!-- CSS適応 -->
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSUser/GroupCreate.css">



    <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->

</header>
<?php include "header.php"; ?>


    <!-- 作成ボタン -->

<form id="sendbutton" action="" method="POST">
<table id="profileTable" class="box">

<body>
    <!-- グループ名 -->
    <p>グループ名 ：<input type="text" id="groupName" name="groupName"></p>

    <!-- 参加人数 -->
    <p>参加人数　：
        <label class="selectbox-6">
            <select name="joinNum">
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </label>
    </p>

    <!-- メインジャンルとサブジャンル -->
    <div class="dropdown-container">
        <label for="maingenreName">大ジャンル名</label>
        <select id="maingenreName" name="maingenreName">
            <?php foreach($genreList as $genre): ?>
            <option value="<?= $genre[0] ?>">
                <?= htmlspecialchars($genre[0]) ?>
            </option>
            <?php endforeach ?>
        </select>

        <!-- サブジャンル選択 -->
        <a>
            <label for="subGenreName">中ジャンル名</label>
            <select id="subGenreName" name="subGenreName">
                <option hidden>選択してください</option>
            </select>
    </div>

    </a>

    
        

    <script src="jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainGenreSelect = document.getElementById('maingenreName');
            const subGenreSelect = document.getElementById('subGenreName');
            const genres = JSON.parse('<?php echo $genre_json; ?>');

            console.log("Genres:", genres); // デバッグ用

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
    </script>

    <!--グループ詳細-->
    <label>
        <span class="textbox-1-label">グループの説明：</span>
        <input type="text" class="textbox-1" id="textbox-2" name="groupDetail" />
    </label>

    <!--　グループ作成ボタン --> 
    <button type="submit" id="submitButton">作成</button>
    </table>
</form>    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        // 作成ボタンがクリックされたとき
        $(document).ready(function() {
            // フォームの送信処理をカスタマイズ
            $('#sendbutton').on('submit', function(e) {
                e.preventDefault(); // 通常の送信を防ぐ

                // SweetAlert2を使って確認ダイアログを表示
                Swal.fire({
                    title: '編集確認',
                    text: '編集を確定しますか？',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: 'キャンセル',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 「送信」ボタンが押された場合、フォームを送信
                        this.submit();
                    }
                });
            });
        });
    </script>

    <!--検索画面に戻る-->
<<<<<<< HEAD
    <a href="genreSelect.html"><input type="button" value="検索画面に戻る" id="back"></a>
=======
<a href="genreSelect.html"><input type="button" value="検索画面に戻る" id="searchBack" class="searchBack"></a>
>>>>>>> bb77f9aebd0f38788ba70311e8c0413218cca94a

    
</body>

</html>