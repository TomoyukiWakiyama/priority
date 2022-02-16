<?php 

  // タイムゾーン設定
  date_default_timezone_set('Asia/Tokyo');

  // 変数の初期化
  $current_date = null;
  $message = array();
  $message_array = array();

  $success_message = null;
  $error_message = array();
  

    // DB接続
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_NAME', 'todolist');
  
    // DB <=> SQL接続
    $pdo = null;
    $stmt = null;
    $res = null;
    $option = null;
  
  session_start();
    
  /*==================================
      DB接続
  ==================================*/
  try {
    $option = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    );
    // データベースに接続
    $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
  } catch(PDOException $e){
    // 接続エラー時、エラー内容を取得格納する
    $error_message[] = $e -> getMessage();
  }

  /*==================================
        submit 'btn_submit' 実行
  ==================================*/
  require('./php/post.php');

  /*==================================
        submit 'btn_update' 実行
  ==================================*/
  require('./php/postUpdate.php');
  /*==================================
        submit 'btn_delete' 実行
  ==================================*/
  require('./php/postDelete.php');

/*==================================
      DB全件表示 実行
==================================*/
require('./php/allPost.php');

$pdo = null;
  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/style.css?<?php echo date('Ymd-His'); ?>">
</head>
<body>
<h1><a href="./index.php">お問い合わせフォーム</a></h1>
<div id="app">
  <?php if( !empty($_SESSION['post_success_message']) ): ?>
    <?php echo '<post-success-message></post-success-message>' ?>
    <?php unset($_SESSION['post_success_message']) ?>
  <?php endif; ?>
</div>

<?php if( !empty($error_message) ): ?>
  <ul class="error_message">
    <?php foreach( $error_message as $value ): ?>
      <li>・<?php echo $value; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form action="" method="post">
  <dl>
    <div class="form-item">
      <dt><label for="language" class="icon_dt">言語</label></dt>
      <dd>
        <label class="label-radio"><input id="language" type="radio" name="language" value="HTML" checked>HTML</label>
        <label class="label-radio"><input id="language" type="radio" name="language" value="JS">JS</label>
        <label class="label-radio"><input id="language" type="radio" name="language" value="PHP">PHP</label>
      </dd>
    </div>

    <div class="form-item">
      <dt><label for="title" class="icon_dt">気になっていることは？</label></dt>
      <dd><input type="text" id="title" name="title" value=""></dd>
            
    </div>

    <div class="form-item">
      <dt><label for="priority" class="icon_dt">優先順位</label></dt>
      <dd>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="3" checked>高</label>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="2">中</label>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="1">低</label>
      </dd>
    </div>

    <div class="form-item">
      <dt><label for="post" class="icon_dt">メタ認知</label></dt>
      <dd><textarea id="post" name="post"></textarea></dd>
    </div>
  </dl>

  <div class="form-submit">
    <input type="submit" name="btn_submit" value="保存する">
  </div>
  
</form>
<hr>
<div class="task_area">
  <?php if(!empty($message_array)): ?>
    <?php foreach($message_array as $value): ?>
      <article>
        <form action="" method="post">
          <div class="info">
            <h2 name="title" class="<?php if($value['priority'] == 3){ echo 'icon_hight'; } elseif($value['priority'] == 2){ echo 'icon_middle'; } elseif($value['priority'] == 1){ echo 'icon_low';}?>">
              <?php echo htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8') ?>
            </h2>
            
            <h3><?php echo $value['language'] ?></h3>
            <time><?php echo $value['created'] ?></time>
          </div>
          <p><?php echo nl2br(htmlspecialchars($value['post'], ENT_QUOTES, 'UTF-8')) ?></p>
          <div class="button_contents">
            <input type="button" onclick="location.href='./index.php'" class="button_update" name="btn_update" value="更新">
            <input type="submit" class="button_delete" name="btn_delete" value="削除">
            <input type="hidden" name="post_id" value="<?php echo $value['id']; ?>">
          </div>
        </form>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</div>


<!-- JS読み込み -->
<script>
  // 削除実行前の確認フォーム formにしか効かないから注意
  // onsubmit="return ask()"
  ask = ()=> {
    return confirm('投稿を削除しますか');
  }
</script>
<script src="https://unpkg.com/vue@next"></script>
<script src="./js/vue.js"></script>

</body>
</html>