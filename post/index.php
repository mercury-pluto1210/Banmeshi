<?php
session_start();
require('../dbconnect.php');

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
  // エラー項目の確認
  if($_POST['menu_name'] == ''){
    $error['menu_name'] = 'blank';
  }
  $fileName = $_FILES['image']['name'];
  if(!empty($fileName)){
    $ext = substr($fileName, -3);
    if($ext != 'jpg' && $ext != 'gif'){
      $error['image'] = 'type';
    }
  }

  if(empty($error)){
    // 画像をアップロードする
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../picture/'. $image);

    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');
    exit();
  }
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
  <h1 class="main-title">Banmeshiメニューを投稿</h1>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="cookpost">
      <p class="cookpost-text"><span class="required">*</span>メニューの名前</p>
      <input type="text" name="menu_name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['menu_name'], ENT_QUOTES); ?>" class="cookpost-input">
      <?php if($error['menu_name'] == 'blank'): ?>
      <p class="error">メニューの名前を入力してください</p>
      <?php endif; ?>
    </div>
    <div class="cookpost">
      <p class="cookpost-text">材料を入力してください</p>
      <textarea name="ingredient" id="" cols="30" rows="10" class="cookpost-textarea"><?php echo htmlspecialchars($_POST['ingredient'], ENT_QUOTES); ?></textarea>
    </div>
    <div class="cookpost">
      <p class="cookpost-text">レシピを入力してください</p>
      <textarea name="recipe" id="" cols="30" rows="10" class="cookpost-textarea"><?php echo htmlspecialchars($_POST['recipe'], ENT_QUOTES); ?></textarea>
    </div>
    <div class="cookpost">
      <p class="cookpost-text">一言メモを入力してください</p>
      <textarea name="memo" id="" cols="30" rows="10" class="cookpost-textarea"><?php echo htmlspecialchars($_POST['memo'], ENT_QUOTES); ?></textarea>
    </div>
    <div class="cookpost">
      <p class="cookpost-text">写真を選んでください</p>
      <label for="id-img">
        <span class="cookpost-choicefile">写真を選択</span>
        <input id="id-img" type="file" name="image" class="cookpost-choicefile-hidden">
      </label>
      <?php if($error['image'] == 'type'): ?>
      <p class="error">写真は「.gif」または「.jpg」の画像を指定してください</p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="error">恐れ入りますが、画像を改めて指定してください</p>
      <?php endif; ?>
    </div>
    <div class="cookpost">
      <input type="submit" value="投稿を確認する" class="cookpost-submit">
    </div>
  </form>
</div>
</body>
</html>