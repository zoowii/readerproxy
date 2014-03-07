$(function () {
    $(".search-xiami-btn").click(function () {
        var $searchItem = $(this).parent(".search-item");
        var name = $searchItem.find('.search-field').val();
        if (name) {
            window.location.href = host + '/index.php/sites/xiami/search/' + name;
        }
    });
});
