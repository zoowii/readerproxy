<link rel="stylesheet" href="<?= $static_url('css/signin.css') ?>"/>
<div class="flash-messages" style="margin: 0 auto; width: 40%;">
    <? if ($successMsg) { ?>
        <div class="alert alert-success"><?= $successMsg ?></div>
    <? } ?>
    <? if ($infoMsg) { ?>
        <div class="alert alert-info"><?= $infoMsg ?></div>
    <? } ?>
    <? if ($warningMsg) { ?>
        <div class="alert alert-warning"><?= $warningMsg ?></div>
    <? } ?>
    <? if ($errorMsg) { ?>
        <div class="alert alert-danger"><?= $errorMsg ?></div>
    <? } ?>
</div>
<form class="form-signin" role="form" method="POST" action="<?= $url_for(null, 'registerAction') ?>">
    <h2 class="form-signin-heading">注册帐号</h2>
    <input type="username" name="username" class="form-control" placeholder="用户名" required autofocus>
    <input type="password" name="password" class="form-control" placeholder="密码" required>
    <label class="checkbox">
        <a href="<?= $url_for(null, 'loginPageAction') ?>">已有帐号？登录</a>
        <span>或者</span>
        <a href="/index.php/auth/qq/login">
            <img src="<?= $static_url('qq/Connect_logo_1.png') ?>" alt="QQ帐号登录"/>
            登录
        </a>
    </label>
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="注册"/>
</form>