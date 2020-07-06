<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])){
  header('Location: index.php');
  exit();
}

if(!empty($_POST)){
  // 登録処理をする
  $statement = $db->prepare('INSERT INTO members SET name=?, email=?,
  password=?, created=NOW()');
  echo $ret = $statement->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password'])
  ));
  unset($_SESSION['join']);

  header('Location: thanks.php');
  exit();
}

// 書き直し
if($_REQUEST['action'] == 'rewrite'){
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
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
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
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
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
    </div>
  </div>
</header>

<div class="main">
  <h1 class="main-title">アカウント確認</h1>
  <form action="" method="POST">
    <input type="hidden" name="action" value="submit" />
    <div class="confirm">
      <p class="confirm-text">ニックネーム</p>
      <p><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></p>
    </div>
    <div class="confirm">
      <p>メールアドレス</p>
      <p><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></p>               
    </div>
    <div class="confirm">
      <p>パスワード</p>
      <p>【表示されません】</p>
    </div>
    <div class="confirm-button">
      <a href="index.php?action=rewrite" class="confirm-button-rewrite">書き直す</a> 
      <input type="submit" value="登録する" class="confirm-button-register">
    </div>
  </form>
</div>
</body>

</html>