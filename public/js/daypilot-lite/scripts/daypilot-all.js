/*
DayPilot Lite
Copyright (c) 2005 - 2021 Annpoint s.r.o.
http://www.daypilot.org/
Licensed under Apache Software License 2.0
Version: 2021.1.248-lite
*/
if (typeof DayPilot === 'undefined') {
    var DayPilot = {};
};
(function() {
    if (typeof DayPilot.$ !== 'undefined') {
        return;
    };
    if (typeof DayPilot.Global === "undefined") {
        DayPilot.Global = {};
    };
    DayPilot.$ = function(id) {
        return document.getElementById(id);
    };
    DayPilot.isKhtml = (navigator && navigator.userAgent && navigator.userAgent.indexOf("KHTML") !== -1);
    DayPilot.isIE = (navigator && navigator.userAgent && navigator.userAgent.indexOf("MSIE") !== -1);
    DayPilot.mo2 = function($a, ev) {
        ev = ev || window.event;
        if (typeof(ev.offsetX) !== 'undefined') {
            var $b = {
                x: ev.offsetX + 1,
                y: ev.offsetY + 1
            };
            if (!$a) {
                return $b;
            };
            var $c = ev.srcElement;
            while ($c && $c !== $a) {
                if ($c.tagName !== 'SPAN') {
                    $b.x += $c.offsetLeft;
                    if ($c.offsetTop > 0) {
                        $b.y += $c.offsetTop - $c.scrollTop;
                    }
                };
                $c = $c.offsetParent;
            };
            if ($c) {
                return $b;
            };
            return null;
        };
        if (typeof(ev.layerX) !== 'undefined') {
            var $b = {
                x: ev.layerX,
                y: ev.layerY,
                $d: ev.target
            };
            if (!$a) {
                return $b;
            };
            var $c = ev.target;
            while ($c && $c.style.position !== 'absolute' && $c.style.position !== 'relative') {
                $c = $c.parentNode;
                if (DayPilot.isKhtml) {
                    $b.y += $c.scrollTop;
                }
            }
            while ($c && $c !== $a) {
                $b.x += $c.offsetLeft;
                $b.y += $c.offsetTop - $c.scrollTop;
                $c = $c.offsetParent;
            };
            if ($c) {
                return $b;
            };
            return null;
        };
        return null;
    };
    DayPilot.mo3 = function($a, ev, $e) {
        ev = ev || window.event;
        if (typeof(ev.pageX) !== 'undefined') {
            var $f = DayPilot.abs($a, $e);
            var $b = {
                x: ev.pageX - $f.x,
                y: ev.pageY - $f.y
            };
            return $b;
        };
        return DayPilot.mo2($a, ev);
    };
    DayPilot.browser = {};
    DayPilot.browser.ie9 = (function() {
        var $g = document.createElement("div");
        $g.innerHTML = "<!--[if IE 9]><i></i><![endif]-->";
        var $h = ($g.getElementsByTagName("i").length === 1);
        return $h;
    })();
    DayPilot.browser.ielt9 = (function() {
        var $g = document.createElement("div");
        $g.innerHTML = "<!--[if lt IE 9]><i></i><![endif]-->";
        var $h = ($g.getElementsByTagName("i").length === 1);
        return $h;
    })();
    DayPilot.abs = function(element, $i) {
        if (!element) {
            return null;
        };
        var r = {
            x: element.offsetLeft,
            y: element.offsetTop,
            w: element.clientWidth,
            h: element.clientHeight,
            toString: function() {
                return "x:" + this.x + " y:" + this.y + " w:" + this.w + " h:" + this.h;
            }
        };
        if (element.getBoundingClientRect) {
            var b = element.getBoundingClientRect();
            r.x = b.left;
            r.y = b.top;
            var d = DayPilot.doc();
            r.x -= d.clientLeft || 0;
            r.y -= d.clientTop || 0;
            var $j = DayPilot.pageOffset();
            r.x += $j.x;
            r.y += $j.y;
            if ($i) {
                var $k = DayPilot.absOffsetBased(element, false);
                var $i = DayPilot.absOffsetBased(element, true);
                r.x += $i.x - $k.x;
                r.y += $i.y - $k.y;
                r.w = $i.w;
                r.h = $i.h;
            };
            return r;
        } else {
            return DayPilot.absOffsetBased(element, $i);
        }
    };
    DayPilot.absOffsetBased = function(element, $i) {
        var r = {
            x: element.offsetLeft,
            y: element.offsetTop,
            w: element.clientWidth,
            h: element.clientHeight,
            toString: function() {
                return "x:" + this.x + " y:" + this.y + " w:" + this.w + " h:" + this.h;
            }
        };
        while (element.offsetParent) {
            element = element.offsetParent;
            r.x -= element.scrollLeft;
            r.y -= element.scrollTop;
            if ($i) {
                if (r.x < 0) {
                    r.w += r.x;
                    r.x = 0;
                };
                if (r.y < 0) {
                    r.h += r.y;
                    r.y = 0;
                };
                if (element.scrollLeft > 0 && r.x + r.w > element.clientWidth) {
                    r.w -= r.x + r.w - element.clientWidth;
                };
                if (element.scrollTop && r.y + r.h > element.clientHeight) {
                    r.h -= r.y + r.h - element.clientHeight;
                }
            };
            r.x += element.offsetLeft;
            r.y += element.offsetTop;
        };
        var $j = DayPilot.pageOffset();
        r.x += $j.x;
        r.y += $j.y;
        return r;
    };
    DayPilot.isArray = function(o) {
        return Object.prototype.toString.call(o) === '[object Array]';
    };
    DayPilot.sheet = function() {
        var style = document.createElement("style");
        style.setAttribute("type", "text/css");
        if (!style.styleSheet) {
            style.appendChild(document.createTextNode(""));
        };
        var h = document.head || document.getElementsByTagName('head')[0];
        h.appendChild(style);
        var $l = !!style.styleSheet;
        var $m = {};
        $m.rules = [];
        $m.commit = function() {
            if ($l) {
                style.styleSheet.cssText = this.rules.join("\n");
            }
        };
        $m.add = function($n, $o, $p) {
            if ($l) {
                this.rules.push($n + "{" + $o + "\u007d");
                return;
            };
            if (style.sheet.insertRule) {
                if (typeof $p === "undefined") {
                    $p = style.sheet.cssRules.length;
                };
                style.sheet.insertRule($n + "{" + $o + "\u007d", $p);
            } else if (style.sheet.addRule) {
                style.sheet.addRule($n, $o, $p);
            }
        };
        return $m;
    };
    (function() {
        if (DayPilot.Global.defaultCss) {
            return;
        };
        var $m = DayPilot.sheet();
        $m.add(".calendar_default_main", "border: 1px solid #c0c0c0; font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px;");
        $m.add(".calendar_default_main *, .calendar_default_main *:before, .calendar_default_main *:after", "box-sizing: content-box;");
        $m.add(".calendar_default_rowheader_inner,.calendar_default_cornerright_inner,.calendar_default_corner_inner,.calendar_default_colheader_inner,.calendar_default_alldayheader_inner", "color: #333;background: #f3f3f3;");
        $m.add(".calendar_default_cornerright_inner", "position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;	border-bottom: 1px solid #c0c0c0;");
        $m.add(".calendar_default_direction_rtl .calendar_default_cornerright_inner", "border-right: 1px solid #c0c0c0;");
        $m.add(".calendar_default_rowheader_inner", "font-size: 16pt;text-align: right; position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0; padding: 3px;");
        $m.add(".calendar_default_direction_rtl .calendar_default_rowheader_inner", "border-right: none;");
        $m.add(".calendar_default_corner_inner", "position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;");
        $m.add(".calendar_default_direction_rtl .calendar_default_corner_inner", "border-right: none;");
        $m.add(".calendar_default_rowheader_minutes", "font-size:10px;vertical-align: super;padding-left: 2px;padding-right: 2px;");
        $m.add(".calendar_default_colheader_inner", "position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0; display: flex; align-items: center; justify-content: center;");
        $m.add(".calendar_default_cell_inner", "position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #ddd;border-bottom: 1px solid #ddd; background: #f9f9f9;");
        $m.add(".calendar_default_cell_business .calendar_default_cell_inner", "background: #fff");
        $m.add(".calendar_default_alldayheader_inner", "text-align: center;position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;");
        $m.add(".calendar_default_message", "opacity: 0.9; padding: 10px; color: #ffffff;background: #ffa216;");
        $m.add(".calendar_default_alldayevent_inner,.calendar_default_event_inner", 'color: #666; border: 1px solid #999;');
        $m.add(".calendar_default_event_bar", "top: 0px;bottom: 0px;left: 0px;width: 4px;background-color: #9dc8e8;");
        $m.add(".calendar_default_event_bar_inner", "position: absolute;width: 4px;background-color: #1066a8;");
        $m.add(".calendar_default_alldayevent_inner,.calendar_default_event_inner", 'background: #fff;background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee));background: -webkit-linear-gradient(top, #ffffff 0%, #eeeeee);background: -moz-linear-gradient(top, #ffffff 0%, #eeeeee);background: -ms-linear-gradient(top, #ffffff 0%, #eeeeee);background: -o-linear-gradient(top, #ffffff 0%, #eeeeee);background: linear-gradient(top, #ffffff 0%, #eeeeee);filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr="#ffffff", endColorStr="#eeeeee");');
        $m.add(".calendar_default_selected .calendar_default_event_inner", "background: #ddd;");
        $m.add(".calendar_default_alldayevent_inner", "position: absolute;top: 2px;bottom: 2px;left: 2px;right: 2px;overflow:hidden;padding: 2px;margin-right: 1px;font-size: 12px;");
        $m.add(".calendar_default_event_withheader .calendar_default_event_inner", "padding-top: 15px;");
        $m.add(".calendar_default_event", "cursor: default;");
        $m.add(".calendar_default_event_inner", "position: absolute;overflow: hidden;top: 0px;bottom: 0px;left: 0px;right: 0px;padding: 2px 2px 2px 6px;font-size: 12px;");
        $m.add(".calendar_default_shadow_inner", "position:absolute;top:0px;left:0px;right:0px;bottom:0px;background-color: #666666; opacity: 0.5;");
        $m.add(".calendar_default_event_delete", "background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAAI5JREFUKFNtkLERgCAMRbmzdK8s4gAUlhYOYEHJEJYOYOEwDmGBPxC4kOPfvePy84MGR0RJ2N1A8H3N6DATwSQ57m2ql8NBG+AEM7D+UW+wjdfUPgerYNgB5gOLRHqhcasg84C2QxPMtrUhSqQIhg7ypy9VM2EUZPI/4rQ7rGxqo9sadTegw+UdjeDLAKUfhbaQUVPIfJYAAAAASUVORK5CYII=) center center no-repeat; opacity: 0.6; cursor: pointer;");
        $m.add(".calendar_default_event_delete:hover", "opacity: 1;-ms-filter: none;");
        $m.add(".calendar_default_scroll_up", "background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAB3RJTUUH2wESDiYcrhwCiQAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAARnQU1BAACxjwv8YQUAAACcSURBVHjaY2AgF9wWsTW6yGMlhi7OhC7AyMDQzMnBXIpFHAFuCtuaMTP+P8nA8P/b1x//FfW/HHuF1UQmxv+NUP1c3OxMVVhNvCVi683E8H8LXOY/w9+fTH81tF8fv4NiIpBRj+YoZtZ/LDUoJmKYhsVUpv0MDiyMDP96sIYV0FS2/8z9ICaLlOhvS4b/jC//MzC8xBG0vJeF7GQBlK0xdiUzCtsAAAAASUVORK5CYII=);");
        $m.add(".calendar_default_scroll_down", "background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAALiMAAC4jAXilP3YAAACqSURBVChTY7wpam3L9J+xmQEP+PGPKZZxP4MDi4zI78uMDIwa2NT+Z2DYovrmiC+TI8OBP/8ZmEqwGvif4e8vxr+FIDkmEKH25vBWBgbG0+iK/zEwLtF+ffwOXCGI8Y+BoRFFIdC030x/WmBiYBNhpgLdswNJ8RSYaSgmgk39z1gPUfj/29ef/9rwhQTDHRHbrbdEbLvRFcGthkkAra/9/uMvhkK8piNLAgCRpTnNn4AEmAAAAABJRU5ErkJggg==);");
        $m.add(".calendar_default_now", "background-color: red;");
        $m.add(".calendar_default_now:before", "content: ''; top: -5px; border-width: 5px; border-color: transparent transparent transparent red; border-style: solid; width: 0px; height:0px; position: absolute; -moz-transform: scale(.9999);");
        $m.add(".calendar_default_shadow_forbidden .calendar_default_shadow_inner", "background-color: red;");
        $m.add(".calendar_default_shadow_top", 'box-sizing: border-box; padding:2px;border:1px solid #ccc;background:#fff;background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee));background: -webkit-linear-gradient(top, #ffffff 0%, #eeeeee);background: -moz-linear-gradient(top, #ffffff 0%, #eeeeee);background: -ms-linear-gradient(top, #ffffff 0%, #eeeeee);background: -o-linear-gradient(top, #ffffff 0%, #eeeeee);background: linear-gradient(top, #ffffff 0%, #eeeeee);filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr="#ffffff", endColorStr="#eeeeee");');
        $m.add(".calendar_default_shadow_bottom", 'box-sizing: border-box; padding:2px;border:1px solid #ccc;background:#fff;background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee));background: -webkit-linear-gradient(top, #ffffff 0%, #eeeeee);background: -moz-linear-gradient(top, #ffffff 0%, #eeeeee);background: -ms-linear-gradient(top, #ffffff 0%, #eeeeee);background: -o-linear-gradient(top, #ffffff 0%, #eeeeee);background: linear-gradient(top, #ffffff 0%, #eeeeee);filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr="#ffffff", endColorStr="#eeeeee");');
        $m.add(".calendar_default_crosshair_vertical, .calendar_default_crosshair_horizontal, .calendar_default_crosshair_left, .calendar_default_crosshair_top", "background-color: gray; opacity: 0.2;");
        $m.add(".calendar_default_loading", "background-color: orange; color: white; padding: 2px;");
        $m.add(".calendar_default_scroll", "background-color: #f3f3f3;");
        $m.add(".scheduler_default_selected .scheduler_default_event_inner", "background: #ddd;");
        $m.add(".scheduler_default_main", "border: 1px solid #c0c0c0;font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px;");
        $m.add(".scheduler_default_timeheader", "cursor: default;color: #333;");
        $m.add(".scheduler_default_message", "opacity: 0.9;filter: alpha(opacity=90);padding: 10px; color: #ffffff;background: #ffa216;");
        $m.add(".scheduler_default_timeheadergroup,.scheduler_default_timeheadercol", "color: #333;background: #f3f3f3;");
        $m.add(".scheduler_default_rowheader,.scheduler_default_corner", "color: #333;background: #f3f3f3;");
        $m.add(".scheduler_default_rowheader_inner", "position: absolute;left: 0px;right: 0px;top: 0px;bottom: 0px;border-right: 1px solid #eee;padding: 2px;");
        $m.add(".scheduler_default_timeheadergroup, .scheduler_default_timeheadercol", "text-align: center;");
        $m.add(".scheduler_default_timeheadergroup_inner", "position: absolute;left: 0px;right: 0px;top: 0px;bottom: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;");
        $m.add(".scheduler_default_timeheadercol_inner", "position: absolute;left: 0px;right: 0px;top: 0px;bottom: 0px;border-right: 1px solid #c0c0c0;");
        $m.add(".scheduler_default_divider", "background-color: #c0c0c0;");
        $m.add(".scheduler_default_divider_horizontal", "background-color: #c0c0c0;");
        $m.add(".scheduler_default_matrix_vertical_line", "background-color: #eee;");
        $m.add(".scheduler_default_matrix_vertical_break", "background-color: #000;");
        $m.add(".scheduler_default_matrix_horizontal_line", "background-color: #eee;");
        $m.add(".scheduler_default_resourcedivider", "background-color: #c0c0c0;");
        $m.add(".scheduler_default_shadow_inner", "background-color: #666666;opacity: 0.5;filter: alpha(opacity=50);height: 100%;xborder-radius: 5px;");
        $m.add(".scheduler_default_event", "font-size:12px;color:#666;");
        $m.add(".scheduler_default_event_inner", "position:absolute;top:0px;left:0px;right:0px;bottom:0px;padding:5px 2px 2px 2px;overflow:hidden;border:1px solid #ccc;");
        $m.add(".scheduler_default_event_bar", "top:0px;left:0px;right:0px;height:4px;background-color:#9dc8e8;");
        $m.add(".scheduler_default_event_bar_inner", "position:absolute;height:4px;background-color:#1066a8;");
        $m.add(".scheduler_default_event_inner", 'background:#fff;background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee));background: -webkit-linear-gradient(top, #ffffff 0%, #eeeeee);background: -moz-linear-gradient(top, #ffffff 0%, #eeeeee);background: -ms-linear-gradient(top, #ffffff 0%, #eeeeee);background: -o-linear-gradient(top, #ffffff 0%, #eeeeee);background: linear-gradient(top, #ffffff 0%, #eeeeee);filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr="#ffffff", endColorStr="#eeeeee");');
        $m.add(".scheduler_default_event_float_inner", "padding:6px 2px 2px 8px;");
        $m.add(".scheduler_default_event_float_inner:after", 'content:"";border-color: transparent #666 transparent transparent;border-style:solid;border-width:5px;width:0;height:0;position:absolute;top:8px;left:-4px;');
        $m.add(".scheduler_default_columnheader_inner", "font-weight: bold;");
        $m.add(".scheduler_default_columnheader_splitter", "background-color: #666;opacity: 0.5;filter: alpha(opacity=50);");
        $m.add(".scheduler_default_columnheader_cell_inner", "padding: 2px;");
        $m.add(".scheduler_default_cell", "background-color: #f9f9f9;");
        $m.add(".scheduler_default_cell.scheduler_default_cell_business", "background-color: #fff;");
        $m.add(".navigator_default_main", "border-left: 1px solid #c0c0c0;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;background-color: white;color: #000000; box-sizing: content-box;");
        $m.add(".navigator_default_main *, .navigator_default_main *:before, .navigator_default_main *:after", "box-sizing: content-box;");
        $m.add(".navigator_default_month", "font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 11px;");
        $m.add(".navigator_default_day", "color: black;");
        $m.add(".navigator_default_weekend", "background-color: #f0f0f0;");
        $m.add(".navigator_default_dayheader", "color: black;");
        $m.add(".navigator_default_line", "border-bottom: 1px solid #c0c0c0;");
        $m.add(".navigator_default_dayother", "color: gray;");
        $m.add(".navigator_default_todaybox", "border: 1px solid red;");
        $m.add(".navigator_default_title, .navigator_default_titleleft, .navigator_default_titleright", 'border-top: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;color: #333;background: #f3f3f3;');
        $m.add(".navigator_default_busy", "font-weight: bold;");
        $m.add(".navigator_default_cell", "text-align: center;");
        $m.add(".navigator_default_select .navigator_default_cell_box", "background-color: #FFE794; opacity: 0.5;");
        $m.add(".navigator_default_title", "text-align: center;");
        $m.add(".navigator_default_titleleft, .navigator_default_titleright", "text-align: center;");
        $m.add(".navigator_default_dayheader", "text-align: center;");
        $m.add(".navigator_default_weeknumber", "text-align: center;");
        $m.add(".navigator_default_cell_text", "cursor: pointer;");
        $m.add(".month_default_main", "border: 1px solid #c0c0c0;font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px;color: #333;");
        $m.add(".month_default_main *, .month_default_main *:before, .month_default_main *:after", "box-sizing: content-box;");
        $m.add(".month_default_cell_inner", "border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;background-color: #f9f9f9;");
        $m.add(".month_default_cell_business .month_default_cell_inner", "background-color: #fff;");
        $m.add(".month_default_cell_header", "text-align: right; padding: 4px; box-sizing: border-box;");
        $m.add(".month_default_header_inner", 'position: absolute;top: 0px;left: 0px;bottom: 0px;right: 0px;border-right: 1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;cursor: default;color: #333;background: #f3f3f3; overflow:hidden; display: flex; align-items: center; justify-content: center;');
        $m.add(".month_default_message", 'padding: 10px;opacity: 0.9; color: #ffffff;background: #ffa216;');
        $m.add(".month_default_event_inner", 'position: absolute;top: 0px;bottom: 0px;left: 1px;right: 1px;overflow:hidden;padding: 2px;padding-left: 5px;font-size: 12px;color: #333;background: #fff;background: linear-gradient(to bottom, #ffffff 0%, #eeeeee);border: 1px solid #999;border-radius: 0px;display: flex; align-items: center;');
        $m.add(".month_default_event_continueright .month_default_event_inner", "border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-right-style: dotted;");
        $m.add(".month_default_event_continueleft .month_default_event_inner", "border-top-left-radius: 0px;border-bottom-left-radius: 0px;border-left-style: dotted;");
        $m.add(".month_default_selected .month_default_event_inner", "background: #ddd;");
        $m.add(".month_default_shadow_inner", "background-color: #666666;opacity: 0.5;height: 100%;");
        $m.add(".month_default_event_delete", "background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAAI5JREFUKFNtkLERgCAMRbmzdK8s4gAUlhYOYEHJEJYOYOEwDmGBPxC4kOPfvePy84MGR0RJ2N1A8H3N6DATwSQ57m2ql8NBG+AEM7D+UW+wjdfUPgerYNgB5gOLRHqhcasg84C2QxPMtrUhSqQIhg7ypy9VM2EUZPI/4rQ7rGxqo9sadTegw+UdjeDLAKUfhbaQUVPIfJYAAAAASUVORK5CYII=) center center no-repeat; opacity: 0.6; cursor: pointer;");
        $m.add(".month_default_event_delete:hover", "opacity: 1;-ms-filter: none;");
        $m.add(".month_default_event_timeleft", "color: #ccc; font-size: 8pt");
        $m.add(".month_default_event_timeright", "color: #ccc; font-size: 8pt; text-align: right;");
        $m.add(".month_default_loading", "background-color: orange; color: white; padding: 2px;");
        $m.commit();
        DayPilot.Global.defaultCss = true;
    })();
    DayPilot.doc = function() {
        var de = document.documentElement;
        return (de && de.clientHeight) ? de : document.body;
    };
    DayPilot.guid = function() {
        var S4 = function() {
            return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        };
        return ("" + S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
    };
    DayPilot.pageOffset = function() {
        if (typeof pageXOffset !== 'undefined') {
            return {
                x: pageXOffset,
                y: pageYOffset
            };
        };
        var d = DayPilot.doc();
        return {
            x: d.scrollLeft,
            y: d.scrollTop
        };
    };
    DayPilot.indexOf = function($q, $r) {
        if (!$q || !$q.length) {
            return -1;
        };
        for (var i = 0; i < $q.length; i++) {
            if ($q[i] === $r) {
                return i;
            }
        };
        return -1;
    };
    DayPilot.mc = function(ev) {
        if (ev.pageX || ev.pageY) {
            return {
                x: ev.pageX,
                y: ev.pageY
            };
        };
        return {
            x: ev.clientX + document.documentElement.scrollLeft,
            y: ev.clientY + document.documentElement.scrollTop
        };
    };
    DayPilot.Stats = {};
    DayPilot.Stats.eventObjects = 0;
    DayPilot.Stats.dateObjects = 0;
    DayPilot.Stats.cacheHitsCtor = 0;
    DayPilot.Stats.cacheHitsParsing = 0;
    DayPilot.Stats.cacheHitsTicks = 0;
    DayPilot.Stats.print = function() {
        console.log("DayPilot.Stats.eventObjects: " + DayPilot.Stats.eventObjects);
        console.log("DayPilot.Stats.dateObjects: " + DayPilot.Stats.dateObjects);
        console.log("DayPilot.Stats.cacheHitsCtor: " + DayPilot.Stats.cacheHitsCtor);
        console.log("DayPilot.Stats.cacheHitsParsing: " + DayPilot.Stats.cacheHitsParsing);
        console.log("DayPilot.Stats.cacheHitsTicks: " + DayPilot.Stats.cacheHitsTicks);
        console.log("DayPilot.Date.Cache.Ctor keys: " + Object.keys(DayPilot.Date.Cache.Ctor).length);
        console.log("DayPilot.Date.Cache.Parsing keys: " + Object.keys(DayPilot.Date.Cache.Parsing).length);
    };
    DayPilot.re = function(el, ev, $s) {
        if (el.addEventListener) {
            el.addEventListener(ev, $s, false);
        } else if (el.attachEvent) {
            el.attachEvent("on" + ev, $s);
        }
    };
    DayPilot.pu = function(d) {
        var a = d.attributes,
            i, l, n;
        if (a) {
            l = a.length;
            for (i = 0; i < l; i += 1) {
                if (!a[i]) {
                    continue;
                };
                n = a[i].name;
                if (typeof d[n] === 'function') {
                    d[n] = null;
                }
            }
        };
        a = d.childNodes;
        if (a) {
            l = a.length;
            for (i = 0; i < l; i += 1) {
                var $t = DayPilot.pu(d.childNodes[i]);
            }
        }
    };
    DayPilot.de = function(e) {
        if (!e) {
            return;
        };
        e.parentNode && e.parentNode.removeChild(e);
    };
    DayPilot.sw = function(element) {
        if (!element) {
            return 0;
        };
        return element.offsetWidth - element.clientWidth;
    };
    DayPilot.am = function() {
        if (typeof angular === "undefined") {
            return null;
        };
        if (!DayPilot.am.cached) {
            DayPilot.am.cached = angular.module("daypilot", []);
        };
        return DayPilot.am.cached;
    };
    DayPilot.Selection = function($u, end, $v, $w) {
        this.type = 'selection';
        this.start = $u.isDayPilotDate ? $u : new DayPilot.Date($u);
        this.end = end.isDayPilotDate ? end : new DayPilot.Date(end);
        this.resource = $v;
        this.root = $w;
        this.toJSON = function($x) {
            var $y = {};
            $y.start = this.start;
            $y.end = this.end;
            $y.resource = this.resource;
            return $y;
        };
    };
    DayPilot.request = function($z, $A, $B, $C) {
        var $D = DayPilot.createXmlHttp();
        if (!$D) {
            return;
        };
        $D.open("POST", $z, true);
        $D.setRequestHeader('Content-type', 'text/plain');
        $D.onreadystatechange = function() {
            if ($D.readyState !== 4) return;
            if ($D.status !== 200 && $D.status !== 304) {
                if ($C) {
                    $C($D);
                } else {
                    if (window.console) {
                        console.log('HTTP error ' + $D.status);
                    }
                };
                return;
            };
            $A($D);
        };
        if ($D.readyState === 4) {
            return;
        };
        if (typeof $B === 'object') {
            $B = JSON.stringify($B);
        };
        $D.send($B);
    };
    DayPilot.ajax = function($E) {
        if (!$E) {
            throw new DayPilot.Exception("Parameter object required.");
        };
        if (typeof $E.url !== "string") {
            throw new DayPilot.Exception("The parameter object must have 'url' property.")
        };
        var $D = DayPilot.createXmlHttp();
        if (!$D) {
            throw new DayPilot.Exception("Unable to create XMLHttpRequest object");
        };
        var $F = typeof $E.data === "object";
        var $G = $E.data;
        var $H = $E.method || ($E.data ? "POST" : "GET");
        var $I = $E.success || function() {};
        var $J = $E.error || function() {};
        var $z = $E.url;
        var $K = $E.contentType || ($F ? "application/json" : "text/plain");
        var $L = $E.headers || {};
        $D.open($H, $z, true);
        $D.setRequestHeader('Content-type', $K);
        DayPilot.Util.ownPropsAsArray($L).forEach(function($M) {
            $D.setRequestHeader($M.key, $M.val);
        });
        $D.onreadystatechange = function() {
            if ($D.readyState !== 4) {
                return;
            };
            if ($D.status !== 200 && $D.status !== 201 && $D.status !== 204 && $D.status !== 304) {
                if ($J) {
                    var $N = {};
                    $N.request = $D;
                    $J($N);
                } else {
                    if (window.console) {
                        console.log('HTTP error ' + $D.status);
                    }
                };
                return;
            };
            var $N = {};
            $N.request = $D;
            if ($D.responseText) {
                $N.data = JSON.parse($D.responseText);
            };
            $I($N);
        };
        if ($D.readyState === 4) {
            return;
        };
        if ($F) {
            $G = JSON.stringify($G);
        };
        $D.send($G);
    };
    DayPilot.createXmlHttp = function() {
        return new XMLHttpRequest();
    };
    DayPilot.Http = {};
    DayPilot.Http.ajax = function($E) {
        DayPilot.ajax($E);
    };
    DayPilot.Util = {};
    DayPilot.Util.addClass = function($r, name) {
        if (!$r) {
            return;
        };
        if (!$r.className) {
            $r.className = name;
            return;
        };
        var $O = new RegExp("(^|\\s)" + name + "($|\\s)");
        if (!$O.test($r.className)) {
            $r.className = $r.className + ' ' + name;
        }
    };
    DayPilot.Util.removeClass = function($r, name) {
        if (!$r) {
            return;
        };
        var $O = new RegExp("(^|\\s)" + name + "($|\\s)");
        $r.className = $r.className.replace($O, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    };
    DayPilot.Util.ownPropsAsArray = function($r) {
        var $h = [];
        if (!$r) {
            return $h;
        };
        for (var name in $r) {
            if ($r.hasOwnProperty(name)) {
                var $M = {};
                $M.key = name;
                $M.val = $r[name];
                $h.push($M);
            }
        };
        return $h;
    };
    DayPilot.Util.isNullOrUndefined = function($P) {
        return $P === null || typeof $P === "undefined";
    };
    DayPilot.Exception = function($Q) {
        return new Error($Q);
    };
    DayPilot.Locale = function(id, $R) {
        this.id = id;
        this.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        this.dayNamesShort = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
        this.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        this.datePattern = "M/d/yyyy";
        this.timePattern = "H:mm";
        this.dateTimePattern = "M/d/yyyy H:mm";
        this.timeFormat = "Clock12Hours";
        this.weekStarts = 0;
        if ($R) {
            for (var name in $R) {
                this[name] = $R[name];
            }
        }
    };
    DayPilot.Locale.all = {};
    DayPilot.Locale.find = function(id) {
        if (!id) {
            return null;
        };
        var $S = id.toLowerCase();
        if ($S.length > 2) {
            $S[2] = '-';
        };
        return DayPilot.Locale.all[$S];
    };
    DayPilot.Locale.register = function($T) {
        DayPilot.Locale.all[$T.id] = $T;
    };
    DayPilot.Locale.register(new DayPilot.Locale('ca-es', {
        'dayNames': ['diumenge', 'dilluns', 'dimarts', 'dimecres', 'dijous', 'divendres', 'dissabte'],
        'dayNamesShort': ['dg', 'dl', 'dt', 'dc', 'dj', 'dv', 'ds'],
        'monthNames': ['gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre', ''],
        'monthNamesShort': ['gen.', 'febr.', 'març', 'abr.', 'maig', 'juny', 'jul.', 'ag.', 'set.', 'oct.', 'nov.', 'des.', ''],
        'timePattern': 'H:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('cs-cz', {
        'dayNames': ['neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota'],
        'dayNamesShort': ['ne', 'po', 'út', 'st', 'čt', 'pá', 'so'],
        'monthNames': ['leden', 'únor', 'březen', 'duben', 'květen', 'červen', 'červenec', 'srpen', 'září', 'říjen', 'listopad', 'prosinec', ''],
        'monthNamesShort': ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', ''],
        'timePattern': 'H:mm',
        'datePattern': 'd. M. yyyy',
        'dateTimePattern': 'd. M. yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('da-dk', {
        'dayNames': ['søndag', 'mandag', 'tirsdag', 'onsdag', 'torsdag', 'fredag', 'lørdag'],
        'dayNamesShort': ['sø', 'ma', 'ti', 'on', 'to', 'fr', 'lø'],
        'monthNames': ['januar', 'februar', 'marts', 'april', 'maj', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'december', ''],
        'monthNamesShort': ['jan', 'feb', 'mar', 'apr', 'maj', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd-MM-yyyy',
        'dateTimePattern': 'dd-MM-yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('de-at', {
        'dayNames': ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        'dayNamesShort': ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        'monthNames': ['Jänner', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember', ''],
        'monthNamesShort': ['Jän', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('de-ch', {
        'dayNames': ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        'dayNamesShort': ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        'monthNames': ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('de-de', {
        'dayNames': ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        'dayNamesShort': ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        'monthNames': ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('de-lu', {
        'dayNames': ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        'dayNamesShort': ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        'monthNames': ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('en-au', {
        'dayNames': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'dayNamesShort': ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        'monthNames': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ''],
        'timePattern': 'h:mm tt',
        'datePattern': 'd/MM/yyyy',
        'dateTimePattern': 'd/MM/yyyy h:mm tt',
        'timeFormat': 'Clock12Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('en-ca', {
        'dayNames': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'dayNamesShort': ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        'monthNames': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ''],
        'timePattern': 'h:mm tt',
        'datePattern': 'yyyy-MM-dd',
        'dateTimePattern': 'yyyy-MM-dd h:mm tt',
        'timeFormat': 'Clock12Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('en-gb', {
        'dayNames': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'dayNamesShort': ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        'monthNames': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('en-us', {
        'dayNames': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'dayNamesShort': ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        'monthNames': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
        'monthNamesShort': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ''],
        'timePattern': 'h:mm tt',
        'datePattern': 'M/d/yyyy',
        'dateTimePattern': 'M/d/yyyy h:mm tt',
        'timeFormat': 'Clock12Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('es-es', {
        'dayNames': ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
        'dayNamesShort': ['D', 'L', 'M', 'X', 'J', 'V', 'S'],
        'monthNames': ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre', ''],
        'monthNamesShort': ['ene.', 'feb.', 'mar.', 'abr.', 'may.', 'jun.', 'jul.', 'ago.', 'sep.', 'oct.', 'nov.', 'dic.', ''],
        'timePattern': 'H:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('es-mx', {
        'dayNames': ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
        'dayNamesShort': ['do.', 'lu.', 'ma.', 'mi.', 'ju.', 'vi.', 'sá.'],
        'monthNames': ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre', ''],
        'monthNamesShort': ['ene.', 'feb.', 'mar.', 'abr.', 'may.', 'jun.', 'jul.', 'ago.', 'sep.', 'oct.', 'nov.', 'dic.', ''],
        'timePattern': 'hh:mm tt',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy hh:mm tt',
        'timeFormat': 'Clock12Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('eu-es', {
        'dayNames': ['igandea', 'astelehena', 'asteartea', 'asteazkena', 'osteguna', 'ostirala', 'larunbata'],
        'dayNamesShort': ['ig', 'al', 'as', 'az', 'og', 'or', 'lr'],
        'monthNames': ['urtarrila', 'otsaila', 'martxoa', 'apirila', 'maiatza', 'ekaina', 'uztaila', 'abuztua', 'iraila', 'urria', 'azaroa', 'abendua', ''],
        'monthNamesShort': ['urt.', 'ots.', 'mar.', 'api.', 'mai.', 'eka.', 'uzt.', 'abu.', 'ira.', 'urr.', 'aza.', 'abe.', ''],
        'timePattern': 'H:mm',
        'datePattern': 'yyyy/MM/dd',
        'dateTimePattern': 'yyyy/MM/dd H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('fi-fi', {
        'dayNames': ['sunnuntai', 'maanantai', 'tiistai', 'keskiviikko', 'torstai', 'perjantai', 'lauantai'],
        'dayNamesShort': ['su', 'ma', 'ti', 'ke', 'to', 'pe', 'la'],
        'monthNames': ['tammikuu', 'helmikuu', 'maaliskuu', 'huhtikuu', 'toukokuu', 'kesäkuu', 'heinäkuu', 'elokuu', 'syyskuu', 'lokakuu', 'marraskuu', 'joulukuu', ''],
        'monthNamesShort': ['tammi', 'helmi', 'maalis', 'huhti', 'touko', 'kesä', 'heinä', 'elo', 'syys', 'loka', 'marras', 'joulu', ''],
        'timePattern': 'H:mm',
        'datePattern': 'd.M.yyyy',
        'dateTimePattern': 'd.M.yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('fr-be', {
        'dayNames': ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
        'dayNamesShort': ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
        'monthNames': ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre', ''],
        'monthNamesShort': ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd-MM-yy',
        'dateTimePattern': 'dd-MM-yy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('fr-ch', {
        'dayNames': ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
        'dayNamesShort': ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
        'monthNames': ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre', ''],
        'monthNamesShort': ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('fr-fr', {
        'dayNames': ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
        'dayNamesShort': ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
        'monthNames': ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre', ''],
        'monthNamesShort': ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('fr-lu', {
        'dayNames': ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
        'dayNamesShort': ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
        'monthNames': ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre', ''],
        'monthNamesShort': ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('gl-es', {
        'dayNames': ['domingo', 'luns', 'martes', 'mércores', 'xoves', 'venres', 'sábado'],
        'dayNamesShort': ['do', 'lu', 'ma', 'mé', 'xo', 've', 'sá'],
        'monthNames': ['xaneiro', 'febreiro', 'marzo', 'abril', 'maio', 'xuño', 'xullo', 'agosto', 'setembro', 'outubro', 'novembro', 'decembro', ''],
        'monthNamesShort': ['xan', 'feb', 'mar', 'abr', 'maio', 'xuño', 'xul', 'ago', 'set', 'out', 'nov', 'dec', ''],
        'timePattern': 'H:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('it-it', {
        'dayNames': ['domenica', 'lunedì', 'martedì', 'mercoledì', 'giovedì', 'venerdì', 'sabato'],
        'dayNamesShort': ['do', 'lu', 'ma', 'me', 'gi', 've', 'sa'],
        'monthNames': ['gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre', ''],
        'monthNamesShort': ['gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('it-ch', {
        'dayNames': ['domenica', 'lunedì', 'martedì', 'mercoledì', 'giovedì', 'venerdì', 'sabato'],
        'dayNamesShort': ['do', 'lu', 'ma', 'me', 'gi', 've', 'sa'],
        'monthNames': ['gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre', ''],
        'monthNamesShort': ['gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('ja-jp', {
        'dayNames': ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
        'dayNamesShort': ['日', '月', '火', '水', '木', '金', '土'],
        'monthNames': ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月', ''],
        'monthNamesShort': ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ''],
        'timePattern': 'H:mm',
        'datePattern': 'yyyy/MM/dd',
        'dateTimePattern': 'yyyy/MM/dd H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('nb-no', {
        'dayNames': ['søndag', 'mandag', 'tirsdag', 'onsdag', 'torsdag', 'fredag', 'lørdag'],
        'dayNamesShort': ['sø', 'ma', 'ti', 'on', 'to', 'fr', 'lø'],
        'monthNames': ['januar', 'februar', 'mars', 'april', 'mai', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'desember', ''],
        'monthNamesShort': ['jan', 'feb', 'mar', 'apr', 'mai', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'des', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('nl-nl', {
        'dayNames': ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
        'dayNamesShort': ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        'monthNames': ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december', ''],
        'monthNamesShort': ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'd-M-yyyy',
        'dateTimePattern': 'd-M-yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('nl-be', {
        'dayNames': ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
        'dayNamesShort': ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        'monthNames': ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december', ''],
        'monthNamesShort': ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec', ''],
        'timePattern': 'H:mm',
        'datePattern': 'd/MM/yyyy',
        'dateTimePattern': 'd/MM/yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('nn-no', {
        'dayNames': ['søndag', 'måndag', 'tysdag', 'onsdag', 'torsdag', 'fredag', 'laurdag'],
        'dayNamesShort': ['sø', 'må', 'ty', 'on', 'to', 'fr', 'la'],
        'monthNames': ['januar', 'februar', 'mars', 'april', 'mai', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'desember', ''],
        'monthNamesShort': ['jan', 'feb', 'mar', 'apr', 'mai', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'des', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('pt-br', {
        'dayNames': ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'],
        'dayNamesShort': ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        'monthNames': ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', ''],
        'monthNamesShort': ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('pl-pl', {
        'dayNames': ['niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota'],
        'dayNamesShort': ['N', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So'],
        'monthNames': ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień', ''],
        'monthNamesShort': ['sty', 'lut', 'mar', 'kwi', 'maj', 'cze', 'lip', 'sie', 'wrz', 'paź', 'lis', 'gru', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'yyyy-MM-dd',
        'dateTimePattern': 'yyyy-MM-dd HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('pt-pt', {
        'dayNames': ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'],
        'dayNamesShort': ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        'monthNames': ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', ''],
        'monthNamesShort': ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'dd/MM/yyyy',
        'dateTimePattern': 'dd/MM/yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 0
    }));
    DayPilot.Locale.register(new DayPilot.Locale('ro-ro', {
        'dayNames': ['duminică', 'luni', 'marți', 'miercuri', 'joi', 'vineri', 'sâmbătă'],
        'dayNamesShort': ['D', 'L', 'Ma', 'Mi', 'J', 'V', 'S'],
        'monthNames': ['ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie', 'iulie', 'august', 'septembrie', 'octombrie', 'noiembrie', 'decembrie', ''],
        'monthNamesShort': ['ian.', 'feb.', 'mar.', 'apr.', 'mai.', 'iun.', 'iul.', 'aug.', 'sep.', 'oct.', 'nov.', 'dec.', ''],
        'timePattern': 'H:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('ru-ru', {
        'dayNames': ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
        'dayNamesShort': ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        'monthNames': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь', ''],
        'monthNamesShort': ['янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек', ''],
        'timePattern': 'H:mm',
        'datePattern': 'dd.MM.yyyy',
        'dateTimePattern': 'dd.MM.yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('sk-sk', {
        'dayNames': ['nedeľa', 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota'],
        'dayNamesShort': ['ne', 'po', 'ut', 'st', 'št', 'pi', 'so'],
        'monthNames': ['január', 'február', 'marec', 'apríl', 'máj', 'jún', 'júl', 'august', 'september', 'október', 'november', 'december', ''],
        'monthNamesShort': ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ''],
        'timePattern': 'H:mm',
        'datePattern': 'd.M.yyyy',
        'dateTimePattern': 'd.M.yyyy H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('sv-se', {
        'dayNames': ['söndag', 'måndag', 'tisdag', 'onsdag', 'torsdag', 'fredag', 'lördag'],
        'dayNamesShort': ['sö', 'må', 'ti', 'on', 'to', 'fr', 'lö'],
        'monthNames': ['januari', 'februari', 'mars', 'april', 'maj', 'juni', 'juli', 'augusti', 'september', 'oktober', 'november', 'december', ''],
        'monthNamesShort': ['jan', 'feb', 'mar', 'apr', 'maj', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'yyyy-MM-dd',
        'dateTimePattern': 'yyyy-MM-dd HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('tr-tr', {
        'dayNames': ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'],
        'dayNamesShort': ['Pz', 'Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct'],
        'monthNames': ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık', ''],
        'monthNamesShort': ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara', ''],
        'timePattern': 'HH:mm',
        'datePattern': 'd.M.yyyy',
        'dateTimePattern': 'd.M.yyyy HH:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.register(new DayPilot.Locale('zh-cn', {
        'dayNames': ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        'dayNamesShort': ['日', '一', '二', '三', '四', '五', '六'],
        'monthNames': ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月', ''],
        'monthNamesShort': ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月', ''],
        'timePattern': 'H:mm',
        'datePattern': 'yyyy/M/d',
        'dateTimePattern': 'yyyy/M/d H:mm',
        'timeFormat': 'Clock24Hours',
        'weekStarts': 1
    }));
    DayPilot.Locale.US = DayPilot.Locale.find("en-us");
    DayPilot.Duration = function($U) {
        var d = this;
        var $V = 1000 * 60 * 60 * 24.0;
        var $W = 1000 * 60 * 60.0;
        var $X = 1000 * 60.0;
        var $Y = 1000.0;
        if (arguments.length === 2) {
            var $u = arguments[0];
            var end = arguments[1];
            if (!($u instanceof DayPilot.Date) && (typeof $u !== "string")) {
                throw "DayPilot.Duration(): Invalid start argument, DayPilot.Date expected";
            };
            if (!(end instanceof DayPilot.Date) && (typeof end !== "string")) {
                throw "DayPilot.Duration(): Invalid end argument, DayPilot.Date expected";
            };
            if (typeof $u === "string") {
                $u = new DayPilot.Date($u);
            };
            if (typeof end === "string") {
                end = new DayPilot.Date(end);
            };
            $U = end.getTime() - $u.getTime();
        };
        this.ticks = $U;
        if (DayPilot.Date.Cache.DurationCtor["" + $U]) {
            return DayPilot.Date.Cache.DurationCtor["" + $U];
        };
        DayPilot.Date.Cache.DurationCtor["" + $U] = this;
        this.toString = function($Z) {
            if (!$Z) {
                return d.days() + "." + d.hours() + ":" + d.minutes() + ":" + d.seconds() + "." + d.milliseconds();
            };
            var $00 = d.minutes();
            $00 = ($00 < 10 ? "0" : "") + $00;
            var $h = $Z;
            $h = $h.replace("mm", $00);
            $h = $h.replace("m", d.minutes());
            $h = $h.replace("H", d.hours());
            $h = $h.replace("h", d.hours());
            $h = $h.replace("d", d.days());
            $h = $h.replace("s", d.seconds());
            return $h;
        };
        this.totalHours = function() {
            return d.ticks / $W;
        };
        this.totalDays = function() {
            return d.ticks / $V;
        };
        this.totalMinutes = function() {
            return d.ticks / $X;
        };
        this.totalSeconds = function() {
            return d.ticks / $Y;
        };
        this.days = function() {
            return Math.floor(d.totalDays());
        };
        this.hours = function() {
            var $01 = d.ticks - d.days() * $V;
            return Math.floor($01 / $W);
        };
        this.minutes = function() {
            var $02 = d.ticks - Math.floor(d.totalHours()) * $W;
            return Math.floor($02 / $X);
        };
        this.seconds = function() {
            var $03 = d.ticks - Math.floor(d.totalMinutes()) * $X;
            return Math.floor($03 / $Y);
        };
        this.milliseconds = function() {
            return d.ticks % $Y;
        };
    };
    DayPilot.Duration.weeks = function(i) {
        return new DayPilot.Duration(i * 1000 * 60 * 60 * 24 * 7);
    };
    DayPilot.Duration.days = function(i) {
        return new DayPilot.Duration(i * 1000 * 60 * 60 * 24);
    };
    DayPilot.Duration.hours = function(i) {
        return new DayPilot.Duration(i * 1000 * 60 * 60);
    };
    DayPilot.Duration.minutes = function(i) {
        return new DayPilot.Duration(i * 1000 * 60);
    };
    DayPilot.Duration.seconds = function(i) {
        return new DayPilot.Duration(i * 1000);
    };
    DayPilot.TimeSpan = function() {
        throw "Please use DayPilot.Duration class instead of DayPilot.TimeSpan.";
    };
    try {
        DayPilot.TimeSpan.prototype = Object.create(DayPilot.Duration.prototype);
    } catch (e) {};
    DayPilot.Date = function($04, $05) {
        if ($04 instanceof DayPilot.Date) {
            return $04;
        };
        var $U;
        if (DayPilot.Util.isNullOrUndefined($04)) {
            $U = DayPilot.DateUtil.fromLocal().getTime();
            $04 = $U;
        };
        var $06 = DayPilot.Date.Cache.Ctor;
        if ($06[$04]) {
            DayPilot.Stats.cacheHitsCtor += 1;
            return $06[$04];
        };
        var $07 = false;
        if (typeof $04 === "string") {
            $U = DayPilot.DateUtil.fromStringSortable($04, $05).getTime();
            $07 = true;
        } else if (typeof $04 === "number") {
            if (isNaN($04)) {
                throw "Cannot create DayPilot.Date from NaN";
            };
            $U = $04;
        } else if ($04 instanceof Date) {
            if ($05) {
                $U = DayPilot.DateUtil.fromLocal($04).getTime();
            } else {
                $U = $04.getTime();
            }
        } else {
            throw "Unrecognized parameter: use Date, number or string in ISO 8601 format";
        };
        var $08 = ticksToSortable($U);
        if ($06[$08]) {
            return $06[$08];
        };
        $06[$08] = this;
        $06[$U] = this;
        if ($07 && $08 !== $04 && DayPilot.DateUtil.hasTzSpec($04)) {
            $06[$04] = this;
        };
        if (Object.defineProperty && !DayPilot.browser.ielt9) {
            Object.defineProperty(this, "ticks", {
                get: function() {
                    return $U;
                }
            });
            Object.defineProperty(this, "value", {
                "value": $08,
                "writable": false,
                "enumerable": true
            });
        } else {
            this.ticks = $U;
            this.value = $08;
        };
        if (DayPilot.Date.Config.legacyShowD) {
            this.d = new Date($U);
        };
        DayPilot.Stats.dateObjects += 1;
    };
    DayPilot.Date.Config = {};
    DayPilot.Date.Config.legacyShowD = false;
    DayPilot.Date.Cache = {};
    DayPilot.Date.Cache.Parsing = {};
    DayPilot.Date.Cache.Ctor = {};
    DayPilot.Date.Cache.Ticks = {};
    DayPilot.Date.Cache.DurationCtor = {};
    DayPilot.Date.Cache.clear = function() {
        DayPilot.Date.Cache.Parsing = {};
        DayPilot.Date.Cache.Ctor = {};
        DayPilot.Date.Cache.Ticks = {};
        DayPilot.Date.Cache.DurationCtor = {};
    };
    DayPilot.Date.prototype.addDays = function($09) {
        if (!$09) {
            return this;
        };
        return new DayPilot.Date(this.ticks + $09 * 24 * 60 * 60 * 1000);
    };
    DayPilot.Date.prototype.addHours = function($0a) {
        if (!$0a) {
            return this;
        };
        return this.addTime($0a * 60 * 60 * 1000);
    };
    DayPilot.Date.prototype.addMilliseconds = function($0b) {
        if (!$0b) {
            return this;
        };
        return this.addTime($0b);
    };
    DayPilot.Date.prototype.addMinutes = function($00) {
        if (!$00) {
            return this;
        };
        return this.addTime($00 * 60 * 1000);
    };
    DayPilot.Date.prototype.addMonths = function($0c) {
        if (!$0c) {
            return this;
        };
        var $04 = new Date(this.ticks);
        var y = $04.getUTCFullYear();
        var m = $04.getUTCMonth() + 1;
        if ($0c > 0) {
            while ($0c >= 12) {
                $0c -= 12;
                y++;
            };
            if ($0c > 12 - m) {
                y++;
                m = $0c - (12 - m);
            } else {
                m += $0c;
            }
        } else {
            while ($0c <= -12) {
                $0c += 12;
                y--;
            };
            if (m + $0c <= 0) {
                y--;
                m = 12 + m + $0c;
            } else {
                m = m + $0c;
            }
        };
        var d = new Date($04.getTime());
        d.setUTCDate(1);
        d.setUTCFullYear(y);
        d.setUTCMonth(m - 1);
        var $0d = new DayPilot.Date(d).daysInMonth();
        d.setUTCDate(Math.min($0d, $04.getUTCDate()));
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.addSeconds = function($0e) {
        if (!$0e) {
            return this;
        };
        return this.addTime($0e * 1000);
    };
    DayPilot.Date.prototype.addTime = function($U) {
        if (!$U) {
            return this;
        };
        if ($U instanceof DayPilot.Duration) {
            $U = $U.ticks;
        };
        return new DayPilot.Date(this.ticks + $U);
    };
    DayPilot.Date.prototype.addYears = function($0f) {
        var $0g = new Date(this.ticks);
        var d = new Date(this.ticks);
        var y = this.getYear() + $0f;
        var m = this.getMonth();
        d.setUTCDate(1);
        d.setUTCFullYear(y);
        d.setUTCMonth(m);
        var $0d = new DayPilot.Date(d).daysInMonth();
        d.setUTCDate(Math.min($0d, $0g.getUTCDate()));
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.dayOfWeek = function() {
        return new Date(this.ticks).getUTCDay();
    };
    DayPilot.Date.prototype.getDayOfWeek = function() {
        return new Date(this.ticks).getUTCDay();
    };
    DayPilot.Date.prototype.getDayOfYear = function() {
        var $0h = this.firstDayOfYear();
        return DayPilot.DateUtil.daysDiff($0h, this) + 1;
    };
    DayPilot.Date.prototype.daysInMonth = function() {
        var $04 = new Date(this.ticks);
        var $0i = $04.getUTCMonth() + 1;
        var $0j = $04.getUTCFullYear();
        var m = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if ($0i !== 2) return m[$0i - 1];
        if ($0j % 4 !== 0) return m[1];
        if ($0j % 100 === 0 && $0j % 400 !== 0) return m[1];
        return m[1] + 1;
    };
    DayPilot.Date.prototype.daysInYear = function() {
        var $0j = this.getYear();
        if ($0j % 4 !== 0) {
            return 365;
        };
        if ($0j % 100 === 0 && $0j % 400 !== 0) {
            return 365;
        };
        return 366;
    };
    DayPilot.Date.prototype.dayOfYear = function() {
        return Math.ceil((this.getDatePart().getTime() - this.firstDayOfYear().getTime()) / 86400000) + 1;
    };
    DayPilot.Date.prototype.equals = function($0k) {
        if ($0k === null) {
            return false;
        };
        if ($0k instanceof DayPilot.Date) {
            return this === $0k;
        } else {
            throw "The parameter must be a DayPilot.Date object (DayPilot.Date.equals())";
        }
    };
    DayPilot.Date.prototype.firstDayOfMonth = function() {
        var d = new Date();
        d.setUTCFullYear(this.getYear(), this.getMonth(), 1);
        d.setUTCHours(0);
        d.setUTCMinutes(0);
        d.setUTCSeconds(0);
        d.setUTCMilliseconds(0);
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.firstDayOfYear = function() {
        var $0j = this.getYear();
        var d = new Date();
        d.setUTCFullYear($0j, 0, 1);
        d.setUTCHours(0);
        d.setUTCMinutes(0);
        d.setUTCSeconds(0);
        d.setUTCMilliseconds(0);
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.firstDayOfWeek = function($0l) {
        var d = this;
        if ($0l instanceof DayPilot.Locale) {
            $0l = $0l.weekStarts;
        } else if (typeof $0l === "string" && DayPilot.Locale.find($0l)) {
            var $T = DayPilot.Locale.find($0l);
            $0l = $T.weekStarts;
        } else {
            $0l = $0l || 0;
        };
        var $V = d.dayOfWeek();
        while ($V !== $0l) {
            d = d.addDays(-1);
            $V = d.dayOfWeek();
        };
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.getDay = function() {
        return new Date(this.ticks).getUTCDate();
    };
    DayPilot.Date.prototype.getDatePart = function() {
        var d = new Date(this.ticks);
        d.setUTCHours(0);
        d.setUTCMinutes(0);
        d.setUTCSeconds(0);
        d.setUTCMilliseconds(0);
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.getYear = function() {
        return new Date(this.ticks).getUTCFullYear();
    };
    DayPilot.Date.prototype.getHours = function() {
        return new Date(this.ticks).getUTCHours();
    };
    DayPilot.Date.prototype.getMilliseconds = function() {
        return new Date(this.ticks).getUTCMilliseconds();
    };
    DayPilot.Date.prototype.getMinutes = function() {
        return new Date(this.ticks).getUTCMinutes();
    };
    DayPilot.Date.prototype.getMonth = function() {
        return new Date(this.ticks).getUTCMonth();
    };
    DayPilot.Date.prototype.getSeconds = function() {
        return new Date(this.ticks).getUTCSeconds();
    };
    DayPilot.Date.prototype.getTotalTicks = function() {
        return this.getTime();
    };
    DayPilot.Date.prototype.getTime = function() {
        return this.ticks;
    };
    DayPilot.Date.prototype.getTimePart = function() {
        var $0m = this.getDatePart();
        return DayPilot.DateUtil.diff(this, $0m);
    };
    DayPilot.Date.prototype.lastDayOfMonth = function() {
        var d = new Date(this.firstDayOfMonth().getTime());
        var length = this.daysInMonth();
        d.setUTCDate(length);
        return new DayPilot.Date(d);
    };
    DayPilot.Date.prototype.weekNumber = function() {
        var $0h = this.firstDayOfYear();
        var $09 = (this.getTime() - $0h.getTime()) / 86400000;
        return Math.ceil(($09 + $0h.dayOfWeek() + 1) / 7);
    };
    DayPilot.Date.prototype.weekNumberISO = function() {
        var $0n = false;
        var $0o = this.dayOfYear();
        var $0p = this.firstDayOfYear().dayOfWeek();
        var $0q = this.firstDayOfYear().addYears(1).addDays(-1).dayOfWeek();
        if ($0p === 0) {
            $0p = 7;
        };
        if ($0q === 0) {
            $0q = 7;
        };
        var $0r = 8 - ($0p);
        if ($0p === 4 || $0q === 4) {
            $0n = true;
        };
        var $0s = Math.ceil(($0o - ($0r)) / 7.0);
        var $0t = $0s;
        if ($0r >= 4) {
            $0t = $0t + 1;
        };
        if ($0t > 52 && !$0n) {
            $0t = 1;
        };
        if ($0t === 0) {
            $0t = this.firstDayOfYear().addDays(-1).weekNumberISO();
        };
        return $0t;
    };
    DayPilot.Date.prototype.toDateLocal = function() {
        var $04 = new Date(this.ticks);
        var d = new Date();
        d.setFullYear($04.getUTCFullYear(), $04.getUTCMonth(), $04.getUTCDate());
        d.setHours($04.getUTCHours());
        d.setMinutes($04.getUTCMinutes());
        d.setSeconds($04.getUTCSeconds());
        d.setMilliseconds($04.getUTCMilliseconds());
        return d;
    };
    DayPilot.Date.prototype.toDate = function() {
        return new Date(this.ticks);
    };
    DayPilot.Date.prototype.toJSON = function() {
        return this.value;
    };
    DayPilot.Date.prototype.toString = function($Z, $T) {
        if (!$Z) {
            return this.toStringSortable();
        };
        return new $0u($Z, $T).print(this);
    };
    DayPilot.Date.prototype.toStringSortable = function() {
        return ticksToSortable(this.ticks);
    };

    function ticksToSortable($U) {
        var $06 = DayPilot.Date.Cache.Ticks;
        if ($06[$U]) {
            DayPilot.Stats.cacheHitsTicks += 1;
            return $06[$U];
        };
        var d = new Date($U);
        var $0v;
        var ms = d.getUTCMilliseconds();
        if (ms === 0) {
            $0v = "";
        } else if (ms < 10) {
            $0v = ".00" + ms;
        } else if (ms < 100) {
            $0v = ".0" + ms;
        } else {
            $0v = "." + ms
        };
        var $Y = d.getUTCSeconds();
        if ($Y < 10) $Y = "0" + $Y;
        var $X = d.getUTCMinutes();
        if ($X < 10) $X = "0" + $X;
        var $W = d.getUTCHours();
        if ($W < 10) $W = "0" + $W;
        var $V = d.getUTCDate();
        if ($V < 10) $V = "0" + $V;
        var $0i = d.getUTCMonth() + 1;
        if ($0i < 10) $0i = "0" + $0i;
        var $0j = d.getUTCFullYear();
        if ($0j <= 0) {
            throw "The minimum year supported is 1.";
        };
        if ($0j < 10) {
            $0j = "000" + $0j;
        } else if ($0j < 100) {
            $0j = "00" + $0j;
        } else if ($0j < 1000) {
            $0j = "0" + $0j;
        };
        var $h = $0j + "-" + $0i + "-" + $V + 'T' + $W + ":" + $X + ":" + $Y + $0v;
        $06[$U] = $h;
        return $h;
    };
    DayPilot.Date.parse = function(str, $Z, $T) {
        var p = new $0u($Z, $T);
        return p.parse(str);
    };
    var $0w = 0;
    DayPilot.Date.today = function() {
        return new DayPilot.Date(DayPilot.DateUtil.localToday(), true);
    };
    DayPilot.Date.fromYearMonthDay = function($0j, $0i, $V) {
        $0i = $0i || 1;
        $V = $V || 1;
        var d = new Date(0);
        d.setUTCFullYear($0j);
        d.setUTCMonth($0i - 1);
        d.setUTCDate($V);
        return new DayPilot.Date(d);
    };
    DayPilot.DateUtil = {};
    DayPilot.DateUtil.fromStringSortable = function($0x, $05) {
        if (!$0x) {
            throw "Can't create DayPilot.Date from an empty string";
        };
        var $0y = $0x.length;
        var $04 = $0y === 10;
        var $0z = $0y === 19;
        var long = $0y > 19;
        if (!$04 && !$0z && !long) {
            throw "Invalid string format (use '2010-01-01' or '2010-01-01T00:00:00'): " + $0x;
        };
        if (DayPilot.Date.Cache.Parsing[$0x] && !$05) {
            DayPilot.Stats.cacheHitsParsing += 1;
            return DayPilot.Date.Cache.Parsing[$0x];
        };
        var $0j = $0x.substring(0, 4);
        var $0i = $0x.substring(5, 7);
        var $V = $0x.substring(8, 10);
        var d = new Date(0);
        d.setUTCFullYear($0j, $0i - 1, $V);
        if ($04) {
            DayPilot.Date.Cache.Parsing[$0x] = d;
            return d;
        };
        var $0a = $0x.substring(11, 13);
        var $00 = $0x.substring(14, 16);
        var $0e = $0x.substring(17, 19);
        d.setUTCHours($0a);
        d.setUTCMinutes($00);
        d.setUTCSeconds($0e);
        if ($0z) {
            DayPilot.Date.Cache.Parsing[$0x] = d;
            return d;
        };
        var $0A = $0x[19];
        var $0B = 0;
        if ($0A === ".") {
            var ms = parseInt($0x.substring(20, 23));
            d.setUTCMilliseconds(ms);
            $0B = DayPilot.DateUtil.getTzOffsetMinutes($0x.substring(23));
        } else {
            $0B = DayPilot.DateUtil.getTzOffsetMinutes($0x.substring(19));
        };
        var dd = new DayPilot.Date(d);
        if (!$05) {
            dd = dd.addMinutes(-$0B);
        };
        d = dd.toDate();
        DayPilot.Date.Cache.Parsing[$0x] = d;
        return d;
    };
    DayPilot.DateUtil.getTzOffsetMinutes = function($0x) {
        if (DayPilot.Util.isNullOrUndefined($0x) || $0x === "") {
            return 0;
        };
        if ($0x === "Z") {
            return 0;
        };
        var $0A = $0x[0];
        var $0C = parseInt($0x.substring(1, 3));
        var $0D = parseInt($0x.substring(4));
        var $0B = $0C * 60 + $0D;
        if ($0A === "-") {
            return -$0B;
        } else if ($0A === "+") {
            return $0B;
        } else {
            throw "Invalid timezone spec: " + $0x;
        }
    };
    DayPilot.DateUtil.hasTzSpec = function($0x) {
        if ($0x.indexOf("+")) {
            return true;
        };
        if ($0x.indexOf("-")) {
            return true;
        };
        return false;
    };
    DayPilot.DateUtil.daysDiff = function($0h, $Y) {
        ($0h && $Y) || (function() {
            throw "two parameters required";
        })();
        $0h = new DayPilot.Date($0h);
        $Y = new DayPilot.Date($Y);
        if ($0h.getTime() > $Y.getTime()) {
            return null;
        };
        var i = 0;
        var $0E = $0h.getDatePart();
        var $0F = $Y.getDatePart();
        while ($0E < $0F) {
            $0E = $0E.addDays(1);
            i++;
        };
        return i;
    };
    DayPilot.DateUtil.daysSpan = function($0h, $Y) {
        ($0h && $Y) || (function() {
            throw "two parameters required";
        })();
        $0h = new DayPilot.Date($0h);
        $Y = new DayPilot.Date($Y);
        if ($0h === $Y) {
            return 0;
        };
        var $0G = DayPilot.DateUtil.daysDiff($0h, $Y);
        if ($Y == $Y.getDatePart()) {
            $0G--;
        };
        return $0G;
    };
    DayPilot.DateUtil.diff = function($0h, $Y) {
        if (!($0h && $Y && $0h.getTime && $Y.getTime)) {
            throw "Both compared objects must be Date objects (DayPilot.Date.diff).";
        };
        return $0h.getTime() - $Y.getTime();
    };
    DayPilot.DateUtil.fromLocal = function($0H) {
        if (!$0H) {
            $0H = new Date();
        };
        var d = new Date();
        d.setUTCFullYear($0H.getFullYear(), $0H.getMonth(), $0H.getDate());
        d.setUTCHours($0H.getHours());
        d.setUTCMinutes($0H.getMinutes());
        d.setUTCSeconds($0H.getSeconds());
        d.setUTCMilliseconds($0H.getMilliseconds());
        return d;
    };
    DayPilot.DateUtil.localToday = function() {
        var d = new Date();
        d.setHours(0);
        d.setMinutes(0);
        d.setSeconds(0);
        d.setMilliseconds(0);
        return d;
    };
    DayPilot.DateUtil.hours = function($04, $0I) {
        var $X = $04.getUTCMinutes();
        if ($X < 10) $X = "0" + $X;
        var $W = $04.getUTCHours();
        if ($0I) {
            var am = $W < 12;
            var $W = $W % 12;
            if ($W === 0) {
                $W = 12;
            };
            var $0J = am ? "AM" : "PM";
            return $W + ':' + $X + ' ' + $0J;
        } else {
            return $W + ':' + $X;
        }
    };
    DayPilot.DateUtil.max = function($0h, $Y) {
        if ($0h.getTime() > $Y.getTime()) {
            return $0h;
        } else {
            return $Y;
        }
    };
    DayPilot.DateUtil.min = function($0h, $Y) {
        if ($0h.getTime() < $Y.getTime()) {
            return $0h;
        } else {
            return $Y;
        }
    };
    var $0u = function($Z, $T) {
        if (typeof $T === "string") {
            $T = DayPilot.Locale.find($T);
        };
        var $T = $T || DayPilot.Locale.US;
        var $0K = [{
            "seq": "yyyy",
            "expr": "[0-9]{4,4\u007d",
            "str": function(d) {
                return d.getYear();
            }
        }, {
            "seq": "yy",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                return d.getYear() % 100;
            }
        }, {
            "seq": "mm",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var r = d.getMinutes();
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "m",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getMinutes();
                return r;
            }
        }, {
            "seq": "HH",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var r = d.getHours();
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "H",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getHours();
                return r;
            }
        }, {
            "seq": "hh",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var $W = d.getHours();
                var $W = $W % 12;
                if ($W === 0) {
                    $W = 12;
                };
                var r = $W;
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "h",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var $W = d.getHours();
                var $W = $W % 12;
                if ($W === 0) {
                    $W = 12;
                };
                return $W;
            }
        }, {
            "seq": "ss",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var r = d.getSeconds();
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "s",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getSeconds();
                return r;
            }
        }, {
            "seq": "MMMM",
            "expr": "[^\\s0-9]*",
            "str": function(d) {
                var r = $T.monthNames[d.getMonth()];
                return r;
            },
            "transform": function($0L) {
                var $p = DayPilot.indexOf($T.monthNames, $0L, $0M);
                if ($p < 0) {
                    return null;
                };
                return $p + 1;
            }
        }, {
            "seq": "MMM",
            "expr": "[^\\s0-9]*",
            "str": function(d) {
                var r = $T.monthNamesShort[d.getMonth()];
                return r;
            },
            "transform": function($0L) {
                var $p = DayPilot.indexOf($T.monthNamesShort, $0L, $0M);
                if ($p < 0) {
                    return null;
                };
                return $p + 1;
            }
        }, {
            "seq": "MM",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var r = d.getMonth() + 1;
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "M",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getMonth() + 1;
                return r;
            }
        }, {
            "seq": "dddd",
            "expr": "[^\\s0-9]*",
            "str": function(d) {
                var r = $T.dayNames[d.getDayOfWeek()];
                return r;
            }
        }, {
            "seq": "ddd",
            "expr": "[^\\s0-9]*",
            "str": function(d) {
                var r = $T.dayNamesShort[d.getDayOfWeek()];
                return r;
            }
        }, {
            "seq": "dd",
            "expr": "[0-9]{2,2\u007d",
            "str": function(d) {
                var r = d.getDay();
                return r < 10 ? "0" + r : r;
            }
        }, {
            "seq": "%d",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getDay();
                return r;
            }
        }, {
            "seq": "d",
            "expr": "[0-9]{1,2\u007d",
            "str": function(d) {
                var r = d.getDay();
                return r;
            }
        }, {
            "seq": "tt",
            "expr": "(AM|PM|am|pm)",
            "str": function(d) {
                var $W = d.getHours();
                var am = $W < 12;
                return am ? "AM" : "PM";
            },
            "transform": function($0L) {
                return $0L.toUpperCase();
            }
        }, ];
        var $0N = function($0O) {
            return $0O.replace(/[-[\]{};()*+?.,\\^$|#\s]/g, "\\$&");
        };
        this.init = function() {
            this.year = this.findSequence("yyyy");
            this.month = this.findSequence("MMMM") || this.findSequence("MMM") || this.findSequence("MM") || this.findSequence("M");
            this.day = this.findSequence("dd") || this.findSequence("d");
            this.hours = this.findSequence("HH") || this.findSequence("H");
            this.minutes = this.findSequence("mm") || this.findSequence("m");
            this.seconds = this.findSequence("ss") || this.findSequence("s");
            this.ampm = this.findSequence("tt");
            this.hours12 = this.findSequence("hh") || this.findSequence("h");
        };
        this.findSequence = function($0P) {
            function defaultTransform($08) {
                return parseInt($08);
            };
            var $p = $Z.indexOf($0P);
            if ($p === -1) {
                return null;
            };
            return {
                "findValue": function($0L) {
                    var $0Q = $0N($Z);
                    var $0R = null;
                    for (var i = 0; i < $0K.length; i++) {
                        var $0y = $0K[i].length;
                        var $0S = ($0P === $0K[i].seq);
                        var $0T = $0K[i].expr;
                        if ($0S) {
                            $0T = "(" + $0T + ")";
                            $0R = $0K[i].transform;
                        };
                        $0Q = $0Q.replace($0K[i].seq, $0T);
                    };
                    $0Q = "^" + $0Q + "$";
                    try {
                        var r = new RegExp($0Q);
                        var $q = r.exec($0L);
                        if (!$q) {
                            return null;
                        };
                        $0R = $0R || defaultTransform;
                        return $0R($q[1]);
                    } catch (e) {
                        throw "unable to create regex from: " + $0Q;
                    }
                }
            };
        };
        this.print = function($04) {
            var find = function(t) {
                for (var i = 0; i < $0K.length; i++) {
                    if ($0K[i] && $0K[i].seq === t) {
                        return $0K[i];
                    }
                };
                return null;
            };
            var $0U = $Z.length <= 0;
            var $0V = 0;
            var $0W = [];
            while (!$0U) {
                var $0X = $Z.substring($0V);
                var $0Y = /%?(.)\1*/.exec($0X);
                if ($0Y && $0Y.length > 0) {
                    var $0Z = $0Y[0];
                    var q = find($0Z);
                    if (q) {
                        $0W.push(q);
                    } else {
                        $0W.push($0Z);
                    };
                    $0V += $0Z.length;
                    $0U = $Z.length <= $0V;
                } else {
                    $0U = true;
                }
            };
            for (var i = 0; i < $0W.length; i++) {
                var c = $0W[i];
                if (typeof c !== 'string') {
                    $0W[i] = c.str($04);
                }
            };
            return $0W.join("");
        };
        this.parse = function($0L) {
            var $0j = this.year.findValue($0L);
            if (!$0j) {
                return null;
            };
            var $0i = this.month.findValue($0L);
            if (DayPilot.Util.isNullOrUndefined($0i)) {
                return null;
            };
            if ($0i > 12 || $0i < 1) {
                return null;
            };
            var $V = this.day.findValue($0L);
            var $10 = DayPilot.Date.fromYearMonthDay($0j, $0i).daysInMonth();
            if ($V < 1 || $V > $10) {
                return null;
            };
            var $0a = this.hours ? this.hours.findValue($0L) : 0;
            var $00 = this.minutes ? this.minutes.findValue($0L) : 0;
            var $0e = this.seconds ? this.seconds.findValue($0L) : 0;
            var $11 = this.ampm ? this.ampm.findValue($0L) : null;
            if (this.ampm && this.hours12) {
                var $12 = this.hours12.findValue($0L);
                if ($12 < 1 || $12 > 12) {
                    return null;
                };
                if ($11 === "PM") {
                    if ($12 === 12) {
                        $0a = 12;
                    } else {
                        $0a = $12 + 12;
                    }
                } else {
                    if ($12 === 12) {
                        $0a = 0;
                    } else {
                        $0a = $12;
                    }
                }
            };
            if ($0a < 0 || $0a > 23) {
                return null;
            };
            if ($00 < 0 || $00 > 59) {
                return null;
            };
            if ($0e < 0 || $0e > 59) {
                return null;
            };
            var d = new Date();
            d.setUTCFullYear($0j, $0i - 1, $V);
            d.setUTCHours($0a);
            d.setUTCMinutes($00);
            d.setUTCSeconds($0e);
            d.setUTCMilliseconds(0);
            return new DayPilot.Date(d);
        };
        this.init();
    };
    DayPilot.Event = function($G, $13, $14) {
        var e = this;
        this.calendar = $13;
        this.data = $G ? $G : {};
        this.part = $14 ? $14 : {};
        if (typeof this.data.id === 'undefined') {
            this.data.id = this.data.value;
        };
        var $15 = {};
        var $16 = ["id", "text", "start", "end", "leader", "technician", "room_no", "subject"];
        this.isEvent = true;
        this.temp = function() {
            if ($15.dirty) {
                return $15;
            };
            for (var i = 0; i < $16.length; i++) {
                $15[$16[i]] = e.data[$16[i]];
            };
            $15.dirty = true;
            return $15;
        };
        this.copy = function() {
            var $h = {};
            for (var i = 0; i < $16.length; i++) {
                $h[$16[i]] = e.data[$16[i]];
            };
            return $h;
        };
        this.commit = function() {
            if (!$15.dirty) {
                return;
            };
            for (var i = 0; i < $16.length; i++) {
                e.data[$16[i]] = $15[$16[i]];
            };
            $15.dirty = false;
        };
        this.dirty = function() {
            return $15.dirty;
        };
        this.id = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.id;
            } else {
                this.temp().id = $P;
            }
        };
        this.value = function($P) {
            if (typeof $P === 'undefined') {
                return e.id();
            } else {
                e.id($P);
            }
        };
        this.text = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.text;
            } else {
                this.temp().text = $P;
                this.client.innerHTML($P);
            }
        };
        this.start = function($P) {
            if (typeof $P === 'undefined') {
                return new DayPilot.Date(e.data.start);
            } else {
                this.temp().start = new DayPilot.Date($P);
            }
        };
        this.end = function($P) {
            if (typeof $P === 'undefined') {
                return new DayPilot.Date(e.data.end);
            } else {
                this.temp().end = new DayPilot.Date($P);
            }
        };

        //SD start
        this.leader = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.leader;
            } else {
                this.temp().leader = $P;
                this.client.innerHTML($P);
            }
        };
        this.technician = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.technician;
            } else {
                this.temp().technician = $P;
                this.client.innerHTML($P);
            }
        };
        this.room_no = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.room_no;
            } else {
                this.temp().room_no = $P;
                this.client.innerHTML($P);
            }
        };
        this.subject = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.subject;
            } else {
                this.temp().subject = $P;
                this.client.innerHTML($P);
            }
        };
        //SD end




        this.partStart = function() {
            return new DayPilot.Date(this.part.start);
        };
        this.partEnd = function() {
            return new DayPilot.Date(this.part.end);
        };
        this.tag = function($17) {
            var $18 = e.data.tag;
            if (!$18) {
                return null;
            };
            if (typeof $17 === 'undefined') {
                return e.data.tag;
            };
            var $19 = e.calendar.tagFields;
            var $p = -1;
            for (var i = 0; i < $19.length; i++) {
                if ($17 === $19[i]) $p = i;
            };
            if ($p === -1) {
                throw "Field name not found.";
            };
            return $18[$p];
        };
        this.client = {};
        this.client.innerHTML = function($P) {
            if (typeof $P === 'undefined') {
                if (e.cache && typeof e.cache.html !== "undefined") {
                    return e.cache.html;
                };
                if (typeof e.data.html !== "undefined") {
                    return e.data.html;
                };
                return e.data.text;
            } else {
                e.data.html = $P;
            }
        };
        this.client.html = this.client.innerHTML;
        this.client.header = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.header;
            } else {
                e.data.header = $P;
            }
        };
        this.client.cssClass = function($P) {
            if (typeof $P === 'undefined') {
                return e.data.cssClass;
            } else {
                e.data.cssClass = $P;
            }
        };
        this.client.toolTip = function($P) {
            if (typeof $P === 'undefined') {
                if (e.cache && typeof e.cache.toolTip !== "undefined") {
                    return e.cache.toolTip;
                };
                return typeof e.data.toolTip !== 'undefined' ? e.data.toolTip : e.data.text;
            } else {
                e.data.toolTip = $P;
            }
        };
        this.client.barVisible = function($P) {
            if (typeof $P === 'undefined') {
                if (e.cache && typeof e.cache.barHidden !== "undefined") {
                    return !e.cache.barHidden;
                };
                return e.calendar.durationBarVisible && !e.data.barHidden;
            } else {
                e.data.barHidden = !$P;
            }
        };
        this.client.backColor = function($P) {
            if (typeof $P === 'undefined') {
                if (e.cache && typeof e.cache.backColor !== "undefined") {
                    return e.cache.backColor;
                };
                return typeof e.data.backColor !== "undefined" ? e.data.backColor : e.calendar.eventBackColor;
            } else {
                e.data.backColor = $P;
            }
        };
        this.client.borderColor = function($P) {
            if (typeof $P === 'undefined') {
                if (e.cache && typeof e.cache.borderColor !== "undefined") {
                    return e.cache.borderColor;
                };
                return typeof e.data.borderColor !== "undefined" ? e.data.borderColor : e.calendar.eventBorderColor;
            } else {
                e.data.borderColor = $P;
            }
        };
        this.client.moveEnabled = function($P) {
            if (typeof $P === 'undefined') {
                return e.calendar.eventMoveHandling !== 'Disabled' && !e.data.moveDisabled;
            } else {
                e.data.moveDisabled = !$P;
            }
        };
        this.client.resizeEnabled = function($P) {
            if (typeof $P === 'undefined') {
                return e.calendar.eventResizeHandling !== 'Disabled' && !e.data.resizeDisabled;
            } else {
                e.data.resizeDisabled = !$P;
            }
        };
        this.client.clickEnabled = function($P) {
            if (typeof $P === 'undefined') {
                return e.calendar.eventClickHandling !== 'Disabled' && !e.data.clickDisabled;
            } else {
                e.data.clickDisabled = !$P;
            }
        };
        this.toJSON = function($x) {
            var $y = {};
            $y.value = this.id();
            $y.id = this.id();
            $y.text = this.text();
            $y.start = this.start();
            $y.end = this.end();
            $y.leader = this.leader();//SD
            $y.technician = this.technician();//SD
            $y.room_no = this.room_no();//SD
            $y.subject = this.subject();//SD
            $y.tag = {};
            if (e.calendar && e.calendar.tagFields) {
                var $19 = e.calendar.tagFields;
                for (var i = 0; i < $19.length; i++) {
                    $y.tag[$19[i]] = this.tag($19[i]);
                }
            };
            return $y;
        };
    };
})();
DayPilot.JSON = {};
(function() {
    function f(n) {
        return n < 10 ? '0' + n : n;
    };
    if (typeof Date.prototype.toJSON2 !== 'function') {
        Date.prototype.toJSON2 = function($x) {
            return this.getUTCFullYear() + '-' + f(this.getUTCMonth() + 1) + '-' + f(this.getUTCDate()) + 'T' + f(this.getUTCHours()) + ':' + f(this.getUTCMinutes()) + ':' + f(this.getUTCSeconds()) + '';
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function($x) {
            return this.valueOf();
        };
    };
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        $1a = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        $1b, $1c, $1d = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        },
        $1e;

    function quote($0x) {
        $1a.lastIndex = 0;
        return $1a.test($0x) ? '"' + $0x.replace($1a, function(a) {
            var c = $1d[a];
            if (typeof c === 'string') {
                return c;
            };
            return '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + $0x + '"';
    };

    function str($x, $1f) {
        var i, k, v, length, $1g = $1b,
            $1h, $08 = $1f[$x];
        if ($08 && typeof $08 === 'object' && typeof $08.toJSON2 === 'function') {
            $08 = $08.toJSON2($x);
        } else if ($08 && typeof $08 === 'object' && typeof $08.toJSON === 'function' && !$08.ignoreToJSON) {
            $08 = $08.toJSON($x);
        };
        if (typeof $1e === 'function') {
            $08 = $1e.call($1f, $x, $08);
        };
        switch (typeof $08) {
            case 'string':
                return quote($08);
            case 'number':
                return isFinite($08) ? String($08) : 'null';
            case 'boolean':
            case 'null':
                return String($08);
            case 'object':
                if (!$08) {
                    return 'null';
                };
                $1b += $1c;
                $1h = [];
                if (typeof $08.length === 'number' && !$08.propertyIsEnumerable('length')) {
                    length = $08.length;
                    for (i = 0; i < length; i += 1) {
                        $1h[i] = str(i, $08) || 'null';
                    };
                    v = $1h.length === 0 ? '[]' : $1b ? '[\n' + $1b + $1h.join(',\n' + $1b) + '\n' + $1g + ']' : '[' + $1h.join(',') + ']';
                    $1b = $1g;
                    return v;
                };
                if ($1e && typeof $1e === 'object') {
                    length = $1e.length;
                    for (i = 0; i < length; i += 1) {
                        k = $1e[i];
                        if (typeof k === 'string') {
                            v = str(k, $08);
                            if (v) {
                                $1h.push(quote(k) + ($1b ? ': ' : ':') + v);
                            }
                        }
                    }
                } else {
                    for (k in $08) {
                        if (Object.hasOwnProperty.call($08, k)) {
                            v = str(k, $08);
                            if (v) {
                                $1h.push(quote(k) + ($1b ? ': ' : ':') + v);
                            }
                        }
                    }
                };
                v = ($1h.length === 0) ? '{\u007D' : $1b ? '{\n' + $1b + $1h.join(',\n' + $1b) + '\n' + $1g + '\u007D' : '{' + $1h.join(',') + '\u007D';
                $1b = $1g;
                return v;
        }
    };
    if (typeof DayPilot.JSON.stringify !== 'function') {
        DayPilot.JSON.stringify = function($08, $1i, $1j) {
            var i;
            $1b = '';
            $1c = '';
            if (typeof $1j === 'number') {
                for (i = 0; i < $1j; i += 1) {
                    $1c += ' ';
                }
            } else if (typeof $1j === 'string') {
                $1c = $1j;
            };
            $1e = $1i;
            if ($1i && typeof $1i !== 'function' && (typeof $1i !== 'object' || typeof $1i.length !== 'number')) {
                throw new Error('JSON.stringify');
            };
            return str('', {
                '': $08
            });
        };
    }
})();


if (typeof DayPilot === 'undefined') {
    var DayPilot = {};
};
if (typeof DayPilot.Global === 'undefined') {
    DayPilot.Global = {};
};
(function() {
    var $a = function() {};
    if (typeof DayPilot.Calendar !== 'undefined') {
        return;
    };
    var DayPilotCalendar = {};
    DayPilotCalendar.selectedCells = [];
    DayPilotCalendar.topSelectedCell = null;
    DayPilotCalendar.bottomSelectedCell = null;
    DayPilotCalendar.selecting = false;
    DayPilotCalendar.column = null;
    DayPilotCalendar.firstSelected = null;
    DayPilotCalendar.firstMousePos = null;
    DayPilotCalendar.originalMouse = null;
    DayPilotCalendar.originalHeight = null;
    DayPilotCalendar.originalTop = null;
    DayPilotCalendar.resizing = null;
    DayPilotCalendar.globalHandlers = false;
    DayPilotCalendar.moving = null;
    DayPilotCalendar.register = function($b) {
        if (!DayPilotCalendar.registered) {
            DayPilotCalendar.registered = [];
        };
        var r = DayPilotCalendar.registered;
        for (var i = 0; i < r.length; i++) {
            if (r[i] === $b) {
                return;
            }
        };
        r.push($b);
    };
    DayPilotCalendar.unregister = function($b) {
        var a = DayPilotCalendar.registered;
        if (!a) {
            return;
        };
        var i = DayPilot.indexOf(a, $b);
        if (i === -1) {
            return;
        };
        a.splice(i, 1);
    };
    DayPilotCalendar.getCellsAbove = function($c) {
        var $d = [];
        var c = DayPilotCalendar.getColumn($c);
        var tr = $c.parentNode;
        var $e = null;
        while (tr && $e !== DayPilotCalendar.firstSelected) {
            $e = tr.getElementsByTagName("td")[c];
            $d.push($e);
            tr = tr.previousSibling;
            while (tr && tr.tagName !== "TR") {
                tr = tr.previousSibling;
            }
        };
        return $d;
    };
    DayPilotCalendar.getCellsBelow = function($c) {
        var $d = [];
        var c = DayPilotCalendar.getColumn($c);
        var tr = $c.parentNode;
        var $e = null;
        while (tr && $e !== DayPilotCalendar.firstSelected) {
            $e = tr.getElementsByTagName("td")[c];
            $d.push($e);
            tr = tr.nextSibling;
            while (tr && tr.tagName !== "TR") {
                tr = tr.nextSibling;
            }
        };
        return $d;
    };
    DayPilotCalendar.getColumn = function($c) {
        var i = 0;
        while ($c.previousSibling) {
            $c = $c.previousSibling;
            if ($c.tagName === "TD") {
                i++;
            }
        };
        return i;
    };
    DayPilotCalendar.gUnload = function(ev) {
        if (!DayPilotCalendar.registered) {
            return;
        };
        var r = DayPilotCalendar.registered;
        for (var i = 0; i < r.length; i++) {
            var c = r[i];
            c.dispose();
            DayPilotCalendar.unregister(c);
        }
    };
    DayPilotCalendar.gMouseUp = function(e) {
        if (DayPilotCalendar.resizing) {
            if (!DayPilotCalendar.resizingShadow) {
                DayPilotCalendar.resizing.style.cursor = 'default';
                document.body.style.cursor = 'default';
                DayPilotCalendar.resizing = null;
                return;
            };
            var $f = DayPilotCalendar.resizing.event;
            var $g = DayPilotCalendar.resizingShadow.clientHeight + 4;
            var top = DayPilotCalendar.resizingShadow.offsetTop;
            var $h = DayPilotCalendar.resizing.dpBorder;
            DayPilotCalendar.deleteShadow(DayPilotCalendar.resizingShadow);
            DayPilotCalendar.resizingShadow = null;
            DayPilotCalendar.resizing.style.cursor = 'default';
            $f.calendar.nav.top.style.cursor = 'auto';
            DayPilotCalendar.resizing.onclick = null;
            DayPilotCalendar.resizing = null;
            $f.calendar.$1U($f, $g, top, $h);
        } else if (DayPilotCalendar.moving) {
            if (!DayPilotCalendar.movingShadow) {
                DayPilotCalendar.moving = null;
                document.body.style.cursor = 'default';
                return;
            };
            var top = DayPilotCalendar.movingShadow.offsetTop;
            DayPilotCalendar.deleteShadow(DayPilotCalendar.movingShadow);
            var $f = DayPilotCalendar.moving.event;
            var $i = DayPilotCalendar.movingShadow.column;
            DayPilotCalendar.moving = null;
            DayPilotCalendar.movingShadow = null;
            $f.calendar.nav.top.style.cursor = 'auto';
            var ev = e || window.event;
            $f.calendar.$1V($f, $i, top, ev);
        } else if (DayPilotCalendar.selecting && DayPilotCalendar.topSelectedCell !== null) {
            var $b = DayPilotCalendar.selecting.calendar;
            DayPilotCalendar.selecting = false;
            var $j = $b.getSelection();
            $b.$1W($j.start, $j.end);
            if ($b.timeRangeSelectedHandling !== "Hold" && $b.timeRangeSelectedHandling !== "HoldForever") {
                $a();
            }
        } else {
            DayPilotCalendar.selecting = false;
        }
    };
    DayPilotCalendar.deleteShadow = function($k) {
        if (!$k) {
            return;
        };
        if (!$k.parentNode) {
            return;
        };
        $k.parentNode.removeChild($k);
    };
    DayPilotCalendar.moveShadow = function($l) {
        var $k = DayPilotCalendar.movingShadow;
        var parent = $k.parentNode;
        parent.style.display = 'none';
        $k.parentNode.removeChild($k);
        $l.firstChild.appendChild($k);
        $k.style.left = '0px';
        parent.style.display = '';
        $k.style.width = (DayPilotCalendar.movingShadow.parentNode.offsetWidth + 1) + 'px';
    };
    DayPilotCalendar.Calendar = function(id) {
        var $m = false;
        if (this instanceof DayPilotCalendar.Calendar && !this.$1X) {
            $m = true;
            this.$1X = true;
        };
        if (!$m) {
            throw "DayPilot.Calendar() is a constructor and must be called as 'var c = new DayPilot.Calendar(id);'";
        };
        var $b = this;
        this.uniqueID = null;
        this.v = '2021.1.248-lite';
        this.id = id;
        this.clientName = id;
        this.cache = {};
        this.cache.pixels = {};
        this.elements = {};
        this.elements.events = [];
        this.nav = {};
        this.afterRender = function() {};
        this.fasterDispose = true;
        this.angularAutoApply = false;
        this.api = 2;
        this.borderColor = "#CED2CE";
        this.businessBeginsHour = 7;    //sd
        this.businessEndsHour = 22;     //sd
        this.cellHeight = 15;           //sd
        this.columnMarginRight = 5;
        this.cornerBackColor = "#F3F3F9";
        this.$1Y = true;
        this.days = 1;
        this.durationBarVisible = true;
        this.eventHeaderHeight = 14;
        this.eventHeaderVisible = true;
        this.eventsLoadMethod = "GET";
        this.headerHeight = 30;
        this.height = 400;
        this.heightSpec = 'BusinessHours';
        this.hideUntilInit = true;
        this.hourWidth = 60;
        this.initScrollPos = 'Auto';
        this.loadingLabelText = "Loading...";
        this.loadingLabelVisible = true;
        this.loadingLabelBackColor = "ff0000";
        this.loadingLabelFontColor = "#ffffff";
        this.loadingLabelFontFamily = "Tahoma, Arial, Helvetica, sans-serif";
        this.loadingLabelFontSize = "10pt";
        this.locale = "en-us";
        this.selectedColor = "#316AC5";
        this.showToolTip = true;
        this.startDate = new DayPilot.Date().getDatePart();
        this.cssClassPrefix = "calendar_default";
        this.theme = null;
        this.timeFormat = 'Auto';
        this.visible = true;
        this.timeRangeSelectedHandling = 'Enabled';
        this.eventClickHandling = 'Enabled';
        this.eventResizeHandling = 'Update';
        this.eventMoveHandling = 'Update';
        this.eventDeleteHandling = "Disabled";
        this.onBeforeEventRender = null;
        this.onEventClick = null;
        this.onEventClicked = null;
        this.onEventDelete = null;
        this.onEventDeleted = null;
        this.onEventMove = null;
        this.onEventMoved = null;
        this.onEventResize = null;
        this.onEventResized = null;
        this.onTimeRangeSelect = null;
        this.onTimeRangeSelected = null;
        this.clearSelection = function() {
            for (var j = 0; j < DayPilotCalendar.selectedCells.length; j++) {
                var $c = DayPilotCalendar.selectedCells[j];
                if ($c) {
                    if ($c.selected) {
                        DayPilot.de($c.selected);
                        $c.firstChild && ($c.firstChild.style.display = '');
                        $c.selected = null;
                    }
                }
            };
            DayPilotCalendar.selectedCells = [];
        };
        this._ie = (navigator && navigator.userAgent && navigator.userAgent.indexOf("MSIE") !== -1);
        this._ff = (navigator && navigator.userAgent && navigator.userAgent.indexOf("Firefox") !== -1);
        this.$1Z = (function() {
            if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
                var v = new Number(RegExp.$1);
                return v >= 10.5;
            };
            return false;
        })();
        this.$20 = (function() {
            if (/AppleWebKit[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
                var v = new Number(RegExp.$1);
                return v >= 522;
            };
            return false;
        })();
        this.cleanSelection = this.clearSelection;
        this.$21 = function($n, $o, $p) {
            var $q = {};
            $q.action = $n;
            $q.parameters = $p;
            $q.data = $o;
            $q.header = this.$22();
            var $r = "JSON" + DayPilot.JSON.stringify($q);
            __doPostBack($b.uniqueID, $r);
        };
        this.$23 = function($n, $o, $p) {
            if (this.callbackTimeout) {
                window.clearTimeout(this.callbackTimeout);
            };
            this.callbackTimeout = window.setTimeout(function() {
                $b.loadingStart();
            }, 100);
            var $q = {};
            $q.action = $n;
            $q.parameters = $p;
            $q.data = $o;
            $q.header = this.$22();
            var $r = "JSON" + DayPilot.JSON.stringify($q);
            if (this.backendUrl) {
                DayPilot.request(this.backendUrl, this.$24, $r, this.ajaxError);
            } else if (typeof WebForm_DoCallback === 'function') {
                WebForm_DoCallback(this.uniqueID, $r, this.$25, this.clientName, this.onCallbackError, true);
            }
        };
        this.onCallbackError = function($s, $t) {
            alert("Error!\r\nResult: " + $s + "\r\nContext:" + $t);
        };
        this.dispose = function() {
            var c = $b;
            c.$26();
            c.nav.zoom.onmousemove = null;
            c.nav.scroll.root = null;
            DayPilot.pu(c.nav.loading);
            c.$27();
            c.$28();
            c.nav.select = null;
            c.nav.cornerRight = null;
            c.nav.scrollable = null;
            c.nav.zoom = null;
            c.nav.loading = null;
            c.nav.header = null;
            c.nav.hourTable = null;
            c.nav.scrolltop = null;
            c.nav.scroll = null;
            c.nav.main = null;
            c.nav.message = null;
            c.nav.messageClose = null;
            c.nav.top = null;
            DayPilotCalendar.unregister(c);
        };
        this.$29 = function() {
            this.nav.top.dispose = this.dispose;
        };
        this.$24 = function($u) {
            $b.$25($u.responseText);
        };
        this.$22 = function() {
            var h = {};
            h.control = "dpc";
            h.id = this.id;
            h.v = this.v;
            h.days = $b.days;
            h.startDate = $b.startDate;
            h.heightSpec = $b.heightSpec;
            h.businessBeginsHour = $b.businessBeginsHour;
            h.businessEndsHour = $b.businessEndsHour;
            h.hashes = $b.hashes;
            h.timeFormat = $b.timeFormat;
            h.viewType = $b.viewType;
            h.locale = $b.locale;
            return h;
        };
        this.$2a = function($v, $w) {
            var $x = $v.parentNode;
            while ($x && $x.tagName !== "TD") {
                $x = $x.parentNode;
            };
            var $k = document.createElement('div');
            $k.setAttribute('unselectable', 'on');
            $k.style.position = 'absolute';
            $k.style.width = ($v.offsetWidth) + 'px';
            $k.style.height = ($v.offsetHeight) + 'px';
            $k.style.left = ($v.offsetLeft) + 'px';
            $k.style.top = ($v.offsetTop) + 'px';
            $k.style.boxSizing = "border-box";
            $k.style.zIndex = 101;
            $k.className = $b.$2b("_shadow");
            var $y = document.createElement("div");
            $y.className = $b.$2b("_shadow_inner");
            $k.appendChild($y);
            if ($w && false) {
                $k.style.overflow = 'hidden';
                $k.style.fontSize = $v.style.fontSize;
                $k.style.fontFamily = $v.style.fontFamily;
                $k.style.color = $v.style.color;
                $k.innerHTML = $v.data.client.html();
            };
            $x.firstChild.appendChild($k);
            return $k;
        };
        this.$2c = {};
        this.$2c.locale = function() {
            var $z = DayPilot.Locale.find($b.locale);
            if (!$z) {
                return DayPilot.Locale.US;
            };
            return $z;
        };
        this.$2c.timeFormat = function() {
            if ($b.timeFormat !== 'Auto') {
                return $b.timeFormat;
            };
            return this.locale().timeFormat;
        };
        var $A = this.$2c;
        this.$25 = function($s, $t) {
            if ($s && $s.indexOf("$$$") === 0) {
                if (window.console) {
                    console.log("Error received from the server side: " + $s);
                } else {
                    throw "Error received from the server side: " + $s;
                };
                return;
            };
            var $s = eval("(" + $s + ")");
            if ($s.CallBackRedirect) {
                document.location.href = $s.CallBackRedirect;
                return;
            };
            if ($s.UpdateType === "None") {
                $b.loadingStop();
                $b.$2d();
                return;
            };
            $b.$26();
            if ($s.UpdateType === "Full") {
                $b.columns = $s.Columns;
                $b.days = $s.Days;
                $b.startDate = new DayPilot.Date($s.StartDate);
                $b.heightSpec = $s.HeightSpec ? $s.HeightSpec : $b.heightSpec;
                $b.businessBeginsHour = $s.BusinessBeginsHour ? $s.BusinessBeginsHour : $b.businessBeginsHour;
                $b.businessEndsHour = $s.BusinessEndsHour ? $s.BusinessEndsHour : $b.businessEndsHour;
                $b.headerDateFormat = $s.HeaderDateFormat ? $s.HeaderDateFormat : $b.headerDateFormat;
                $b.viewType = $s.ViewType;
                $b.backColor = $s.BackColor ? $s.BackColor : $b.backColor;
                $b.eventHeaderVisible = $s.EventHeaderVisible ? $s.EventHeaderVisible : $b.eventHeaderVisible;
                $b.timeFormat = $s.TimeFormat ? $s.TimeFormat : $b.timeFormat;
                $b.locale = $s.Locale ? $s.Locale : $b.locale;
                $b.$2e();
            };
            if ($s.Hashes) {
                for (var $B in $s.Hashes) {
                    $b.hashes[$B] = $s.Hashes[$B];
                }
            };
            $b.events.list = $s.Events;
            $b.$2f();
            $b.$2g();
            if ($s.UpdateType === "Full") {
                $b.$2h();
                $b.$2i();
                $b.$2j();
                $b.$2k();
            };
            $b.$2d();
            $b.$2l();
            $b.clearSelection();
            $b.afterRender($s.CallBackData, true);
            $b.loadingStop();
        };
        this.$2m = function() {
            return 24;
        };
        this.$2n = function() {
            if (this.businessBeginsHour > this.businessEndsHour) {
                return 24 - this.businessBeginsHour + this.businessEndsHour;
            } else {
                return this.businessEndsHour - this.businessBeginsHour;
            }
        };
        this.$2o = function() {
            return 48;
        };
        this.$2p = function() {
            return $b.api === 2;
        };
        this.eventClickCallBack = function(e, $o) {
            this.$23('EventClick', $o, e);
        };
        this.eventClickPostBack = function(e, $o) {
            this.$21('EventClick', $o, e);
        };
        this.$2q = function(e) {
            var $C = this;
            var e = $C.event;
            if ($b.$2p()) {
                var $D = {};
                $D.e = e;
                $D.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $b.onEventClick === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventClick($D);
                    });
                    if ($D.preventDefault.value) {
                        return;
                    }
                };
                switch ($b.eventClickHandling) {
                    case 'CallBack':
                        $b.eventClickCallBack(e);
                        break;
                    case 'PostBack':
                        $b.eventClickPostBack(e);
                        break;
                };
                if (typeof $b.onEventClicked === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventClicked($D);
                    });
                }
            } else {
                switch ($b.eventClickHandling) {
                    case 'PostBack':
                        $b.eventClickPostBack(e);
                        break;
                    case 'CallBack':
                        $b.eventClickCallBack(e);
                        break;
                    case 'JavaScript':
                        $b.onEventClick(e);
                        break;
                }
            }
        };
        this.eventDeleteCallBack = function(e, $o) {
            this.$23('EventDelete', $o, e);
        };
        this.eventDeletePostBack = function(e, $o) {
            this.$21('EventDelete', $o, e);
        };
        this.$2s = function(e) {
            if ($b.$2p()) {
                var $D = {};
                $D.e = e;
                $D.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $b.onEventDelete === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventDelete($D);
                    });
                    if ($D.preventDefault.value) {
                        return;
                    }
                };
                switch ($b.eventDeleteHandling) {
                    case 'CallBack':
                        $b.eventDeleteCallBack(e);
                        break;
                    case 'PostBack':
                        $b.eventDeletePostBack(e);
                        break;
                    case 'Update':
                        $b.events.remove(e);
                        break;
                };
                if (typeof $b.onEventDeleted === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventDeleted($D);
                    });
                }
            } else {
                switch ($b.eventDeleteHandling) {
                    case 'PostBack':
                        $b.eventDeletePostBack(e);
                        break;
                    case 'CallBack':
                        $b.eventDeleteCallBack(e);
                        break;
                    case 'JavaScript':
                        $b.onEventDelete(e);
                        break;
                }
            }
        };
        this.eventResizeCallBack = function(e, $E, $F, $o) {
            if (!$E) throw 'newStart is null';
            if (!$F) throw 'newEnd is null';
            var $G = {};
            $G.e = e;
            $G.newStart = $E;
            $G.newEnd = $F;
            this.$23('EventResize', $o, $G);
        };
        this.eventResizePostBack = function(e, $E, $F, $o) {
            if (!$E) throw 'newStart is null';
            if (!$F) throw 'newEnd is null';
            var $G = {};
            $G.e = e;
            $G.newStart = $E;
            $G.newEnd = $F;
            this.$21('EventResize', $o, $G);
        };
        this.$1U = function(e, $H, $I, $h) {
            var $J = 1;
            var $E = new Date();
            var $F = new Date();
            var $K = e.start();
            var end = e.end();
            if ($h === 'top') {
                var $L = $K.getDatePart();
                var $M = Math.floor(($I - $J) / $b.cellHeight);
                var $N = $M * 30;
                var ts = $N * 60 * 1000;
                $E = $L.addTime(ts);
                $F = e.end();
            } else if ($h === 'bottom') {
                var $L = end.getDatePart();
                var $M = Math.floor(($I + $H - $J) / $b.cellHeight);
                var $N = $M * 30;
                var ts = $N * 60 * 1000;
                $E = $K;
                $F = $L.addTime(ts);
            };
            if ($b.$2p()) {
                var $D = {};
                $D.e = e;
                $D.newStart = $E;
                $D.newEnd = $F;
                $D.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $b.onEventResize === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventResize($D);
                    });
                    if ($D.preventDefault.value) {
                        return;
                    }
                };
                switch ($b.eventResizeHandling) {
                    case 'PostBack':
                        $b.eventResizePostBack(e, $E, $F);
                        break;
                    case 'CallBack':
                        $b.eventResizeCallBack(e, $E, $F);
                        break;
                    case 'Update':
                        e.start($E);
                        e.end($F);
                        $b.events.update(e);
                        break;
                };
                if (typeof $b.onEventResized === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventResized($D);
                    });
                }
            } else {
                switch ($b.eventResizeHandling) {
                    case 'PostBack':
                        $b.eventResizePostBack(e, $E, $F);
                        break;
                    case 'CallBack':
                        $b.eventResizeCallBack(e, $E, $F);
                        break;
                    case 'JavaScript':
                        $b.onEventResize(e, $E, $F);
                        break;
                }
            }
        };
        this.eventMovePostBack = function(e, $E, $F, $O, $o) {
            if (!$E) throw 'newStart is null';
            if (!$F) throw 'newEnd is null';
            var $G = {};
            $G.e = e;
            $G.newStart = $E;
            $G.newEnd = $F;
            this.$21('EventMove', $o, $G);
        };
        this.eventMoveCallBack = function(e, $E, $F, $O, $o) {
            if (!$E) throw 'newStart is null';
            if (!$F) throw 'newEnd is null';
            var $G = {};
            $G.e = e;
            $G.newStart = $E;
            $G.newEnd = $F;
            this.$23('EventMove', $o, $G);
        };
        this.$1V = function(e, $i, $I, ev) {
            var $J = 1;
            var $M = Math.floor(($I - $J) / $b.cellHeight);
            var $P = $M * 30 * 60 * 1000;
            var $K = e.start();
            var end = e.end();
            var $L = new Date();
            if ($K instanceof DayPilot.Date) {
                $K = $K.toDate();
            };
            $L.setTime(Date.UTC($K.getUTCFullYear(), $K.getUTCMonth(), $K.getUTCDate()));
            var $Q = $K.getTime() - ($L.getTime() + $K.getUTCHours() * 3600 * 1000 + Math.floor($K.getUTCMinutes() / 30) * 30 * 60 * 1000);
            var length = end.getTime() - $K.getTime();
            var $R = this.columns[$i];
            var $S = $R.Start.getTime();
            var $T = new Date();
            $T.setTime($S + $P + $Q);
            var $E = new DayPilot.Date($T);
            var $F = $E.addTime(length);
            if ($b.$2p()) {
                var $D = {};
                $D.e = e;
                $D.newStart = $E;
                $D.newEnd = $F;
                $D.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $b.onEventMove === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventMove($D);
                    });
                    if ($D.preventDefault.value) {
                        return;
                    }
                };
                switch ($b.eventMoveHandling) {
                    case 'PostBack':
                        $b.eventMovePostBack(e, $E, $F, $R.Value);
                        break;
                    case 'CallBack':
                        $b.eventMoveCallBack(e, $E, $F, $R.Value);
                        break;
                    case 'Update':
                        e.start($E);
                        e.end($F);
                        $b.events.update(e);
                        break;
                };
                if (typeof $b.onEventMoved === 'function') {
                    $b.$2r.apply(function() {
                        $b.onEventMoved($D);
                    });
                }
            } else {
                switch ($b.eventMoveHandling) {
                    case 'PostBack':
                        $b.eventMovePostBack(e, $E, $F, $R.Value);
                        break;
                    case 'CallBack':
                        $b.eventMoveCallBack(e, $E, $F, $R.Value);
                        break;
                    case 'JavaScript':
                        $b.onEventMove(e, $E, $F, $R.Value, false);
                        break;
                }
            }
        };
        this.timeRangeSelectedPostBack = function($K, end, $U, $o) {
            var $V = {};
            $V.start = $K;
            $V.end = end;
            this.$21('TimeRangeSelected', $o, $V);
        };
        this.timeRangeSelectedCallBack = function($K, end, $U, $o) {
            var $V = {};
            $V.start = $K;
            $V.end = end;
            this.$23('TimeRangeSelected', $o, $V);
        };
        this.$1W = function($K, end) {
            $K = new DayPilot.Date($K);
            end = new DayPilot.Date(end);
            if (this.$2p()) {
                var $D = {};
                $D.start = $K;
                $D.end = end;
                $D.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $b.onTimeRangeSelect === 'function') {
                    $b.$2r.apply(function() {
                        $b.onTimeRangeSelect($D);
                    });
                    if ($D.preventDefault.value) {
                        return;
                    }
                };
                switch ($b.timeRangeSelectedHandling) {
                    case 'PostBack':
                        $b.timeRangeSelectedPostBack($K, end);
                        break;
                    case 'CallBack':
                        $b.timeRangeSelectedCallBack($K, end);
                        break;
                };
                if (typeof $b.onTimeRangeSelected === 'function') {
                    $b.$2r.apply(function() {
                        $b.onTimeRangeSelected($D);
                    });
                }
            } else {
                switch ($b.timeRangeSelectedHandling) {
                    case 'PostBack':
                        $b.timeRangeSelectedPostBack($K, end);
                        break;
                    case 'CallBack':
                        $b.timeRangeSelectedCallBack($K, end);
                        break;
                    case 'JavaScript':
                        $b.onTimeRangeSelected($K, end);
                        break;
                }
            }
        };
        this.$2t = function(ev) {
            if (DayPilotCalendar.selecting) {
                return;
            };
            if ($b.timeRangeSelectedHandling === "Disabled") {
                return;
            };
            var $W = (window.event) ? window.event.button : ev.which;
            if ($W !== 1 && $W !== 0) {
                return;
            };
            DayPilotCalendar.firstMousePos = DayPilot.mc(ev || window.event);
            DayPilotCalendar.selecting = {};
            DayPilotCalendar.selecting.calendar = $b;
            if (DayPilotCalendar.selectedCells) {
                $b.clearSelection();
                DayPilotCalendar.selectedCells = [];
            };
            DayPilotCalendar.column = DayPilotCalendar.getColumn(this);
            DayPilotCalendar.selectedCells.push(this);
            DayPilotCalendar.firstSelected = this;
            DayPilotCalendar.topSelectedCell = this;
            DayPilotCalendar.bottomSelectedCell = this;
            $b.$2u();
            return false;
        };
        this.$2u = function() {
            var $X = this.getSelection();
            for (var j = 0; j < DayPilotCalendar.selectedCells.length; j++) {
                var $c = DayPilotCalendar.selectedCells[j];
                if ($c && !$c.selected) {
                    var $Y = document.createElement("div");
                    $Y.style.height = ($b.cellHeight - 1) + "px";
                    $Y.style.backgroundColor = $b.selectedColor;
                    $c.firstChild.style.display = "none";
                    $c.insertBefore($Y, $c.firstChild);
                    $c.selected = $Y;
                }
            }
        };
        this.$2v = function(ev) {
            if (typeof(DayPilotCalendar) === 'undefined') {
                return;
            };
            if (!DayPilotCalendar.selecting) {
                return;
            };
            var $Z = DayPilot.mc(ev || window.event);
            var $00 = DayPilotCalendar.getColumn(this);
            if ($00 !== DayPilotCalendar.column) {
                return;
            };
            $b.clearSelection();
            if ($Z.y < DayPilotCalendar.firstMousePos.y) {
                DayPilotCalendar.selectedCells = DayPilotCalendar.getCellsBelow(this);
                DayPilotCalendar.topSelectedCell = DayPilotCalendar.selectedCells[0];
                DayPilotCalendar.bottomSelectedCell = DayPilotCalendar.firstSelected;
            } else {
                DayPilotCalendar.selectedCells = DayPilotCalendar.getCellsAbove(this);
                DayPilotCalendar.topSelectedCell = DayPilotCalendar.firstSelected;
                DayPilotCalendar.bottomSelectedCell = DayPilotCalendar.selectedCells[0];
            };
            $b.$2u();
        };
        this.getSelection = function() {
            var $K = DayPilotCalendar.topSelectedCell.start;
            var end = DayPilotCalendar.bottomSelectedCell.end;
            return new DayPilot.Selection($K, end, null, $b);
        };
        this.$2w = function(ev) {};
        this.$2e = function() {
            this.columns = this.$2x();
            for (var i = 0; i < this.columns.length; i++) {
                this.$2y(this.columns[i]);
            }
        };
        this.$2y = function($l) {
            $l.Start = new DayPilot.Date($l.Start);
            $l.putIntoBlock = function(ep) {
                for (var i = 0; i < this.blocks.length; i++) {
                    var $01 = this.blocks[i];
                    if ($01.overlapsWith(ep.part.top, ep.part.height)) {
                        $01.events.push(ep);
                        $01.min = Math.min($01.min, ep.part.top);
                        $01.max = Math.max($01.max, ep.part.top + ep.part.height);
                        return i;
                    }
                };
                var $01 = [];
                $01.lines = [];
                $01.events = [];
                $01.overlapsWith = function($K, $02) {
                    var end = $K + $02 - 1;
                    if (!(end < this.min || $K > this.max - 1)) {
                        return true;
                    };
                    return false;
                };
                $01.putIntoLine = function(ep) {
                    var $03 = this;
                    for (var i = 0; i < this.lines.length; i++) {
                        var $04 = this.lines[i];
                        if ($04.isFree(ep.part.top, ep.part.height)) {
                            $04.push(ep);
                            return i;
                        }
                    };
                    var $04 = [];
                    $04.isFree = function($K, $02) {
                        var end = $K + $02 - 1;
                        var $05 = this.length;
                        for (var i = 0; i < $05; i++) {
                            var e = this[i];
                            if (!(end < e.part.top || $K > e.part.top + e.part.height - 1)) {
                                return false;
                            }
                        };
                        return true;
                    };
                    $04.push(ep);
                    this.lines.push($04);
                    return this.lines.length - 1;
                };
                $01.events.push(ep);
                $01.min = ep.part.top;
                $01.max = ep.part.top + ep.part.height;
                this.blocks.push($01);
                return this.blocks.length - 1;
            };
            $l.putIntoLine = function(ep) {
                var $03 = this;
                for (var i = 0; i < this.lines.length; i++) {
                    var $04 = this.lines[i];
                    if ($04.isFree(ep.part.top, ep.part.height)) {
                        $04.push(ep);
                        return i;
                    }
                };
                var $04 = [];
                $04.isFree = function($K, $02) {
                    var end = $K + $02 - 1;
                    var $05 = this.length;
                    for (var i = 0; i < $05; i++) {
                        var e = this[i];
                        if (!(end < e.part.top || $K > e.part.top + e.part.height - 1)) {
                            return false;
                        }
                    };
                    return true;
                };
                $04.push(ep);
                this.lines.push($04);
                return this.lines.length - 1;
            };
        };
        this.$2x = function() {
            var $06 = [];
            var $K = this.startDate.getDatePart();
            var $07 = this.days;
            switch (this.viewType) {
                case "Day":
                    $07 = 1;
                    break;
                case "Week":
                    $07 = 7;
                    $K = $K.firstDayOfWeek($A.locale().weekStarts);
                    break;
                case "WorkWeek":
                    $07 = 5;
                    $K = $K.firstDayOfWeek(1);
                    break;
            };
            for (var i = 0; i < $07; i++) {
                var $08 = $b.headerDateFormat ? $b.headerDateFormat : $A.locale().datePattern;
                var $l = {};
                $l.Start = $K.addDays(i);
                $l.Name = $l.Start.toString($08, $A.locale());
                $l.InnerHTML = $l.Name;
                $06.push($l);
            };
            return $06;
        };
        this.visibleStart = function() {
            return this.columns[0].Start;
        };
        this.visibleEnd = function() {
            var $05 = this.columns.length - 1;
            return this.columns[$05].Start.addDays(1);
        };
        this.$2b = function($09) {
            var $0a = this.theme || this.cssClassPrefix;
            if ($0a) {
                return $0a + $09;
            } else {
                return "";
            }
        };
        this.$26 = function() {
            if (this.elements.events) {
                for (var i = 0; i < this.elements.events.length; i++) {
                    var $Y = this.elements.events[i];
                    var $v = $Y.event;
                    if ($v) {
                        $v.calendar = null;
                    };
                    $Y.onclick = null;
                    $Y.onclickSave = null;
                    $Y.onmouseover = null;
                    $Y.onmouseout = null;
                    $Y.onmousemove = null;
                    $Y.onmousedown = null;
                    if ($Y.firstChild && $Y.firstChild.firstChild && $Y.firstChild.firstChild.tagName && $Y.firstChild.firstChild.tagName.toUpperCase() === 'IMG') {
                        var $0b = $Y.firstChild.firstChild;
                        $0b.onmousedown = null;
                        $0b.onmousemove = null;
                        $0b.onclick = null;
                    };
                    $Y.helper = null;
                    $Y.data = null;
                    $Y.event = null;
                    DayPilot.de($Y);
                }
            };
            this.elements.events = [];
        };
        this.$2z = function(e) {
            var $o = e.cache || e.data;
            var $0c = this.nav.events;
            var $0d = true;
            var $0e = true;
            var $0f = false;
            var $Y = document.createElement("div");
            $Y.style.position = 'absolute';
            $Y.style.left = e.part.left + '%';
            $Y.style.top = (e.part.top) + 'px';
            $Y.style.width = e.part.width + '%';
            $Y.style.height = Math.max(e.part.height, 2) + 'px';
            $Y.style.overflow = 'hidden';
            $Y.data = e;
            $Y.event = e;
            $Y.unselectable = 'on';
            $Y.style.MozUserSelect = 'none';
            $Y.style.KhtmlUserSelect = 'none';
            $Y.className = this.$2b("_event");
            if ($o.cssClass) {
                DayPilot.Util.addClass($Y, $o.cssClass);
            };
            $Y.isFirst = e.part.start.getTime() === e.start().getTime();
            $Y.isLast = e.part.end.getTime() === e.end().getTime();
            $Y.onclick = this.$2q;
            $Y.onmouseout = function(ev) {
                if ($Y.deleteIcon) {
                    $Y.deleteIcon.style.display = "none";
                }
            };
            $Y.onmousemove = function(ev) {
                var $0g = 5;
                var $0h = $b.eventHeaderVisible ? ($b.eventHeaderHeight) : 10;
                if (typeof(DayPilotCalendar) === 'undefined') {
                    return;
                };
                var $0i = DayPilot.mo3($Y, ev);
                if (!$0i) {
                    return;
                };
                if (DayPilotCalendar.resizing || DayPilotCalendar.moving) {
                    return;
                };
                if ($Y.deleteIcon) {
                    $Y.deleteIcon.style.display = "";
                };
                var $0j = this.isLast;
                if ($0i.y <= $0h && $b.eventResizeHandling !== 'Disabled') {
                    this.style.cursor = "n-resize";
                    this.dpBorder = 'top';
                } else if (this.offsetHeight - $0i.y <= $0g && $b.eventResizeHandling !== 'Disabled') {
                    if ($0j) {
                        this.style.cursor = "s-resize";
                        this.dpBorder = 'bottom';
                    } else {
                        this.style.cursor = 'not-allowed';
                    }
                } else if (!DayPilotCalendar.resizing && !DayPilotCalendar.moving) {
                    if ($b.eventClickHandling !== 'Disabled') {
                        this.style.cursor = 'pointer';
                    } else {
                        this.style.cursor = 'default';
                    }
                }
            };
            $Y.onmousedown = function(ev) {
                ev = ev || window.event;
                var $W = ev.which || ev.button;
                if ((this.style.cursor === 'n-resize' || this.style.cursor === 's-resize') && $W === 1) {
                    DayPilotCalendar.resizing = this;
                    DayPilotCalendar.originalMouse = DayPilot.mc(ev);
                    DayPilotCalendar.originalHeight = this.offsetHeight;
                    DayPilotCalendar.originalTop = this.offsetTop;
                    $b.nav.top.style.cursor = this.style.cursor;
                } else if ($W === 1 && $b.eventMoveHandling !== 'Disabled') {
                    DayPilotCalendar.moving = this;
                    DayPilotCalendar.moving.event = this.event;
                    var $0k = DayPilotCalendar.moving.helper = {};
                    $0k.oldColumn = $b.columns[this.data.part.dayIndex].Value;
                    DayPilotCalendar.originalMouse = DayPilot.mc(ev);
                    DayPilotCalendar.originalTop = this.offsetTop;
                    var $0i = DayPilot.mo3(this, ev);
                    if ($0i) {
                        DayPilotCalendar.moveOffsetY = $0i.y;
                    } else {
                        DayPilotCalendar.moveOffsetY = 0;
                    };
                    $b.nav.top.style.cursor = 'move';
                };
                return false;
            };
            var $y = document.createElement("div");
            $y.setAttribute("unselectable", "on");
            $y.className = $b.$2b("_event_inner");
            $y.innerHTML = e.client.html();
            if ($o.borderColor) {
                $y.style.borderColor = $o.borderColor;
            };
            if ($o.backColor) {
                $y.style.background = $o.backColor;
                if (DayPilot.browser.ie9 || DayPilot.browser.ielt9) {
                    $y.style.filter = '';
                }
            };
            if ($o.fontColor) {
                $y.style.color = $o.fontColor;
            };
            $Y.appendChild($y);
            if (e.client.barVisible()) {
                var $g = e.part.height - 2;
                var $0l = 100 * e.part.barTop / $g;
                var $0m = Math.ceil(100 * e.part.barHeight / $g);
                var $0n = document.createElement("div");
                $0n.setAttribute("unselectable", "on");
                $0n.className = this.$2b("_event_bar");
                $0n.style.position = "absolute";
                if ($o.barBackColor) {
                    $0n.style.backgroundColor = $o.barBackColor;
                };
                var $0o = document.createElement("div");
                $0o.setAttribute("unselectable", "on");
                $0o.className = this.$2b("_event_bar_inner");
                $0o.style.top = $0l + "%";
                if (0 < $0m && $0m <= 1) {
                    $0o.style.height = "1px";
                } else {
                    $0o.style.height = $0m + "%";
                };
                if ($o.barColor) {
                    $0o.style.backgroundColor = $o.barColor;
                };
                $0n.appendChild($0o);
                $Y.appendChild($0n);
            };
            if ($b.eventDeleteHandling !== "Disabled") {
                var $0p = document.createElement("div");
                $0p.style.position = "absolute";
                $0p.style.right = "2px";
                $0p.style.top = "2px";
                $0p.style.width = "17px";
                $0p.style.height = "17px";
                $0p.className = $b.$2b("_event_delete");
                $0p.onclick = function(ev) {
                    var e = this.parentNode.event;
                    if (e) {
                        $b.$2s(e);
                    }
                };
                $0p.style.display = "none";
                $Y.deleteIcon = $0p;
                $Y.appendChild($0p);
            };
            if ($0c.rows[0].cells[e.part.dayIndex]) {
                var $0q = $0c.rows[0].cells[e.part.dayIndex].firstChild;
                $0q.appendChild($Y);
                $b.$2A($Y);
            };
            $b.elements.events.push($Y);
        };
        this.$2A = function(el) {
            var c = (el && el.childNodes) ? el.childNodes.length : 0;
            for (var i = 0; i < c; i++) {
                try {
                    var $0r = el.childNodes[i];
                    if ($0r.nodeType === 1) {
                        $0r.unselectable = 'on';
                        this.$2A($0r);
                    }
                } catch (e) {}
            }
        };
        this.$2l = function() {
            for (var i = 0; i < this.columns.length; i++) {
                var $0s = this.columns[i];
                if (!$0s.blocks) {
                    continue;
                };
                for (var m = 0; m < $0s.blocks.length; m++) {
                    var $01 = $0s.blocks[m];
                    for (var j = 0; j < $01.lines.length; j++) {
                        var $04 = $01.lines[j];
                        for (var k = 0; k < $04.length; k++) {
                            var e = $04[k];
                            e.part.width = 100 / $01.lines.length;
                            e.part.left = e.part.width * j;
                            var $0t = (j === $01.lines.length - 1);
                            if (!$0t) {
                                e.part.width = e.part.width * 1.5;
                            };
                            this.$2z(e);
                        }
                    }
                }
            }
        };
        this.$2B = function() {
            this.nav.top.innerHTML = '';
            if (this.$1Y) {
                DayPilot.Util.addClass(this.nav.top, this.$2b("_main"));
            } else {
                this.nav.top.style.lineHeight = "1.2";
                this.nav.top.style.textAlign = "left";
            };
            this.nav.top.style.MozUserSelect = 'none';
            this.nav.top.style.KhtmlUserSelect = 'none';
            this.nav.top.style.position = 'relative';
            this.nav.top.style.width = this.width ? this.width : '100%';
            if (this.hideUntilInit) {
                this.nav.top.style.visibility = 'hidden';
            };
            if (!this.visible) {
                this.nav.top.style.display = "none";
            };
            this.nav.scroll = document.createElement("div");
            this.nav.scroll.style.height = this.$2C() + "px";
            if (this.heightSpec === 'BusinessHours') {
                this.nav.scroll.style.overflow = "auto";
            } else {
                this.nav.scroll.style.overflow = "hidden";
            };
            this.nav.scroll.style.position = "relative";
            if (!this.$1Y) {};
            var $0u = this.$2D();
            this.nav.top.appendChild($0u);
            this.nav.scroll.style.zoom = 1;
            var $0v = this.$2E();
            this.nav.scrollable = $0v.firstChild;
            this.nav.scroll.appendChild($0v);
            this.nav.top.appendChild(this.nav.scroll);
            this.nav.scrollLayer = document.createElement("div");
            this.nav.scrollLayer.style.position = 'absolute';
            this.nav.scrollLayer.style.top = '0px';
            this.nav.scrollLayer.style.left = '0px';
            this.nav.top.appendChild(this.nav.scrollLayer);
            this.nav.loading = document.createElement("div");
            this.nav.loading.style.position = 'absolute';
            this.nav.loading.style.top = '0px';
            this.nav.loading.style.left = (this.hourWidth + 5) + "px";
            this.nav.loading.style.backgroundColor = this.loadingLabelBackColor;
            this.nav.loading.style.fontSize = this.loadingLabelFontSize;
            this.nav.loading.style.fontFamily = this.loadingLabelFontFamily;
            this.nav.loading.style.color = this.loadingLabelFontColor;
            this.nav.loading.style.padding = '2px';
            this.nav.loading.innerHTML = this.loadingLabelText;
            this.nav.loading.style.display = 'none';
            this.nav.top.appendChild(this.nav.loading);
        };
        this.$2j = function() {
            if (!this.fasterDispose) DayPilot.pu(this.nav.hourTable);
            this.nav.scrollable.rows[0].cells[0].innerHTML = '';
            this.nav.hourTable = this.$2F();
            this.nav.scrollable.rows[0].cells[0].appendChild(this.nav.hourTable);
        };
        this.$2E = function() {
            var $0w = document.createElement("div");
            $0w.style.zoom = 1;
            $0w.style.position = 'relative';
            var $0x = document.createElement("table");
            $0x.cellSpacing = "0";
            $0x.cellPadding = "0";
            $0x.border = "0";
            $0x.style.border = "0px none";
            $0x.style.width = "100%";
            $0x.style.position = 'absolute';
            var r = $0x.insertRow(-1);
            var c;
            c = r.insertCell(-1);
            c.valign = "top";
            c.style.padding = '0px';
            c.style.border = '0px none';
            this.nav.hourTable = this.$2F();
            c.appendChild(this.nav.hourTable);
            c = r.insertCell(-1);
            c.valign = "top";
            c.width = "100%";
            c.style.padding = '0px';
            c.style.border = '0px none';
            if (!this.$1Y) {
                c.appendChild(this.$2G());
            } else {
                var $0v = document.createElement("div");
                $0v.style.position = "relative";
                c.appendChild($0v);
                $0v.appendChild(this.$2G());
                $0v.appendChild(this.$2H());
            };
            $0w.appendChild($0x);
            this.nav.zoom = $0w;
            return $0w;
        };
        this.$2G = function() {
            var $0x = document.createElement("table");
            $0x.cellPadding = "0";
            $0x.cellSpacing = "0";
            $0x.border = "0";
            $0x.style.width = "100%";
            $0x.style.border = "0px none";
            $0x.style.tableLayout = 'fixed';
            if (!this.$1Y) {
                $0x.style.borderLeft = "1px solid " + this.borderColor;
            };
            this.nav.main = $0x;
            this.nav.events = $0x;
            return $0x;
        };
        this.$2H = function() {
            var $0x = document.createElement("table");
            $0x.style.top = "0px";
            $0x.cellPadding = "0";
            $0x.cellSpacing = "0";
            $0x.border = "0";
            $0x.style.position = "absolute";
            $0x.style.width = "100%";
            $0x.style.border = "0px none";
            $0x.style.tableLayout = 'fixed';
            this.nav.events = $0x;
            var $0y = true;
            var $06 = this.columns;
            var cl = $06.length;
            var r = ($0y) ? $0x.insertRow(-1) : $0x.rows[0];
            for (var j = 0; j < cl; j++) {
                var c = ($0y) ? r.insertCell(-1) : r.cells[j];
                if ($0y) {
                    c.style.padding = '0px';
                    c.style.border = '0px none';
                    c.style.height = '0px';
                    c.style.overflow = 'visible';
                    if (!$b.rtl) {
                        c.style.textAlign = 'left';
                    };
                    var $Y = document.createElement("div");
                    $Y.style.marginRight = $b.columnMarginRight + "px";
                    $Y.style.position = 'relative';
                    $Y.style.height = '1px';
                    if (!this.$1Y) {
                        $Y.style.fontSize = '1px';
                        $Y.style.lineHeight = '1.2';
                    };
                    $Y.style.marginTop = '-1px';
                    c.appendChild($Y);
                }
            };
            return $0x;
        };
        this.$2F = function() {
            var $0x = document.createElement("table");
            $0x.cellSpacing = "0";
            $0x.cellPadding = "0";
            $0x.border = "0";
            $0x.style.border = '0px none';
            $0x.style.width = this.hourWidth + "px";
            $0x.oncontextmenu = function() {
                return false;
            };
            var $0z = this.$1Y ? 0 : 1;
            if ($0z) {
                var r = $0x.insertRow(-1);
                r.style.height = "1px";
                r.style.backgroundColor = "white";
                var c = r.insertCell(-1);
                c.style.padding = '0px';
                c.style.border = '0px none';
            };
            var $0A = 24;
            for (var i = 0; i < $0A; i++) {
                this.$2I($0x, i);
            };
            return $0x;
        };
        this.$2I = function($0x, i) {
            var $g = (this.cellHeight * 2);
            var r = $0x.insertRow(-1);
            r.style.height = $g + "px";
            var c = r.insertCell(-1);
            c.valign = "bottom";
            c.unselectable = "on";
            if (!this.$1Y) {};
            c.style.cursor = "default";
            c.style.padding = '0px';
            c.style.border = '0px none';
            var $0B = document.createElement("div");
            $0B.style.position = "relative";
            if (this.$1Y) {
                $0B.className = this.$2b("_rowheader");
            };
            $0B.style.width = this.hourWidth + "px";
            $0B.style.height = ($g) + "px";
            $0B.style.overflow = 'hidden';
            $0B.unselectable = 'on';
            var $01 = document.createElement("div");
            if (this.$1Y) {
                $01.className = this.$2b("_rowheader_inner");
            } else {};
            $01.unselectable = "on";
            var $0C = document.createElement("div");
            $0C.unselectable = "on";
            var $K = this.startDate.addHours(i);
            var $0D = $K.getHours();
            var am = $0D < 12;
            var $0E = $A.timeFormat();
            if ($0E === "Clock12Hours") {
                $0D = $0D % 12;
                if ($0D === 0) {
                    $0D = 12;
                }
            };
            $0C.innerHTML = $0D;
            var $0F = document.createElement("span");
            $0F.unselectable = "on";
            if (!this.$1Y) {
                $0F.style.fontSize = "10px";
                $0F.style.verticalAlign = "super";
            } else {
                $0F.className = this.$2b("_rowheader_minutes");
            };
            var $0G;
            if ($0E === "Clock12Hours") {
                if (am) {
                    $0G = "AM";
                } else {
                    $0G = "PM";
                }
            } else {
                $0G = "00";
            };
            if (!this.$1Y) {
                $0F.innerHTML = "&nbsp;" + $0G;
            } else {
                $0F.innerHTML = $0G;
            };
            $0C.appendChild($0F);
            $01.appendChild($0C);
            $0B.appendChild($01);
            c.appendChild($0B);
        };
        this.$2C = function() {
            switch (this.heightSpec) {
                case "Full":
                    return (24 * 2 * this.cellHeight);
                case "BusinessHours":
                    var $0H = this.$2n();
                    return $0H * this.cellHeight * 2;
                case "BusinessHoursNoScroll":
                    var $0H = this.$2n();
                    return $0H * this.cellHeight * 2;
                default:
                    throw "DayPilot.Calendar: Unexpected 'heightSpec' value.";
            }
        };
        this.$2J = function() {
            var parent = $b.nav.corner ? $b.nav.corner.parentNode : null;
            if (!parent) {
                return;
            };
            parent.innerHTML = '';
            var $0I = this.$2K();
            parent.appendChild($0I);
            $b.nav.corner = $0I;
        };
        this.$2D = function() {
            var $0u = document.createElement("div");
            if (!this.$1Y) {
                $0u.style.borderLeft = "1px solid " + this.borderColor;
                $0u.style.borderRight = "1px solid " + this.borderColor;
            };
            $0u.style.overflow = "auto";
            var $0x = document.createElement("table");
            $0x.cellPadding = "0";
            $0x.cellSpacing = "0";
            $0x.border = "0";
            $0x.style.width = "100%";
            $0x.style.borderCollapse = 'separate';
            $0x.style.border = "0px none";
            var r = $0x.insertRow(-1);
            var c = r.insertCell(-1);
            c.style.padding = '0px';
            c.style.border = '0px none';
            var $0I = this.$2K();
            c.appendChild($0I);
            this.nav.corner = $0I;
            c = r.insertCell(-1);
            c.style.width = "100%";
            if (!this.$1Y) {};
            c.valign = "top";
            c.style.position = 'relative';
            c.style.padding = '0px';
            c.style.border = '0px none';
            this.nav.header = document.createElement("table");
            this.nav.header.cellPadding = "0";
            this.nav.header.cellSpacing = "0";
            this.nav.header.border = "0";
            this.nav.header.width = "100%";
            this.nav.header.style.tableLayout = "fixed";
            if (!this.$1Y) {
                this.nav.header.style.borderBottom = "0px none #000000";
                this.nav.header.style.borderRight = "0px none #000000";
                this.nav.header.style.borderLeft = "1px solid " + this.borderColor;
                this.nav.header.style.borderTop = "1px solid " + this.borderColor;
            };
            this.nav.header.oncontextmenu = function() {
                return false;
            };
            var $0J = this.nav.scroll.style.overflow !== 'hidden';
            if (!this.$1Y) {
                if ($0J) {
                    this.nav.header.style.borderRight = "1px solid " + this.borderColor;
                }
            };
            c.appendChild(this.nav.header);
            if ($0J) {
                c = r.insertCell(-1);
                c.unselectable = "on";
                if (!this.$1Y) {};
                var $0K = document.createElement("div");
                $0K.unselectable = "on";
                $0K.style.position = "relative";
                $0K.style.width = "16px";
                if (!this.$1Y) {} else {
                    $0K.style.height = this.headerHeight + "px";
                    $0K.className = this.$2b("_cornerright");
                    var $y = document.createElement("div");
                    $y.className = this.$2b('_cornerright_inner');
                    $0K.appendChild($y);
                };
                c.appendChild($0K);
                this.nav.cornerRight = $0K;
            };
            $0u.appendChild($0x);
            return $0u;
        };
        this.$2K = function() {
            var $0v = document.createElement("div");
            $0v.style.position = 'relative';
            if (this.$1Y) {
                $0v.className = this.$2b("_corner");
            } else {};
            $0v.style.width = this.hourWidth + "px";
            $0v.style.height = this.headerHeight + "px";
            $0v.oncontextmenu = function() {
                return false;
            };
            var $0I = document.createElement("div");
            $0I.unselectable = "on";
            if (this.$1Y) {
                $0I.className = this.$2b("_corner_inner");
            };
            $0v.appendChild($0I);
            return $0v;
        };
        this.$27 = function() {
            var $0x = this.nav.main;
            $0x.root = null;
            $0x.onmouseup = null;
            for (var y = 0; y < $0x.rows.length; y++) {
                var r = $0x.rows[y];
                for (var x = 0; x < r.cells.length; x++) {
                    var c = r.cells[x];
                    c.root = null;
                    c.onmousedown = null;
                    c.onmousemove = null;
                    c.onmouseout = null;
                    c.onmouseup = null;
                }
            };
            if (!this.fasterDispose) DayPilot.pu($0x);
        };
        this.$2i = function() {
            var $0L = [];
            var $0M = [];
            var $0x = this.nav.main;
            var $M = 30 * 60 * 1000;
            var $0N = this.$2o();
            var $06 = $b.columns;
            var $0y = true;
            if ($0x) {
                this.$27();
            }
            while ($0x && $0x.rows && $0x.rows.length > 0 && $0y) {
                if (!this.fasterDispose) {
                    DayPilot.pu($0x.rows[0]);
                };
                $0x.deleteRow(0);
            };
            this.tableCreated = true;
            var cl = $06.length;
            if (this.$1Y) {
                var $0O = this.nav.events;
                while ($0O && $0O.rows && $0O.rows.length > 0 && $0y) {
                    if (!this.fasterDispose) {
                        DayPilot.pu($0O.rows[0]);
                    };
                    $0O.deleteRow(0);
                };
                var cl = $06.length;
                var r = ($0y) ? $0O.insertRow(-1) : $0O.rows[0];
                for (var j = 0; j < cl; j++) {
                    var c = ($0y) ? r.insertCell(-1) : r.cells[j];
                    if ($0y) {
                        c.style.padding = '0px';
                        c.style.border = '0px none';
                        c.style.height = '0px';
                        c.style.overflow = 'visible';
                        if (!$b.rtl) {
                            c.style.textAlign = 'left';
                        };
                        var $Y = document.createElement("div");
                        $Y.style.marginRight = $b.columnMarginRight + "px";
                        $Y.style.position = 'relative';
                        $Y.style.height = '1px';
                        if (!this.$1Y) {
                            $Y.style.fontSize = '1px';
                            $Y.style.lineHeight = '1.2';
                        };
                        $Y.style.marginTop = '-1px';
                        c.appendChild($Y);
                    }
                }
            };
            if (!this.$1Y) {
                var r = ($0y) ? $0x.insertRow(-1) : $0x.rows[0];
                if ($0y) {
                    r.style.backgroundColor = 'white';
                };
                for (var j = 0; j < cl; j++) {
                    var c = ($0y) ? r.insertCell(-1) : r.cells[j];
                    if ($0y) {
                        c.style.padding = '0px';
                        c.style.border = '0px none';
                        c.style.height = '1px';
                        c.style.overflow = 'visible';
                        c.style.width = (100.0 / $06.length) + "%";
                        var $Y = document.createElement("div");
                        $Y.style.display = 'block';
                        $Y.style.marginRight = $b.columnMarginRight + "px";
                        $Y.style.position = 'relative';
                        $Y.style.height = '1px';
                        $Y.style.fontSize = '1px';
                        $Y.style.lineHeight = '1.2';
                        $Y.style.marginTop = '-1px';
                        c.appendChild($Y);
                    }
                }
            };
            for (var i = 0; i < $0N; i++) {
                var $0z = this.$1Y ? 0 : 1;
                var r = ($0y) ? $0x.insertRow(-1) : $0x.rows[i + $0z];
                if ($0y) {
                    r.style.MozUserSelect = 'none';
                    r.style.KhtmlUserSelect = 'none';
                };
                for (var j = 0; j < cl; j++) {
                    var $0s = this.columns[j];
                    var c = ($0y) ? r.insertCell(-1) : r.cells[j];
                    c.start = $0s.Start.addTime(i * $M);
                    c.end = c.start.addTime($M);
                    c.onmousedown = this.$2t;
                    c.onmousemove = this.$2v;
                    c.onmouseup = function() {
                        return false;
                    };
                    c.onclick = function() {
                        return false;
                    };
                    if ($0y) {
                        c.root = this;
                        c.style.padding = '0px';
                        c.style.border = '0px none';
                        c.style.verticalAlign = 'top';
                        if (!this.$1Y) {};
                        c.style.height = $b.cellHeight + 'px';
                        c.style.overflow = 'hidden';
                        c.unselectable = 'on';
                        if (!this.$1Y) {} else {
                            var $Y = document.createElement("div");
                            $Y.unselectable = 'on';
                            $Y.style.height = $b.cellHeight + "px";
                            $Y.style.position = "relative";
                            $Y.className = this.$2b("_cell");
                            var $0P = this.$2L(c.start, c.end);
                            if ($0P && this.$1Y) {
                                DayPilot.Util.addClass($Y, $b.$2b("_cell_business"));
                            };
                            var $y = document.createElement("div");
                            $y.setAttribute("unselectable", "on");
                            $y.className = this.$2b("_cell_inner");
                            $Y.appendChild($y);
                            c.appendChild($Y);
                        };
                        c.appendChild($Y);
                    };
                    $0Q = c.firstChild;
                }
            };
            $0x.onmouseup = this.$2w;
            $0x.root = this;
            $b.nav.scrollable.onmousemove = function(ev) {
                ev = ev || window.event;
                var $0R = $b.nav.scrollable;
                $b.coords = DayPilot.mo3($0R, ev);
                var $Z = DayPilot.mc(ev);
                if (DayPilotCalendar.resizing) {
                    if (!DayPilotCalendar.resizingShadow) {
                        DayPilotCalendar.resizingShadow = $b.$2a(DayPilotCalendar.resizing, false, $b.shadow);
                    };
                    var $0S = $b.cellHeight;
                    var $J = 1;
                    var $0T = ($Z.y - DayPilotCalendar.originalMouse.y);
                    if (DayPilotCalendar.resizing.dpBorder === 'bottom') {
                        var $0U = Math.floor(((DayPilotCalendar.originalHeight + DayPilotCalendar.originalTop + $0T) + $0S / 2) / $0S) * $0S - DayPilotCalendar.originalTop + $J;
                        if ($0U < $0S) $0U = $0S;
                        var $05 = $b.nav.main.clientHeight;
                        if (DayPilotCalendar.originalTop + $0U > $05) $0U = $05 - DayPilotCalendar.originalTop;
                        DayPilotCalendar.resizingShadow.style.height = ($0U) + 'px';
                    } else if (DayPilotCalendar.resizing.dpBorder === 'top') {
                        var $0V = Math.floor(((DayPilotCalendar.originalTop + $0T - $J) + $0S / 2) / $0S) * $0S + $J;
                        if ($0V < $J) {
                            $0V = $J;
                        };
                        if ($0V > DayPilotCalendar.originalTop + DayPilotCalendar.originalHeight - $0S) {
                            $0V = DayPilotCalendar.originalTop + DayPilotCalendar.originalHeight - $0S;
                        };
                        var $0U = DayPilotCalendar.originalHeight - ($0V - DayPilotCalendar.originalTop);
                        if ($0U < $0S) {
                            $0U = $0S;
                        } else {
                            DayPilotCalendar.resizingShadow.style.top = $0V + 'px';
                        };
                        DayPilotCalendar.resizingShadow.style.height = ($0U) + 'px';
                    }
                } else if (DayPilotCalendar.moving) {
                    if (!DayPilotCalendar.movingShadow) {
                        DayPilotCalendar.movingShadow = $b.$2a(DayPilotCalendar.moving, !$b._ie, $b.shadow);
                        DayPilotCalendar.movingShadow.style.width = (DayPilotCalendar.movingShadow.parentNode.offsetWidth + 1) + 'px';
                    };
                    if (!$b.coords) {
                        return;
                    };
                    var $0S = $b.cellHeight;
                    var $J = 1;
                    var $0i = DayPilotCalendar.moveOffsetY;
                    if (!$0i) {
                        $0i = $0S / 2;
                    };
                    var $0V = Math.floor((($b.coords.y - $0i - $J) + $0S / 2) / $0S) * $0S + $J;
                    if ($0V < $J) {
                        $0V = $J;
                    };
                    var $0c = $b.nav.events;
                    var $05 = $b.nav.main.clientHeight;
                    var $g = parseInt(DayPilotCalendar.movingShadow.style.height);
                    if ($0V + $g > $05) {
                        $0V = $05 - $g;
                    };
                    DayPilotCalendar.movingShadow.parentNode.style.display = 'none';
                    DayPilotCalendar.movingShadow.style.top = $0V + 'px';
                    DayPilotCalendar.movingShadow.parentNode.style.display = '';
                    var $0W = $0c.clientWidth / $0c.rows[0].cells.length;
                    var $l = Math.floor(($b.coords.x - 45) / $0W);
                    if ($l < 0) {
                        $l = 0;
                    };
                    if ($l < $0c.rows[0].cells.length && $l >= 0 && DayPilotCalendar.movingShadow.column !== $l) {
                        DayPilotCalendar.movingShadow.column = $l;
                        DayPilotCalendar.moveShadow($0c.rows[0].cells[$l]);
                    }
                }
            };
            $b.nav.scrollable.style.display = '';
        };
        this.$2L = function($K, end) {
            if (this.businessBeginsHour < this.businessEndsHour) {
                return !($K.getHours() < this.businessBeginsHour || $K.getHours() >= this.businessEndsHour || $K.getDayOfWeek() === 6 || $K.getDayOfWeek() === 0);
            };
            if ($K.getHours() >= this.businessBeginsHour) {
                return true;
            };
            if ($K.getHours() < this.businessEndsHour) {
                return true;
            };
            return false;
        };
        this.$28 = function() {
            var $0x = this.nav.header;
            if ($0x && $0x.rows) {
                for (var y = 0; y < $0x.rows.length; y++) {
                    var r = $0x.rows[y];
                    for (var x = 0; x < r.cells.length; x++) {
                        var c = r.cells[x];
                        c.onclick = null;
                        c.onmousemove = null;
                        c.onmouseout = null;
                    }
                }
            };
            if (!this.fasterDispose) DayPilot.pu($0x);
        };
        this.$2M = function($0y) {
            var r = ($0y) ? this.nav.header.insertRow(-1) : this.nav.header.rows[0];
            var $06 = this.columns;
            var $0X = $06.length;
            for (var i = 0; i < $0X; i++) {
                var $o = $06[i];
                var $c = ($0y) ? r.insertCell(-1) : r.cells[i];
                $c.data = $o;
                $c.style.overflow = 'hidden';
                $c.style.padding = '0px';
                $c.style.border = '0px none';
                $c.style.height = (this.headerHeight) + "px";
                var $Y = ($0y) ? document.createElement("div") : $c.firstChild;
                if ($0y) {
                    $Y.unselectable = 'on';
                    $Y.style.MozUserSelect = 'none';
                    $Y.style.backgroundColor = $o.BackColor;
                    $Y.style.cursor = 'default';
                    $Y.style.position = 'relative';
                    if (!this.$1Y) {} else {
                        $Y.className = $b.$2b('_colheader');
                    };
                    $Y.style.height = this.headerHeight + "px";
                    var $0C = document.createElement("div");
                    if (!this.$1Y) {
                        $0C.style.position = 'absolute';
                        $0C.style.left = '0px';
                        $0C.style.width = '100%';
                        $0C.style.padding = "2px";
                        $Y.style.textAlign = 'center';
                    } else {
                        $0C.className = $b.$2b('_colheader_inner');
                    };
                    $0C.unselectable = 'on';
                    $Y.appendChild($0C);
                    $c.appendChild($Y);
                };
                var $0C = $Y.firstChild;
                $0C.innerHTML = $o.InnerHTML;
            }
        };
        this.$2N = function() {
            if (this.width && this.width.indexOf("px") !== -1) {
                return "Pixel";
            };
            return "Percentage";
        };
        this.$2h = function() {
            var $0u = this.nav.header;
            var $0y = true;
            var $06 = this.columns;
            var $0X = $06.length;
            while (this.headerCreated && $0u && $0u.rows && $0u.rows.length > 0 && $0y) {
                if (!this.fasterDispose) DayPilot.pu($0u.rows[0]);
                $0u.deleteRow(0);
            };
            this.headerCreated = true;
            if (!$0y) {
                var $0I = $b.nav.corner;
                if (!this.$1Y) {
                    $0I.style.backgroundColor = $b.cornerBackColor;
                };
                if (!this.fasterDispose) DayPilot.pu($0I.firstChild);
            };
            this.$2M($0y);
        };
        this.loadingStart = function() {
            if (this.loadingLabelVisible) {
                this.nav.loading.innerHTML = this.loadingLabelText;
                this.nav.loading.style.top = (this.headerHeight + 5) + "px";
                this.nav.loading.style.display = '';
            }
        };
        this.commandCallBack = function($0Y, $o) {
            var $G = {};
            $G.command = $0Y;
            this.$23('Command', $o, $G);
        };
        this.loadingStop = function($0Z) {
            if (this.callbackTimeout) {
                window.clearTimeout(this.callbackTimeout);
            };
            this.nav.loading.style.display = 'none';
        };
        this.$2O = function() {
            var $10 = this.nav.scroll;
            if (!$10.onscroll) {
                $10.onscroll = function() {
                    $b.$2P();
                };
            };
            var $11 = (typeof this.$2Q.scrollpos !== 'undefined') ? this.$2Q.scrollpos : this.initScrollPos;
            if (!$11) {
                return;
            };
            if ($11 === 'Auto') {
                if (this.heightSpec === "BusinessHours") {
                    $11 = 2 * this.cellHeight * this.businessBeginsHour;
                } else {
                    $11 = 0;
                }
            };
            $10.root = this;
            if ($10.scrollTop === 0) {
                $10.scrollTop = $11;
            }
        };
        this.callbackError = function($s, $t) {
            alert("Error!\r\nResult: " + $s + "\r\nContext:" + $t);
        };
        this.$2R = function() {
            var w = DayPilot.sw(this.nav.scroll);
            var d = this.nav.cornerRight;
            if (d && w > 0) {
                if (this.$1Y) {
                    d.style.width = w + 'px';
                } else {
                    d.style.width = (w - 3) + 'px';
                }
            }
        };
        this.$2S = function() {
            if (!DayPilotCalendar.globalHandlers) {
                DayPilotCalendar.globalHandlers = true;
                DayPilot.re(document, 'mouseup', DayPilotCalendar.gMouseUp);
                DayPilot.re(window, 'unload', DayPilotCalendar.gUnload);
            }
        };
        this.events = {};
        this.events.add = function(e) {
            e.calendar = $b;
            if (!$b.events.list) {
                $b.events.list = [];
            };
            $b.events.list.push(e.data);
            $b.update();
            $b.$2r.notify();
        };
        this.events.update = function(e) {
            e.commit();
            $b.update();
            $b.$2r.notify();
        };
        this.events.remove = function(e) {
            var $12 = DayPilot.indexOf($b.events.list, e.data);
            $b.events.list.splice($12, 1);
            $b.update();
            $b.$2r.notify();
        };
        this.events.load = function($13, $14, $15) {
            var $16 = function($D) {
                var $17 = {};
                $17.exception = $D.exception;
                $17.request = $D.request;
                if (typeof $15 === 'function') {
                    $15($17);
                }
            };
            var $18 = function($D) {
                var r = $D.request;
                var $o;
                try {
                    $o = JSON.parse(r.responseText);
                } catch (e) {
                    var $19 = {};
                    $19.exception = e;
                    $16($19);
                    return;
                };
                if (DayPilot.isArray($o)) {
                    var $1a = {};
                    $1a.preventDefault = function() {
                        this.preventDefault.value = true;
                    };
                    $1a.data = $o;
                    if (typeof $14 === "function") {
                        $14($1a);
                    };
                    if ($1a.preventDefault.value) {
                        return;
                    };
                    $b.events.list = $o;
                    if ($b.$2T) {
                        $b.update();
                    }
                }
            };
            var $1b = $b.eventsLoadMethod && $b.eventsLoadMethod.toUpperCase() === "POST";
            if ($1b) {
                DayPilot.Http.ajax({
                    "method": "POST",
                    "data": {
                        "start": $b.visibleStart().toString(),
                        "end": $b.visibleEnd().toString()
                    },
                    "url": $13,
                    "success": $18,
                    "error": $16
                });
            } else {
                var $1c = $13;
                var $1d = "start=" + $b.visibleStart().toString() + "&end=" + $b.visibleEnd().toString();
                if ($1c.indexOf("?") > -1) {
                    $1c += "&" + $1d;
                } else {
                    $1c += "?" + $1d;
                };
                DayPilot.Http.ajax({
                    "method": "GET",
                    "url": $1c,
                    "success": $18,
                    "error": $16
                });
            }
        };
        this.update = function() {
            $b.$2U();
            $b.$2V();
            $b.$26();
            $b.nav.top.style.cursor = "auto";
            var $1e = true;
            if ($1e) {
                $b.$2e();
                $b.$2h();
                $b.$2i();
                $b.$2j();
                $b.$2k();
                $b.$2J();
                $b.$2W();
            };
            $b.$2f();
            $b.$2g();
            $b.$2d();
            $b.$2l();
            $b.clearSelection();
            if (this.visible) {
                this.show();
            } else {
                this.hide();
            }
        };
        this.$2X = function() {
            if (this.id && this.id.tagName) {
                this.nav.top = this.id;
            } else if (typeof this.id === "string") {
                this.nav.top = document.getElementById(this.id);
                if (!this.nav.top) {
                    throw "DayPilot.Calendar: The placeholder element not found: '" + id + "'.";
                }
            } else {
                throw "DayPilot.Calendar() constructor requires the target element or its ID as a parameter";
            }
        };
        this.$2Y = {};
        this.$2Y.events = [];
        this.$2Z = function(i) {
            var $1f = this.$2Y.events;
            var $o = this.events.list[i];
            var $1g = {};
            for (var name in $o) {
                $1g[name] = $o[name];
            };
            if (typeof this.onBeforeEventRender === 'function') {
                var $D = {};
                $D.data = $1g;
                this.onBeforeEventRender($D);
            };
            $1f[i] = $1g;
        };
        this.$2f = function() {
            var $0O = this.events.list;
            $b.$2Y.events = [];
            if (!$0O) {
                return;
            };
            var length = $0O.length;
            var $1h = 24 * 60 * 60 * 1000;
            this.cache.pixels = {};
            var $1i = [];
            this.scrollLabels = [];
            this.minStart = 10000;
            this.maxEnd = 0;
            for (var i = 0; i < length; i++) {
                var e = $0O[i];
                e.start = new DayPilot.Date(e.start);
                e.end = new DayPilot.Date(e.end);
            };
            if (typeof this.onBeforeEventRender === 'function') {
                for (var i = 0; i < length; i++) {
                    this.$2Z(i);
                }
            };
            for (var i = 0; i < this.columns.length; i++) {
                var scroll = {};
                scroll.minEnd = 1000000;
                scroll.maxStart = -1;
                this.scrollLabels.push(scroll);
                var $0s = this.columns[i];
                $0s.events = [];
                $0s.lines = [];
                $0s.blocks = [];
                var $1j = new DayPilot.Date($0s.Start);
                var $1k = $1j.getTime();
                var $1l = $1j.addTime($1h);
                var $1m = $1l.getTime();
                for (var j = 0; j < length; j++) {
                    if ($1i[j]) {
                        continue;
                    };
                    var e = $0O[j];
                    var $K = e.start;
                    var end = e.end;
                    var $1n = $K.getTime();
                    var $1o = end.getTime();
                    if ($1o < $1n) {
                        continue;
                    };
                    var $1p = !($1o <= $1k || $1n >= $1m);
                    if ($1p) {
                        var ep = new DayPilot.Event(e, $b);
                        ep.part.dayIndex = i;
                        ep.part.start = $1k < $1n ? e.start : $1j;
                        ep.part.end = $1m > $1o ? e.end : $1l;
                        var $1q = this.getPixels(ep.part.start, $0s.Start);
                        var $1r = this.getPixels(ep.part.end, $0s.Start);
                        var top = $1q.top;
                        var $1s = $1r.top;
                        if (top === $1s && ($1q.cut || $1r.cut)) {
                            continue;
                        };
                        var $1t = $1r.boxBottom;
                        ep.part.top = Math.floor(top / this.cellHeight) * this.cellHeight + 1;
                        ep.part.height = Math.max(Math.ceil($1t / this.cellHeight) * this.cellHeight - ep.part.top, this.cellHeight - 1) + 1;
                        ep.part.barTop = Math.max(top - ep.part.top - 1, 0);
                        ep.part.barHeight = Math.max($1s - top - 2, 1);
                        var $K = ep.part.top;
                        var end = ep.part.top + ep.part.height;
                        if ($K > scroll.maxStart) {
                            scroll.maxStart = $K;
                        };
                        if (end < scroll.minEnd) {
                            scroll.minEnd = end;
                        };
                        if ($K < this.minStart) {
                            this.minStart = $K;
                        };
                        if (end > this.maxEnd) {
                            this.maxEnd = end;
                        };
                        $0s.events.push(ep);
                        if (typeof this.onBeforeEventRender === 'function') {
                            ep.cache = this.$2Y.events[j];
                        };
                        if (ep.part.start.getTime() === $1n && ep.part.end.getTime() === $1o) {
                            $1i[j] = true;
                        }
                    }
                }
            };
            for (var i = 0; i < this.columns.length; i++) {
                var $0s = this.columns[i];
                $0s.events.sort(this.$30);
                for (var j = 0; j < $0s.events.length; j++) {
                    var e = $0s.events[j];
                    $0s.putIntoBlock(e);
                };
                for (var j = 0; j < $0s.blocks.length; j++) {
                    var $01 = $0s.blocks[j];
                    $01.events.sort(this.$30);
                    for (var k = 0; k < $01.events.length; k++) {
                        var e = $01.events[k];
                        $01.putIntoLine(e);
                    }
                }
            }
        };
        this.$30 = function(a, b) {
            if (!a || !b || !a.start || !b.start) {
                return 0;
            };
            var $1u = a.start().getTime() - b.start().getTime();
            if ($1u !== 0) {
                return $1u;
            };
            var $1v = b.end().getTime() - a.end().getTime();
            return $1v;
        };
        this.debug = function($0Z, $1w) {
            if (!this.debuggingEnabled) {
                return;
            };
            if (!$b.debugMessages) {
                $b.debugMessages = [];
            };
            $b.debugMessages.push($0Z);
            if (typeof console !== 'undefined') {
                console.log($0Z);
            }
        };
        this.getPixels = function($S, $K) {
            if (!$K) $K = this.startDate;
            var $1n = $K.getTime();
            var $1x = $S.getTime();
            var $1f = this.cache.pixels[$1x + "_" + $1n];
            if ($1f) {
                return $1f;
            };
            $1n = $K.getTime();
            var $1y = 30 * 60 * 1000;
            var $1z = $1x - $1n;
            var $1A = $1z % $1y;
            var $1B = $1z - $1A;
            var $1C = $1B + $1y;
            if ($1A === 0) {
                $1C = $1B;
            };
            var $s = {};
            $s.cut = false;
            $s.top = this.$31($1z);
            $s.boxTop = this.$31($1B);
            $s.boxBottom = this.$31($1C);
            this.cache.pixels[$1x + "_" + $1n] = $s;
            return $s;
        };
        this.$31 = function($1x) {
            return Math.floor((this.cellHeight * $1x) / (1000 * 60 * 30));
        };
        this.$2V = function() {
            this.startDate = new DayPilot.Date(this.startDate).getDatePart();
        };
        this.$2g = function() {
            if (this.nav.corner) {
                this.nav.corner.style.height = this.headerHeight + "px";
            }
        };
        this.$2k = function() {
            var sh = this.$2C();
            if (this.nav.scroll && sh > 0) {
                this.nav.scroll.style.height = sh + "px";
            }
        };
        this.$2r = {};
        this.$2r.scope = null;
        this.$2r.notify = function() {
            if ($b.$2r.scope) {
                $b.$2r.scope["$apply"]();
            }
        };
        this.$2r.apply = function(f) {
            f();
        };
        this.$2P = function() {
            var top = $b.nav.scroll.scrollTop;
            var $1D = top / (2 * $b.cellHeight);
            $b.$2Q.scrollHour = $1D;
        };
        this.$2W = function() {
            var $11 = 0;
            if ($b.$2Q.scrollHour) {
                $11 = 2 * $b.cellHeight * $b.$2Q.scrollHour;
            } else {
                if ($b.initScrollPos === 'Auto') {
                    if (this.heightSpec === "BusinessHours") {
                        $11 = 2 * this.cellHeight * this.businessBeginsHour;
                    } else {
                        $11 = 0;
                    }
                }
            };
            $b.nav.scroll.scrollTop = $11;
        };
        this.$32 = function() {
            if (this.backendUrl || typeof WebForm_DoCallback === 'function') {
                return (typeof $b.events.list === 'undefined') || (!$b.events.list);
            } else {
                return false;
            }
        };
        this.$2d = function() {
            if (this.nav.top.style.visibility === 'hidden') {
                this.nav.top.style.visibility = 'visible';
            }
        };
        this.show = function() {
            $b.visible = true;
            $b.nav.top.style.display = '';
        };
        this.hide = function() {
            $b.visible = false;
            $b.nav.top.style.display = 'none';
        };
        this.$33 = function() {
            this.$2V();
            this.$2e();
            this.$2B();
            this.$2h();
            this.$2i();
            this.$2R();
            this.$2O();
            this.$2S();
            DayPilotCalendar.register(this);
            this.$34();
            this.$23('Init');
        };
        this.$2Q = {};
        this.$35 = function() {
            this.$2Q.themes = [];
            this.$2Q.themes.push(this.theme || this.cssClassPrefix);
        };
        this.$36 = function() {
            var $1E = this.$2Q.themes;
            for (var i = 0; i < $1E.length; i++) {
                var $1F = $1E[i];
                DayPilot.Util.removeClass(this.nav.top, $1F + "_main");
            };
            this.$2Q.themes = [];
        };
        this.$37 = function() {
            this.afterRender(null, false);
            if (typeof this.onAfterRender === "function") {
                var $D = {};
                $D.isCallBack = false;
                this.onAfterRender($D);
            }
        };
        this.$38 = function() {
            if (typeof this.onInit === "function" && !this.$39) {
                this.$39 = true;
                var $D = {};
                this.onInit($D);
            }
        };
        this.$2U = function() {
            if (!$b.$1Y) {
                $b.$1Y = true;
                window.console && window.console.log && window.console.log("DayPilot: cssOnly = false mode is not supported anymore.");
            }
        };
        this.$3a = function() {
            var el = $b.nav.top;
            return el.offsetWidth > 0 && el.offsetHeight > 0;
        };
        this.$34 = function() {
            var $1G = $b.$3a;
            if (!$1G()) {
                $b.$3b = setInterval(function() {
                    if ($1G()) {
                        $b.$2O();
                        $b.$2R();
                        clearInterval($b.$3b);
                    }
                }, 100);
            }
        };
        this.init = function() {
            this.$2X();
            var $1H = this.$32();
            this.$2U();
            this.$35();
            if ($1H) {
                this.$33();
                return;
            };
            this.$2V();
            this.$2e();
            this.$2f();
            this.$2B();
            this.$2h();
            this.$2i();
            this.$2d();
            this.$2R();
            this.$2O();
            this.$2S();
            DayPilotCalendar.register(this);
            if (this.events) {
                this.$2g();
                this.$2l();
            };
            this.$37();
            this.$38();
            this.$34();
            this.$2T = true;
        };
        this.Init = this.init;
    };
    DayPilotCalendar.Cell = function($K, end, $l) {
        this.start = $K;
        this.end = end;
        this.column = function() {};
    };
    DayPilotCalendar.Column = function($1I, name, $S) {
        this.value = $1I;
        this.name = name;
        this.date = new DayPilot.Date($S);
    };
    DayPilot.Calendar = DayPilotCalendar.Calendar;
    if (typeof jQuery !== 'undefined') {
        (function($) {
            $.fn.daypilotCalendar = function($1J) {
                var $1K = null;
                var j = this.each(function() {
                    if (this.daypilot) {
                        return;
                    };
                    var $1L = new DayPilot.Calendar(this.id);
                    this.daypilot = $1L;
                    for (name in $1J) {
                        $1L[name] = $1J[name];
                    };
                    $1L.init();
                    if (!$1K) {
                        $1K = $1L;
                    }
                });
                if (this.length === 1) {
                    return $1K;
                } else {
                    return j;
                }
            };
        })(jQuery);
    };
    (function registerAngularModule() {
        var $1M = DayPilot.am();
        if (!$1M) {
            return;
        };
        $1M.directive("daypilotCalendar", ['$parse', function($1N) {
            return {
                "restrict": "E",
                "template": "<div></div>",
                "replace": true,
                "link": function($1O, element, $1P) {
                    var $b = new DayPilot.Calendar(element[0]);
                    $b.$2r.scope = $1O;
                    $b.init();
                    var $1Q = $1P["id"];
                    if ($1Q) {
                        $1O[$1Q] = $b;
                    };
                    var $1R = $1P["publishAs"];
                    if ($1R) {
                        var getter = $1N($1R);
                        var setter = getter.assign;
                        setter($1O, $b);
                    };
                    for (var name in $1P) {
                        if (name.indexOf("on") === 0) {
                            (function(name) {
                                $b[name] = function($D) {
                                    var f = $1N($1P[name]);
                                    $1O["$apply"](function() {
                                        f($1O, {
                                            "args": $D
                                        });
                                    });
                                };
                            })(name);
                        }
                    };
                    var $1S = $1O["$watch"];
                    var $1T = $1P["config"] || $1P["daypilotConfig"];
                    var $0O = $1P["events"] || $1P["daypilotEvents"];
                    $1S.call($1O, $1T, function($1I) {
                        for (var name in $1I) {
                            $b[name] = $1I[name];
                        };
                        $b.update();
                        $b.$38();
                    }, true);
                    $1S.call($1O, $0O, function($1I) {
                        $b.events.list = $1I;
                        $b.update();
                    }, true);
                }
            };
        }]);
    })();
})();


