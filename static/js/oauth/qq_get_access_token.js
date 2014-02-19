$(function () {
    var hash = window.location.hash.trim();
    if (hash.length > 0 && hash[0] === '#') {
        hash = hash.substr(1);
        var args = hash.split('&');
        var hashParams = {};
        for (var i = 0; i < args.length; ++i) {
            var arg = args[i];
            var tmp1 = arg.split('=');
            if (tmp1.length > 1) {
                hashParams[tmp1[0]] = tmp1[1];
            }
        }
        hashParams.type = 'qq';
        var getOpenIdUrl = 'https://graph.qq.com/oauth2.0/me';
        window.callback = function (json) {
            $.post('/index.php/oauth/login', _.extend({}, hashParams, json), function (json) {
                console.log(json);
                if (json.success) {
                    window.location.href = json.data;
                } else {
                    alert(json.data);
                    window.location.href = '/';
                }
            }, 'json');
        };
        $.jsonp({
            url: getOpenIdUrl + '?access_token=' + hashParams['access_token']
        });
    }
});