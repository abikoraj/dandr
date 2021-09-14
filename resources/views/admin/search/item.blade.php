<style>
    .search-result {
        background: white;
        padding: 10px;
        position: fixed;
        display: none;
        z-index: 1000;
        overflow-x: auto;
        border: 1px solid #ced4da;

    }

    .search-result .search-item {
        cursor: pointer;
    }
    .search-result .search-item.active {
        background: blue;
        color:white;
    }

    .search-result .search-item:hover {
        cursor: pointer;
        background: rgba(0, 0, 0, 0.05);
    }
    .search-result .search-item.active:hover {
        cursor: pointer;
        background: rgba(0, 0, 255, 0.733);
        color:white;
    }

    .search-close {
        padding: 5px;

    }

</style>
<span id="ren" class="d-none">

</span>
<script>
    var skipContainers=[

    ];

    var currentContainer='';
    (function($) {

        $.fn.search = function(options) {

            var search_id = 1;
            var search_setting = $.extend({
                color: "#556b2f",
                backgroundColor: "white",
                url: 'http://localhost:8000/admin/items',
                method: 'post',
                dorender: true,
                renderfunc: "itemRender",
                rendercustom: false,
                renderele: "#ren",
                mod: "item"
            }, options);
            return this.each(function(index, ele) {
                console.log(ele);

                const result_id = search_setting.mod + '_result_' + search_id.toString();
                $(ele).attr('data-searchid', result_id);
                $('body').append('<div id="' + result_id + '_overlay" class=" xxx_tp1 search-result">' +
                    '<div class="search-close xxx_tp1" >' +
                    '<span class="btn xxx_tp1" data-searchid="' + result_id +
                    '" onclick="$(this).closeSearch();">X</span>' +
                    '</div>' +
                    '<div class="xxx_tp1" data-index="0" id="' + result_id + '" ></div></div>');
                search_id += 1;
                $('#'+result_id + '_overlay').click(function(){
                    currentContainer='#'+result_id + '_overlay';
                    console.log(currentContainer);
                })
                $(ele).on('propertychange input', function(e) {
                    const _keyword = $(ele).val();
                    console.log(_keyword.length, ele.dataset.searchid);
                    const __r_id = ele.dataset.searchid;
                    const _r_id = __r_id + '_overlay';
                    // debugger;
                    if (_keyword.length > 2) {
                        if(!search_setting.rendercustom){

                            const el = ele.getBoundingClientRect();
                            const t = $(ele).offset().top - $(window).scrollTop();
                            const h = window.innerHeight

                            $('#' + _r_id).css('top', (t + el.height + 3).toString() + "px");
                            $('#' + _r_id).css('height', "300px");
                            if ((h - t) < 300) {
                                $('#' + _r_id).css('height', (h - t).toString() + "px");
                            }
                            $('#' + _r_id).css('left', el.left + "px");
                            $('#' + _r_id).css('width', el.width + "px");
                            $('#' + _r_id).css('display', "block");
                        }

                        if (search_setting.method == 'post') {
                            axios.post(search_setting.url, {
                                    keyword: _keyword
                                })
                                .then((res) => {
                                    res.ele = '#' + _r_id;
                                    if (search_setting.dorender) {
                                        const fn = window[search_setting.renderfunc];
                                        console.log(res.data);
                                        if (typeof fn === "function") {
                                            const _d = fn.call(res);

                                            if (search_setting.rendercustom) {
                                                $(search_setting.renderele).html(_d);

                                            } else {

                                                $('#' + __r_id).html(_d);
                                                $('#' + __r_id).data('index', 0);
                                            }
                                        }
                                        // (search_setting.renderfunc)(res.data);
                                    } else {
                                        $('#' + __r_id).html(res.data);
                                    }
                                })
                                .catch((err) => {

                                });
                        } else {
                            axios.get(search_setting.url + "?keyword=" + _keyword)
                                .then((res) => {
                                    if (search_setting.dorender) {
                                        (search_setting.renderfunc)(res);
                                    } else {
                                        $('#' + _r_id).html(res.data);
                                    }
                                })
                                .catch((err) => {

                                });
                        }
                    } else {
                        $('#' + _r_id).css('display', "none");

                    }
                });
                $(ele).focusin(function (e) { 
                    const _keyword = $(ele).val();
                    if(_keyword.length>2){
                        $(ele).showSearch();
                    }
                    
                });
                $(ele).focusout(function (e) { 
                    console.log(e,$(e.relatedTarget).hasClass('xxx_tp1'));
                    if(e.relatedTarget!=null  ){
                        
                        $(ele).closeSearch();
                    }
                });
                if(!search_setting.rendercustom){
                    $(ele).on('keydown', function(e) {
                        const _r_id = ele.dataset.searchid;
                        const table = $('#' + _r_id + '>table')[0];
                        let _index = $('#' + _r_id).data('index');
                        $('#' + _r_id+">table>tbody>tr").removeClass('active');
                        const _total = $('#' + _r_id+">table>tbody>tr").length;
                        let _ok=false;
                        e = e || window.event;

                        if (e.keyCode == '38') {
                            _index-=1;
                            if(_index<0){
                                _index=0;
                            }
                            _ok=true;
                        } else if (e.keyCode == '40') {
                            _index += 1;
                            console.log(_index);
                            if(_index>=_total){
                                _index=_total-1;
                            }
                            console.log(_index);
                            _ok=true;
                            
                        } else if (e.keyCode == '13') {
                            // left arrow
                            e.preventDefault();
                            console.log('enter pressed');
                            $('#' + _r_id + '>table>tbody>tr:eq(' + _index + ')')[0].click();
                        } else if (e.keyCode == '39') {
                            // right arrow
                        }
                        if(_ok){

                            console.log('#' + _r_id + '>table', table, _total);
    
                            $('#' + _r_id + '>table>tbody>tr:eq(' + _index + ')').addClass(
                            'active');
                            $('#' + _r_id + '>table>tbody>tr:eq(' + _index + ')')[0].scrollIntoView();
                            
                            $('#' + _r_id).data('index', _index);
                        }
                    });

                }

            });

        };

    }(jQuery));

    (function($) {
        $.fn.closeSearch = function() {
            console.log(this);
            return this.each(function(index, ele) {
                const _r_id = ele.dataset.searchid + '_overlay';
                $('#' + _r_id).css('display', "none");
            });

        };
    }(jQuery));

    (function($) {
        $.fn.clearSearch = function() {
            console.log(this);
            return this.each(function(index, ele) {
                const _r_id = ele.dataset.searchid + '_overlay';
                $('#' + _r_id).css('display', "none");
            });

        };
    }(jQuery));

    (function($) {
        $.fn.showSearch = function() {
            console.log(this);
            return this.each(function(index, ele) {
                const _r_id = ele.dataset.searchid + '_overlay';
                $('#' + _r_id).css('display', "block");
            });

        };
    }(jQuery));

    
    $(document).mouseup(function(e){
        var container = $(currentContainer);

        // If the target of the click isn't the search input
        if($(e.target).data('searchid')==undefined){

            // If the target of the click isn't the container
            if(!container.is(e.target) && container.has(e.target).length === 0){
                container.hide();
            }
        }
    });
    function get(ele) {
        const el = ele.getBoundingClientRect();
        const t = $(ele).offset().top - $(window).scrollTop();
        const h = window.innerHeight
        console.log(t, el, $(window).scrollTop(), $(ele).offset().top);
        $('#result').css('top', (t + el.height + 3).toString() + "px");
        $('#result').css('height', "300px");
        if ((h - t) < 300) {
            $('#result').css('height', (h - t).toString() + "px");
        }
        $('#result').css('left', el.left + "px");
        $('#result').css('width', el.width + "px");

    }
</script>