if (typeof DayPilot === 'undefined') {
    var DayPilot = {};
};
(function() {
    if (typeof DayPilot.DatePicker !== 'undefined') {
        return;
    };
    DayPilot.DatePicker = function($a) {
        this.v = '2021.1.248-lite';
        var $b = "navigator_" + new Date().getTime();
        var $c = this;
        this.prepare = function() {
            this.locale = "en-us";
            this.target = null;
            this.resetTarget = true;
            this.pattern = this.$m.locale().datePattern;
            this.cssClassPrefix = "navigator_default";
            this.theme = null;
            this.patterns = [];
            if ($a) {
                for (var name in $a) {
                    this[name] = $a[name];
                }
            };
            this.init();
        };
        this.init = function() {
            this.date = new DayPilot.Date(this.date);
            var $d = this.$n();
            if (this.resetTarget && !$d) {
                this.$o(this.date);
            };
            DayPilot.re(document, "mousedown", function() {
                $c.close();
            });
        };
        this.close = function() {
            if (!this.$p) {
                return;
            };
            if (this.navigator) {
                this.navigator.dispose();
            };
            this.div.innerHTML = '';
            if (this.div && this.div.parentNode === document.body) {
                document.body.removeChild(this.div);
            }
        };
        this.$n = function() {
            var element = this.$q();
            if (!element) {
                return this.date;
            };
            var $d = null;
            if (element.tagName === "INPUT") {
                $d = element.value;
            } else {
                $d = element.innerText;
            };
            if (!$d) {
                return null;
            };
            var $e = DayPilot.Date.parse($d, $c.pattern);
            for (var i = 0; i < $c.patterns.length; i++) {
                if ($e) {
                    return $e;
                };
                $e = DayPilot.Date.parse($d, $c.patterns[i]);
            };
            return $e;
        };
        this.$o = function($e) {
            var element = this.$q();
            if (!element) {
                return;
            };
            var $d = $e.toString($c.pattern, $c.locale);
            if (element.tagName === "INPUT") {
                element.value = $d;
            } else {
                element.innerHTML = $d;
            }
        };
        this.$m = {};
        this.$m.locale = function() {
            return DayPilot.Locale.find($c.locale);
        };
        this.$q = function() {
            var id = this.target;
            var element = (id && id.nodeType && id.nodeType === 1) ? id : document.getElementById(id);
            return element;
        };
        this.show = function() {
            var element = this.$q();
            var navigator = this.navigator;
            var navigator = new DayPilot.Navigator($b);
            navigator.api = 2;
            navigator.$r = true;
            navigator.theme = $c.theme || $c.cssClassPrefix;
            navigator.weekStarts = "Auto";
            navigator.locale = $c.locale;
            navigator.timeRangeSelectedHandling = "JavaScript";
            navigator.onTimeRangeSelected = function($f) {
                $c.date = $f.start;
                var $g = $f.start;
                var $d = $g.toString($c.pattern, $c.locale);
                var $f = {};
                $f.start = $g;
                $f.date = $g;
                $f.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $c.onTimeRangeSelect === 'function') {
                    $c.onTimeRangeSelect($f);
                    if ($f.preventDefault.value) {
                        return;
                    }
                };
                $c.$o($d);
                $c.close();
                if (typeof $c.onTimeRangeSelected === 'function') {
                    $c.onTimeRangeSelected($f);
                }
            };
            this.navigator = navigator;
            var $h = DayPilot.abs(element);
            var $i = element.offsetHeight;
            var $j = document.createElement("div");
            $j.style.position = "absolute";
            $j.style.left = $h.x + "px";
            $j.style.top = ($h.y + $i) + "px";
            var $k = document.createElement("div");
            $k.id = $b;
            $j.appendChild($k);
            DayPilot.re($j, "mousedown", function(ev) {
                var ev = ev || window.event;
                ev.cancelBubble = true;
                ev.stopPropagation && ev.stopPropagation();
            });
            document.body.appendChild($j);
            this.div = $j;
            var $l = $c.$n() || new DayPilot.Date().getDatePart();
            navigator.startDate = $l;
            navigator.selectionDay = $l;
            navigator.init();
            this.$p = true;
        };
        this.prepare();
    };
})();


