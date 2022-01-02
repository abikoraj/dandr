<script>
    $("#barcode").keydown(function (e) {
    if (e.which == 13) {
        const _r_id = this.dataset.searchid;
        let _index = $("#" + _r_id).data("index");
        barcode = this.value;
        if (barcode.length > 0 && _index<0) {
            billpanel.addItem(barcode);
        }
    }
});

$("#barcode").focus(function (e) {
    e.preventDefault();
    console.log(e, "focused");
    barcode = this.value;
    if (barcode.length > 0) {
        this.select();
    }
});

$("#input_customer_search").keydown(function (e) {
    if (e.which == 13) {
        billpanel.customerSearch();
    }
});

function customerSearchInit() {
    $("#customer-input-panel").addClass("d-none");
    $("#cutomer-search-panel").removeClass("d-none");
    $('#input_customer_search').focus();
    $('#input_customer_search').select();
}
function addItem(barcode) {
    console.log(barcode);
}

function checkWholeSale(ele) {
    if(ele.checked){
        $('#item-wholesale').removeClass('d-none');
        $('#item-rate').addClass('d-none');
    }else{
        $('#item-wholesale').addClass('d-none');
        $('#item-rate').removeClass('d-none');
    }
}


const producturl = "";
lock = false;
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


var chat;
$.connection.hub.url = "http://localhost:4200/signalr";
try {
    $.connection.hub.error(function (error) {
        console.log("SignalR error: " + error);
    });

    chat = $.connection.billHub;
} catch (err) {
    console.log(err);
}


