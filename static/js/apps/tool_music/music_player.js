$(function () {
    var $playlist = $(".playlist");
    var $player = $(".music-player");
    var $lyricArea = $(".lyric-area");
    var player = $player[0];
    $playlist.html('');
    var playlist = music_common.getPlayList();
    for (var i = 0; i < playlist.length; ++i) {
        var song = playlist[i];
        var $el = $('<a href="javascript: void(0)" class="list-group-item song-list-item"></a>');
        $el.text(song.name);
        $el.attr('data-url', song.url);
        $el.attr('data-lyric-url', song.lyricUrl);
        if (i < 1) {
            $el.addClass('active');
            playMusic({url: song.url, lyricUrl: song.lyricUrl});
        }
        $playlist.append($el);
    }
    function loadLyric(lyricUrl) {
        $.post('/index.php/sites/cross_proxy', {
            url: host + lyricUrl,
            method: 'GET',
            params: {}
        }, function (data) {
            data = data.replace(/\n/g, '<br/>');
            $lyricArea.html(data);
        }, 'html');
    }

    function playMusic(song) {
        $player.attr('src', song.url);
        player.play();
        loadLyric(song.lyricUrl);
    }

    $(document).on('click', '.song-list-item', function () {
        if ($(this).hasClass('active')) {
            return;
        }
        $('.song-list-item.active').removeClass('active');
        $(this).addClass('active');
        playMusic({url: $(this).attr('data-url'), lyricUrl: $(this).attr('data-lyric-url')});
    });
    $(document).on('dblclick', '.song-list-item', function () {
        $(this).remove();
        // TODO: remove the song from the stored playlist
    });
});