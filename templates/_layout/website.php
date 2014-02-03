<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?php echo isset($title) ? $title : '' ?></title>
    <script src="<?php echo $static_url('js/underscore.min.js'); ?>"></script>
    <script src="<?php echo $static_url('js/jquery.min.js'); ?>"></script>
    <?php
    if (isset($header_resources)) {
        echo $header_resources;
    }
    ?>
</head>
<body>
<?php echo $content; ?>
</body>
</html>