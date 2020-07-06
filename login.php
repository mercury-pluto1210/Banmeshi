<?php
require('dbconnect.php');

session_start();

if($_COOKIE['name'] != ''){
  $_POST['name'] = $_COOKIE['name'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}

if(!empty($_POST)){
  // ログインの処理
  if($_POST['name'] != '' && $_POST['password'] != ''){
    $login = $db->prepare('SELECT * FROM members WHERE name=? AND 
    password=?');
    $login->execute(array(
      $_POST['name'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if($member){
      // ログイン成功
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      // ログイン情報を記録する
      if($_POST['save'] == 'on'){
        setcookie('name', $_POST['name'], time()+60*60*24*14);
        setcookie('password', $_POST['password'], time()+60*60*24*14);
      }

      header('Location: index.php');
      exit();
    }
    else{
      $error['login'] = 'failed';
    }
  }
  else{
    $error['login'] = 'blank';
  }
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
    <a href="http://localhost/Banmeshi/" class="menu-list-button icon"><img src="./img/Banmeshi_icon.png" alt="Banmeshi" class="icon-img"></a>
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
      <a href="http://localhost/Banmeshi/"><img src="./img/Banmeshi_icon.png" alt="Banmeshi" class="icon-img-responsive"></a>
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/postdisplay.php" class="menu-list-button">投稿一覧</a></p>
      <p class="menu-link-text"><a href="http://localhost/Banmeshi/post/" class="menu-list-button">投稿する</a></p>
      <?php if(isset($member['name'])): ?>
        <!-- ログインしている -->
        <p class="menu-link-text"><a href="mypage.php"><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、ようこそ</p>
        <?php else: ?>
      <!-- ログインしていない -->
        <p class="menu-link-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
        <p class="menu-link-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
      <?php endif; ?>
    </div>
  </div>
</header>

<div class="main">
  <h1 class="main-title">Banmeshiにログインする</h1>
  <div>
    <form action="" method="post">
      <div class="login">
        <p class="login-text">ニックネーム</p>
        <input type="text" name="name" size="35" maxlength="255" class="login-input" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>">
        <?php if($error['login'] == 'blank'): ?>
        <p class="error">ニックネームとパスワードをご記入ください</p>
        <?php endif; ?>
        <?php if($error['login'] == 'failed'): ?>
        <p class="error">ログインに失敗しました。正しくご記入ください。</p>
        <?php endif; ?>
      </div>
      <div class="login">
        <p class="login-text">パスワード</p>
        <input type="password" name="password" size="35" maxlength="255" class="login-input" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>">
      </div>
      <div>
        <div class="login-checkbox">
          <input type="checkbox" name="save" id="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </div>
        <input type="submit" value="ログインする" class="login-submit">
      </div>
    </form>
  </div>
</div>
</body>