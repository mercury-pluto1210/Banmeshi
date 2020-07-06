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

$posts = $db->prepare('SELECT m.name, p.* FROM members m, posts p
WHERE m.id=p.dinner_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));

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
  <script>
    function submitChk(){
      /* 確認ダイアログ表示 */
      var flag = confirm('削除してもよろしいですか？\n\n削除したくない場合は[キャンセル]ボタンを押してください');
      /* send_flagがTRUEなら削除、FALSEなら削除しない */
      return flag;
    }
  </script>


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
    <div class="main-block">
      <?php if($post = $posts->fetch()): ?>
        <div class="detail">
          <p class="detail-name"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>さんの投稿</p>
        </div>
        <div class="detail">
          <p class="detail-menu_name"><?php echo htmlspecialchars($post['menu_name'], ENT_QUOTES); ?></p>
        </div>
        <?php if(substr($post['picture'], -3) == 'jpg' || substr($post['picture'], -3) == 'gif'): ?>
          <div class="detail-plus">
            <img src="picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" alt="<?php htmlspecialchars($post['menu_name'], ENT_QUOTES); ?>" class="detail-img">
          </div>
        <?php endif; ?>
        <div class="detail-plus">
          <table class="detail-table">
            <tr>
              <th class="detail-table-cell cell-title">メニューの名前</th>
              <td class="detail-table-cell cell-text"><?php echo htmlspecialchars($post['menu_name'], ENT_QUOTES); ?></td>
            </tr>
            <?php if(isset($post['ingredient'])): ?>
              <tr>
                <th class="detail-table-cell cell-title">材料</th>
                <td class="detail-table-cell cell-text"><?php echo htmlspecialchars($post['ingredient'], ENT_QUOTES); ?></td>
              </tr>
            <?php endif; ?>
            <?php if(isset($post['recipe'])): ?>
              <tr>
                <th class="detail-table-cell cell-title">レシピ</th>
                <td class="detail-table-cell cell-text"><?php echo htmlspecialchars($post['recipe'], ENT_QUOTES); ?></td>
              </tr>
            <?php endif; ?>
            <tr>
              <th class="detail-table-cell cell-title">一言メモ</th>
              <td class="detail-table-cell cell-text"><?php echo mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",
              '<a href="\1\2">\1\2</a>', htmlspecialchars($post['memo'], ENT_QUOTES)); ?></td>
            </tr>
            <tr>
              <th class="detail-table-cell cell-title">投稿日</th>
              <td class="detail-table-cell cell-text"><?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></td>
            </tr>
          </table>
        </div>
      <?php else: ?>
        <p>その投稿は削除されたか、URLが間違えています</p>
      <?php endif; ?>
      <?php if($_SESSION['id'] == $post['dinner_id']): ?>
        <div class="detail">
          <form action="delete.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>"
          method="post" onsubmit="return submitChk()" class="detail-delete">
            <input type="submit" value="削除する">
          </form>
        </div>
      <?php endif; ?>
      <div class="detail return-display">
        <a href="postdisplay.php">一覧に戻る</a>
      </div>
    </div>
  </div>
</body>

</html>