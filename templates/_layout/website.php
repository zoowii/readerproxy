<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= isset($title) ? $title : '' ?></title>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?= $static_url('css/bootstrap.css') ?>"/>
    <link rel="stylesheet" href="<?= $static_url('css/bootstrap-theme.css') ?>"/>
    <script src="<?= $static_url('js/underscore.min.js') ?>"></script>
    <script src="<?= $static_url('js/jquery.min.js') ?>"></script>
    <script src="<?= $static_url('js/bootstrap.js') ?>"></script>
    <?php
    if (isset($header_resources)) {
        echo $header_resources;
    }
    ?>
</head>
<body>
<?= $content ?>
</body>
</html>