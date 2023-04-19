// Для взаимодействия
var hub = new Vue({
    el: "#hub",
    data: {
        state_dttm: null,
        state_render_helper: {},
        state: {
            result: null,
            error_text: null,
            flash_text: null
        },
        user_input: {
            company: null
        }
    },
    methods: {
        dateToText: function (dateUTC) {
          // dateUTC = '2023-04-19T03:08:00+03:00'
          var s = (new Date(dateUTC)).toLocaleString("ru"); // '19.04.2023, 04:08:00'
          //s = s.substring(0, 4) + "." + s.substring(5, 7) + "." + s.substring(8, 10) + " " + s.substring(11,19);
          return s;
        },
        secondsToText: function (seconds) {
            if (seconds == null) {
                return "";
            }

            //
            var hours = Math.trunc(seconds / 60 / 60);
            var minutes = Math.trunc((seconds - hours * 60 * 60) / 60);
            var seconds = Math.trunc(seconds - hours * 60 * 60 - minutes * 60);
            var days = Math.trunc(hours / 24);

            //
            var text = "";
            if (days > 2) {
                text = days + " дн";
            } else if (hours > 4) {
                text = hours + " час";
            } else if (hours > 1) {
                text = hours + " час";
                if (minutes > 0) {
                    text = text + " " + minutes + " мин";
                }
            } else if (minutes > 2) {
                text = minutes + " мин";
            } else if (minutes > 0) {
                text = minutes + " мин";
                if (seconds > 0) {
                    text = text + " " + seconds + " сек";
                }
            } else {
                text = seconds + " сек";
            }

            //
            //return hours + " час " + minutes + " мин " + seconds + " сек";
            return text;
        },
        secondsToTextNBSP: function (seconds) {
            text = this.secondsToText(seconds);
            text = text.replace(' ', '&nbsp;');
            return text;
        },
        secondsLagToClass: function (seconds) {
            var class_name = "";
            if (seconds == null) {
                class_name = "";
            } else if (seconds > 60 * 60 * 24) {
                class_name = "repl-lag-huge";
            } else if (seconds > 60 * 60 * 12) {
                class_name = "repl-lag-big";
            } else if (seconds > 60 * 10) {
                class_name = "repl-lag-small";
            } else if (seconds > 60 * 2) {
                class_name = "repl-lag-tiny";
            }
            return class_name;
        },
        logLagClass: function (dt) {
            var seconds = (new Date() - new Date(dt)) / 1000;
            var class_name = "";
            if (seconds < 30) {
                class_name = "log-lag-small";
            } else if (seconds < 60 * 10) {
                class_name = "log-lag-normal";
            } else if (seconds < 60 * 60) {
                class_name = "log-lag-big";
            } else if (seconds > 60 * 60) {
                class_name = "log-lag-huge";
            }
            return class_name;
        },
        stateClass: function (state) {
            class_name = "state";
            if (state.total > 0 && state.total > state.count) {
                if (state.started === true) {
                    class_name = class_name + " state-bar state-started";
                } else {
                    class_name = class_name + " ";
                }
            } else {
                if (state.started === true) {
                    class_name = class_name + " state-started";
                } else {
                    class_name = class_name + "";
                }
            }
            return class_name;
        },
        stateBarShow: function (state) {
            if (state.total > 0 && state.total > state.count) {
                return true;
            } else {
                return false;
            }
        },
        boxLagClass: function (dt, boxName) {
            var seconds = (new Date() - new Date(dt)) / 1000;
            var class_name = "";
            if (seconds < 60 * 2) {
                class_name = "lag-val-small";
            } else if (seconds < 60 * 10) {
                class_name = "lag-val-normal";
            } else if (seconds > 60 * 60 * 24 && boxName == 'to001') {
                class_name = "lag-val-nosence";
            } else if (seconds > 60 * 60 * 24 && boxName != 'to001') {
                class_name = "lag-val-huge";
            } else if (seconds > 60 * 60 * 12 && boxName != 'to001') {
                class_name = "lag-val-big";
            }
            return class_name;
        },
        diffBoxVsAvailableClass: function (box_no_max, que_available) {
            var class_name = "";
            if (box_no_max == null) {
                class_name = "";
            } else if (parseInt(que_available) - parseInt(box_no_max) > 1) {
                class_name = "box-trash";
            }
            return class_name;
        },
        diffBoxCountClass: function (box_no_min, box_no_max) {
            var class_name = "";
            if (box_no_max == null) {
                class_name = "";
            } else if (parseInt(box_no_max) - parseInt(box_no_min) >= 1) {
                class_name = "box-lag-big";
            } else if (parseInt(box_no_max) == parseInt(box_no_min)) {
                class_name = "box-lag";
            }
            return class_name;
        },
        diffAvailableDoneToClass: function (in_queInNoAvailable, in_queInNoDone) {
            var class_name = "";
            if (in_queInNoAvailable == null) {
                class_name = "";
            } else if (in_queInNoAvailable - in_queInNoDone > 10) {
                class_name = "in-que-lag-huge";
            } else if (in_queInNoAvailable - in_queInNoDone > 1) {
                class_name = "in-que-lag-big";
            } else if (in_queInNoAvailable - in_queInNoDone == 1) {
                class_name = "in-que-lag";
            }
            return class_name;
        },
        diffDatabaseInfoClass: function (srvDatabaseInfo, wsDatabaseInfo) {
            var class_name = "";
            if (srvDatabaseInfo == null) {
                class_name = "";
            } else if (wsDatabaseInfo == null) {
                class_name = "";
            } else if (srvDatabaseInfo == wsDatabaseInfo) {
                class_name = "";
            } else {
                class_name = "repl-database-info-diff";
            }
            return class_name;
        },
        diffAllReceivedToClass: function (srvAvailable, in_queInNoAvailable, in_queInNoDone) {
            var class_name = "";
            if (srvAvailable == null) {
                class_name = "";
            } else if (in_queInNoAvailable == null) {
                class_name = "";
            } else if (in_queInNoDone == null) {
                class_name = "";
            } else if (srvAvailable != in_queInNoAvailable && in_queInNoAvailable == in_queInNoDone) {
                class_name = "in-que-not-all-received";
            }
            return class_name;
        },
        fileSize: function (size) {
            if (size == 0 || size == null) {
                size = "";
            } else if (size > 1024 * 1024) {
                size = Math.round(10 * size / 1024 / 1024) / 10 + "&nbsp;Mб";
            } else if (size > 1024) {
                size = Math.round(10 * size / 1024) / 10 + "&nbsp;Кб";
            } else {
                size = Math.round(100 * size / 1024) / 100 + "&nbsp;Кб";
            }
            return size
        },
        dtToText: function (dttm) {
            var day = dttm.getDate();
            var month = dttm.getMonth() + 1;
            var year = dttm.getFullYear();
            day = (day + '').padStart(2, '0');
            month = (month + '').padStart(2, '0');
            return day + '.' + month + '.' + year;
        },
        dttmToText: function (dttm_str) {
            var dttm = new Date(dttm_str);
            //
            var day = dttm.getDate();
            var month = dttm.getMonth() + 1;
            var year = dttm.getFullYear();
            var hour = dttm.getHours();
            var minute = dttm.getMinutes();
            var second = dttm.getSeconds();
            //
            day = (day + '').padStart(2, '0');
            month = (month + '').padStart(2, '0');
            hour = (hour + '').padStart(2, '0');
            minute = (minute + '').padStart(2, '0');
            second = (second + '').padStart(2, '0');
            //
            return day + '.' + month + '.' + year + ' ' + hour + ':' + minute + ':' + second;
        }
    }
});


