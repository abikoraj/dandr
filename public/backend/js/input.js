var months = Array.from(NepaliFunctions.GetBsMonths());
var year = NepaliFunctions.GetCurrentBsYear();
var month = NepaliFunctions.GetCurrentBsDate().month;
var day = NepaliFunctions.GetCurrentBsDate().day;
var start_y = 2070;
var now_yr = NepaliFunctions.GetCurrentBsYear();
var now_yr1 = now_yr;
for (let index = start_y; index < now_yr; index++) {
    $("#year").append(
        '<option value="' + now_yr1 + '">' + now_yr1 + "</option>"
    );
    now_yr1--;
}

//XXX Load months in select

$(".load-month").each(function () {
    for (let index = 0; index < months.length; index++) {
        const element = months[index];
        if (index + 1 == month) {
            $("#" + $(this).attr("id")).append(
                '<option selected value="' +
                    (index + 1) +
                    '">' +
                    element +
                    "</option>"
            );
        } else {
            $("#" + $(this).attr("id")).append(
                '<option value="' + (index + 1) + '">' + element + "</option>"
            );
        }
    }
});

//XXX Load years
$(".load-year").each(function () {
    for (let index = now_yr; index >= start_y; index--) {
        $("#" + $(this).attr("id")).append(
            '<option value="' + index + '">' + index + "</option>"
        );
    }
});

//XXX Load Session
$(".load-session").each(function () {
    if (day > 15) {
        $("#" + $(this).attr("id"))
            .val(2)
            .change();
    } else {
        $("#" + $(this).attr("id"))
            .val(1)
            .change();
    }
});
//XXX load nepali calenders
$(".calender").each(function () {
    $("#" + $(this).attr("id")).nepaliDatePicker();
    $(this).val(
        NepaliFunctions.GetCurrentBsYear() +
            "-" +
            (month < 10 ? "0" + month : month) +
            "-" +
            (day < 10 ? "0" + day : day)
    );
});

//XXX set nepali calender
function setDate(id, current = false) {
    if(exists('#'+id)){
        var mainInput = document.getElementById(id);
        mainInput.nepaliDatePicker();
        if (current) {
            $("#" + id).val(
                NepaliFunctions.GetCurrentBsYear() +
                    "-" +
                    (month < 10 ? "0" + month : month) +
                    "-" +
                    (day < 10 ? "0" + day : day)
            );
        }
    }
}
//XXX toogle Date/Date Range selector type
function manageDisplay(element){
    type=$(element).val();
    $('.ct').addClass('d-none');
    $('.ct-'+type).removeClass('d-none');
}

$(".next").keydown(function (event) {
    var key = event.keyCode ? event.keyCode : event.which;
    // console.log(key);
    if (key == "13") {
        event.preventDefault();

        id = $(this).data("next");
        console.log("next id", id);
        $("#" + id).focus();
    }
});

$(".modal").each(function () {
    _id = $(this).data("ff");
    console.log(_id);
    $(this).on("shown.bs.modal", function (e) {
        _id = $(this).data("ff");
        console.log("shown", _id);
        $("#" + _id).focus();
    });
});

$(".href").click(function () {
    window.location.href = $(this).data("target");
});

function exists(selector) {
    return $(selector).length > 0;
}

$(".checkfarmer").focusout(function () {
    no = $(this).val();
    if (no != "") {
        if (!exists("#farmer-" + no)) {
            alert("Farmer with farmer no-" + no + " doesnot exist;");
            $(this).val("");
            $(this).focus();
            $(this).select();
        }
    }
});

function CheckFarmer(no) {
    return exists("#farmer-" + no);
}

function bigScreen(params) {
    $('body').addClass('ls-toggle-menu');
    $('body').addClass('right_icon_toggle');
}

$("connectmax").change(function () {
    connected = $(this).data("connected");
    $("#" + connected).attr("max", $(this).val());
});

$(".checkitem").focusout(function () {
    id = $(this).val();
    console.log("running", id);
    if (id != "") {
        if (!exists("#item-" + id)) {
            alert("Farmer with farmer no-" + id + " doesnot exist;");
            $(this).focus();
            $(this).select();
        } else {
            rate_id = $(this).data("rate");
            rate = $("#item-" + id).data("rate");
            $("#" + rate_id).val(rate);
        }
    }
});

$(".focus-select").focusin(function () {
    $(this).select();
});
function CheckItem(id) {
    return exists("#item-" + id);
}

function printDiv(id) {
    var divToPrint = document.getElementById(id);

    var newWin = window.open("", "Report");
    newWin.document.open();
    newWin.document.write(
        '<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"><link rel="stylesheet" href="' +
            printcss +
            '"></head><body onload="window.print()">' +
            divToPrint.innerHTML +
            "</body></html>"
    );

    newWin.document.close();
}

function closeModal(modal) {
    $("#" + modal).modal("close");
}

function setData(id, value) {
    $("#" + id)
        .val(value)
        .change();
}

function closeModal(id, value) {
    $("#" + id)
        .val(value)
        .change();
}
//XXX file upload
$('.image-input').each(function(){
    c_id=$(this).attr('id');
    $(this).append('<span class="clear" onclick="remove_image(\''+c_id+'\')">X</span>');
    $('#'+c_id+">label").append('<img src="" class="w-100" alt="">');
});

$('.image-input>input').change(function(){
    m = new FileReader();
    c_id=$(this).parent().attr('id');
    if(this.files[0]){

        m.onload = function(e){
            $('#'+c_id+">label>img").attr('src',e.target.result);
           
        };
        m.readAsDataURL(this.files[0]);
    }else{
        $('#'+c_id+">label>img").attr('src','');
    }
  
});

function remove_image(c_id) {
    $('#'+c_id+">label>img").attr('src','');
    $('#'+c_id+">input").val('');
}


function showProgress(title){
    $('#xxx_123').addClass('active');
    $('#yyy_123').html(title);
}

function hideProgress(){
    $('#xxx_123').removeClass('active');

}