<script src="<?= $static_url('js/apps/tool_music/music_search.js') ?>"></script>
<div>
    <div class="search-item">
        <input class="search-field" type="search" placeholder="Search" value="<?= $search_keyword ?>"/>
        <button class="btn btn-sm btn-info search-xiami-btn">搜索</button>
    </div>
</div>
<div>
    <div>
        <table class="table table-bordered table-striped">
            <thead>
            <th>歌名</th>
            <th>艺人</th>
            <th>专辑</th>
            <th>来源</th>
            <th>下载</th>
            <th>在线试听</th>
            <th>歌词下载</th>
            <th>收藏</th>
            </thead>
            <tbody>
            <?php
            foreach ($result['data'] as $item) {
                ?>
                <tr>
                    <td>
                        <?= $item['name'] ?>
                    </td>
                    <td>
                        <?= $item['artist'] ?>
                    </td>
                    <td><?= $item['album'] ?></td>
                    <td><?= $item['source'] ?></td>
                    <td>
                        <a href="<?= $item['url'] ?>" download="<?= $item['name'] ?>.mp3">下载</a>
                    </td>
                    <td>
                        <a href="<?= $item['listen_url'] ?>" target="_blank">试听</a>
                    </td>
                    <td>
                        <a href="/index.php/sites/xiami/lyric/<?= $item['id'] ?>"
                           download="<?= $item['name'] ?>.lrc">下载歌词</a>
                    </td>
                    <td>
                        TODO
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