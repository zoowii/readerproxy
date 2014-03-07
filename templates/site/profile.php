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