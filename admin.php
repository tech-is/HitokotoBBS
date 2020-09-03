<?php
/****************************************/
// ひとこと掲示板 管理画面
/****************************************/

// 定数ファイル読み込み
include_once("define.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();

//セッションの開始を宣言
session_start();

if( !empty($_GET['btn_logout']) ) {
    unset($_SESSION['admin_login']);
}

if( !empty($_POST['btn_submit']) ) {
    if( !empty($_POST['admin_password']) && $_POST['admin_password'] === PASSWORD ) {
            $_SESSION['admin_login'] = true;
        } else {
            $error_message[] = 'ログインに失敗しました。';
    }
}

// データベースに接続
$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if( $mysqli->connect_errno ) {
    $error_message[] = 'データの読み込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
} else {
    $sql = "SELECT id,view_name,message,post_date FROM message ORDER BY post_date DESC";
    $res = $mysqli->query($sql);
    if( $res ) {
        $message_array = $res->fetch_all(MYSQLI_ASSOC);
    }
    $mysqli->close();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_TITLE ?>管理ページ</title>
</head>
<body class="body-offcanvas bg-light">
    <header class="clearfix">
        <nav class="navbar navbar-expand-lg navbar-light bg-light pb-3">
            <a class="navbar-brand" href="#">管理者画面</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            参考リンク
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="http://www.htmq.com/" target="_blank">HTMLリファレンス</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://dev.mysql.com/doc/refman/5.6/ja/" target="_blank">Mysql リファレンス</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://www.php.net/manual/ja/langref.php" target="_blank">phpリファレンス</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://learning.techis.jp/" target="_blank">カリキュラム</a>
                    </li>
                </ul>
                <a class="btn btn-outline-success my-2 my-sm-0 mr-1" href="#" role="button">会員登録</a>
                <a class="btn btn-outline-secondary my-2 my-sm-0 mr-1" href="#" role="button">ログイン</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <?php if( !empty($error_message) ): ?>
            <?php foreach( $error_message as $value ): ?>
                    ・<?php echo $value; ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ): ?>
            <form method="get" action="./download.php">
            <div class="form-group">
                <select name="limit">
                    <option value="">全て</option>
                    <option value="10">10件</option>
                    <option value="30">30件</option>
                </select>
            </div>
            <div class="pb-3">
                <button type="submit" class="btn btn-info" name="btn_download">ダウンロード</button>
            </div>
            </form>
            <?php if( !empty($message_array) ){ ?>
                <?php foreach( $message_array as $value ){ ?>
                    <div class="row py-3 my-2 bg-white">
                        <div class="col-sm-12">
                            <p class="h5"><?php echo $value['view_name']; ?> <span class="text-secondary h6"><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></span></p>
                            <p><?php echo nl2br($value['message']); ?></p>
                            <p><a href="edit.php?message_id=<?php echo $value['id']; ?>">編集</a>&nbsp;&nbsp;<a href="delete.php?message_id=<?php echo $value['id']; ?>">削除</a></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
		<?php else: ?>
            <form method="post">
                <div>
                    <label for="admin_password">ログインパスワード</label>
                    <input id="admin_password" type="password" name="admin_password" value="">
                </div>
                <input type="submit" name="btn_submit" value="ログイン">
            </form>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>