var billpanel = {
    holdBillId:null,
    raw: null,
    customer: null,
    customerSelected: false,
    products: [],
    index: 0,
    billitems: [],
    print: false,
    selectedItem:null,
    total: {
        total: 0,
        discount: 0,
        taxable: 0,
        tax: 0,
        grandtotal: 0,
        rounding: 0,
        paid: 0,
        due: 0,
        return: 0,
    },
    resetInput: function () {
        for (const key in this.total) {
            this.total[key] = 0;
            this.setVal(key, 0);
        }
        this.setVal('rounding', 0,2);

    },
    setVal: function (name, value,type=1) {
        if(type==1){
            $("#input-" + name).val(value);

        }else{
            $("#input-" + name).html(value);

        }
    },
    getVal: function (name) {
        return $("#input-" + name).val();
    },
    calculateTotal: function () {
        let _amount = 0;
        let _discount = 0;
        let _taxable = 0;
        let _tax = 0;
        let _total = 0;
        for (let key in this.billitems) {
            bi=this.billitems[key];
            _amount += parseFloat(bi.amount);
            _discount +=parseFloat( bi.discount);
            _taxable+= parseFloat(bi.taxable);
            _tax += parseFloat(bi.tax);
            _total += parseFloat( bi.total);
        }
        let _temptotal=_total;
        _total=Math.ceil(_total);
        const _rounding=(_total-_temptotal).toFixed(2);
        this.setVal("total", _amount);
        this.setVal("discount", _discount);
        this.setVal("taxable", _taxable);
        this.setVal("tax", _tax);
        this.setVal("grandtotal", _total);
        this.setVal("rounding", _rounding,2);

        this.total.total = _amount;
        this.total.taxable = _taxable;
        this.total.discount = _discount;
        this.total.tax = _tax;
        this.total.rounding = _rounding;
        this.total.grandtotal = _total;

        this.total.paid = parseFloat(this.getVal("paid"));
        if (isNaN(this.total.paid)) {
            this.total.paid = 0;
        }
        this.total.due = this.total.grandtotal - this.total.paid;
        this.total.return = 0;
        if (this.total.due < 0) {
            this.total.return = -1 * this.total.due;
            this.total.due = 0;
        }
        this.setVal("due", this.total.due);
        this.setVal("return", this.total.return);
    },
    ele: function () {
        return $("#bill-items");
    },
    init: function () {
        var p = Array();
        showProgress("Loading Items");
        axios
            .get(itemsURL)
            .then((res) => {
                items = res.data;
                billpanel.raw = res.data;

                for (let index = 0; index < items.length; index++) {
                    const item = items[index];
                    barcode = format(index.toString(), 5);
                    p[item.barcode] = {
                        id: item.id,
                        name: item.name,
                        rate: item.rate,
                        taxable: item.taxable,
                        tax: item.tax,
                        wholesale: item.wholesale,
                    };
                }
                this.products = p;
                $("#barcode").search({
                    list: billpanel.raw,
                    mod: "barcode",
                    renderfunc: "renderBarcode",
                });
                $("#item-barcode").search({
                    list: billpanel.raw,
                    mod: "item_barcode",
                    filterfunc:'filterItemBarcode',
                    renderfunc: "renderBarcodeItem",
                });
                $("#item-name").search({
                    list: billpanel.raw,
                    mod: "item",
                    renderfunc: "renderItem",
                    filterfunc:'filterItem'
                });
                hideProgress();
            })
            .catch((err) => {
                hideProgress();
            });

        setInterval(() => {
            pushData();
        }, 10000);
        pushData();
    },
    setRate: function () {
        var rate = $("#item-name option:selected").data("rate");
        $("#item-rate").val(rate);
        $("#item-qty").focus();
    },
    plus: function (key) {
        this.billitems[key].qty += 1;
                const _amount=this.billitems[key].item_rate*this.billitems[key].qty;
                const _discount=0;
                const tot=_amount-_discount;
                let _tax=0;
                let _taxable=0;
                if(this.billitems[key].item_taxable==1){
                    _taxable=tot;
                    _tax=((_taxable)*(this.billitems[key].item_tax)/100).toFixed(2);
                }
                const _total=(parseFloat(_tax)+tot).toFixed(2);
                this.billitems[key].amount = _amount;
                this.billitems[key].discount = _discount;
                this.billitems[key].taxable = _taxable;
                this.billitems[key].tax = _tax;
                this.billitems[key].total = _total;

                this.renderBillItem(key);
    },
    minus: function (key) {
        this.billitems[key].qty -= 1;
                const _amount=this.billitems[key].item_rate*this.billitems[key].qty;
                const _discount=0;
                const tot=_amount-_discount;
                let _tax=0;
                let _taxable=0;
                if(this.billitems[key].item_taxable==1){
                    _taxable=tot;
                    _tax=((_taxable)*(this.billitems[key].item_tax)/100).toFixed(2);
                }
                const _total=(parseFloat(_tax)+tot).toFixed(2);
                this.billitems[key].amount = _amount;
                this.billitems[key].discount = _discount;
                this.billitems[key].taxable = _taxable;
                this.billitems[key].tax = _tax;
                this.billitems[key].total = _total;

        if (this.billitems[key].amount > 0) {
            this.renderBillItem(key);
        } else {
            this.removeBillItem(key);
        }
    },
    removeBillItem: function (key) {
        $("#bill-item-" + key).remove();
        delete this.billitems[key];
        // console.log($temparr);
        // this.billitems = $temparr;
        this.calculateTotal();
    },
    updateBillItem: function (billItem) {
        key = billItem.item.id.toString();
        $("#bill-item-amount-" + key).html(billItem.amount);
        $("#bill-item-total-" + key).html(billItem.amount * billItem.item.rate);
        this.calculateTotal();
    },
    renderBillItem: function (key) {
        item = this.billitems[key];
        html = "<tr class='bill-item' id='bill-item-" + key + "'>";
        html += "<td>" + item.item_name + "</td>";
        html += "<td>" + item.item_rate + "</td>";
        html += "<td> <div  class='qty'>";
        html +=
            "<span class='btn-qty' onclick='billpanel.plus(\"" +
            key +
            "\")'><img src='images/plus.svg' class='w-100'></span>";
        html +=
            "<span class='qty-value' id='bill-item-amount-" +
            key +
            "'>" +
            item.qty +
            "</span>";
        html +=
            "<span class='btn-qty' onclick='billpanel.minus(\"" +
            key +
            "\")'><img src='images/sub.svg' class='w-100'></span>";
        html += "</div></td>";

        html +=
            "<td id='bill-item-amount-" +
            key +
            "'>" +
            item.amount +
            "</td>";
        html +=
            "<td id='bill-item-discount-" +
            key +
            "'>" +
            item.discount +
            "</td>";
            if(companyUseTax){

            html +=
                "<td id='bill-item-taxable-" +
                key +
                "'>" +
                item.taxable +
                "</td>";
            html +=
                "<td id='bill-item-tax-" +
                key +
                "'>" +
                item.tax +
                "</td>";
            }
        html +=
            "<td id='bill-item-total-" +
            key +
            "'>" +
            item.total +
            "</td>";
        html += "</tr>";
        if($("#bill-item-" + key ).length>0){
            $("#bill-item-" + key ).replaceWith(html);
        }else{

        this.ele().append(html);
        }
        this.calculateTotal();
    },
    addItem: function (barcode) {
        if(posCache){

            item = this.products[barcode];
            if (item == undefined) {
                $.notify("Not Item found With Barcode " + barcode, {
                    className: "error",
                });
                $("#barcode").focus();
                $("#barcode").select();
            } else {
                const key = item.id.toString()+"-item";
                billItem = this.billitems[key];
                if (billItem == undefined) {

                    const _amount=item.rate*1;
                    const _discount=0;
                    const tot=_amount-_discount;

                    let _taxable=0;
                    let _tax=0;

                    if(item.taxable==1 && companyUseTax){
                        _taxable=_amount-_discount;
                        _tax=((_taxable)*(item.tax)/100).toFixed(2);
                    }
                    const _total=(parseFloat(_tax)+tot).toFixed(2);
                    this.billitems[key] = {
                        item_id: item.id,
                        item_name: item.name,
                        item_rate:item.rate,
                        item_taxable:item.taxable,
                        item_tax:item.tax,
                        amount:_amount,
                        discount:_discount,
                        taxable:_taxable,
                        tax:_tax,
                        total:_total,
                        qty: 1,
                    };
                    this.renderBillItem(key);
                } else {
                    this.billitems[key].qty += 1;
                    const _amount=this.billitems[key].item_rate*this.billitems[key].qty;
                    const _discount=0;
                    const tot=_amount-_discount;
                    let _tax=0;
                    let _taxable=0;
                    if(this.billitems[key].item_taxable==1 && companyUseTax){
                        _taxable=_amount-_discount;
                        _tax=((_taxable)*(this.billitems[key].item_tax)/100).toFixed(2);
                    }
                    const _total=(parseFloat(_tax)+tot).toFixed(2);
                    this.billitems[key].amount = _amount;
                    this.billitems[key].discount = _discount;
                    this.billitems[key].taxable = _taxable;
                    this.billitems[key].tax = _tax;
                    this.billitems[key].total = _total;

                    this.renderBillItem(key);
                }
                console.log(this.billitems);
            }
            console.log(item);
            $("#barcode").val("");
            $("#barcode").closeSearch();
            $("#barcode").focus();
        }else{
            axios.post('{{route('pos.items-single')}}',{"barcode":barcode})
            .then((res)=>{
               const item=res.data;
                if (item == undefined) {
                    $.notify("Not Item found With Barcode " + barcode, {
                        className: "error",
                    });
                    $("#barcode").focus();
                    $("#barcode").select();
                } else {
                    const key = item.id.toString()+"-item";
                    billItem = this.billitems[key];
                    if (billItem == undefined) {

                        const _amount=item.rate*1;
                        const _discount=0;
                        const tot=_amount-_discount;
                        let _taxable=0;
                        let _tax=0;
                        if(item.taxable==1 && companyUseTax){
                            _taxable=_amount-_discount;
                            _tax=((_taxable)*(item.tax)/100).toFixed(2);
                        }
                        const _total=(parseFloat(_tax)+tot).toFixed(2);
                        this.billitems[key] = {
                            item_id: item.id,
                            item_name: item.name,
                            item_rate:item.rate,
                            item_taxable:item.taxable,
                            item_tax:item.tax,
                            amount:_amount,
                            discount:_discount,
                            taxable:_taxable,
                            tax:_tax,
                            total:_total,
                            qty: 1,
                        };
                        this.renderBillItem(key);
                    } else {
                        this.billitems[key].qty += 1;
                        const _amount=this.billitems[key].item_rate*this.billitems[key].qty;
                        const _discount=0;
                        const tot=_amount-_discount;
                        let _tax=0;
                        let _taxable=0;
                        if(this.billitems[key].item_taxable==1 && companyUseTax){
                            _taxable=_amount-_discount;
                            _tax=((_taxable)*(this.billitems[key].item_tax)/100).toFixed(2);
                        }
                        const _total=(parseFloat(_tax)+tot).toFixed(2);
                        this.billitems[key].amount = _amount;
                        this.billitems[key].discount = _discount;
                        this.billitems[key].taxable = _taxable;
                        this.billitems[key].tax = _tax;
                        this.billitems[key].total = _total;

                        this.renderBillItem(key);
                    }
                    console.log(this.billitems);
                }
                console.log(item);
                $("#barcode").val("");
                $("#barcode").closeSearch();
                $("#barcode").focus();
                })
            .catch((err)=>{
                console.log(err);
            })
        }
    },
    addItemSelect: function () {


        const _qty = parseFloat($("#item-qty").val());
        if (isNaN(_qty)) {
            $.notify("Please Enter Qty", {
                className: "error",
            });
            return;
        } else {
            if (_qty <= 0) {
                $.notify("Please Enter Qty", {
                    className: "error",
                });
                return;
            }
        }
        item = this.selectedItem;
        if (item == undefined || item==null) {
            $.notify("Please Select A Item", {
                className: "error",
            });
            $("#item-name").focus();
            $("#item-name").select();
        } else {
            const is_wholesale=$('#item-iswholesale')[0].checked;
            key = item.id.toString();
            billItem = this.billitems[key];
            billItem = this.billitems[key];
            if (billItem == undefined) {

                const _amount=(is_wholesale?item.wholesale: item.rate)*_qty;
                const _discount=0;
                const tot=_amount-_discount;
                let _taxable=0;
                let _tax=0;
                if(item.taxable==1 && companyUseTax){
                    _taxable=tot;
                    _tax=((_taxable)*(item.tax)/100).toFixed(2);
                }
                const _total=(parseFloat(_tax)+tot).toFixed(2);
                this.billitems[key] = {
                    item_id: item.id,
                    item_name: item.name,
                    item_rate:(is_wholesale?item.wholesale: item.rate),
                    item_taxable:item.taxable,
                    item_tax:item.tax,
                    amount:_amount,
                    discount:_discount,
                    taxable:_taxable,
                    tax:_tax,
                    total:_total,
                    qty: _qty,
                };
                this.renderBillItem(key);
            } else {
                this.billitems[key].qty += _qty;
                const _amount=this.billitems[key].item_rate*this.billitems[key].qty;
                const _discount=0;
                const tot=_amount-_discount;
                let _tax=0;
                let _taxable=0;
                if(this.billitems[key].item_taxable==1 && companyUseTax){
                    _taxable=tot;
                    _tax=((_taxable)*(this.billitems[key].item_tax)/100).toFixed(2);
                }
                const _total=(parseFloat(_tax)+tot).toFixed(2);
                this.billitems[key].amount = _amount;
                this.billitems[key].discount = _discount;
                this.billitems[key].taxable = _taxable;
                this.billitems[key].tax = _tax;
                this.billitems[key].total = _total;

                this.renderBillItem(key);
            }
            $("#item-name").val('').trigger("change");
            $("#item-rate").val("");
            $("#item-barcode").val('');
            $("#item-qty").val("");
            $("#item-barcode").focus().select();
            this.selectedItem=null;
            console.log(this.billitems);
        }
    },
    cancelBill: function () {
        if (confirm("Do You Want To Cancel Current Bill")) {
            this.resetBill();
        }
    },
    resetBill: function () {
        this.resetInput();
        this.resetCustomer();
        this.holdBillId=null;
        this.billitems = [];
        this.ele().html("");
        billpanel.resetCustomer();
        $("#payment-form")[0].reset();
        managePaymentType(0);
        $("#payment").modal("hide");
    },
    customerSearch: function () {
        console.log("search started");
        searchType = $('input[name="radio_customer_search"]:checked').val();
        keyword = $("#input_customer_search").val();
        data = {};
        if (searchType == 1) {
            if (keyword.length < 5) {
                return;
            }
            data = { phone: keyword };
        } else {
            if (keyword.length < 2) {
                return;
            }
            data = { name: keyword };
        }
        axios
            .post(customerSearchURL, data)
            .then((res) => {
                console.log(res);
                html = "";
                if (res.data.length > 0) {
                    res.data.forEach((customer) => {
                        html = "<div ";
                        for (const key in customer) {
                            html += "data-" + key + "='" + customer[key] + "' ";
                        }

                        html +=
                            " class='customer-single' onclick='billpanel.selectCustomer(this)'><b class='name'>" +
                            customer.name +
                            "</b><br>" +
                            "<b class='phone'>" +
                            customer.phone +
                            "</b><br>" +
                            "</div>";
                    });
                } else {
                    html =
                        "<div class='customer-single'>No Customer Found</div>";
                }
                console.log(html);
                $("#customer_list").html(html);
            })
            .catch((err) => {});
    },
    selectCustomer: function (ele) {
        this.customer = ele.dataset;
        console.log(this.customer);
        this.customerSelected = true;
        $("#customer-input-panel").removeClass("d-none");
        $("#cutomer-search-panel").addClass("d-none");
        $("#customer-name").val(this.customer.name);
        $("#customer-phone").val(this.customer.phone);
        $("#customer-address").val(this.customer.address??"");
        $("#customer-panvat").val(this.customer.panvat=='null'?"":this.customer.panvat);
    },
    resetCustomer: function () {
        this.customer = null;
        this.customerSelected = false;
        $("#customer-input-panel").removeClass("d-none");
        $("#cutomer-search-panel").addClass("d-none");
        $("#customer-name").val("");
        $("#customer-phone").val("");
        $("#customer-address").val("");
        $("#customer-panvat").val("");
    },
    closeCusSearch: function () {
        $("#customer-input-panel").removeClass("d-none");
        $("#cutomer-search-panel").addClass("d-none");
    },
    saveCustomer: function (e, ele) {
        e.preventDefault();
        if (!lock) {
            lock = true;
            showProgress("Adding Customer");
            var data = new FormData(ele);
            axios
                .post(addCustomer, data)
                .then((res) => {
                    hideProgress();
                    this.customer = res.data;
                    console.log(this.customer);
                    this.customerSelected = true;
                    $("#customer-input-panel").removeClass("d-none");
                    $("#cutomer-search-panel").addClass("d-none");
                    $("#customer-name").val(this.customer.name);
                    $("#customer-phone").val(this.customer.phone);
                    $("#customer-address").val(this.customer.address);
                    lock = false;
                    $("#addCustomerModal").modal("hide");
                    ele.reset();
                })
                .catch((err) => {
                    hideProgress();
                    lock = false;
                });
        }
    },
    initSaveBill: function (_print) {
        if (Object.keys(this.billitems).length <= 0) {
            alert("No Items added to bill.");
            return;
        }
        if (this.total.due > 0) {
            if (this.customer == null) {
                alert("Please Select A customer For Due Bill.");
                return;
            }
        }
        this.print = _print;

        if (savePayment == 1) {
            if (this.total.paid > 0) {
                $("#payment").modal("show");
            } else {
                billpanel.saveBill();
            }
        } else {
            billpanel.saveBill();
        }
    },
    saveBill: function () {
        let bis=[];
        for (const key in this.billitems) {
            if (Object.hasOwnProperty.call(this.billitems, key)) {
                bis.push(this.billitems[key]);
            }
        }
        data = {
            customer: this.customer,
            billitems: bis,
            total: this.total,
            panvat: $("#customer-panvat").val(),
            payment_type: $("input[name='payment-type']:checked").val(),
            bank: $("#bank").val(),
            gateway: $("#gateway").val(),
            bank_name: $("#bank-name").val(),
            cardno: $("#cardno").val(),
            txnno: $("#txnno").val(),
            chequeno: $("#chequeno").val(),
            holdBillId:this.holdBillId,
        };
        console.log(data);
        if (data.payment_type == 1) {
            if (
                (data.cardno == null || data.cardno == "") &&
                cardnoRequired == 1
            ) {
                alert("Please Enter Card No");
                return;
            }
        }
        if (data.payment_type == 2) {
            if (
                (data.bank_name == null || data.bank_name == "") &&
                cardnoRequired == 1
            ) {
                alert("Please Enter Bank name");
                return;
            }
            if (
                (data.chequeno == null || data.chequeno == "") &&
                cardnoRequired == 1
            ) {
                alert("Please Enter Cheque No");
                return;
            }
        }
        showProgress("Saving Bill");
        axios
            .post(addBillURL, data)
            .then((res) => {
                console.log(res);
                billpanel.resetBill();
                if (this.print) {
                    billpanel.printProcedure(res.data);
                } else {
                    hideProgress();
                }
            })
            .catch((err) => {
                console.log(err);
                hideProgress();
            });
    },
    printProcedure: function (data) {
        printSetting.print(data);
    },
};

