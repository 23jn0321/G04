<?php
require_once 'helpers/ReportDAO.php';
require_once 'helpers/studentDAO.php';
if (isset($_GET['freezeUserID'])) {
  $userIDToFreeze = $_GET['freezeUserID'];
  $reportCategory = $_GET['reportCategory'];
  $studentDAO = new StudentDAO();
  $studentDAO->freezeUser($userIDToFreeze, $reportCategory);
  //varcharの文字数が短く入らない通報理由があるため、文字数を確認
  //echo "文字数: " . mb_strlen($_GET['reportCategory'], 'UTF-8');
  //echo "バイト数: " . strlen($_GET['reportCategory']);
} 

$reportDAO = new ReportDAO();
$reportedUsers = $reportDAO->getReportedUsers();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>通報管理画面</title>
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSAdmin/reportView.css">
</head>
<body>
<header>
  <a href="admin.html"><img src="jecMatching/Jec.jpg" width="450px" alt="Jec Matching"></a>
  <hr>
</header>
<div class="content">
<ul>
  <?php foreach ($reportedUsers as $user): ?>
    <?php if ($user->UserFreezeFlag != 1): ?> <!-- UserFreezeFlag が 1 でない場合に表示 -->
      <li onclick="showUserDetails(<?= htmlspecialchars(json_encode($user)) ?>)">
        <div class="user-info">
          <p><?= $user->GakusekiNo ?> <?= $user->UserName ?><br><?= $user->ReportCategory ?></p>
          <button class="freeze-btn" data-user-id="<?= $user->UserID ?>" data-report-category="<?= $user->ReportCategory ?>" onclick="freezeUser(this)">凍結</button>
        </div>
      </li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>

  <div class="box">
    <div id="userDetails"></div>
  </div>
</div>

<script>
function showUserDetails(user) {
  const userDetailsContainer = document.getElementById('userDetails');

  // 初期化
  userDetailsContainer.innerHTML = `<h2>${user.UserName} の詳細</h2><p>ロード中...</p>`;

  // サーバーからデータを取得
  fetch(`getUserDetail.php?userID=${user.UserID}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        userDetailsContainer.innerHTML = `<p>${data.error}</p>`;
        return;
      }

      // グループ情報の表示
      let groupDetails = `<p>所属グループ:</p>`;
      data.groups.forEach(group => {
        groupDetails += `<p>・${group.GroupName}</p>`;
      });

      // グループ内チャットの表示
      let chatDetails = `<p>グループ内での発言:</p>`;
      data.chats.forEach(chat => {
        chatDetails += `
          <div class="chat-log">
            <p><strong>${chat.GroupName} グループ:</strong></p>
            <p>${chat.MessageDetail}</p>
            <p><em>${chat.SendTime}</em></p>
          </div>`;
      });

      // 詳細をHTMLに挿入
      userDetailsContainer.innerHTML = `
        <h2>${user.UserName} の詳細</h2>
        <p>学籍番号: ${user.GakusekiNo}</p>
        <p>通報理由: ${user.ReportCategory}</p>
        ${chatDetails}
      `;
    })
    .catch(error => {
      console.error('エラー:', error);
      userDetailsContainer.innerHTML = `<p>データ取得中にエラーが発生しました。</p>`;
    });
}
function freezeUser(button) {
  const userID = button.getAttribute("data-user-id");
  const reportCategory = button.getAttribute("data-report-category");

  // GETリクエストで同じページに遷移
  window.location.href = `reportView.php?freezeUserID=${userID}&reportCategory=${encodeURIComponent(reportCategory)}`;
}
</script>
</body>
</html>
