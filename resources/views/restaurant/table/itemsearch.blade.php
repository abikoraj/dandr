<script>
    function selItem(id) {
        from = 2;
        item = items.find(o => o.id == id);
        if (item != undefined) {
            $('#qty').focus();
            $('#item_id').val(item.title);
        } else {
            $('#item_id').focus();
        }
    }

    function listItems(keyword) {
        let html = '';
        item=null;
        console.log(keyword, searchable);
        if (searchable) {
            $('#item-search').html(html);
            $('#item-search').css('display', 'block');
            const _items = items.filter(o => o.title.toLowerCase().startsWith(keyword.toLowerCase())).splice(0, 10);
            if(_items.length==1){
                if(_items[0].title.toLowerCase()==keyword.toLowerCase()){
                    item=_items[0];
                }
            }
            console.log(_items);
            _items.forEach(_item => {
                html += "<div onclick='selItem(" + _item.id + ")' data-id='" + _item.id + "'>" + _item.title +
                    "</div>"
            });
        }else{
            $('#item-search').css('display', 'none');

        }
        $('#item-search').html(html);
        selectedIndex = 0;
        if (html.length > 0) {
            changeSelection();
        }
        console.log(item,"item");

    }


    function searchItem(e, ele) {
        if (e.which == 13) {
            from = 1;
            item = items.find(o => o.number == ele.value);
            if (item != undefined) {
                $('#item').val(item.id);
                $('#qty').focus();
            } else {
                showNotification('bg-danger', 'Item Not found');
            }
        }
    }

    function searchItemName(e, ele) {
        if (e.which == 13) {
            e.preventDefault();
            $($('#item-search>div')[selectedIndex])[0].click();
        } else if (e.which == 38) {
            e.preventDefault();
            selectedIndex -= 1;
            if (selectedIndex < 0) {
                selectedIndex = $('#item-search>div').length - 1;
            }
            changeSelection();
            console.log(selectedIndex);
        } else if (e.which == 40) {
            e.preventDefault();
            selectedIndex += 1;
            if (selectedIndex >= $('#item-search>div').length) {
                selectedIndex = 0;
            }
            changeSelection();

        }
    }

    function changeSelection() {
        try {
            $('#item-search>div').removeClass('btn-primary');
            $($('#item-search>div')[selectedIndex]).addClass('btn-primary');
            $($('#item-search>div')[selectedIndex])[0].scrollIntoView();

        } catch (error) {

        }

    }

    function changeFocus(sel){
        console.log('focus',sel);
        searchable=sel;
        $('#item-search').css('display', searchable?'block':'none');

    }
</script>