if (typeof(DayPilot) === 'undefined') {
    DayPilot = {};
};
(function() {
    'use strict';
    DayPilot.ModalStatic = {};
    DayPilot.ModalStatic.list = [];
    DayPilot.ModalStatic.hide = function() {
        if (this.list.length > 0) {
            var $a = this.list.pop();
            if ($a) {
                $a.hide();
            }
        }
    };
    DayPilot.ModalStatic.remove = function($b) {
        var $c = DayPilot.ModalStatic.list;
        for (var i = 0; i < $c.length; i++) {
            if ($c[i] === $b) {
                $c.splice(i, 1);
                return;
            }
        }
    };
    DayPilot.ModalStatic.close = function($d) {
        DayPilot.ModalStatic.result($d);
        DayPilot.ModalStatic.hide();
    };
    DayPilot.ModalStatic.result = function(r) {
        var $c = DayPilot.ModalStatic.list;
        if ($c.length > 0) {
            $c[$c.length - 1].result = r;
        }
    };
    DayPilot.ModalStatic.displayed = function($b) {
        var $c = DayPilot.ModalStatic.list;
        for (var i = 0; i < $c.length; i++) {
            if ($c[i] === $b) {
                return true;
            }
        };
        return false;
    };
    DayPilot.ModalStatic.stretch = function() {
        if (this.list.length > 0) {
            var $a = this.list[this.list.length - 1];
            if ($a) {
                $a.stretch();
            }
        }
    };
    DayPilot.ModalStatic.last = function() {
        var $c = DayPilot.ModalStatic.list;
        if ($c.length > 0) {
            return $c[$c.length - 1];
        };
        return null;
    };
    var $e = function() {
        var style = document.createElement("style");
        style.setAttribute("type", "text/css");
        if (!style.styleSheet) {
            style.appendChild(document.createTextNode(""));
        };
        var h = document.head || document.getElementsByTagName('head')[0];
        h.appendChild(style);
        var $f = !!style.styleSheet;
        var $g = {};
        $g.rules = [];
        $g.commit = function() {
            try {
                if ($f) {
                    style.styleSheet.cssText = this.rules.join("\n");
                }
            } catch (e) {}
        };
        $g.add = function($h, $i, $j) {
            if ($f) {
                this.rules.push($h + "{" + $i + "\u007d");
                return;
            };
            if (style.sheet.insertRule) {
                if (typeof $j === "undefined") {
                    $j = style.sheet.cssRules.length;
                };
                style.sheet.insertRule($h + "{" + $i + "\u007d", $j);
            } else if (style.sheet.addRule) {
                style.sheet.addRule($h, $i, $j);
            } else {
                throw "No CSS registration method found";
            }
        };
        return $g;
    };
    var $g = new $e();
    $g.add(".modal_default_main", "border: 10px solid #ccc;");
    $g.add(".modal_default_inner", "padding: 10px;");
    $g.add(".modal_default_input", "padding: 10px 0px;");
    $g.add(".modal_default_buttons", "padding: 10px 0px;");
    $g.add(".modal_default_background", "opacity: 0.3; background-color: #000;");
    $g.add(".modal_min_main", "border: 1px solid #ccc;");
    $g.add(".modal_min_background", "opacity: 0.3; background-color: #000;");
    $g.commit();
    DayPilot.Modal = function($k) {
        this.autoStretch = true;
        this.autoStretchFirstLoadOnly = false;
        this.className = null;
        this.theme = "modal_default";
        this.disposeOnClose = true;
        this.dragDrop = true;
        this.loadingHtml = null;
        this.maxHeight = null;
        this.scrollWithPage = true;
        this.useIframe = true;
        this.zIndex = null;
        this.left = null;
        this.width = 500;
        this.top = 20;
        this.height = 200;
        this.closed = null;
        this.onClosed = null;
        this.onShow = null;
        var $l = this;
        this.id = '_' + new Date().getTime() + 'n' + (Math.random() * 10);
        this.$0c = false;
        this.$0d = null;
        this.$0e = null;
        this.showHtml = function($m) {
            if (DayPilot.ModalStatic.displayed(this)) {
                throw "This modal dialog is already displayed.";
            };
            if (!this.div) {
                this.$0f();
            };
            this.$0g();
            if (this.useIframe) {
                var $n = function(p, $o) {
                    return function() {
                        p.setInnerHTML(p.id + "iframe", $o);
                    };
                };
                window.setTimeout($n(this, $m), 0);
            } else {
                if ($m.nodeType) {
                    this.div.appendChild($m);
                } else {
                    this.div.innerHTML = $m;
                }
            };
            this.$0g();
            this.$0h();
            this.$0i();
        };
        this.$0j = function() {
            return this.corners && this.corners.toLowerCase() === 'rounded';
        };
        this.showUrl = function($p) {
            if (DayPilot.ModalStatic.displayed(this)) {
                throw "This modal dialog is already displayed.";
            };
            if (this.useIframe) {
                if (!this.div) {
                    this.$0f();
                };
                var $q = this.loadingHtml;
                if ($q) {
                    this.iframe.src = "about:blank";
                    this.setInnerHTML(this.id + "iframe", $q);
                };
                this.re(this.iframe, "load", this.$0k);
                this.iframe.src = $p;
                this.$0g();
                this.$0h();
                this.$0i();
            } else {
                $l.$0l({
                    "url": $p,
                    "success": function($r) {
                        var $m = $r.request.responseText;
                        $l.showHtml($m);
                    },
                    "error": function($r) {
                        $l.showHtml("Error loading the modal dialog");
                    }
                })
            }
        };
        this.$0i = function() {
            if (typeof $l.onShow === "function") {
                var $r = {};
                $r.root = $l.$0m();
                $l.onShow($r);
            }
        };
        this.$0m = function() {
            return $l.iframe ? $l.iframe.contentWindow.document : $l.div;
        };
        this.$0l = function($s) {
            var $t = new XMLHttpRequest();
            if (!$t) {
                return;
            };
            var $u = $s.method || "GET";
            var $v = $s.success || function() {};
            var $w = $s.error || function() {};
            var $x = $s.data;
            var $p = $s.url;
            $t.open($u, $p, true);
            $t.setRequestHeader('Content-type', 'text/plain');
            $t.onreadystatechange = function() {
                if ($t.readyState !== 4) return;
                if ($t.status !== 200 && $t.status !== 304) {
                    if ($w) {
                        var $r = {};
                        $r.request = $t;
                        $w($r);
                    } else {
                        if (window.console) {
                            console.log('HTTP error ' + $t.status);
                        }
                    };
                    return;
                };
                var $r = {};
                $r.request = $t;
                $v($r);
            };
            if ($t.readyState === 4) {
                return;
            };
            if (typeof $x === 'object') {
                $x = JSON.stringify($x);
            };
            $t.send($x);
        };
        this.$0g = function() {
            var $y = window;
            var $z = document;
            var scrollY = $y.pageYOffset ? $y.pageYOffset : (($z.documentElement && $z.documentElement.scrollTop) ? $z.documentElement.scrollTop : $z.body.scrollTop);
            var $A = function() {
                return $l.$0n().y;
            };
            if (this.theme) {
                this.hideDiv.className = this.theme + "_background";
            };
            if (this.zIndex) {
                this.hideDiv.style.zIndex = this.zIndex;
            };
            this.hideDiv.style.display = '';
            window.setTimeout(function() {
                $l.hideDiv.onclick = function() {
                    $l.hide({
                        "backgroundClick": true
                    });
                };
            }, 500);
            if (this.theme) {
                this.div.className = this.theme + "_main";
            } else {
                this.div.className = "";
            };
            if (this.className) {
                this.div.className += " " + this.className;
            };
            if (this.left) {
                this.div.style.left = this.left + "px";
            } else {
                this.div.style.marginLeft = '-' + Math.floor(this.width / 2) + "px";
            };
            this.div.style.position = 'absolute';
            this.div.style.boxSizing = "content-box";
            this.div.style.top = (scrollY + this.top) + 'px';
            this.div.style.width = this.width + 'px';
            if (this.zIndex) {
                this.div.style.zIndex = this.zIndex;
            };
            if (this.height) {
                if (this.useIframe || !this.autoStretch) {
                    this.div.style.height = this.height + 'px';
                } else {
                    this.div.style.height = '';
                }
            };
            if (this.useIframe && this.height) {
                this.iframe.style.height = (this.height) + 'px';
            };
            this.div.style.display = '';
            DayPilot.ModalStatic.list.push(this);
        };
        this.$0k = function() {
            $l.iframe.contentWindow.modal = $l;
            if ($l.autoStretch) {
                $l.stretch();
            }
        };
        this.stretch = function() {
            var $A = function() {
                return $l.$0n().y;
            };
            var $B = function() {
                return $l.$0n().x;
            };
            if (this.useIframe) {
                var $C = $B() - 40;
                for (var w = this.width; w < $C && this.$0o(); w += 10) {
                    this.div.style.width = w + 'px';
                    this.div.style.marginLeft = '-' + Math.floor(w / 2) + "px";
                };
                var $D = this.maxHeight || $A() - 2 * this.top;
                for (var h = this.height; h < $D && this.$0p(); h += 10) {
                    this.iframe.style.height = (h) + 'px';
                    this.div.style.height = h + 'px';
                };
                if (this.autoStretchFirstLoadOnly) {
                    this.ue(this.iframe, "load", this.$0k);
                }
            } else {
                this.div.style.height = '';
            }
        };
        this.$0o = function() {
            var document = this.iframe.contentWindow.document;
            var $E = document.compatMode === 'BackCompat' ? document.body : document.documentElement;
            var $F = $E.scrollWidth;
            var $G = document.body.children;
            for (var i = 0; i < $G.length; i++) {
                var $H = $G[i].offsetLeft + $G[i].offsetWidth;
                $F = Math.max($F, $H);
            };
            var $I = $F > $E.clientWidth;
            return $I;
        };
        this.$0p = function() {
            var document = this.iframe.contentWindow.document;
            var $E = document.compatMode === 'BackCompat' ? document.body : document.documentElement;
            var $J = $E.scrollHeight;
            var $G = document.body.children;
            for (var i = 0; i < $G.length; i++) {
                var $H = $G[i].offsetTop + $G[i].offsetHeight;
                $J = Math.max($J, $H);
            };
            var $K = $J > $E.clientHeight;
            return $K;
        };
        this.$0n = function() {
            var $z = document;
            if ($z.compatMode === "CSS1Compat" && $z.documentElement && $z.documentElement.clientWidth) {
                var x = $z.documentElement.clientWidth;
                var y = $z.documentElement.clientHeight;
                return {
                    x: x,
                    y: y
                };
            } else {
                var x = $z.body.clientWidth;
                var y = $z.body.clientHeight;
                return {
                    x: x,
                    y: y
                };
            }
        };
        this.$0h = function() {
            if (this.$0c) {
                return;
            };
            this.re(window, 'resize', this.$0q);
            this.re(window, 'scroll', this.$0r);
            if (this.dragDrop) {
                this.re(document, 'mousemove', this.$0s);
                this.re(document, 'mouseup', this.$0t);
            };
            this.$0c = true;
        };
        this.$0u = function(e) {
            if (e.target !== $l.div) {
                return;
            };
            $l.div.style.cursor = "move";
            $l.$0v();
            $l.$0e = $l.mc(e || window.event);
            $l.$0d = {
                x: $l.div.offsetLeft,
                y: $l.div.offsetTop
            };
        };
        this.$0s = function(e) {
            if (!$l.$0e) {
                return;
            };
            var e = e || window.event;
            var $L = $l.mc(e);
            var x = $L.x - $l.$0e.x;
            var y = $L.y - $l.$0e.y;
            $l.div.style.marginLeft = '0px';
            $l.div.style.top = ($l.$0d.y + y) + "px";
            $l.div.style.left = ($l.$0d.x + x) + "px";
        };
        this.$0t = function(e) {
            if (!$l.$0e) {
                return;
            };
            $l.$0w();
            $l.div.style.cursor = null;
            $l.$0e = null;
        };
        this.$0v = function() {
            if (!this.useIframe) {
                return;
            };
            var $M = 80;
            var $N = document.createElement("div");
            $N.style.backgroundColor = "#ffffff";
            $N.style.filter = "alpha(opacity=" + $M + ")";
            $N.style.opacity = "0." + $M;
            $N.style.width = "100%";
            $N.style.height = this.height + "px";
            $N.style.position = "absolute";
            $N.style.left = '0px';
            $N.style.top = '0px';
            this.div.appendChild($N);
            this.mask = $N;
        };
        this.$0w = function() {
            if (!this.useIframe) {
                return;
            };
            this.div.removeChild(this.mask);
            this.mask = null;
        };
        this.$0q = function() {
            $l.$0x;
        };
        this.$0r = function() {
            $l.$0x;
        };
        this.$0x = function() {
            if (!$l.hideDiv) {
                return;
            };
            if (!$l.div) {
                return;
            };
            if ($l.hideDiv.style.display === 'none') {
                return;
            };
            if ($l.div.style.display === 'none') {
                return;
            };
            var scrollY = window.pageYOffset ? window.pageYOffset : ((document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop);
            if (!$l.scrollWithPage) {
                $l.div.style.top = (scrollY + $l.top) + 'px';
            }
        };
        this.re = function(el, ev, $O) {
            if (el.addEventListener) {
                el.addEventListener(ev, $O, false);
            } else if (el.attachEvent) {
                el.attachEvent("on" + ev, $O);
            }
        };
        this.ue = function(el, ev, $O) {
            if (el.removeEventListener) {
                el.removeEventListener(ev, $O, false);
            } else if (el.detachEvent) {
                el.detachEvent("on" + ev, $O);
            }
        };
        this.mc = function(ev) {
            if (ev.pageX || ev.pageY) {
                return {
                    x: ev.pageX,
                    y: ev.pageY
                };
            };
            return {
                x: ev.clientX + document.documentElement.scrollLeft,
                y: ev.clientY + document.documentElement.scrollTop
            };
        };
        this.abs = function(element) {
            var r = {
                x: element.offsetLeft,
                y: element.offsetTop
            };
            while (element.offsetParent) {
                element = element.offsetParent;
                r.x += element.offsetLeft;
                r.y += element.offsetTop;
            };
            return r;
        };
        this.$0f = function() {
            var $P = document.createElement("div");
            $P.id = this.id + "hide";
            $P.style.position = 'fixed';
            $P.style.left = "0px";
            $P.style.top = "0px";
            $P.style.right = "0px";
            $P.style.bottom = "0px";
            $P.oncontextmenu = function() {
                return false;
            };
            $P.onmousedown = function() {
                return false;
            };
            document.body.appendChild($P);
            var $Q = document.createElement("div");
            $Q.id = this.id + 'popup';
            $Q.style.position = 'fixed';
            $Q.style.left = '50%';
            $Q.style.top = '0px';
            $Q.style.backgroundColor = 'white';
            $Q.style.width = "50px";
            $Q.style.height = "50px";
            if (this.dragDrop) {
                $Q.onmousedown = this.$0u;
            };
            var $R = 50;
            var $S = null;
            if (this.useIframe) {
                $S = document.createElement("iframe");
                $S.id = this.id + "iframe";
                $S.name = this.id + "iframe";
                $S.frameBorder = '0';
                $S.style.width = '100%';
                $S.style.height = $R + 'px';
                $Q.appendChild($S);
            };
            document.body.appendChild($Q);
            this.div = $Q;
            this.iframe = $S;
            this.hideDiv = $P;
        };
        this.setInnerHTML = function(id, $o) {
            var $T = window.frames[id];
            var $z = $T.contentWindow || $T.document || $T.contentDocument;
            if ($z.document) {
                $z = $z.document;
            };
            if ($z.body == null) {
                $z.write("<body></body>");
            };
            if ($o.nodeType) {
                $z.body.appendChild($o);
            } else {
                $z.body.innerHTML = $o;
            };
            if ($l.autoStretch) {
                if (!$l.autoStretchFirstLoadOnly || !$l.$0y) {
                    $l.stretch();
                    $l.$0y = true;
                }
            }
        };
        this.close = function($d) {
            this.result = $d;
            this.hide();
        };
        this.hide = function($k) {
            $k = $k || {};
            var $r = {};
            $r.backgroundClick = !!$k.backgroundClick;
            $r.result = this.result;
            $r.preventDefault = function() {
                this.preventDefault.value = true;
            };
            if (typeof this.onClose === "function") {
                this.onClose($r);
                if ($r.preventDefault.value) {
                    return;
                }
            };
            if (this.div) {
                this.div.style.display = 'none';
                this.hideDiv.style.display = 'none';
                if (!this.useIframe) {
                    this.div.innerHTML = null;
                }
            };
            window.focus();
            DayPilot.ModalStatic.remove(this);
            if (typeof this.onClosed === "function") {
                this.onClosed($r);
            } else if (this.closed) {
                this.closed();
            };
            this.result = null;
            if (this.disposeOnClose && $l.div) {
                var p = $l.div.parentNode;
                p.removeChild($l.div);
                $l.div = null;
            }
        };
        this.$0z = function() {
            if (!$k) {
                return;
            };
            for (var name in $k) {
                this[name] = $k[name];
            }
        };
        this.$0z();
    };
    DayPilot.Modal.alert = function($U, $k) {
        $k = $k || {};
        $k.height = $k.height || 40;
        $k.useIframe = false;
        var $V = $k.okText || "OK";
        var $W = $k.cancelText || "Cancel";
        return DayPilot.getPromise(function($v, $X) {
            $k.onClosed = function($r) {
                $v($r);
            };
            var $b = new DayPilot.Modal($k);
            var $Q = document.createElement("div");
            $Q.className = $b.theme + "_inner";
            var $Y = document.createElement("div");
            $Y.className = $b.theme + "_content";
            $Y.innerHTML = $U;
            var $Z = document.createElement("div");
            $Z.className = $b.theme + "_buttons";
            var $00 = document.createElement("button");
            $00.innerText = $V;
            $00.className = $b.theme + "_ok";
            $00.onclick = function(e) {
                parent.DayPilot.ModalStatic.close("OK");
            };
            $Z.appendChild($00);
            $Q.appendChild($Y);
            $Q.appendChild($Z);
            $b.showHtml($Q);
            $00.focus();
        });
    };
    DayPilot.Modal.confirm = function($U, $k) {
        $k = $k || {};
        $k.height = $k.height || 40;
        $k.useIframe = false;
        var $V = $k.okText || "OK";
        var $W = $k.cancelText || "Cancel";
        return DayPilot.getPromise(function($v, $X) {
            $k.onClosed = function($r) {
                $v($r);
            };
            var $b = new DayPilot.Modal($k);
            var $Q = document.createElement("div");
            $Q.className = $b.theme + "_inner";
            var $Y = document.createElement("div");
            $Y.className = $b.theme + "_content";
            $Y.innerHTML = $U;
            var $Z = document.createElement("div");
            $Z.className = $b.theme + "_buttons";
            var $00 = document.createElement("button");
            $00.innerText = $V;
            $00.className = $b.theme + "_ok";
            $00.onclick = function(e) {
                parent.DayPilot.ModalStatic.close("OK");
            };
            var $01 = document.createTextNode(" ");
            var $02 = document.createElement("button");
            $02.innerText = $W;
            $02.className = $b.theme + "_cancel";
            $02.onclick = function(e) {
                parent.DayPilot.ModalStatic.close();
            };
            $Z.appendChild($00);
            $Z.appendChild($01);
            $Z.appendChild($02);
            $Q.appendChild($Y);
            $Q.appendChild($Z);
            $b.showHtml($Q);
            $00.focus();
        });
    };
    DayPilot.Modal.prompt = function($U, $k) {
        $k = $k || {};
        $k.height = $k.height || 40;
        $k.useIframe = false;
        var $V = $k.okText || "OK";
        var $W = $k.cancelText || "Cancel";
        return DayPilot.getPromise(function($v, $X) {
            $k.onClosed = function($r) {
                $v($r);
            };
            var $b = new DayPilot.Modal($k);
            var $Q = document.createElement("div");
            $Q.className = $b.theme + "_inner";
            var $Y = document.createElement("div");
            $Y.className = $b.theme + "_content";
            $Y.innerHTML = $U;
            var $03 = document.createElement("div");
            $03.className = $b.theme + "_input";
            var $04 = document.createElement("input");
            $04.style.width = "100%";
            $04.onkeydown = function(e) {
                switch (e.keyCode) {
                    case 13:
                        parent.DayPilot.ModalStatic.close(this.value);
                        break;
                    case 27:
                        parent.DayPilot.ModalStatic.close();
                        break;
                }
            };
            $03.appendChild($04);
            var $Z = document.createElement("div");
            $Z.className = $b.theme + "_buttons";
            var $00 = document.createElement("button");
            $00.innerText = $V;
            $00.className = $b.theme + "_ok";
            $00.onclick = function(e) {
                parent.DayPilot.ModalStatic.close($04.value);
            };
            var $01 = document.createTextNode(" ");
            var $02 = document.createElement("button");
            $02.innerText = $W;
            $02.className = $b.theme + "_cancel";
            $02.onclick = function(e) {
                parent.DayPilot.ModalStatic.close();
            };
            $Z.appendChild($00);
            $Z.appendChild($01);
            $Z.appendChild($02);
            $Q.appendChild($Y);
            $Q.appendChild($03);
            $Q.appendChild($Z);
            $b.showHtml($Q);
            $04.focus();
        });
    };
    DayPilot.Modal.close = function($d) {
        if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
            parent.DayPilot.ModalStatic.close($d);
        } else {
            throw "Unable to close DayPilot.Modal dialog.";
        }
    };
    DayPilot.Modal.closeSerialized = function() {
        var $a = DayPilot.Modal.opener() || DayPilot.ModalStatic.last();
        var $05 = $a.$0m();
        var $06 = $05.querySelectorAll("input, textarea, select");
        var $d = {};
        for (var i = 0; i < $06.length; i++) {
            var $07 = $06[i];
            var name = $07.name;
            if (!name) {
                continue;
            };
            var $08 = $07.value;
            $d[name] = $08;
        };
        DayPilot.Modal.close($d);
    };
    DayPilot.Modal.opener = function() {
        return parent && parent.DayPilot && parent.DayPilot.ModalStatic && parent.DayPilot.ModalStatic.list[parent.DayPilot.ModalStatic.list.length - 1];
    };
    if (typeof DayPilot.getPromise === "undefined") {
        DayPilot.getPromise = function(f) {
            if (typeof $09 !== 'undefined') {
                return new $09(f);
            };
            DayPilot.Promise = function(f) {
                var p = this;
                this.then = function($0a, $0b) {
                    $0a = $0a || function() {};
                    $0b = $0b || function() {};
                    f($0a, $0b);
                    return DayPilot.getPromise(f);
                };
                this['catch'] = function($0b) {
                    p.then(null, $0b);
                    return DayPilot.getPromise(f);
                };
            };
            return new DayPilot.Promise(f);
        };
    }
})();


