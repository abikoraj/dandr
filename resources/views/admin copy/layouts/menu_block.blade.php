
@if ($block['link']!=null)
@else
@php
    $hasall=false;
    foreach ($block['children'] as $menu_item_check) {
        if (auth_has_per($menu_item_check[1])){
            $hasall=true;
            break;
        }
    }
@endphp
@if ($hasall)
    <li>
        <a href="javascript:void(0);" class="waves-effect waves-block menu-toggle">
            <i class="zmdi zmdi-{{$block['icon']}}"></i><span>{{$block['text']}}</span></a>
        <ul class="ml-menu">
            @foreach ($block['children'] as $menu_item)
                @if (auth_has_per($menu_item[1]))
                    <li><a href="{{ $menu_item[2] }}" class="waves-effect waves-block">{{$menu_item[0]}}</a></li>
                @endif
            @endforeach
        </ul>
    </li>
@endif
@endif
