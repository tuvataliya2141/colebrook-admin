@php
    $value = null; 
@endphp

<ul>
    <li data-jstree='{ "opened" : @php echo $sdJstree; @endphp }'><span onclick="testfunction({{ $child['id'] }})">{{translate($child['menu_name'])}} </span>            
        @if ($child->customMenu)
            @foreach ($child->customMenu as $key => $menu)
            @if ($key == 0)
                @php $sdJstree = 'true'; @endphp
            @else 
                @php $sdJstree = 'false'; @endphp
            @endif
                @include('costom_menu.custom_child_menu', ['child' => $menu, 'sdJstree' => $sdJstree])
            @endforeach
        @endif
    </li>
</ul>
