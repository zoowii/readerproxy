(function () {
    var exports = this.music_common = {};

    function clearPlayList() {
        delete localStorage['music_playlist'];
    }

    function checkAndFixPlayList() {
        try {
            var tmp = JSON.parse(localStorage['music_playlist']);
            if (!_.isArray(tmp)) {
                clearPlayList();
            }
        } catch (e) {
            clearPlayList();
        }
    }

    function getPlayList() {
        checkAndFixPlayList();
        if (!localStorage['music_playlist'] || localStorage['music_playlist'] === '') {
            localStorage['music_playlist'] = JSON.stringify([]);
        }
        return JSON.parse(localStorage['music_playlist']);
    }

    function addToPlayList(song) {
        checkAndFixPlayList();
        var playlist = getPlayList();
        playlist.push(song);
        localStorage['music_playlist'] = JSON.stringify(playlist);
    }

    function removeSongFromPlayList(song) {
        // TODO
    }

    exports.addToPlayList = addToPlayList;
    exports.getPlayList = getPlayList;
    exports.removeSongFromPlayList = removeSongFromPlayList;
})();