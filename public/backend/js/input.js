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
    cc_id=$(this).attr("id");
    $("#" + cc_id).nepaliDatePicker();
    $("#" + cc_id).mask('0000-00-00');
    $(this).attr("placeholder",'YYYY-MM-DD');
    $(this).focus(function(){
        this.select();
    })
    if(this.value=="" || this.value==undefined){

        $(this).val(
            NepaliFunctions.GetCurrentBsYear() +
                "-" +
                (month < 10 ? "0" + month : month) +
                "-" +
                (day < 10 ? "0" + day : day)
        );
    }
});

function loadDates(selector){
    $(selector).each(function () {
        cc_id=$(this).attr("id");
        $("#" + cc_id).nepaliDatePicker();
        $("#" + cc_id).mask('0000-00-00');
        $(this).attr("placeholder",'YYYY-MM-DD');
        $(this).focus(function(){
            this.select();
        })

    });
}

$(".mask").each(function () {
    maskid=$(this).attr("id");
    console.log('ading mask',maskid );
    $("#" + maskid).mask($("#" + maskid).data('dmask'));
    console.log('added mask',maskid );

});

//XXX set nepali calender
function setDate(id, current = false) {
    if(exists('#'+id)){
        $("#" + id).mask('0000-00-00');
        $("#" + id).attr("placeholder",'YYYY-MM-DD');

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
        $("#" + id).select();
    }
});

function dotSanitize(txt=''){
    return txt.replaceAll('.','_');
}

$(".next-switch").keydown(function (event) {
    var key = event.keyCode ? event.keyCode : event.which;
    // console.log(key);

    if (key == "13") {
        id='';
        event.preventDefault();
        f=$(this).data("from");
        if(document.getElementById(f).checked){
            id = $(this).data("next");
        }else{
            id = $(this).data("switch");
        }
        console.log("next id", id,f,document.getElementById(f).checked);
        $("#" + id).focus();
    }
});

$(".modal").each(function () {
    _id = $(this).data("ff");
    console.log(_id);
    $(this).on("shown.bs.modal", function (e) {
        _id = $(this).data("ff");
        console.log("shown", _id);
        // $("#" + _id)[0].focus();
        if(_id!=undefined){
            document.getElementById(_id).focus();
        }
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

function getDateTimeLocal(dateVal){
    var day = dateVal.getDate().toString().padStart(2, "0");
    var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
    var hour = dateVal.getHours().toString().padStart(2, "0");
    var minute = dateVal.getMinutes().toString().padStart(2, "0");
    var sec = dateVal.getSeconds().toString().padStart(2, "0");
    var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
    return dateVal.getFullYear() + "-" + (month) + "-" + (day) + "T" + (hour) + ":" + (minute)  ;
}

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
    id = dotSanitize( $(this).val());
    
    console.log("running", id);
    if (id != "") {
        if (!exists("#item-" + id)) {
            alert("Item with item no-" + id + " doesnot exist;");
            $(this).focus();
            $(this).val('');
            $(this).select();
        } else {
            rate_id = $(this).data("rate");
            rate = $("#item-" + id).data("rate");
            $("#" + rate_id).val(rate);
            try {
                loadBatch($("#item-" + id).data("id"));
            } catch (error) {
                
            }
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
    $('#'+c_id+">label").append('<img src="" style="min-height: 100px;" class="w-100" alt="">');
    $('#'+c_id+">input").attr('accept','image/*');
});

$('.switch').each(function(){
    target=this.dataset.switch;
    _if=this.dataset.if;
    if(_if!=undefined){
        if($(_if)[0].checked){
            $(target).removeClass('d-none');
        }else{
            if(this.checked){
                $(target).removeClass('d-none');
            }else{
                $(target).addClass('d-none');
            }
        }
    }else{

        if(this.checked){
            $(target).removeClass('d-none');
        }else{
            $(target).addClass('d-none');
        }
    }
    console.log('switching',target,this.checked);
    $(this).change(function(){
        target=this.dataset.switch;
        cs=this.dataset.case;
        d_main=this.dataset.main;
        _if=this.dataset.if;
        if(d_main!=undefined){
            $(d_main).addClass('d-none');
        }
        if(_if!=undefined){
            if($(_if)[0].checked){
                $(target).removeClass('d-none');
            }else{
                if(cs!=undefined){
                    if(this.checked){
                        $(cs).addClass('d-none');
                    }else{
                        $(cs).removeClass('d-none');
                    }
                }
                if(this.checked){
                    $(target).removeClass('d-none');
                }else{
                    $(target).addClass('d-none');
                }
            }
        }else{
            if(cs!=undefined){
                if(this.checked){
                    $(cs).addClass('d-none');
                }else{
                    $(cs).removeClass('d-none');
                }
            }
            if(this.checked){
                $(target).removeClass('d-none');
            }else{
                $(target).addClass('d-none');
            }
        }


        console.log('switching again',target,this.checked);

    });
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

$('.toogle').each(function(){
    on=$(this).data('on');
    collapse=$(this).data('collapse');
    if(on){
        $(collapse).removeClass('d-none');
        $(this).children('.on').removeClass('d-none');
        $(this).children('.off').addClass('d-none');
    }else{
        $(collapse).addClass('d-none');
        $(this).children('.on').addClass('d-none');
        $(this).children('.off').removeClass('d-none');

    }
    console.log(collapse,on,'collapse');
    $(this).click(function(){
        on=$(this).data('on');
        collapse=$(this).data('collapse');
        on=!on;
        if(on){
            $(collapse).removeClass('d-none');
            $(this).children('.on').removeClass('d-none');
            $(this).children('.off').addClass('d-none');
        }else{
            $(collapse).addClass('d-none');
            $(this).children('.on').addClass('d-none');
            $(this).children('.off').removeClass('d-none');

        }
        $(this).data('on',on);
        console.log(collapse,on,'collapse');

    });

});



function validate(selector,type){
    val='';
    val=$(selector).val();
    if(type=='date'){
        if(val==''){
            return false;
        }
        minlist=val.split('-');
        if(minlist.length<3){
            return false;
        }
    }
    return true;
}

function copyInput(list){
    console.log(list);
    for (let index = 0; index < list.length; index+=2) {
        const element1 = list[index];
        const element2 = list[index+1];
        $(element1).val($(element2).val());
    }
}

function setFocusShortCut(list){
    for (let index = 0; index < list.length; index+=2) {
        const element1 = list[index];
        const element2 = list[index+1];
        $('body,.form-control1, .form-control').bind('keydown', element1, function(e){
            e.preventDefault();
            $('#'+element2).focus();
            $('#'+element2).select();
        });
    }
}

function setClickShortCut(list){
    for (let index = 0; index < list.length; index+=2) {
        const element1 = list[index];
        const element2 = list[index+1];
        $('body,.form-control1, .form-control').bind('keydown', element1, function(e){
            e.preventDefault();
            $('#'+element2).trigger( "click" );
        });
    }
}

function newTab(url){
	$('#xxx_52').attr('href',url);
	$('#xxx_52')[0].click();
}


function toNepaliDate(d){
    console.log(d);
    const year=parseInt(d/10000);
    let _d=d%10000;
    const month=parseInt(_d/100);
     _d=_d%100;
    return ''+year+'-'+(month<10?'0'+month:month)+'-'+(_d<10?('0'+_d):_d);

}

function fromNepaliDate(){

}
