<h2>奇书网（小说搜索）</h2>

<div class="search-item">
    <input type="search" class="search-field" placeholder="Search" value="红楼梦"/>
    <button class="btn search-qishu-btn">搜索</button>
</div>
<h2>虾米音乐</h2>
<div class="search-item">
    <input class="search-field" type="search" placeholder="Search" value="董贞"/>
    <button class="btn search-xiami-btn">搜索</button>
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
