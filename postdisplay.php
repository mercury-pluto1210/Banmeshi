<?php
session_start();
require('./dbconnect.php');

$members = $db->prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($_SESSION['id']));
$member = $members->fetch();

$posts = $db->query('SELECT m.name, p.* FROM members m, posts p
WHERE m.id=p.dinner_id ORDER BY p.created DESC');
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
    <?php if(isset($member['name'])): ?>
      <!-- ログインしている -->
      <p class="menu-list-text"><a href="mypage.php"><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、ようこそ</p>
    <?php else: ?>
      <!-- ログインしていない -->
      <p class="menu-list-text"><a href="http://localhost/Banmeshi/join/" class="menu-list-button">会員登録</a></p>
      <p class="menu-list-text"><a href="http://localhost/Banmeshi/login.php" class="menu-list-button">ログイン</a></p>
    <?php endif; ?>
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
  <div class="alldisplay">
    <?php foreach($posts as $post): ?>
      <div class="alldisplay-block">
        <p class="alldisplay-block-name"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>さんの投稿</p>
      </div>
      <div class="alldisplay-block">
        <p class="alldisplay-block-menu_name"><?php echo htmlspecialchars($post['menu_name'], ENT_QUOTES); ?></p>
      </div>
      <?php if(substr($post['picture'], -3) == 'jpg' || substr($post['picture'], -3) == 'gif'): ?>
        <div class="alldisplay-block">
          <img src="./picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($post['menu_name'], ENT_QUOTES); ?>" class="alldisplay-block-img">
        </div>
      <?php endif; ?>
      <div class="alldisplay-block">
        <p><?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></p>
      </div>
      <a href="view.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">詳細を見る</a>
      <hr>
    <?php endforeach; ?>
  </div>
</div>
</body>

</html>