var chat;
const states = {
    connecting: 0,
    connected: 1,
    reconnecting: 2,
    disconnected: 4,
};
const stateText = [
    " connecting",
    " connected",
    " reconnecting",
    "",
    " disconnected",
];
const stateClass = [
    "bg-secondary",
    " bg-success",
    " bg-warning text-dark",
    "",
    " bg-danger",
];
var printSetting = {
    set: false,
    type: 0,
    data: null,
    queue: false,
    sendPrintNotification: function () {
        axios.post(printedBillURL, { id: this.data.id });
    },
    reconnectServer: function () {
        state=4;
        try {

            state = $.connection.hub.state;
            if (state == undefined) {
                state = 4;
            }
        } catch (error) {
            console.log(error);
        }
        if (state == 4) {
            printSetting.restart();
        }
    },
    setStatus: function () {
        state=4;
        try {

            state = $.connection.hub.state;
            if (state == undefined) {
                state = 4;
            }
        } catch (error) {
            console.log(error);
        }
        $("#print-server-status").attr("class", "badge " + stateClass[state]);
        $("#print-server-status").text("Printer " + stateText[state]);
    },
    setType: function () {
        this.type = $("input[name='print-type']:checked").val();
    },
    restart: function () {
        if (this.set) {
            $.connection.hub.start().done(function () {
                if (printSetting.queue) {
                    printSetting.print(printSetting.data);
                }
            });
        } else {
            $.getScript("http://localhost:4200/signalr/hubs")
                .done(function (res, stat) {
                    console.log(res, stat, "restart status");
                    printSetting.set = true;
                    $.connection.hub.url = "http://localhost:4200/signalr";
                    $.connection.hub.error(function (error) {
                        console.log("SignalR error: " + error);
                    });
                    $.connection.hub.stateChanged(function (e) {
                        console.log(e);
                        printSetting.setStatus();

                    });
                    chat = $.connection.billHub;
                    printSetting.setStatus();
                    printSetting.restart();
                })
                .fail(function (jqxhr, settings, exception) {
                    if (printSetting.queue) {
                        document.getElementById("print-type-0").checked = true;
                        printSetting.type = 0;
                        set = false;
                        printSetting.setStatus();

                    }
                });
        }
    },
    print: function (_data) {
        printSetting.queue = true;
        printSetting.data = _data;
        if (this.type == 0) {
            url = printBillURL.replace("__xx__", this.data.id);
            // alert(url, this.data.id);
            // window.open(url);
            newTab(url);
            hideProgress();
            printSetting.queue = false;
        } else {
            if ($.connection.hub.state == states.connected) {
                showProgress("Printing");
                console.log('printing');
                chat.server.print(printSetting.data)
                    .then((res) => {
                        console.log(res);
                        printSetting.queue = false;
                        hideProgress();
                        printSetting.sendPrintNotification();
                    })
                    .catch((err) => {
                        console.log(err);
                        url = printBillURL.replace("__xx__", this.data.id);
                        newTab(url);
                        hideProgress();
                        printSetting.queue = false;
                        hideProgress();
                    });
            } else {
                this.restart();
            }
        }
    },
    init: function () {
        $('body').append('<a href="" id="xxx_52" class="d-none" target="_blank">click</a>');
        this.type = $("input[name='print-type']:checked").val();

        if (chat != undefined) {
            $.connection.hub.stateChanged(function (e) {
                console.log(e);
                printSetting.setStatus();
            });
            $.connection.hub.start().fail(function () {
                console.log("Could not Connect!");
            });
        } else {
            $.getScript("http://localhost:4200/signalr/hubs")
                .done(function (res, stat) {
                    console.log(res, stat, "restart status");
                    printSetting.set = true;
                    $.connection.hub.url = "http://localhost:4200/signalr";
                    $.connection.hub.error(function (error) {
                        console.log("SignalR error: " + error);
                    });
                    chat = $.connection.billHub;
                    $.connection.hub.stateChanged(function (e) {
                        console.log(e);
                        printSetting.setStatus();
                    });
                    $.connection.hub.start().fail(function () {
                        console.log("Could not Connect!");
                    });
                })
                .fail(function (jqxhr, settings, exception) {
                    document.getElementById("print-type-0").checked = true;
                    printSetting.type = 0;
                    printSetting.setStatus();

                });
        }
    },
};