var printSetting = {
    set: false,
    type: 0,
    data: null,
    queue: false,
    sendPrintNotification: function () {
        axios.post(printedBillURL, { id: this.data.id });
    },
    reconnectServer: function () {
        state = $.connection.hub.state;
        if (state == undefined) {
            state = 4;
        }
        if (state == 4) {
            printSetting.restart();
        }
    },
    setStatus: function () {
        state = $.connection.hub.state;
        if (state == undefined) {
            state = 4;
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
                    });
                    chat = $.connection.billHub;
                    printSetting.restart();
                })
                .fail(function (jqxhr, settings, exception) {
                    if (printSetting.queue) {
                        $("#print-type-0")[0].checked = true;
                        printSetting.type = 0;
                        url = printBillURL.replace(
                            "__xx__",
                            printSetting.data.id
                        );
                        // window.open(url);
                        newTab(url);
                        hideProgress();
                        printSetting.queue = false;
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

                chat.server
                    .print(this.data)
                    .then((res) => {
                        console.log(res);
                        this.queue = false;
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
            this.setStatus();
        }
    },
};
$(function () {
    billpanel.init();
    // Declare a proxy to reference the hub.
    printSetting.init();

    $('body, input').bind('keydown', 'f1', function(e){
            e.preventDefault();
            $('#barcode').focus();
            $('#barcode').select();
        });
        $('body, input').bind('keydown', 'f2', function(e){
            e.preventDefault();
            $('#input-paid').focus();
            $('#input-paid').select();
        });
        $('body, input').bind('keydown', 'f3', function(e){
            e.preventDefault();
            console.log($('#item-barcode')[0]);
            $('#item-barcode').focus();
            $('#item-barcode').select();
        });

        $('body, input').bind('keydown', 'alt+s', function(e){
            e.preventDefault();
            customerSearchInit();

        });
        $('body, input').bind('keydown', 'alt+a', function(e){
            e.preventDefault();
            $('#addCustomerModal').modal('show');
            $('#name').focus();
        });
        $('body, input').bind('keydown', 'alt+n', function(e){
            e.preventDefault();
            $('input[name="radio_customer_search"][value="2"]')[0].checked=true;
        });

        $('body, input').bind('keydown', 'alt+p', function(e){
            e.preventDefault();
            $('input[name="radio_customer_search"][value="1"]')[0].checked=true;
        });


        $('body, input').bind('keydown', 'f4', function(e){
            e.preventDefault();
            console.log($('#item-name')[0]);
            $('#item-name').focus();
            $('#item-name').select();
        });

        $('body, input').bind('keydown', 'ctrl+s', function(e){
            e.preventDefault();
            if($('#payment')[0].style.display=='none' || $('#payment')[0].style.display==''){
                billpanel.initSaveBill();
            }else{
                billpanel.saveBill();
            }
        });

        $('body, input').bind('keydown', 'ctrl+p', function(e){
            e.preventDefault();
            billpanel.initSaveBill(true);
        });

        $('body, input').bind('keydown', 'ctrl+c', function(e){
            e.preventDefault();
            billpanel.cancelBill();
        });

        $('body, input').bind('keydown', 'ctrl+h', function(e){
            e.preventDefault();
            holdBillPanel.init();
        });

        $('#item-qty').bind('keydown', 'return', function(e){
            e.preventDefault();
            billpanel.addItemSelect();
        });

        //payment shourcut
        $('body, input').bind('keydown', 'alt+1', function(e){
            e.preventDefault();
            $('#payment-type-1')[0].checked=true;
            $('#payment-type-1').focus();
            managePaymentType(0);

        });
        $('body, input').bind('keydown', 'alt+2', function(e){
            e.preventDefault();
            $('#payment-type-2')[0].checked=true;
            $('#payment-type-2').focus();
            managePaymentType(1);


        });
        $('body, input').bind('keydown', 'alt+3', function(e){
            e.preventDefault();
            $('#payment-type-3')[0].checked=true;
            $('#payment-type-3').focus();
            managePaymentType(2);


        });
        $('body, input').bind('keydown', 'alt+4', function(e){
            e.preventDefault();
            $('#payment-type-4')[0].checked=true;
            $('#payment-type-4').focus();
            managePaymentType(3);

        });

});

</script>
