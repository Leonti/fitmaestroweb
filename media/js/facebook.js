window.fbAsyncInit = function() {
    FB.init({
        appId  : '124862400943074',
        status : true, // check login status
        cookie : true, // enable cookies to allow the server to access the session
        xfbml  : true,  // parse XFBML
        oauth : true //enables OAuth 2.0
    });

    $('#fb-login').click(function() {
        FB.login(function(response) {
            if (response.authResponse) {
                window.location = "../user/login";
            }
        }, { scope: 'email' });

        return false;
    });   
};

(function() {
var e = document.createElement('script');
e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
e.async = true;
document.getElementById('fb-root').appendChild(e);
}());


