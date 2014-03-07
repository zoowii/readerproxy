$(function () {
    $(".search-qishu-btn").click(function () {
        var $searchItem = $(this).parent('.search-item');
        var name = $searchItem.find(".search-field").val();
        if (name) {
            window.location.href = host + '/index.php/sites/qishu/search/' + name;
        }
    });
});