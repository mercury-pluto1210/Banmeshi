<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])){
  header('Location: index.php');
  exit();
}

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

if(!empty($_POST)){
  // 登録処理をする
  $statement = $db->prepare('INSERT INTO posts SET dinner_id=?, menu_name=?, ingredient=?,
  recipe=?, memo=?, picture=?, created=NOW()');
  echo $ret = $statement->execute(array(
    $member['id'],
    $_SESSION['join']['menu_name'],
    $_SESSION['join']['ingredient'],
    $_SESSION['join']['recipe'],
    $_SESSION['join']['memo'],
    $_SESSION['join']['image']
  ));
  unset($_SESSION['join']);

  header('Location: thanks.php');
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
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<body>
<header>
  <div class="menu-list">
  <a href="http://localhost/Banmeshi/" class="menu-list-button icon"><img src="../img/Banmeshi_icon.png" alt="Banmeshi" class="icon-img"></a>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/postdisplay.php" class="menu-list-button">投稿一覧</a></p>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/post/" class="menu-list-button">投稿する</a></p>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/mypage.php"><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、ようこそ</p>
  </div>

  <div class="menu-list-responsive">
    <label clsss="menu-button" for="open">
      <i class="fas fa-align-justify menu-button-icon" aria-hidden="true"></i>
    </label>
    <input type="checkbox" id="open">
    <div class="menu-link">
      <a href="http://localhost/Banmeshi/"><img src="../img/Banmeshi_icon.png" alt="Banmeshi" class="icon-img-responsive"></a>
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/postdisplay.php" class="menu-list-button">投稿一覧</a></p>
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/post/" class="menu-list-button">投稿する</a></p>
      <p class="menu-link-text"><a href="mypage.php"><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、ようこそ</p>
    </div>
  </div>
</header>

<div class="main">
  <h1 class="main-title">投稿を確認</h1>
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="submit">
    <div class="confirm">
      <p class="confirm-text">メニューの名前</p>
      <?php echo htmlspecialchars($_SESSION['join']['menu_name'], ENT_QUOTES); ?>
    </div>
    <hr class="confirm-underline">
    <div class="confirm">
      <p class="confirm-text">材料</p>
      <?php echo htmlspecialchars($_SESSION['join']['ingredient'], ENT_QUOTES); ?>
    </div>
    <hr class="confirm-underline">
    <div class="confirm">
      <p class="confirm-text">レシピ</p>
      <?php echo htmlspecialchars($_SESSION['join']['recipe'], ENT_QUOTES); ?>
    </div>
    <hr class="confirm-underline">
    <div class="confirm">
      <p class="confirm-text">一言メモ</p>
      <?php echo htmlspecialchars($_SESSION['join']['memo'], ENT_QUOTES); ?>
    </div>
    <hr class="confirm-underline">
    <div class="confirm">
      <p class="confirm-text">写真</p>
      <img src="../picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>" alt="" class="confirm-img">
    </div>
    <hr class="confirm-underline">
    <div class="confirm-button">
      <a href="index.php?action=rewrite" class="confirm-button-rewrite">書き直す</a> 
      <input type="submit" value="投稿する" class="confirm-button-cookpost">
    </div>
  </form>
</div>
</body>

</html>