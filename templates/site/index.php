<link rel="stylesheet" href="<?= $static_url('css/index.css') ?>"/>
<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">我的小工具们</h3>
                    <ul class="nav masthead-nav">
                        <li class="active"><a href="/">主页</a></li>
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
                </div>
            </div>

            <div class="inner cover">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">音乐搜索</h3>
                    </div>
                    <div class="panel-body">
                        <div class="search-item">
                            <input class="search-field" type="search" placeholder="Search" value="董贞"/>
                            <button class="btn btn-sm btn-info search-xiami-btn">搜索</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">小说搜索</h3>
                    </div>
                    <div class="panel-body">
                        <div class="search-item">
                            <input type="search" class="search-field" placeholder="Search" value="红楼梦"/>
                            <button class="btn btn-sm btn-info search-qishu-btn">搜索</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">股票查看</h3>
                    </div>
                    <div class="panel-body">
                        <a href="http://stockkit.sinaapp.com" target="_blank">股票查看网址</a>
                    </div>
                </div>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p>&copy;2014 <a href="http://zoowii.com">zoowii</a>, code hosted <a
                            href="https://github.com/zoowii">@Github</a>.</p>
                </div>
            </div>

        </div>

    </div>

</div>
<script>
    var host = 'http://' + window.location.host;
    $(function () {
        $(".search-qishu-btn").click(function () {
            var $searchItem = $(this).parent('.search-item');
            var name = $searchItem.find(".search-field").val();
            if (name) {
                window.location.href = host + '/index.php/sites/qishu/search/' + name;
            }
        });
        $(".search-xiami-btn").click(function () {
            var $searchItem = $(this).parent(".search-item");
            var name = $searchItem.find('.search-field').val();
            if (name) {
                window.location.href = host + '/index.php/sites/xiami/search/' + name;
            }
        });
    });
</script>
