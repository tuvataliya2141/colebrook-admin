@php
    $value = null;
    for ($i=0; $i < $child->menu_level; $i++){
        $value .= '--';
    }
@endphp
<option value="{{ $child->id }}">{{ $value." ".$child->menu_name }}</option>
@if ($child->customMenu)
    @foreach ($child->customMenu as $menu)
        @include('costom_menu.child_menu', ['child' => $menu])
    @endforeach
@endif