// Список 
var appList = new Vue({
    el: "#appList",
    data: {
        hub: hub,
        company_list: [],
        dt: null
    },
    filters: {
        dttmToText: function (dttm_str) {
            return hub.dttmToText(dttm_str);
        },
        secondsToText: function (sec) {
            return hub.secondsToText(sec);
        },
        secondsToTextNBSP: function (sec) {
            return hub.secondsToTextNBSP(sec);
        },
        secondsLagToClass: function (sec) {
            return hub.secondsLagToClass(sec);
        },
        fileSize: function (size) {
            return hub.fileSize(size);
        }
    }
});


send_http_async = function (url, params, callback) {
    var xhr = new XMLHttpRequest();
    seed = new Date().getTime();
    xhr.open('GET', url + '?seed=' + seed + '&' + params, true);

    //
    xhr.onload = function (e) {
        //console.info("xhr.onload");
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                callback(xhr.responseText);
            } else {
                console.error("xhr.status != 200: [" + xhr.statusText + "]");
                callback(xhr.statusText);
            }
        }
    };

    //
    xhr.onerror = function (e) {
        console.error("xhr.onerror: [" + xhr.statusText + "]");
        callback(xhr.statusText);
    };

    //
    xhr.send(null);
};

on_done = function (res) {
    // appData.input_data.btn_disabled = false;

    // Скроем первичный wait
    document.getElementById("wait").style = "display: none";
    // Покажем главный блок (уберем сокрытие)
    el_appList = document.getElementById("appList");
    if (el_appList != null) {
        el_appList.style = "";
    }
    //
    el_hub = document.getElementById("hub");
    if (el_hub != null) {
        el_hub.style = "";
    }

    //
    try {
        if (res == "") {
            throw new Error("Ответ от сервера не получен");
        }

        //
        var data = JSON.parse(res);

        //
        if (data.success == false) {
            console.info(data.errors);
            //
            hub.state = {};
            hub.state.result = "error";
            hub.state.error_text = (new Date()).toISOString().replace('T', ' ').substring(0, 19) + ": " + data.errors[0].text + "[" + data.errors[0].code + "]";
            hub.state_render_helper = new Date();  // заставляет vue перерисоваться
            //
            if (data.errors[0].code == 401) {
                window.location = "index.php";
            }
            //
            return;
        }

        //
        appList.company_list = data;
        appList.dt = data.dt;

        //
        hub.state_dttm = new Date();

        //
        hub.state.result = "ok";
        hub.state.error_text = null;
        hub.state_render_helper = new Date();  // заставляет vue перерисоваться
    } catch (e) {
        console.info(e);
        //
        hub.state = {};
        hub.state.result = "error";
        hub.state.error_text = (new Date()).toISOString().replace('T', ' ').substring(0, 19) + ": " + e.message;
        hub.render_helper_value = new Date();  // заставляет vue перерисоваться
        //
    } finally {
        set_timeout_reload();
    }
};

isString = function (val) {
    if (typeof val === 'string' || val instanceof String)
        return true;
    else
        return false;
};

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


set_timeout_reload = function () {
    setTimeout("do_reload()", 1000);
};

do_reload = function () {
    try {
        var res0 = send_http_async("web_status_json.php", "guid=" + getUrlParameter("guid"), on_done);
    } catch (e) {
        console.info(e);
    }
};


//
setTimeout("do_reload()", 100);