<?php
session_start();

// if(isset($_SESSION['id'])){
//   unset($_SESSION['id']);
//   unset($_SESSION['time']);
// }

// セッション情報を削除
$_SESSION = array();
if(ini_get("session.use_cookies")){
  $rapams = session_get_cookie_params();
  setcookie(session_name(). ''. time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

session_destroy();

// Cookies情報も削除
setcookie('email', '', time() - 3600);
setcookie('password', '', time() - 3600);
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
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
    <p class="menu-list-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
  </div>
</header>

<div class="main">
  <p>ログアウトしました</p>
  <p>またのご利用をお待ちしております</p>
  <a href="index.php">TOPページに戻る</a>
</div>
</body>

</html>