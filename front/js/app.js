
var app = {
    init: function () {
        console.log('init');
        $('#js-button-navigation-open').on('click', app.openNavigation)
        $('#js-button-navigation-close').on('click', app.closeNavigation)
    },

    openNavigation: function () {


        var iWindowsSize = $(window).width();
        if (iWindowsSize  <= 1024 && iWindowsSize >=300){
            document.getElementById("mySidenav").style.width = "100%";
        } else {
            document.getElementById("mySidenav").style.width = "20%";
        }
    },

    closeNavigation: function () {
        document.getElementById("mySidenav").style.width = "0";
    },



}
$(app.init);