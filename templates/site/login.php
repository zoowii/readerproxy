<link rel="stylesheet" href="<?= $static_url('css/signin.css') ?>"/>
<form class="form-signin" role="form" method="POST" action="/index.php/login">
    <h2 class="form-signin-heading">请登录</h2>
    <input type="username" name="username" class="form-control" placeholder="用户名" required autofocus>
    <input type="password" name="password" class="form-control" placeholder="密码" required>
    <label class="checkbox">
        <input type="checkbox" value="remember-me"> 记住我
        <a href="">用QQ帐号登录</a>
    </label>
    <input class="btn btn-lg btn-primary btn-block" type="submit" value="登录" />
</form>