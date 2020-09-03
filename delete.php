<?php
/****************************************/
// ひとこと掲示板 削除
/****************************************/

// 定数ファイル読み込み
include_once("define.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$error_message = array();
$message_data = array();

//セッションの開始を宣言
session_start();

if( empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true ) {
    // ログインページへリダイレクト
	header("Location: ./admin.php");
}

if( !empty($_GET['message_id']) && empty($_POST['message_id']) ) {
	$message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);
	// データベースに接続
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);
	// 接続エラーの確認
	if( $mysqli->connect_errno ) {
		$error_message[] = 'データベースの接続に失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
	} else {
		// データの読み込み
		$sql = "SELECT * FROM message WHERE id = $message_id";
		$res = $mysqli->query($sql);
		if( $res ) {
			$message_data = $res->fetch_assoc();
		} else {
			// データが読み込めなかったら一覧に戻る
			header("Location: ./admin.php");
		}
		$mysqli->close();
	}
} elseif( !empty($_POST['message_id']) ) {
	$message_id = (int)htmlspecialchars( $_POST['message_id'], ENT_QUOTES);
	// データベースに接続
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);
	// 接続エラーの確認
	if( $mysqli->connect_errno ) {
		$error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		$sql = "DELETE FROM message WHERE id = $message_id";
		$res = $mysqli->query($sql);
	}
	$mysqli->close();
	
	// 更新に成功したら一覧に戻る
	if( $res ) {
		header("Location: ./admin.php");
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= SITE_TITLE ?>管理ページ（投稿の削除）</title>
</head>
<body class="body-offcanvas bg-light">
<header class="clearfix">
        <nav class="navbar navbar-expand-lg navbar-light bg-light pb-3">
            <a class="navbar-brand" href="#">ひとこと掲示板</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <!--
                    <li class="nav-item active">
                        <a class="nav-link" href="#">ホーム <span class="sr-only">(current)</span></a>
                    </li>
                    -->
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
		<h4><?= SITE_TITLE ?> 管理ページ（投稿の編集）</h3>
		<?php if( !empty($error_message) ): ?>
			<ul class="error_message">
				<?php foreach( $error_message as $value ): ?>
					<li>・<?php echo $value; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<form method="post">
			<div class="form-group">
				<label for="text1">表示名:</label>
				<input type="text" id="text1" class="form-control" name="view_name" value="<?php if( !empty($message_data['view_name']) ){ echo $message_data['view_name']; } ?>">
			</div>
			<div class="form-group">
				<label for="textarea1">ひと言メッセージ:</label>
				<textarea id="textarea1" name="message" class="form-control"><?php if( !empty($message_data['message']) ){ echo $message_data['message']; } ?></textarea>
			</div>
			<div class="text-center pb-3">
				<a class="btn_cancel" href="admin.php">キャンセル</a>
				<button type="submit" class="btn btn-info" name="btn_submit">削除する</button>
				<input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
			</div>
	</form>
	</div>
</body>
</html>