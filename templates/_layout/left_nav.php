<link rel="stylesheet" href="<?= $static_url('css/dashboard.css') ?>"/>
<link rel="stylesheet" href="<?= $static_url('css/styles.css') ?>"/>
<script>
    var host = 'http://' + window.location.host;
</script>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">我的小工具们</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">主页</a></li>
                <? if ($currentUser) { ?>
                    <li>
                        <a href="/index.php/profile">个人信息</a>
                    </li>
                    <li>
                        <a href="/index.php/logout">退出</a>
                    </li>
                <? } else { ?>
                    <li>
                        <a href="/index.php/login">登录</a>
                    </li>
                <? } ?>
                <li><a href="mailto:1992zhouwei@gmail.com">联系我</a></li>
            </ul>
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <? foreach ($cloudApps as $category => $apps) { ?>
                <ul class="nav nav-sidebar">
                    <? foreach ($apps as $app) { ?>

                        <li class="
                            <?= ($app->app_id === $currentAppId) ? 'active' : '' ?>
                            ">
                            <a href="<?= $app->url ?>" target="<?= $app->getLinkTarget() ?>"><?= $app->name ?></a>
                        </li>
                    <? } ?>
                </ul>
            <? } ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?= $content ?>
        </div>
    </div>
    <div class="mastfoot footer">
        <div class="inner">
            <p>&copy;2014 <a href="http://zoowii.com">zoowii</a>, code hosted <a
                    href="https://github.com/zoowii">@Github</a>.</p>
        </div>
    </div>
</div>
