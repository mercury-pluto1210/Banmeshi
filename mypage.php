<?php 
session_start();
require('./dbconnect.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){ 
  // ログインしている
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
}
else{
  // ログインしていない
  header('Location: ../login.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Banmeshi</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="responsive.css">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet"></link>
</head>

<body>
<header>
  <div class="menu-list">
    <a href="http://localhost/Banmeshi/" class="menu-list-button icon"><img src="./img/Banmeshi_icon.png" alt="Banmeshi" class="icon-img"></a>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/postdisplay.php" class="menu-list-button">投稿一覧</a></p>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/post/" class="menu-list-button">投稿する</a></p>
    <?php if(isset($member['name'])): ?>
      <!-- ログインしている -->
      <p class="menu-list-text"><a href=""><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、ようこそ</p>
    <?php else: ?>
      <!-- ログインしていない -->
      <p class="menu-list-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
      <p class="menu-list-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
    <?php endif; ?>
  </div>
</header>

<div class="main">
  <h1><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さんのマイページ</h1>
  <form action="logout.php" method="post">
    <input type="submit" value="ログアウトする">
  </form>
</div>
</body>

</html>