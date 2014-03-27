<script src="<?= $static_url('js/apps/tool_music/music_common.js') ?>"></script>
<script src="<?= $static_url('js/apps/tool_music/music_search.js') ?>"></script>
<div>
    <div class="search-item">
        <input class="search-field" type="search" placeholder="Search" value="<?= $search_keyword ?>"/>
        <button class="btn btn-sm btn-info search-xiami-btn">搜索</button>
        <button class="btn btn-sm btn-primary download-all-btn">全部下载</button>
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
            <th>存储</th>
            </thead>
            <tbody>
            <?php
            foreach ($result['data'] as $item) {
                ?>
                <tr>
                    <td data-name="<?= $item['name'] ?>">
                        <?= $item['name'] ?>
                    </td>
                    <td data-artist="<?= $item['artist'] ?>">
                        <?= $item['artist'] ?>
                    </td>
                    <td data-album="<?= $item['album'] ?>"><?= $item['album'] ?></td>
                    <td data-source="<?= $item['source'] ?>"><?= $item['source'] ?></td>
                    <td data-url="<?= $item['url'] ?>">
                        <a href="<?= $item['url'] ?>" download="<?= $item['name'] ?>.mp3">下载</a>
                    </td>
                    <td>
                        <button class="btn btn-sm add-to-play-list-btn">加入播放列表</button>
                    </td>
                    <td data-lyric-url="<?= $url_for('XiamiController', 'downloadLyricAction', $item['id']) ?>">
                        <a href="<?= $url_for('XiamiController', 'downloadLyricAction', $item['id']) ?>"
                           download="<?= $item['name'] ?>.lrc">下载歌词</a>
                    </td>
                    <td>
                        TODO
                    </td>
                    <td>
                        <a href="/index.php/music/store/async?guid=<?= $item['guid'] ?>"
                           class="btn btn-sm btn-success store-btn">存储</a>
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