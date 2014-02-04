<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?= isset($title) ? $title : '' ?></title>
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