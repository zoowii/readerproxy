<div class="container">
    <div>
        <a class="btn btn-primary" href="/index.php/">回到首页</a>
        <hr/>
    </div>
    <div>
        <div>
            <table class="table table-bordered table-striped">
                <tbody>
                <?php
                foreach ($result['data'] as $item) {
                    ?>
                    <tr>
                        <td>
                            <?= $item['title'] ?>
                        </td>
                        <td>
                            <?= $item['description'] ?>
                        </td>
                        <td>
                            <a href="<?= $item['url'] ?>">原站地址</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div>
            <ul class="pager">
                <?php
                $pager = $result['paginator'];
                $currentPage = $pager['current'];
                if ($currentPage <= 1) {
                    ?>
                    <li class="previous disabled"><a href="?page=1">&larr; 上一页</a></li>
                <?php
                } else {
                    ?>
                    <li class="previous"><a href="?page=<?= $currentPage - 1 ?>">&larr; 上一页</a></li>
                <?php
                }
                ?>
                <li><?= $currentPage ?></li>
                <li class="next"><a href="?page=<?= $currentPage + 1 ?>">下一页 &rarr;</a></li>
            </ul>
        </div>
    </div>
</div>
