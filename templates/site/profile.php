<div class="container">
    <a href="/" class="btn btn-primary btn-sm">首页</a>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">个人信息</h3>
        </div>
        <div class="panel-body">
            用户<b><?= $user->getAliasName() ?></b>，您好！
        </div>
        <table class="table">
            <tbody>
            <tr>
                <td>用户名：</td>
                <td><?= $user->username ?></td>
            </tr>
            <tr>
                <td>昵称:</td>
                <td><?= $user->getAliasName() ?></td>
            </tr>
            <tr>
                <td>角色:</td>
                <td><?= $user->getRole()->getDisplayText() ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>