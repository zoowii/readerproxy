<div class="container">
    <h2 style="text-align: center;">来自<b><?= $source ?></b>的用户<b><?= $displayName ?></b>你好，请绑定到本网站用户</h2>
    <style>
        .bind-panel {
            float: left;
            width: 45%;
            margin: 20px;
        }
    </style>
    <div>
        <div class="panel panel-default bind-panel">
            <div class="panel-heading">
                <h3 class="panel-title">新建用户</h3>
            </div>
            <div class="panel-body">
                <form action="" method="POST">
                    <div>
                        <span>用户名:</span>
                        <input type="text" name="username" class="form-control" placeholder="用户名" required/>
                    </div>
                    <div>
                        <span>密码:</span>
                        <input type="password" name="password" class="form-control" placeholder="密码" required/>
                    </div>
                    <hr/>
                    <div>
                        <input class="btn btn-sm btn-primary" type="submit" value="新建用户并绑定"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-default bind-panel">
            <div class="panel-heading">
                <h3 class="panel-title">绑定到现有用户</h3>
            </div>
            <div class="panel-body">
                <form action="" method="POST">
                    <div>
                        <span>用户名:</span>
                        <input type="text" name="username" class="form-control" placeholder="用户名" required/>
                    </div>
                    <div>
                        <span>密码:</span>
                        <input type="password" name="password" class="form-control" placeholder="密码" required/>
                    </div>
                    <hr/>
                    <div>
                        <input class="btn btn-sm btn-primary" type="submit" value="绑定"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>