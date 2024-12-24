<?php
  require_once './helpers/GroupCreateDAO.php';
  
  include "header.php"; 
  //セッションの開始
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!empty($_SESSION['userInfo'])){
    //セッション変数の会員情報を取得する
    $user = $_SESSION['userInfo'];
}
//DAOを取得
$groupCreateDAO=new GruopDetailDAO();
$genreList = $groupCreateDAO->groupSelect();
$genre_json = json_encode($genreList); //JSONエンコード


//POSTメソッドでリクエストされたとき
if($_SERVER["REQUEST_METHOD"] === "POST"){
            //入力されたグループの内容を受け取る
            $GroupName = $_POST['groupName'];
            $MaxMember = $_POST['joinNum'];
            $GroupDetial = $_POST['groupDetail'];
            $MainGenreName = $_POST['maingenreName'];
            $SubGenreName = $_POST['subGenreName'];


            $GroupCreateDAO = new GruopDetailDAO();
            $groupID = $GroupCreateDAO->insert($GroupName,$MaxMember,$MainGenreName,$SubGenreName,$GroupDetial,$user->UserID);

            header('Location: message.php?GroupID='. urlencode($groupID));
            exit;
            
            
    }

?>


<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">


<form id="sendbutton" action="" method="POST">
<table id="profileTable" class="box">

<body>
    <!-- グループ名 -->
    <p><lavel id="groupNamelavel">グループ名　：</label></p>
    <p><input type="text" id="groupName" name="groupName"></p>

    <!-- 参加人数 -->
    <p><lavel id="groupJoinlavel">参加人数　：</lavel>
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
        <label for="maingenreName" id="maingenreNamelavel">大ジャンル名　：</label>
        <select id="maingenreName" name="maingenreName">
            <?php foreach($genreList as $genre): ?>
            <option value="<?= $genre[0] ?>">
                <?= htmlspecialchars($genre[0]) ?>
            </option>
            <?php endforeach ?>
        </select>

        <!-- サブジャンル選択 -->
        <a>
            <label for="subGenreName" id="subGenreNamelavel">中ジャンル名　：</label>
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
        <span class="textbox-1-label" >グループの説明：</span>
        <textarea class="textbox-3" id="textbox-2" name="groupDetail" rows="5" cols="20"></textarea>
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

                var groupNameValue = $('#groupName').val(); // グループ名のテキスト内容
                var subGenreNameValue = $('#subGenreName').val(); // 中ジャンルのテキスト内容

                // グループ名と中ジャンルが空かどうかをチェック
                if (groupNameValue === "" || subGenreNameValue === "選択してください") {
                // 空の場合、警告を表示
                Swal.fire({
                    title: '入力エラー',
                    text: 'グループ名または中ジャンルが選択されていません',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });

                //空のテキストを赤く表記させる
                $('#groupName').css('border', '2px solid red');
                $('#subGenreName').css('border', '2px solid red');

                return; // フォーム送信を中止
            }

                // SweetAlert2を使って確認ポップアップを表示
                Swal.fire({
                    title: '確認',
                    text: 'グループを作成しますか？',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: 'キャンセル',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 「確定」ボタンが押された場合、フォームを送信
                        this.submit();
                    }
                });
            });
        });
    </script>

    <!--検索画面に戻る-->
<a href="genreSelect.php"><input type="button" value="検索画面に戻る" id="searchBack" class="searchBack"></a>

    
</body>

</html>