if (typeof DayPilot === 'undefined') {
    var DayPilot = {};
};
if (typeof DayPilot.Global === 'undefined') {
    DayPilot.Global = {};
};
(function() {
    var $a = function() {};
    if (typeof DayPilot.Month !== 'undefined') {
        return;
    };
    var DayPilotMonth = {};
    DayPilotMonth.Month = function($b) {
        this.v = '2021.1.248-lite';
        this.nav = {};
        var $c = this;
        this.id = $b;
        this.isMonth = true;
        this.hideUntilInit = true;
        this.angularAutoApply = false;
        this.api = 2;
        this.$1a = true;
        this.cssClassPrefix = "month_default";
        this.theme = null;
        this.startDate = new DayPilot.Date();
        this.width = '100%';
        this.cellHeight = 100;
        this.headerHeight = 30;
        this.weekStarts = 1;
        this.eventHeight = 30;
        this.cellHeaderHeight = 24;
        this.afterRender = function() {};
        this.eventsLoadMethod = "GET";
        this.lineSpace = 1;
        this.eventClickHandling = 'Enabled';
        this.eventMoveHandling = 'Update';
        this.eventResizeHandling = 'Update';
        this.timeRangeSelectedHandling = 'Enabled';
        this.onEventClick = null;
        this.onEventClicked = null;
        this.onEventMove = null;
        this.onEventMoved = null;
        this.onEventResize = null;
        this.onEventResized = null;
        this.onTimeRangeSelect = null;
        this.onTimeRangeSelected = null;
        this.backendUrl = null;
        this.cellEvents = [];
        this.elements = {};
        this.elements.events = [];
        this.cache = {};
        this.$1b = function($d, $e) {
            var $d = eval("(" + $d + ")");
            if ($d.CallBackRedirect) {
                document.location.href = $d.CallBackRedirect;
                return;
            };
            if ($d.UpdateType === "None") {
                $c.fireAfterRenderDetached($d.CallBackData, true);
                return;
            };
            $c.events.list = $d.Events;
            if ($d.UpdateType === "Full") {
                $c.startDate = $d.StartDate;
                $c.timeFormat = $d.TimeFormat ? $d.TimeFormat : $c.timeFormat;
                if (typeof $d.WeekStarts !== 'undefined') {
                    $c.weekStarts = $d.WeekStarts;
                };
                $c.hashes = $d.Hashes;
            };
            $c.$1c();
            $c.$1d();
            $c.$1e();
            if ($d.UpdateType === "Full") {
                $c.$1f();
                $c.$1g();
            };
            $c.$1h();
            $c.show();
            $c.$1i();
            $c.fireAfterRenderDetached($d.CallBackData, true);
        };
        this.fireAfterRenderDetached = function($f, $g) {
            var $h = function($f, $i) {
                return function() {
                    if ($c.afterRender) {
                        $c.afterRender($f, $i);
                    }
                };
            };
            window.setTimeout($h($f, $g), 0);
        };
        this.lineHeight = function() {
            return this.eventHeight + this.lineSpace;
        };
        this.events = {};
        this.events.add = function(e) {
            e.calendar = $c;
            if (!$c.events.list) {
                $c.events.list = [];
            };
            $c.events.list.push(e.data);
            $c.update();
            $c.$1j.notify();
        };
        this.events.update = function(e) {
            e.commit();
            $c.update();
            $c.$1j.notify();
        };
        this.events.remove = function(e) {
            var $j = DayPilot.indexOf($c.events.list, e.data);
            $c.events.list.splice($j, 1);
            $c.update();
            $c.$1j.notify();
        };
        this.events.load = function($k, $l, $m) {
            var $n = function($o) {
                var $p = {};
                $p.exception = $o.exception;
                $p.request = $o.request;
                if (typeof $m === 'function') {
                    $m($p);
                }
            };
            var $q = function($o) {
                var r = $o.request;
                var $f;
                try {
                    $f = JSON.parse(r.responseText);
                } catch (e) {
                    var $r = {};
                    $r.exception = e;
                    $n($r);
                    return;
                };
                if (DayPilot.isArray($f)) {
                    var $s = {};
                    $s.preventDefault = function() {
                        this.preventDefault.value = true;
                    };
                    $s.data = $f;
                    if (typeof $l === "function") {
                        $l($s);
                    };
                    if ($s.preventDefault.value) {
                        return;
                    };
                    $c.events.list = $f;
                    if ($c.$1k) {
                        $c.update();
                    }
                }
            };
            var $t = $c.eventsLoadMethod && $c.eventsLoadMethod.toUpperCase() === "POST";
            if ($t) {
                DayPilot.Http.ajax({
                    "method": "POST",
                    "data": {
                        "start": $c.visibleStart().toString(),
                        "end": $c.visibleEnd().toString()
                    },
                    "url": $k,
                    "success": $q,
                    "error": $n
                });
            } else {
                var $u = $k;
                var $v = "start=" + $c.visibleStart().toString() + "&end=" + $c.visibleEnd().toString();
                if ($u.indexOf("?") > -1) {
                    $u += "&" + $v;
                } else {
                    $u += "?" + $v;
                };
                DayPilot.Http.ajax({
                    "method": "GET",
                    "url": $u,
                    "success": $q,
                    "error": $n
                });
            }
        };
        this.update = function() {
            if (!this.cells) {
                return;
            };
            var $w = true;
            this.$1l();
            $c.$1c();
            $c.$1d();
            $c.$1e();
            if ($w) {
                $c.$1f();
                $c.$1g();
            };
            $c.$1h();
            $c.show();
            $c.$1i();
        };
        this.$1m = {};
        this.$1m.events = [];
        this.$1n = function(i) {
            var $x = this.$1m.events;
            var $f = this.events.list[i];
            var $y = {};
            for (var name in $f) {
                $y[name] = $f[name];
            };
            if (typeof this.onBeforeEventRender === 'function') {
                var $o = {};
                $o.data = $y;
                this.onBeforeEventRender($o);
            };
            $x[i] = $y;
        };
        this.$1e = function() {
            var $z = this.events.list;
            if (!$z) {
                return;
            };
            if (typeof this.onBeforeEventRender === 'function') {
                for (var i = 0; i < $z.length; i++) {
                    this.$1n(i);
                }
            };
            for (var x = 0; x < $z.length; x++) {
                var $f = $z[x];
                $f.start = new DayPilot.Date($f.start);
                $f.end = new DayPilot.Date($f.end);
                if ($f.start.getTime() > $f.end.getTime()) {
                    continue;
                };
                for (var i = 0; i < this.rows.length; i++) {
                    var $A = this.rows[i];
                    var ep = new DayPilot.Event($f, this);
                    if ($A.belongsHere(ep)) {
                        $A.events.push(ep);
                        if (typeof this.onBeforeEventRender === 'function') {
                            ep.cache = this.$1m.events[x];
                        }
                    }
                }
            };
            for (var ri = 0; ri < this.rows.length; ri++) {
                var $A = this.rows[ri];
                $A.events.sort(this.$1o);
                for (var ei = 0; ei < this.rows[ri].events.length; ei++) {
                    var ev = $A.events[ei];
                    var $B = $A.getStartColumn(ev);
                    var $C = $A.getWidth(ev);
                    var $D = $A.putIntoLine(ev, $B, $C, ri);
                }
            }
        };
        this.$1c = function() {
            for (var i = 0; i < this.elements.events.length; i++) {
                var e = this.elements.events[i];
                e.event = null;
                e.click = null;
                e.parentNode.removeChild(e);
            };
            this.elements.events = [];
        };
        this.$1i = function() {
            this.$1p();
        };
        this.$1p = function() {
            this.elements.events = [];
            for (var ri = 0; ri < this.rows.length; ri++) {
                var $A = this.rows[ri];
                for (var li = 0; li < $A.lines.length; li++) {
                    var $D = $A.lines[li];
                    for (var pi = 0; pi < $D.length; pi++) {
                        this.$1q($D[pi]);
                    }
                }
            }
        };
        this.$1o = function(a, b) {
            if (!a || !b || !a.start || !b.start) {
                return 0;
            };
            var $E = a.start().getTime() - b.start().getTime();
            if ($E !== 0) {
                return $E;
            };
            var $F = b.end().getTime() - a.end().getTime();
            return $F;
        };
        this.drawShadow = function(x, y, $D, $G, $H, e) {
            if (!$H) {
                $H = 0;
            };
            var $I = $G;
            this.shadow = {};
            this.shadow.list = [];
            this.shadow.start = {
                x: x,
                y: y
            };
            this.shadow.width = $G;
            var $J = y * 7 + x - $H;
            if ($J < 0) {
                $I += $J;
                x = 0;
                y = 0;
            };
            var $K = $H;
            while ($K >= 7) {
                y--;
                $K -= 7;
            };
            if ($K > x) {
                var $L = 7 - this.getColCount();
                if ($K > (x + $L)) {
                    y--;
                    x = x + 7 - $K;
                } else {
                    $I = $I - $K + x;
                    x = 0;
                }
            } else {
                x -= $K;
            };
            if (y < 0) {
                y = 0;
                x = 0;
            };
            var $M = null;
            if (DayPilotMonth.resizingEvent) {
                $M = 'w-resize';
            } else if (DayPilotMonth.movingEvent) {
                $M = "move";
            };
            this.nav.top.style.cursor = $M;
            while ($I > 0 && y < this.rows.length) {
                var $N = Math.min(this.getColCount() - x, $I);
                var $A = this.rows[y];
                var top = this.getRowTop(y);
                var $O = $A.getHeight();
                var $P = document.createElement("div");
                $P.setAttribute("unselectable", "on");
                $P.style.position = 'absolute';
                $P.style.left = (this.getCellWidth() * x) + '%';
                $P.style.width = (this.getCellWidth() * $N) + '%';
                $P.style.top = (top) + 'px';
                $P.style.height = ($O) + 'px';
                $P.style.cursor = $M;
                var $Q = document.createElement("div");
                $Q.setAttribute("unselectable", "on");
                $P.appendChild($Q);
                $Q.style.position = "absolute";
                $Q.style.top = "0px";
                $Q.style.right = "0px";
                $Q.style.left = "0px";
                $Q.style.bottom = "0px";
                $Q.style.backgroundColor = "#aaaaaa";
                $Q.style.opacity = 0.5;
                $Q.style.filter = "alpha(opacity=50)";
                this.nav.top.appendChild($P);
                this.shadow.list.push($P);
                $I -= ($N + 7 - this.getColCount());
                x = 0;
                y++;
            }
        };
        this.clearShadow = function() {
            if (this.shadow) {
                for (var i = 0; i < this.shadow.list.length; i++) {
                    this.nav.top.removeChild(this.shadow.list[i]);
                };
                this.shadow = null;
                this.nav.top.style.cursor = '';
            }
        };
        this.getEventTop = function($A, $D) {
            var top = this.headerHeight;
            for (var i = 0; i < $A; i++) {
                top += this.rows[i].getHeight();
            };
            top += this.cellHeaderHeight;
            top += $D * this.lineHeight();
            return top;
        };
        this.getDateFromCell = function(x, y) {
            return this.firstDate.addDays(y * 7 + x);
        };
        this.$1q = function(e) {
            var $f = e.cache || e.data;
            var $A = e.part.row;
            var $D = e.part.line;
            var $B = e.part.colStart;
            var $C = e.part.colWidth;
            var $R = this.getCellWidth() * ($B);
            var $G = this.getCellWidth() * ($C);
            var top = this.getEventTop($A, $D);
            var $S = document.createElement("div");
            $S.setAttribute("unselectable", "on");
            $S.style.height = this.eventHeight + 'px';
            $S.className = this.$1r("_event");
            if ($f.cssClass) {
                DayPilot.Util.addClass($S, $f.cssClass);
            };
            $S.event = e;
            $S.style.width = $G + '%';
            $S.style.position = 'absolute';
            $S.style.left = $R + '%';
            $S.style.top = top + 'px';
            if (this.showToolTip && e.client.toolTip()) {
                $S.title = e.client.toolTip();
            };
            $S.onclick = $c.$1s;
            $S.onmousedown = function(ev) {
                ev = ev || window.event;
                var $T = ev.which || ev.button;
                ev.cancelBubble = true;
                if (ev.stopPropagation) {
                    ev.stopPropagation();
                };
                if ($T === 1) {
                    DayPilotMonth.movingEvent = null;
                    if (this.style.cursor === 'w-resize' || this.style.cursor === 'e-resize') {
                        var $U = {};
                        $U.start = {};
                        $U.start.x = $B;
                        $U.start.y = $A;
                        $U.event = $S.event;
                        $U.width = DayPilot.DateUtil.daysSpan($U.event.start(), $U.event.end()) + 1;
                        $U.direction = this.style.cursor;
                        DayPilotMonth.resizingEvent = $U;
                    } else if (this.style.cursor === 'move' || $c.eventMoveHandling !== 'Disabled') {
                        $c.clearShadow();
                        var $V = DayPilot.mo2($c.nav.top, ev);
                        if (!$V) {
                            return;
                        };
                        var $W = $c.getCellBelowPoint($V.x, $V.y);
                        var $J = DayPilot.DateUtil.daysDiff(e.start(), $c.rows[$A].start);
                        var $H = ($W.y * 7 + $W.x) - ($A * 7 + $B);
                        if ($J) {
                            $H += $J;
                        };
                        var $X = {};
                        $X.start = {};
                        $X.start.x = $B;
                        $X.start.y = $A;
                        $X.start.line = $D;
                        $X.offset = $c.eventMoveToPosition ? 0 : $H;
                        $X.colWidth = $C;
                        $X.event = $S.event;
                        $X.coords = $V;
                        DayPilotMonth.movingEvent = $X;
                    }
                }
            };
            $S.onmousemove = function(ev) {
                if (typeof(DayPilotMonth) === 'undefined') {
                    return;
                };
                if (DayPilotMonth.movingEvent || DayPilotMonth.resizingEvent) {
                    return;
                };
                var $H = DayPilot.mo3($S, ev);
                if (!$H) {
                    return;
                };
                var $Y = 6;
                if ($H.x <= $Y && $c.eventResizeHandling !== 'Disabled') {
                    if (e.part.startsHere) {
                        $S.style.cursor = "w-resize";
                        $S.dpBorder = 'left';
                    } else {
                        $S.style.cursor = 'not-allowed';
                    }
                } else if ($S.clientWidth - $H.x <= $Y && $c.eventResizeHandling !== 'Disabled') {
                    if (e.part.endsHere) {
                        $S.style.cursor = "e-resize";
                        $S.dpBorder = 'right';
                    } else {
                        $S.style.cursor = 'not-allowed';
                    }
                } else {
                    $S.style.cursor = 'default';
                }
            };
            $S.onmouseout = function(ev) {
                $S.style.cursor = '';
            };
            var $Z = document.createElement("div");
            $Z.setAttribute("unselectable", "on");
            $Z.className = this.$1r("_event_inner");
            if ($f.borderColor) {
                $Z.style.borderColor = $f.borderColor;
            };
            if ($f.backColor) {
                $Z.style.background = $f.backColor;
                if (DayPilot.browser.ie9 || DayPilot.browser.ielt9) {
                    $Z.style.filter = '';
                }
            };
            if ($f.fontColor) {
                $Z.style.color = $f.fontColor;
            };
            $Z.innerHTML = e.client.html();
            $S.appendChild($Z);
            this.elements.events.push($S);
            this.nav.events.appendChild($S);
        };
        this.lastVisibleDayOfMonth = function() {
            return this.startDate.lastDayOfMonth();
        };
        this.$1d = function() {
            if (typeof this.startDate === 'string') {
                this.startDate = new DayPilot.Date(this.startDate);
            };
            this.startDate = this.startDate.firstDayOfMonth();
            this.firstDate = this.startDate.firstDayOfWeek(this.getWeekStart());
            var $00 = this.startDate;
            var $01;
            var $02 = this.lastVisibleDayOfMonth();
            var $03 = DayPilot.DateUtil.daysDiff(this.firstDate, $02) + 1;
            $01 = Math.ceil($03 / 7);
            this.days = $01 * 7;
            this.rows = [];
            for (var x = 0; x < $01; x++) {
                var r = {};
                r.start = this.firstDate.addDays(x * 7);
                r.end = r.start.addDays(this.getColCount());
                r.events = [];
                r.lines = [];
                r.index = x;
                r.minHeight = this.cellHeight;
                r.calendar = this;
                r.belongsHere = function(ev) {
                    if (ev.end().getTime() === ev.start().getTime() && ev.start().getTime() === this.start.getTime()) {
                        return true;
                    };
                    return !(ev.end().getTime() <= this.start.getTime() || ev.start().getTime() >= this.end.getTime());
                };
                r.getPartStart = function(ev) {
                    return DayPilot.DateUtil.max(this.start, ev.start());
                };
                r.getPartEnd = function(ev) {
                    return DayPilot.DateUtil.min(this.end, ev.end());
                };
                r.getStartColumn = function(ev) {
                    var $04 = this.getPartStart(ev);
                    return DayPilot.DateUtil.daysDiff(this.start, $04);
                };
                r.getWidth = function(ev) {
                    return DayPilot.DateUtil.daysSpan(this.getPartStart(ev), this.getPartEnd(ev)) + 1;
                };
                r.putIntoLine = function(ev, $B, $C, $A) {
                    var $05 = this;
                    for (var i = 0; i < this.lines.length; i++) {
                        var $D = this.lines[i];
                        if ($D.isFree($B, $C)) {
                            $D.addEvent(ev, $B, $C, $A, i);
                            return i;
                        }
                    };
                    var $D = [];
                    $D.isFree = function($B, $C) {
                        var $06 = true;
                        for (var i = 0; i < this.length; i++) {
                            if (!($B + $C - 1 < this[i].part.colStart || $B > this[i].part.colStart + this[i].part.colWidth - 1)) {
                                $06 = false;
                            }
                        };
                        return $06;
                    };
                    $D.addEvent = function(ep, $B, $C, $A, $j) {
                        ep.part.colStart = $B;
                        ep.part.colWidth = $C;
                        ep.part.row = $A;
                        ep.part.line = $j;
                        ep.part.startsHere = $05.start.getTime() <= ep.start().getTime();
                        ep.part.endsHere = $05.end.getTime() >= ep.end().getTime();
                        this.push(ep);
                    };
                    $D.addEvent(ev, $B, $C, $A, this.lines.length);
                    this.lines.push($D);
                    return this.lines.length - 1;
                };
                r.getStart = function() {
                    var $07 = 0;
                    for (var i = 0; i < $c.rows.length && i < this.index; i++) {
                        $07 += $c.rows[i].getHeight();
                    }
                };
                r.getHeight = function() {
                    return Math.max(this.lines.length * $c.lineHeight() + $c.cellHeaderHeight, this.calendar.cellHeight);
                };
                this.rows.push(r);
            };
            this.endDate = this.firstDate.addDays($01 * 7);
        };
        this.getHeight = function() {
            var $O = this.headerHeight;
            for (var i = 0; i < this.rows.length; i++) {
                $O += this.rows[i].getHeight();
            };
            return $O;
        };
        this.getWidth = function($07, end) {
            var $08 = (end.y * 7 + end.x) - ($07.y * 7 + $07.x);
            return $08 + 1;
        };
        this.getMinCoords = function($09, $0a) {
            if (($09.y * 7 + $09.x) < ($0a.y * 7 + $0a.x)) {
                return $09;
            } else {
                return $0a;
            }
        };
        this.$1r = function($0b) {
            var $0c = this.theme || this.cssClassPrefix;
            if ($0c) {
                return $0c + $0b;
            } else {
                return "";
            }
        };
        this.$1t = function() {
            var $0d = this.nav.top;
            $0d.setAttribute("unselectable", "on");
            $0d.style.MozUserSelect = 'none';
            $0d.style.KhtmlUserSelect = 'none';
            $0d.style.WebkitUserSelect = 'none';
            $0d.style.position = 'relative';
            if (this.width) {
                $0d.style.width = this.width;
            };
            $0d.style.height = this.getHeight() + 'px';
            $0d.onselectstart = function(e) {
                return false;
            };
            if (this.hideUntilInit) {
                $0d.style.visibility = 'hidden';
            };
            $0d.className = this.$1r("_main");
            var $0e = document.createElement("div");
            this.nav.cells = $0e;
            $0e.style.position = "absolute";
            $0e.style.left = "0px";
            $0e.style.right = "0px";
            $0e.setAttribute("unselectable", "on");
            $0d.appendChild($0e);
            var $z = document.createElement("div");
            this.nav.events = $z;
            $z.style.position = "absolute";
            $z.style.left = "0px";
            $z.style.right = "0px";
            $z.setAttribute("unselectable", "on");
            $0d.appendChild($z);
            $0d.onmousemove = function(ev) {
                if (DayPilotMonth.resizingEvent) {
                    var $V = DayPilot.mo2($c.nav.top, ev);
                    if (!$V) {
                        return;
                    };
                    var $W = $c.getCellBelowPoint($V.x, $V.y);
                    $c.clearShadow();
                    var $U = DayPilotMonth.resizingEvent;
                    var $0f = $U.start;
                    var $G, $07;
                    if ($U.direction === 'w-resize') {
                        $07 = $W;
                        var $0g = $U.event.end();
                        if ($0g.getDatePart() === $0g) {
                            $0g = $0g.addDays(-1);
                        };
                        var end = $c.getCellFromDate($0g);
                        $G = $c.getWidth($W, end);
                    } else {
                        $07 = $c.getCellFromDate($U.event.start());
                        $G = $c.getWidth($07, $W);
                    };
                    if ($G < 1) {
                        $G = 1;
                    };
                    $c.drawShadow($07.x, $07.y, 0, $G);
                } else if (DayPilotMonth.movingEvent) {
                    var $V = DayPilot.mo2($c.nav.top, ev);
                    if (!$V) {
                        return;
                    };
                    if ($V.x === DayPilotMonth.movingEvent.coords.x && $V.y === DayPilotMonth.movingEvent.coords.y) {
                        return;
                    };
                    var $W = $c.getCellBelowPoint($V.x, $V.y);
                    $c.clearShadow();
                    var event = DayPilotMonth.movingEvent.event;
                    var $H = DayPilotMonth.movingEvent.offset;
                    var $G = $c.cellMode ? 1 : DayPilot.DateUtil.daysSpan(event.start(), event.end()) + 1;
                    if ($G < 1) {
                        $G = 1;
                    };
                    $c.drawShadow($W.x, $W.y, 0, $G, $H, event);
                } else if (DayPilotMonth.timeRangeSelecting) {
                    var $V = DayPilot.mo2($c.nav.top, ev);
                    if (!$V) {
                        return;
                    };
                    var $W = $c.getCellBelowPoint($V.x, $V.y);
                    $c.clearShadow();
                    var $07 = DayPilotMonth.timeRangeSelecting;
                    var $0h = $07.y * 7 + $07.x;
                    var $0i = $W.y * 7 + $W.x;
                    var $G = Math.abs($0i - $0h) + 1;
                    if ($G < 1) {
                        $G = 1;
                    };
                    var $0j = $0h < $0i ? $07 : $W;
                    DayPilotMonth.timeRangeSelecting.from = {
                        x: $0j.x,
                        y: $0j.y
                    };
                    DayPilotMonth.timeRangeSelecting.width = $G;
                    DayPilotMonth.timeRangeSelecting.moved = true;
                    $c.drawShadow($0j.x, $0j.y, 0, $G, 0, null);
                }
            };
        };
        this.$1h = function() {
            this.nav.top.style.height = this.getHeight() + 'px';
            for (var x = 0; x < this.cells.length; x++) {
                for (var y = 0; y < this.cells[x].length; y++) {
                    this.cells[x][y].style.top = this.getRowTop(y) + 'px';
                    this.cells[x][y].style.height = this.rows[y].getHeight() + 'px';
                }
            }
        };
        this.getCellBelowPoint = function(x, y) {
            var $0k = Math.floor(this.nav.top.clientWidth / this.getColCount());
            var $0l = Math.min(Math.floor(x / $0k), this.getColCount() - 1);
            var $A = null;
            var $O = this.headerHeight;
            var $0m = 0;
            for (var i = 0; i < this.rows.length; i++) {
                var $0n = $O;
                $O += this.rows[i].getHeight();
                if (y < $O) {
                    $0m = y - $0n;
                    $A = i;
                    break;
                }
            };
            if ($A === null) {
                $A = this.rows.length - 1;
            };
            var $W = {};
            $W.x = $0l;
            $W.y = $A;
            $W.relativeY = $0m;
            return $W;
        };
        this.getCellFromDate = function($0o) {
            var $G = DayPilot.DateUtil.daysDiff(this.firstDate, $0o);
            var $W = {
                x: 0,
                y: 0
            };
            while ($G >= 7) {
                $W.y++;
                $G -= 7;
            };
            $W.x = $G;
            return $W;
        };
        this.$1g = function() {
            var $0p = document.createElement("div");
            $0p.oncontextmenu = function() {
                return false;
            };
            this.nav.cells.appendChild($0p);
            this.cells = [];
            for (var x = 0; x < this.getColCount(); x++) {
                this.cells[x] = [];
                var $0q = null;
                var $0r = document.createElement("div");
                $0r.setAttribute("unselectable", "on");
                $0r.style.position = 'absolute';
                $0r.style.left = (this.getCellWidth() * x) + '%';
                $0r.style.width = (this.getCellWidth()) + '%';
                $0r.style.top = '0px';
                $0r.style.height = (this.headerHeight) + 'px';
                var $0s = x + this.getWeekStart();
                if ($0s > 6) {
                    $0s -= 7;
                };
                if (this.$1a) {
                    $0r.className = this.$1r("_header");
                };
                var $Z = document.createElement("div");
                $Z.setAttribute("unselectable", "on");
                $Z.innerHTML = $0t.locale().dayNames[$0s];
                $0r.appendChild($Z);
                $Z.style.position = "absolute";
                $Z.style.top = "0px";
                $Z.style.bottom = "0px";
                $Z.style.left = "0px";
                $Z.style.right = "0px";
                $Z.className = this.$1r("_header_inner");
                $Z.innerHTML = $0t.locale().dayNames[$0s];
                $0p.appendChild($0r);
                for (var y = 0; y < this.rows.length; y++) {
                    this.drawCell(x, y, $0p);
                }
            }
        };
        this.$1f = function() {
            for (var x = 0; x < this.cells.length; x++) {
                for (var y = 0; y < this.cells[x].length; y++) {
                    this.cells[x][y].onclick = null;
                }
            };
            this.nav.cells.innerHTML = '';
        };
        this.$1u = function() {
            return $c.api === 2;
        };
        this.drawCell = function(x, y, $0p) {
            var $A = this.rows[y];
            var d = this.firstDate.addDays(y * 7 + x);
            var $0u = this.cellProperties ? this.cellProperties[y * this.getColCount() + x] : null;
            var $W = document.createElement("div");
            $W.setAttribute("unselectable", "on");
            $W.style.position = 'absolute';
            $W.style.cursor = 'default';
            $W.style.left = (this.getCellWidth() * x) + '%';
            $W.style.width = (this.getCellWidth()) + '%';
            $W.style.top = (this.getRowTop(y)) + 'px';
            $W.style.height = ($A.getHeight()) + 'px';
            if (this.$1a) {
                $W.className = this.$1r("_cell");
                if (!this.isWeekend(d)) {
                    var $0v = this.$1r("_cell_business");
                    DayPilot.Util.addClass($W, $0v);
                }
            };
            var $0w = this.startDate.addMonths(-1).getMonth();
            var $0x = this.startDate.addMonths(1).getMonth();
            var $0y = this.startDate.getMonth();
            var $Z = document.createElement("div");
            $Z.setAttribute("unselectable", "on");
            $W.appendChild($Z);
            $Z.style.position = "absolute";
            $Z.style.left = "0px";
            $Z.style.right = "0px";
            $Z.style.top = "0px";
            $Z.style.bottom = "0px";
            $Z.className = this.$1r("_cell_inner");
            $W.onmousedown = function(e) {
                if ($c.timeRangeSelectedHandling !== 'Disabled') {
                    $c.clearShadow();
                    DayPilotMonth.timeRangeSelecting = {
                        "root": $c,
                        "x": x,
                        "y": y,
                        "from": {
                            x: x,
                            y: y
                        },
                        "width": 1
                    };
                }
            };
            $W.onclick = function() {
                var $0z = function(d) {
                    var $07 = new DayPilot.Date(d);
                    var end = $07.addDays(1);
                    $c.$1v($07, end);
                };
                if ($c.timeRangeSelectedHandling !== 'Disabled') {
                    $0z(d);
                    return;
                }
            };
            var $0A = document.createElement("div");
            $0A.setAttribute("unselectable", "on");
            $0A.style.height = this.cellHeaderHeight + "px";
            $0A.className = this.$1r("_cell_header");
            var $0o = d.getDay();
            if ($0o === 1) {
                $0A.innerHTML = $0t.locale().monthNames[d.getMonth()] + ' ' + $0o;
            } else {
                $0A.innerHTML = $0o;
            };
            $Z.appendChild($0A);
            this.cells[x][y] = $W;
            $0p.appendChild($W);
        };
        this.getWeekStart = function() {
            return $0t.locale().weekStarts;
        };
        this.getColCount = function() {
            return 7;
        };
        this.getCellWidth = function() {
            return 14.285;
        };
        this.getRowTop = function($j) {
            var top = this.headerHeight;
            for (var i = 0; i < $j; i++) {
                top += this.rows[i].getHeight();
            };
            return top;
        };
        this.$1w = function($0B, $f, $0C) {
            var $0D = {};
            $0D.action = $0B;
            $0D.parameters = $0C;
            $0D.data = $f;
            $0D.header = this.$1x();
            var $0E = "JSON" + DayPilot.JSON.stringify($0D);
            if (this.backendUrl) {
                DayPilot.request(this.backendUrl, this.$1y, $0E, this.ajaxError);
            }
        };
        this.$1y = function($0F) {
            $c.$1b($0F.responseText);
        };
        this.$1x = function() {
            var h = {};
            h.control = "dpm";
            h.id = this.id;
            h.v = this.v;
            h.visibleStart = new DayPilot.Date(this.firstDate);
            h.visibleEnd = h.visibleStart.addDays(this.days);
            h.startDate = $c.startDate;
            h.timeFormat = this.timeFormat;
            h.weekStarts = this.weekStarts;
            return h;
        };
        this.eventClickCallBack = function(e, $f) {
            this.$1w('EventClick', $f, e);
        };
        this.$1s = function(e) {
            DayPilotMonth.movingEvent = null;
            DayPilotMonth.resizingEvent = null;
            var $S = this;
            var e = e || window.event;
            var $0G = e.ctrlKey;
            e.cancelBubble = true;
            if (e.stopPropagation) {
                e.stopPropagation();
            };
            $c.eventClickSingle($S, $0G);
        };
        this.eventClickSingle = function($S) {
            var e = $S.event;
            if (!e.client.clickEnabled()) {
                return;
            };
            if ($c.$1u()) {
                var $o = {};
                $o.e = e;
                $o.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $c.onEventClick === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventClick($o);
                    });
                    if ($o.preventDefault.value) {
                        return;
                    }
                };
                switch ($c.eventClickHandling) {
                    case 'CallBack':
                        $c.eventClickCallBack(e);
                        break;
                };
                if (typeof $c.onEventClicked === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventClicked($o);
                    });
                }
            } else {
                switch ($c.eventClickHandling) {
                    case 'CallBack':
                        $c.eventClickCallBack(e);
                        break;
                    case 'JavaScript':
                        $c.onEventClick(e);
                        break;
                }
            }
        };
        this.eventMoveCallBack = function(e, $0H, $0I, $f, $0J) {
            if (!$0H) throw 'newStart is null';
            if (!$0I) throw 'newEnd is null';
            var $0K = {};
            $0K.e = e;
            $0K.newStart = $0H;
            $0K.newEnd = $0I;
            $0K.position = $0J;
            this.$1w('EventMove', $f, $0K);
        };
        this.$1z = function(e, x, y, $H, ev, $0J) {
            var $0L = e.start().getTimePart();
            var $0g = e.end().getDatePart();
            if ($0g !== e.end()) {
                $0g = $0g.addDays(1);
            };
            var $0M = DayPilot.DateUtil.diff(e.end(), $0g);
            var $0N = this.getDateFromCell(x, y);
            $0N = $0N.addDays(-$H);
            var $G = DayPilot.DateUtil.daysSpan(e.start(), e.end()) + 1;
            var $0O = $0N.addDays($G);
            var $0H = $0N.addTime($0L);
            var $0I = $0O.addTime($0M);
            if ($c.$1u()) {
                var $o = {};
                $o.e = e;
                $o.newStart = $0H;
                $o.newEnd = $0I;
                $o.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $c.onEventMove === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventMove($o);
                    });
                    if ($o.preventDefault.value) {
                        return;
                    }
                };
                switch ($c.eventMoveHandling) {
                    case 'CallBack':
                        $c.eventMoveCallBack(e, $0H, $0I);
                        break;
                    case 'Update':
                        e.start($0H);
                        e.end($0I);
                        $c.events.update(e);
                        break;
                };
                if (typeof $c.onEventMoved === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventMoved($o);
                    });
                }
            } else {
                switch ($c.eventMoveHandling) {
                    case 'CallBack':
                        $c.eventMoveCallBack(e, $0H, $0I);
                        break;
                    case 'JavaScript':
                        $c.onEventMove(e, $0H, $0I);
                        break;
                }
            }
        };
        this.eventResizeCallBack = function(e, $0H, $0I, $f) {
            if (!$0H) throw 'newStart is null';
            if (!$0I) throw 'newEnd is null';
            var $0K = {};
            $0K.e = e;
            $0K.newStart = $0H;
            $0K.newEnd = $0I;
            this.$1w('EventResize', $f, $0K);
        };
        this.$1A = function(e, $07, $G) {
            var $0L = e.start().getTimePart();
            var $0g = e.end().getDatePart();
            if ($0g !== e.end()) {
                $0g = $0g.addDays(1);
            };
            var $0M = DayPilot.DateUtil.diff(e.end(), $0g);
            var $0N = this.getDateFromCell($07.x, $07.y);
            var $0O = $0N.addDays($G);
            var $0H = $0N.addTime($0L);
            var $0I = $0O.addTime($0M);
            if ($c.$1u()) {
                var $o = {};
                $o.e = e;
                $o.newStart = $0H;
                $o.newEnd = $0I;
                $o.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $c.onEventResize === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventResize($o);
                    });
                    if ($o.preventDefault.value) {
                        return;
                    }
                };
                switch ($c.eventResizeHandling) {
                    case 'CallBack':
                        $c.eventResizeCallBack(e, $0H, $0I);
                        break;
                    case 'Update':
                        e.start($0H);
                        e.end($0I);
                        $c.events.update(e);
                        break;
                };
                if (typeof $c.onEventResized === 'function') {
                    $c.$1j.apply(function() {
                        $c.onEventResized($o);
                    });
                }
            } else {
                switch ($c.eventResizeHandling) {
                    case 'CallBack':
                        $c.eventResizeCallBack(e, $0H, $0I);
                        break;
                    case 'JavaScript':
                        $c.onEventResize(e, $0H, $0I);
                        break;
                }
            }
        };
        this.timeRangeSelectedCallBack = function($07, end, $f) {
            var $0P = {};
            $0P.start = $07;
            $0P.end = end;
            this.$1w('TimeRangeSelected', $f, $0P);
        };
        this.$1v = function($07, end) {
            if (this.$1u()) {
                var $o = {};
                $o.start = $07;
                $o.end = end;
                $o.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $c.onTimeRangeSelect === 'function') {
                    $c.$1j.apply(function() {
                        $c.onTimeRangeSelect($o);
                    });
                    if ($o.preventDefault.value) {
                        return;
                    }
                };
                switch ($c.timeRangeSelectedHandling) {
                    case 'CallBack':
                        $c.timeRangeSelectedCallBack($07, end);
                        break;
                };
                if (typeof $c.onTimeRangeSelected === 'function') {
                    $c.$1j.apply(function() {
                        $c.onTimeRangeSelected($o);
                    });
                }
            } else {
                switch ($c.timeRangeSelectedHandling) {
                    case 'CallBack':
                        $c.timeRangeSelectedCallBack($07, end);
                        break;
                    case 'JavaScript':
                        $c.onTimeRangeSelected($07, end);
                        break;
                }
            }
        };
        this.$1j = {};
        this.$1j.scope = null;
        this.$1j.notify = function() {
            if ($c.$1j.scope) {
                $c.$1j.scope["$apply"]();
            }
        };
        this.$1j.apply = function(f) {
            f();
        };
        this.clearSelection = function() {
            $c.clearShadow();
        };
        this.commandCallBack = function($0Q, $f) {
            var $0K = {};
            $0K.command = $0Q;
            this.$1w('Command', $f, $0K);
        };
        this.isWeekend = function($0o) {
            $0o = new DayPilot.Date($0o);
            var $0R = 0;
            var $0S = 6;
            if ($0o.dayOfWeek() === $0R) {
                return true;
            };
            if ($0o.dayOfWeek() === $0S) {
                return true;
            };
            return false;
        };
        this.$1B = {};
        this.$1B.locale = function() {
            var $0T = DayPilot.Locale.find($c.locale);
            if (!$0T) {
                return DayPilot.Locale.US;
            };
            return $0T;
        };
        var $0t = this.$1B;
        this.debug = function($0U, $0V) {
            if (!this.debuggingEnabled) {
                return;
            };
            if (!$c.debugMessages) {
                $c.debugMessages = [];
            };
            $c.debugMessages.push($0U);
            if (typeof console !== 'undefined') {
                console.log($0U);
            }
        };
        this.$1C = function() {
            if (!DayPilotMonth.globalHandlers) {
                DayPilotMonth.globalHandlers = true;
                DayPilot.re(document, 'mouseup', DayPilotMonth.gMouseUp);
            }
        };
        this.loadFromServer = function() {
            if (this.backendUrl || typeof WebForm_DoCallback === 'function') {
                return (typeof $c.events.list === 'undefined') || (!$c.events.list);
            } else {
                return false;
            }
        };
        this.show = function() {
            if (this.nav.top.style.visibility === 'hidden') {
                this.nav.top.style.visibility = 'visible';
            }
        };
        this.$1D = function() {
            if (this.id && this.id.tagName) {
                this.nav.top = this.id;
            } else if (typeof this.id === "string") {
                this.nav.top = document.getElementById(this.id);
                if (!this.nav.top) {
                    throw "DayPilot.Month: The placeholder element not found: '" + id + "'.";
                }
            } else {
                throw "DayPilot.Month() constructor requires the target element or its ID as a parameter";
            }
        };
        this.$1l = function() {
            if (!$c.$1a) {
                $c.$1a = true;
                window.console && window.console.log && window.console.log("DayPilot: cssOnly = false mode is not supported anymore.");
            }
        };
        this.$1E = function() {
            this.$1d();
            this.$1t();
            this.$1g();
            this.$1C();
            this.$1w('Init');
        };
        this.init = function() {
            this.$1D();
            var $0W = this.loadFromServer();
            this.$1l();
            if ($0W) {
                this.$1E();
                return;
            };
            this.$1d();
            this.$1e();
            this.$1t();
            this.$1g();
            this.show();
            this.$1i();
            this.$1C();
            if (this.messageHTML) {
                this.message(this.messageHTML);
            };
            this.fireAfterRenderDetached(null, false);
        };
        this.Init = this.init;
    };
    DayPilotMonth.gMouseUp = function(ev) {
        if (DayPilotMonth.movingEvent) {
            var $0X = DayPilotMonth.movingEvent;
            if (!$0X.event) {
                return;
            };
            if (!$0X.event.calendar) {
                return;
            };
            if (!$0X.event.calendar.shadow) {
                return;
            };
            if (!$0X.event.calendar.shadow.start) {
                return;
            };
            var $c = DayPilotMonth.movingEvent.event.calendar;
            var e = DayPilotMonth.movingEvent.event;
            var $07 = $c.shadow.start;
            var $0J = $c.shadow.position;
            var $H = DayPilotMonth.movingEvent.offset;
            $c.clearShadow();
            DayPilotMonth.movingEvent = null;
            var ev = ev || window.event;
            $c.$1z(e, $07.x, $07.y, $H, ev, $0J);
            ev.cancelBubble = true;
            if (ev.stopPropagation) {
                ev.stopPropagation();
            };
            DayPilotMonth.movingEvent = null;
            return false;
        } else if (DayPilotMonth.resizingEvent) {
            var $0X = DayPilotMonth.resizingEvent;
            if (!$0X.event) {
                return;
            };
            if (!$0X.event.calendar) {
                return;
            };
            if (!$0X.event.calendar.shadow) {
                return;
            };
            if (!$0X.event.calendar.shadow.start) {
                return;
            };
            var $c = DayPilotMonth.resizingEvent.event.calendar;
            var e = DayPilotMonth.resizingEvent.event;
            var $07 = $c.shadow.start;
            var $G = $c.shadow.width;
            $c.clearShadow();
            DayPilotMonth.resizingEvent = null;
            $c.$1A(e, $07, $G);
            ev.cancelBubble = true;
            DayPilotMonth.resizingEvent = null;
            return false;
        } else if (DayPilotMonth.timeRangeSelecting) {
            if (DayPilotMonth.timeRangeSelecting.moved) {
                var $0Y = DayPilotMonth.timeRangeSelecting;
                var $c = $0Y.root;
                var $07 = new DayPilot.Date($c.getDateFromCell($0Y.from.x, $0Y.from.y));
                var end = $07.addDays($0Y.width);
                $c.$1v($07, end);
                $c.clearShadow();
            };
            DayPilotMonth.timeRangeSelecting = null;
        }
    };
    DayPilot.Month = DayPilotMonth.Month;
    if (typeof jQuery !== 'undefined') {
        (function($) {
            $.fn.daypilotMonth = function($0Z) {
                var $09 = null;
                var j = this.each(function() {
                    if (this.daypilot) {
                        return;
                    };
                    var $10 = new DayPilot.Month(this.id);
                    this.daypilot = $10;
                    for (name in $0Z) {
                        $10[name] = $0Z[name];
                    };
                    $10.Init();
                    if (!$09) {
                        $09 = $10;
                    }
                });
                if (this.length === 1) {
                    return $09;
                } else {
                    return j;
                }
            };
        })(jQuery);
    };
    (function registerAngularModule() {
        var $11 = DayPilot.am();
        if (!$11) {
            return;
        };
        $11.directive("daypilotMonth", ['$parse', function($12) {
            return {
                "restrict": "E",
                "template": "<div></div>",
                "replace": true,
                "link": function($13, element, $14) {
                    var $c = new DayPilot.Month(element[0]);
                    $c.$1j.scope = $13;
                    $c.init();
                    var $15 = $14["id"];
                    if ($15) {
                        $13[$15] = $c;
                    };
                    var $16 = $14["publishAs"];
                    if ($16) {
                        var getter = $12($16);
                        var setter = getter.assign;
                        setter($13, $c);
                    };
                    for (var name in $14) {
                        if (name.indexOf("on") === 0) {
                            (function(name) {
                                $c[name] = function($o) {
                                    var f = $12($14[name]);
                                    $13["$apply"](function() {
                                        f($13, {
                                            "args": $o
                                        });
                                    });
                                };
                            })(name);
                        }
                    };
                    var $17 = $13["$watch"];
                    var $18 = $14["config"] || $14["daypilotConfig"];
                    var $z = $14["events"] || $14["daypilotEvents"];
                    $17.call($13, $18, function($19) {
                        for (var name in $19) {
                            $c[name] = $19[name];
                        };
                        $c.update();
                    }, true);
                    $17.call($13, $z, function($19) {
                        $c.events.list = $19;
                        $c.update();
                    }, true);
                }
            };
        }]);
    })();
    if (typeof Sys !== 'undefined' && Sys.Application && Sys.Application.notifyScriptLoaded) {
        Sys.Application.notifyScriptLoaded();
    }
})();


