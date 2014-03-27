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
        <tr>
            <td>Email:</td>
            <td><?= $user->email ?></td>
        </tr>
        <tr>
            <td>密码:</td>
            <td>
                <form method="POST" action="<?= $url_for(null, 'changePasswordAction') ?>">
                    <input type="password" name="password" placeholder="输入新密码"/>
                    <input value="修改密码" class="btn btn-primary btn-sm" type="submit"/>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
    <ul class="list-group">
        <li class="list-group-item"><b>绑定的第三方帐号:</b></li>
        <? foreach ($bindings as $binding) { ?>
            <li class="list-group-item"><?= $binding->source ?>帐号: <?= $binding->binding_id ?>
                ，绑定于<?= $binding->created_time ?></li>
        <? } ?>
    </ul>
</div>