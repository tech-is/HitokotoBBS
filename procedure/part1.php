<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_TITLE ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body class="body-offcanvas bg-light">
    <?php require_once("parts/inc_header.php"); ?>
    <div class="container">
        <?php if( empty($_POST['btn_submit']) && !empty($_SESSION['success_message']) ): ?>
            <div class="alert alert-primary" role="alert">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if( !empty($error_message) ): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach( $error_message as $value ): ?>
                    ・<?php echo $value; ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="text1">表示名:</label>
                <input type="text" id="text1" class="form-control" name="view_name" value="">
            </div>
            <div class="form-group">
                <label for="textarea1">本文:</label>
                <textarea id="textarea1" class="form-control" name="message"></textarea>
            </div>
            <div class="text-center pb-3">
                <button type="submit" class="btn btn-info" name="btn_submit">送信する</button>
            </div>
        </form>
        <?php if( !empty($message_array) ){ ?>
            <?php foreach( $message_array as $value ){ ?>
                <div class="row py-3 my-2 bg-white">
                    <div class="col-sm-12">
                        <p class="h5"><?php echo $value['view_name']; ?> <span class="text-secondary h6"><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></span></p>
                        <p><?php echo nl2br($value['message']); ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>