if (typeof DayPilot === 'undefined') {
    var DayPilot = {};
};
if (typeof DayPilot.Global === 'undefined') {
    DayPilot.Global = {};
};
(function() {
    if (typeof DayPilot.Navigator !== 'undefined') {
        return;
    };
    DayPilotNavigator = {};
    DayPilot.Navigator = function(id) {
        this.v = '2021.1.248-lite';
        var $a = this;
        this.id = id;
        this.api = 2;
        this.isNavigator = true;
        this.angularAutoApply = true;
        this.weekStarts = 'Auto';
        this.selectMode = 'day';
        this.titleHeight = 30;
        this.dayHeaderHeight = 30;
        this.cellWidth = 30;
        this.cellHeight = 30;
        this.$02 = true;
        this.selectionStart = null;
        this.selectionEnd = null;
        this.selectionDay = new DayPilot.Date().getDatePart();
        this.showMonths = 1;
        this.skipMonths = 1;
        this.command = "navigate";
        this.year = new DayPilot.Date().getYear();
        this.month = new DayPilot.Date().getMonth() + 1;
        this.locale = "en-us";
        this.theme = "navigator_default";
        this.timeRangeSelectedHandling = "Bind";
        this.onTimeRangeSelect = null;
        this.onTimeRangeSelected = null;
        this.nav = {};
        this.$03 = function() {
            this.root.dp = this;
            if (this.$02) {
                this.root.className = this.$04('_main');
            } else {
                this.root.className = this.$04('main');
            };
            this.root.style.width = (this.cellWidth * 7) + 'px';
            this.root.style.position = "relative";
            var $b = document.createElement("input");
            $b.type = 'hidden';
            $b.name = $a.id + "_state";
            $b.id = $b.name;
            this.root.appendChild($b);
            this.state = $b;
            if (!this.startDate) {
                this.startDate = new DayPilot.Date(DayPilot.Date.fromYearMonthDay(this.year, this.month));
            } else {
                this.startDate = new DayPilot.Date(this.startDate).firstDayOfMonth();
            };
            this.calendars = [];
            this.selected = [];
            this.months = [];
        };
        this.$05 = function() {
            return $a.api === 2;
        };
        this.$06 = function() {
            this.root.innerHTML = '';
        };
        this.$04 = function($c) {
            var $d = this.theme || this.cssClassPrefix;
            if ($d) {
                return $d + $c;
            } else {
                return "";
            }
        };
        this.$07 = function($e, name) {
            var $f = this.$02 ? this.$04("_" + name) : this.$04(name);
            DayPilot.Util.addClass($e, $f);
        };
        this.$08 = function($e, name) {
            var $f = this.$02 ? this.$04("_" + name) : this.$04(name);
            DayPilot.Util.removeClass($e, $f);
        };
        this.$09 = function(j, $g) {
            var $h = {};
            $h.cells = [];
            $h.days = [];
            $h.weeks = [];
            var $i = this.startDate.addMonths(j);
            var $j = $g.before;
            var $k = $g.after;
            var $l = $i.firstDayOfMonth();
            var $m = $l.firstDayOfWeek($n.weekStarts());
            var $o = $l.addMonths(1);
            var $p = DayPilot.DateUtil.daysDiff($m, $o);
            var $q = 6;
            $h.$0a = $q;
            var $r = (new DayPilot.Date()).getDatePart();
            var $s = this.cellWidth * 7;
            var $t = this.cellHeight * $q + this.titleHeight + this.dayHeaderHeight;
            $h.height = $t;
            var $u = document.createElement("div");
            $u.style.width = ($s) + 'px';
            $u.style.height = ($t) + 'px';
            $u.style.position = 'relative';
            if (this.$02) {
                $u.className = this.$04('_month');
            } else {
                $u.className = this.$04('month');
            };
            $u.style.cursor = 'default';
            $u.style.MozUserSelect = 'none';
            $u.style.KhtmlUserSelect = 'none';
            $u.style.WebkitUserSelect = 'none';
            $u.month = $h;
            this.root.appendChild($u);
            var $v = this.titleHeight + this.dayHeaderHeight;
            var tl = document.createElement("div");
            tl.style.position = 'absolute';
            tl.style.left = '0px';
            tl.style.top = '0px';
            tl.style.width = this.cellWidth + 'px';
            tl.style.height = this.titleHeight + 'px';
            tl.style.lineHeight = this.titleHeight + 'px';
            tl.setAttribute("unselectable", "on");
            if (this.$02) {
                tl.className = this.$04('_titleleft');
            } else {
                tl.className = this.$04('titleleft');
            };
            if ($g.left) {
                tl.style.cursor = 'pointer';
                tl.innerHTML = "<span>&lt;</span>";
                tl.onclick = this.$0b;
            };
            $u.appendChild(tl);
            this.tl = tl;
            var ti = document.createElement("div");
            ti.style.position = 'absolute';
            ti.style.left = this.cellWidth + 'px';
            ti.style.top = '0px';
            ti.style.width = (this.cellWidth * 5) + 'px';
            ti.style.height = this.titleHeight + 'px';
            ti.style.lineHeight = this.titleHeight + 'px';
            ti.style.textAlign = 'center';
            ti.setAttribute("unselectable", "on");
            if (this.$02) {
                ti.className = this.$04('_title');
            } else {
                ti.className = this.$04('title');
            };
            ti.innerHTML = $n.locale().monthNames[$i.getMonth()] + ' ' + $i.getYear();
            $u.appendChild(ti);
            this.ti = ti;
            var tr = document.createElement("div");
            tr.style.position = 'absolute';
            tr.style.left = (this.cellWidth * 6) + 'px';
            tr.style.top = '0px';
            tr.style.width = this.cellWidth + 'px';
            tr.style.height = this.titleHeight + 'px';
            tr.style.lineHeight = this.titleHeight + 'px';
            tr.setAttribute("unselectable", "on");
            if (this.$02) {
                tr.className = this.$04('_titleright');
            } else {
                tr.className = this.$04('titleright');
            };
            if ($g.right) {
                tr.style.cursor = 'pointer';
                tr.innerHTML = "<span>&gt;</span>";
                tr.onclick = this.$0c;
            };
            $u.appendChild(tr);
            this.tr = tr;
            for (var x = 0; x < 7; x++) {
                $h.cells[x] = [];
                var dh = document.createElement("div");
                dh.style.position = 'absolute';
                dh.style.left = (x * this.cellWidth) + 'px';
                dh.style.top = this.titleHeight + 'px';
                dh.style.width = this.cellWidth + 'px';
                dh.style.height = this.dayHeaderHeight + 'px';
                dh.style.lineHeight = this.dayHeaderHeight + 'px';
                dh.setAttribute("unselectable", "on");
                if (this.$02) {
                    dh.className = this.$04('_dayheader');
                } else {
                    dh.className = this.$04('dayheader');
                };
                dh.innerHTML = "<span>" + this.$0d(x) + "</span>";
                $u.appendChild(dh);
                $h.days.push(dh);
                for (var y = 0; y < $q; y++) {
                    var $w = $m.addDays(y * 7 + x);
                    var $x = this.$0e($w) && this.$0f() !== 'none';
                    var $y = $w.getMonth() === $i.getMonth();
                    var $z = $w.getTime() < $i.getTime();
                    var $A = $w.getTime() > $i.getTime();
                    var $B;
                    var dc = document.createElement("div");
                    $h.cells[x][y] = dc;
                    dc.day = $w;
                    dc.x = x;
                    dc.y = y;
                    dc.isCurrentMonth = $y;
                    if (this.$02) {
                        dc.className = this.$04(($y ? '_day' : '_dayother'));
                    } else {
                        dc.className = this.$04(($y ? 'day' : 'dayother'));
                    };
                    $a.$07(dc, "cell");
                    if ($w.getTime() === $r.getTime() && $y) {
                        this.$07(dc, 'today');
                    };
                    if ($w.dayOfWeek() === 0 || $w.dayOfWeek() === 6) {
                        this.$07(dc, 'weekend');
                    };
                    dc.style.position = 'absolute';
                    dc.style.left = (x * this.cellWidth) + 'px';
                    dc.style.top = (y * this.cellHeight + $v) + 'px';
                    dc.style.width = this.cellWidth + 'px';
                    dc.style.height = this.cellHeight + 'px';
                    dc.style.lineHeight = this.cellHeight + 'px';
                    var $C = document.createElement("div");
                    $C.style.position = 'absolute';
                    if (this.$02) {
                        $C.className = ($w.getTime() === $r.getTime() && $y) ? this.$04('_todaybox') : this.$04('_daybox');
                    } else {
                        $C.className = ($w.getTime() === $r.getTime() && $y) ? this.$04('todaybox') : this.$04('daybox');
                    };
                    $a.$07($C, "cell_box");
                    $C.style.left = '0px';
                    $C.style.top = '0px';
                    $C.style.right = '0px';
                    $C.style.bottom = '0px';
                    dc.appendChild($C);
                    var $D = null;
                    if (this.cells && this.cells[$w.toStringSortable()]) {
                        $D = this.cells[$w.toStringSortable()];
                        if ($D.css) {
                            this.$07(dc, $D.css);
                        }
                    };
                    if ($y || ($j && $z) || ($k && $A)) {
                        var $E = document.createElement("div");
                        $E.innerHTML = $w.getDay();
                        $E.style.position = "absolute";
                        $E.style.left = '0px';
                        $E.style.top = '0px';
                        $E.style.right = '0px';
                        $E.style.bottom = '0px';
                        $a.$07($E, "cell_text");
                        dc.style.cursor = 'pointer';
                        dc.isClickable = true;
                        if ($D && $D.html) {
                            $E.innerHTML = $D.html;
                        };
                        dc.appendChild($E);
                    };
                    dc.setAttribute("unselectable", "on");
                    dc.onclick = this.$0g;
                    dc.onmousedown = this.$0h;
                    dc.onmousemove = this.$0i;
                    if ($x) {
                        $a.$0j($u, x, y);
                        this.selected.push(dc);
                    };
                    $u.appendChild(dc);
                }
            };
            var $F = document.createElement("div");
            $F.style.position = 'absolute';
            $F.style.left = '0px';
            $F.style.top = ($v - 2) + 'px';
            $F.style.width = (this.cellWidth * 7) + 'px';
            $F.style.height = '1px';
            $F.style.fontSize = '1px';
            $F.style.lineHeight = '1px';
            if (this.$02) {
                $F.className = this.$04("_line");
            } else {
                $F.className = this.$04("line");
            };
            $u.appendChild($F);
            this.months.push($h);
        };
        this.$0j = function($u, x, y) {
            var $G = $u.month.cells[x][y];
            $a.$07($G, 'select');
        };
        this.$0f = function() {
            var $H = this.selectMode || "";
            return $H.toLowerCase();
        };
        this.$0k = function() {
            var $H = $a.$0f();
            switch ($H) {
                case 'day':
                    this.selectionStart = this.selectionDay;
                    this.selectionEnd = this.selectionStart;
                    break;
                case 'week':
                    this.selectionStart = this.selectionDay.firstDayOfWeek($n.weekStarts());
                    this.selectionEnd = this.selectionStart.addDays(6);
                    break;
                case 'month':
                    this.selectionStart = this.selectionDay.firstDayOfMonth();
                    this.selectionEnd = this.selectionDay.lastDayOfMonth();
                    break;
                case 'none':
                    this.selectionStart = this.selectionDay;
                    this.selectionEnd = this.selectionStart;
                    break;
                default:
                    throw "Unknown selectMode value.";
            }
        };
        this.select = function($I) {
            var $J = true;
            var $K = this.selectionStart;
            var $L = this.selectionEnd;
            this.selectionStart = new DayPilot.Date($I).getDatePart();
            this.selectionDay = this.selectionStart;
            var $M = false;
            if ($J) {
                var $N = this.startDate;
                if (this.selectionStart.getTime() < this.visibleStart().getTime() || this.selectionStart.getTime() > this.visibleEnd().getTime()) {
                    $N = this.selectionStart.firstDayOfMonth();
                };
                if ($N.toStringSortable() !== this.startDate.toStringSortable()) {
                    $M = true;
                };
                this.startDate = $N;
            };
            this.$0k();
            this.$06();
            this.$03();
            this.$0l();
            if (!$K.equals(this.selectionStart) || !$L.equals(this.selectionEnd)) {
                this.$0m();
            }
        };
        this.update = function() {
            this.$0n();
            this.$0k();
            this.$06();
            this.$03();
            this.$0l();
        };
        this.$0d = function(i) {
            var x = i + $n.weekStarts();
            if (x > 6) {
                x -= 7;
            };
            return $n.locale().dayNamesShort[x];
        };
        this.$0e = function($I) {
            if (this.selectionStart === null || this.selectionEnd === null) {
                return false;
            };
            if (this.selectionStart.getTime() <= $I.getTime() && $I.getTime() <= this.selectionEnd.getTime()) {
                return true;
            };
            return false;
        };
        this.$0h = function(ev) {};
        this.$0i = function(ev) {};
        this.$0g = function(ev) {
            var $h = this.parentNode.month;
            var x = this.x;
            var y = this.y;
            var $w = $h.cells[x][y].day;
            if (!$h.cells[x][y].isClickable) {
                return;
            };
            $a.clearSelection();
            $a.selectionDay = $w;
            var $w = $a.selectionDay;
            switch ($a.$0f()) {
                case 'none':
                    $a.selectionStart = $w;
                    $a.selectionEnd = $w;
                    break;
                case 'day':
                    var s = $h.cells[x][y];
                    $a.$07(s, 'select');
                    $a.selected.push(s);
                    $a.selectionStart = s.day;
                    $a.selectionEnd = s.day;
                    break;
                case 'week':
                    for (var j = 0; j < 7; j++) {
                        $a.$07($h.cells[j][y], 'select');
                        $a.selected.push($h.cells[j][y]);
                    };
                    $a.selectionStart = $h.cells[0][y].day;
                    $a.selectionEnd = $h.cells[6][y].day;
                    break;
                case 'month':
                    var $O = null;
                    var end = null;
                    for (var y = 0; y < 6; y++) {
                        for (var x = 0; x < 7; x++) {
                            var s = $h.cells[x][y];
                            if (!s) {
                                continue;
                            };
                            if (s.day.getYear() === $w.getYear() && s.day.getMonth() === $w.getMonth()) {
                                $a.$07(s, 'select');
                                $a.selected.push(s);
                                if ($O === null) {
                                    $O = s.day;
                                };
                                end = s.day;
                            }
                        }
                    };
                    $a.selectionStart = $O;
                    $a.selectionEnd = end;
                    break;
                default:
                    throw 'unknown selectMode';
            };
            $a.$0m();
        };
        this.$0m = function() {
            var $O = $a.selectionStart;
            var end = $a.selectionEnd.addDays(1);
            var $p = DayPilot.DateUtil.daysDiff($O, end);
            var $w = $a.selectionDay;
            if ($a.$05()) {
                var $P = {};
                $P.start = $O;
                $P.end = end;
                $P.day = $w;
                $P.days = $p;
                $P.preventDefault = function() {
                    this.preventDefault.value = true;
                };
                if (typeof $a.onTimeRangeSelect === 'function') {
                    $a.$0o.apply(function() {
                        $a.onTimeRangeSelect($P);
                    });
                    if ($P.preventDefault.value) {
                        return;
                    }
                };
                switch ($a.timeRangeSelectedHandling) {
                    case 'Bind':
                        var $Q = eval($a.bound);
                        if ($Q) {
                            var $R = {};
                            $R.start = $O;
                            $R.end = end;
                            $R.days = $p;
                            $R.day = $w;
                            $Q.commandCallBack($a.command, $R);
                        };
                        break;
                    case 'None':
                        break;
                };
                if (typeof $a.onTimeRangeSelected === 'function') {
                    $a.$0o.apply(function() {
                        $a.onTimeRangeSelected($P);
                    });
                }
            } else {
                switch ($a.timeRangeSelectedHandling) {
                    case 'Bind':
                        var $Q = eval($a.bound);
                        if ($Q) {
                            var $R = {};
                            $R.start = $O;
                            $R.end = end;
                            $R.days = $p;
                            $R.day = $w;
                            $Q.commandCallBack($a.command, $R);
                        };
                        break;
                    case 'JavaScript':
                        $a.onTimeRangeSelected($O, end, $w);
                        break;
                    case 'None':
                        break;
                }
            }
        };
        this.$0c = function(ev) {
            $a.$0p($a.skipMonths);
        };
        this.$0b = function(ev) {
            $a.$0p(-$a.skipMonths);
        };
        this.$0p = function(i) {
            this.startDate = this.startDate.addMonths(i);
            this.$06();
            this.$03();
            this.$0l();
        };
        this.visibleStart = function() {
            return $a.startDate.firstDayOfMonth().firstDayOfWeek($n.weekStarts());
        };
        this.visibleEnd = function() {
            return $a.startDate.firstDayOfMonth().addMonths(this.showMonths - 1).firstDayOfWeek($n.weekStarts()).addDays(42);
        };
        this.$0l = function() {
            for (var j = 0; j < this.showMonths; j++) {
                var $g = this.$0q(j);
                this.$09(j, $g);
            };
            this.root.style.height = this.$0r() + "px";
        };
        this.$0r = function() {
            var $S = 0;
            for (var i = 0; i < this.months.length; i++) {
                var $h = this.months[i];
                $S += $h.height;
            };
            return $S;
        };
        this.$0q = function(j) {
            if (this.internal.showLinks) {
                return this.internal.showLinks;
            };
            var $g = {};
            $g.left = (j === 0);
            $g.right = (j === 0);
            $g.before = j === 0;
            $g.after = j === this.showMonths - 1;
            return $g;
        };
        this.internal = {};
        this.$0s = {};
        var $n = this.$0s;
        $n.locale = function() {
            return DayPilot.Locale.find($a.locale);
        };
        $n.weekStarts = function() {
            if ($a.weekStarts === 'Auto') {
                var $T = $n.locale();
                if ($T) {
                    return $T.weekStarts;
                } else {
                    return 0;
                }
            } else {
                return $a.weekStarts;
            }
        };
        this.clearSelection = function() {
            for (var j = 0; j < this.selected.length; j++) {
                this.$08(this.selected[j], 'select');
            };
            this.selected = [];
        };
        this.$0o = {};
        this.$0o.scope = null;
        this.$0o.notify = function() {
            if ($a.$0o.scope) {
                $a.$0o.scope["$apply"]();
            }
        };
        this.$0o.apply = function(f) {
            if ($a.angularAutoApply && $a.$0o.scope) {
                $a.$0o.scope["$apply"](f);
            } else {
                f();
            }
        };
        this.$0t = function() {
            if (this.backendUrl || typeof WebForm_DoCallback === 'function') {
                return (typeof $a.items === 'undefined') || (!$a.items);
            } else {
                return false;
            }
        };
        this.$0n = function() {
            if (!$a.$02) {
                $a.$02 = true;
                window.console && window.console.log && window.console.log("DayPilot: cssOnly = false mode is not supported anymore.");
            }
        };
        this.$0u = function() {
            if (this.id && this.id.tagName) {
                this.nav.top = this.id;
            } else if (typeof this.id === "string") {
                this.nav.top = document.getElementById(this.id);
                if (!this.nav.top) {
                    throw "DayPilot.Navigator: The placeholder element not found: '" + id + "'.";
                }
            } else {
                throw "DayPilot.Navigator() constructor requires the target element or its ID as a parameter";
            };
            this.root = this.nav.top;
        };
        this.init = function() {
            this.$0u();
            this.$0n();
            if (this.root.dp) {
                return;
            };
            this.$0k();
            this.$03();
            this.$0l();
            this.$0v();
            this.initialized = true;
        };
        this.dispose = function() {
            var c = $a;
            if (!c.root) {
                return;
            };
            c.root.removeAttribute("style");
            c.root.removeAttribute("class");
            c.root.dp = null;
            c.root.innerHTML = null;
            c.root = null;
        };
        this.$0v = function() {
            this.root.dispose = this.dispose;
        };
        this.Init = this.init;
    };
    if (typeof jQuery !== 'undefined') {
        (function($) {
            $.fn.daypilotNavigator = function($U) {
                var $m = null;
                var j = this.each(function() {
                    if (this.daypilot) {
                        return;
                    };
                    var $V = new DayPilot.Navigator(this.id);
                    this.daypilot = $V;
                    for (var name in $U) {
                        $V[name] = $U[name];
                    };
                    $V.Init();
                    if (!$m) {
                        $m = $V;
                    }
                });
                if (this.length === 1) {
                    return $m;
                } else {
                    return j;
                }
            };
        })(jQuery);
    };
    (function registerAngularModule() {
        var $W = DayPilot.am();
        if (!$W) {
            return;
        };
        $W.directive("daypilotNavigator", function() {
            return {
                "restrict": "E",
                "template": "<div id='{{id}};'></div>",
                "compile": function compile(element, $X) {
                    element.replaceWith(this["template"].replace("{{id}};", $X["id"]));
                    return function link($Y, element, $X) {
                        var $a = new DayPilot.Navigator(element[0]);
                        $a.$0o.scope = $Y;
                        $a.init();
                        var $Z = $X["id"];
                        if ($Z) {
                            $Y[$Z] = $a;
                        };
                        var $00 = $Y["$watch"];
                        $00.call($Y, $X["daypilotConfig"], function($01) {
                            for (var name in $01) {
                                $a[name] = $01[name];
                            };
                            $a.update();
                        }, true);
                    };
                }
            };
        });
    })();
    if (typeof Sys !== 'undefined' && Sys.Application && Sys.Application.notifyScriptLoaded) {
        Sys.Application.notifyScriptLoaded();
    }
})();