<script src="<?= $static_url('js/apps/tool_novel/novel_search.js') ?>"></script>
<div>
    <div class="search-item">
        <input type="search" class="search-field" placeholder="Search" value="红楼梦"/>
        <button class="btn btn-sm btn-info search-qishu-btn">搜索</button>
    </div>
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
                        <a href="<?= $url_for('QishuController', 'download') ?>?source=<?= urlencode($item['url']) ?>"
                           download="<?= $item['title'] ?>.rar">下载</a>
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
            $total = $pager['total'];
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
            <li>第<?= $currentPage ?>页，共<?= $total ?>条结果</li>
            <li class="next"><a href="?page=<?= $currentPage + 1 ?>">下一页 &rarr;</a></li>
        </ul>
    </div>
</div>