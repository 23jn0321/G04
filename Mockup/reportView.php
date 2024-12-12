<?php
require_once 'helpers/GenreDAO.php';

$genreDAO = new GenreDAO();
$genres = $genreDAO->get_Genre();

// メインジャンル情報を出力
foreach ($genres as $genre) {
  echo "メインジャンルID: " . $genre->mainGenreID . "<br>";
  echo "メインジャンル名: " . $genre->mainGenreName . "<br><br>";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>ユーザーごとのチャット表示</title>
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSAdmin/reportView.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

  <header>
    <a href="admin.html"><img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Matching"></a>
    <hr>
  </header>

  <!-- ヘッダー下の横並びコンテンツ -->
  <div class="content">
    <!-- 左側にユーザーリスト -->
    <ul>
      <li onclick="showUserChats(0)">
        <div class="user-info">
          <p>23jn0000 電子 花子<br>性的な嫌がらせ/出会い目的</p>
          <button class="freeze-btn" data-user-name="花子" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
      <li onclick="showUserChats(1)">
        <div class="user-info">
          <p>23jn0000 電子 太郎<br>不適切な名前</p>
          <button class="freeze-btn" data-user-name="太郎" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
      <li onclick="showUserChats(2)">
        <div class="user-info">
          <p>23jn0000 電子 次郎<br>不適切な言葉使い</p>
          <button class="freeze-btn" data-user-name="次郎" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
      <li onclick="showUserChats(3)">
        <div class="user-info">
          <p>23jn0000 電子 やす子<br>その他/違反行為</p>
          <button class="freeze-btn" data-user-name="やす子" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
      <li onclick="showUserChats(4)">
        <div class="user-info">
          <p>23jn0000 電子 三郎<br>不適切な言葉使い</p>
          <button class="freeze-btn" data-user-name="三郎" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
      <li onclick="showUserChats(5)">
        <div class="user-info">
          <p>23jn0000 電子 由衣<br>性的な嫌がらせ/出会い目的</p>
          <button class="freeze-btn" data-user-name="由衣" onclick="freezeUser(this)">凍結</button>
      </li>
    </ul>





    <!-- 右側に選択したユーザーのグループチャットを表示 -->
    <div class="box">
      <div id="userChats"></div>
    </div>
  </div>

  <script>
    // ユーザーごとのグループチャット内容を2次元配列に格納
    const userChats = [
      // 花子
      [
        { group: 'ゲーム', chat: ['こんにちは、ゲーム好きです！', '今日はRPGの話をしよう！'] },
        { group: '音楽', chat: ['音楽の話しようよ！', 'ボカロ曲最近聴いてる'] },
        { group: 'スポーツ', chat: ['スポーツも好きです！', 'サッカー観戦が楽しい'] }
      ],
      // 太郎
      [
        { group: '勉強', chat: ['プログラミングの話しよう！', '新しいJavaScriptのフレームワーク使ってみた'] },
        { group: 'ゲーム', chat: ['RPGが好き！', '新しいゲームが気になる'] }
      ],
      // 次郎
      [
        { group: '音楽', chat: ['ロック好きだよ！', '最近のアルバム良かった！'] },
        { group: '勉強', chat: ['数学の勉強しよう！', '英語も練習中です'] }
      ],
      // やす子
      [
        { group: 'スポーツ', chat: ['水泳が得意です！', 'バスケ観戦楽しみ'] },
        { group: '勉強', chat: ['Linux勉強中', '資格勉強頑張ってます'] }
      ],
      // 三郎
      [
        { group: '音楽', chat: ['ジャズが好き！', 'ロックライブ行きたい'] },
        { group: 'スポーツ', chat: ['サッカー観戦が楽しみ'] }
      ],
      // 由衣
      [
        { group: 'ゲーム', chat: ['MMORPGが好き', '最近はシューティングもやってる'] },
        { group: '音楽', chat: ['ボカロ最高です！', '最近はロックも聴いてる'] }
      ]
    ];

    // ユーザー名クリック時にそのユーザーのグループチャットを表示する
    function showUserChats(userIndex) {
      const userChatContainer = document.getElementById('userChats');
      userChatContainer.innerHTML = ''; // 既存の内容をクリア

      // 選択されたユーザーのチャットを表示
      const selectedUserChats = userChats[userIndex];

      selectedUserChats.forEach(userChat => {
        const groupName = userChat.group;
        const chats = userChat.chat;

        // グループ名表示
        const groupDiv = document.createElement('div');
        groupDiv.className = 'group-chat';
        const groupTitle = document.createElement('p');
        groupTitle.className = 'group-name';
        groupTitle.textContent = `${groupName} グループ`;
        groupDiv.appendChild(groupTitle);

        // チャットメッセージ表示
        chats.forEach(chat => {
          const chatDiv = document.createElement('p');
          chatDiv.textContent = chat;
          groupDiv.appendChild(chatDiv);
        });

        // グループチャットをコンテナに追加
        userChatContainer.appendChild(groupDiv);
      });
    }

    // 初期状態で花子（0番目のユーザー）のチャットを表示
    window.onload = function () {
      showUserChats(0);
    };
    function freezeUser(button) {
      const userName = button.getAttribute("data-user-name"); // ボタンのdata-user-name属性からユーザー名を取得

      Swal.fire({
        html: `<h1>ユーザー「${userName}」を凍結しますか？</h1>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'キャンセル',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          // OKボタンが押された時
          Swal.fire("ユーザーを凍結しました", {
            icon: "success",
          });
          // 実際に凍結処理をここで行うことができます（例: サーバーへAPIリクエスト）
          // 例: freezeUserInDatabase(userName);
        } else {
          // キャンセルボタンが押された時
          Swal.fire("凍結がキャンセルされました。", {
            icon: "info",
          });
        }
      });
    }

  </script>

</body>

</html>