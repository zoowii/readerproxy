$(function () {
    $(".search-xiami-btn").click(function () {
        var $searchItem = $(this).parent(".search-item");
        var name = $searchItem.find('.search-field').val();
        if (name) {
            window.location.href = host + '/index.php/sites/xiami/search/' + name;
        }
    });

    $(document).on('click', '.add-to-play-list-btn', function () {
        var $tr = $(this).parents('tr');
        var name = $tr.find('td[data-name]').attr('data-name').trim();
        var artist = $tr.find('td[data-artist]').attr('data-artist').trim();
        var album = $tr.find('td[data-album]').attr('data-album').trim();
        var source = $tr.find('td[data-source]').attr('data-source').trim();
        var url = $tr.find('td[data-url]').attr('data-url').trim();
        var lyricUrl = $tr.find('td[data-lyric-url]').attr('data-lyric-url').trim();
        music_common.addToPlayList({
            name: name,
            artist: artist,
            album: album,
            source: source,
            url: url,
            lyricUrl: lyricUrl
        });
    });
});
