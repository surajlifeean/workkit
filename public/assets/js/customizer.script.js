$(document).ready(function() {
    "use strict";
    
    var $appAdminWrap = $(".app-admin-wrap");
    var $html = $("html");
    var $body = $("body");
    var $customizer = $(".customizer");
    var $sidebarColor = $(".sidebar-colors a.color");

    // Change sidebar color
    $sidebarColor.on("click", function(e) {
        e.preventDefault();
        $appAdminWrap.removeClass(function(index, className) {
            return (className.match(/(^|\s)sidebar-\S+/g) || []).join(" ");
        });
        $appAdminWrap.addClass($(this).data("sidebar-class"));
        $sidebarColor.removeClass("active");
        $(this).addClass("active");
    });

    // Change Direction RTL/LTR
    $("#rtl-checkbox").change(function() {
        if (this.checked) {
            $html.attr("dir", "rtl");
        } else {
            $html.attr("dir", "ltr");
        }
    });

    // Dark version
    $("#dark-checkbox").change(function() {
        if (this.checked) {
            $body.addClass("dark-theme");
        } else {
            $body.removeClass("dark-theme");
        }
    });

    $("#moon").click(function() {
        $body.toggleClass("dark-theme");
    });

   $("#sun").click(function() {
        $body.toggleClass("dark-theme");
    });

    let $themeLink = $("#gull-theme");
  
    $(".bootstrap-colors .color").on("click", function(e) {
        e.preventDefault();
        let color = $(this).attr("title");
        console.log(color);
        let fileUrl = "assets/styles/css/themes/" + color + ".min.css";
        if (localStorage) {
            gullUtils.changeCssLink("gull-theme", fileUrl);
        } else {
            $themeLink.attr("href", fileUrl);
        }
    });

    // Toggle customizer
    $(".handle").on("click", function(e) {
        $customizer.toggleClass("open");
    });
});
