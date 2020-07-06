<?php
require('../dbconnect.php');

session_start();

if(!empty($_POST)){
  //エラー項目の確認
  if($_POST['name'] == ''){
    $error['name'] = 'blank';
  }
  if($_POST['email'] == ''){
    $error['email'] = 'blank';
  }
  if(strlen($_POST['password'] < 4)){
    $error['password'] = 'length';
  }
  if($_POST['password'] == ''){
    $error['password'] = 'blank';
  }

  if(empty($error)){
    $_SESSION['join'] = $_POST;
    header('Location: check.php');
    exit();
  }

  // 重複アカウントのチェック
  if(empty($error)){
    $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE
    email=?');
    $member->execute(array($_POST['email']));
    $record = $member->fetch();
    if($record['cnt'] > 0){
      $error['email'] = 'duplicate';
    }
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
  <h1 class="main-title">Banmeshiアカウントを作成</h1>
  <div>
    <form action="" method="post">
      <div class="register">
        <p class="register-text"><span class="required">*</span>ニックネームを入力してください</p>
        <input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" class="register-input">
        <?php if($error['name'] == 'blank'): ?>
        <p class="error">ニックネームを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="register">
        <p class="register-text"><span class="required">*</span>メールアドレスを入力してください</p>
        <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" class="register-input">
        <?php if($error['email'] == 'blank'): ?>
        <p class="error">メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if($error['email'] == 'duplicate'): ?>
        <p class="error">指定されたメールアドレスは既に登録されています</p>
        <?php endif; ?>
      </div>
      <div class="register">
        <p class="register-text"><span class="required">*</span>パスワードを入力してください</p>
        <input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" class="register-input">
        <?php if($error['password'] == 'blank'): ?>
        <p class="error">パスワードを入力してください</p>
        <?php endif; ?>
        <?php if($error['password'] == 'length'): ?>
        <p class="error">パスワードは4文字以上で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="register">
        <input type="submit" value="確認する" class="register-submit">
      </div>
    </form>
  </div>
</div>
</body>

</html>