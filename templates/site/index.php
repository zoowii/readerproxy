<h2>奇书网</h2>

<div>
    <input type="text" class="search-field" placeholder="Search"/>
    <button class="btn search-qishu-btn">搜索</button>
</div>
<script>
    var host = 'http://' + window.location.host;
    $(function () {
        $(".search-qishu-btn").click(function () {
            var name = $(".search-field").val();
            if (name) {
                window.location.href = host + '/index.php/sites/qishu/search/' + name;
            }
        })
    });
</script>