@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '--';
    }
@endphp
<!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
@if(isset($additional))
    <option value="{{ $child_category->id }}" {{ (in_array($child_category->id, $additional)) ? 'selected' : ''}}>{{ $value." ".$child_category->getTranslation('name') }}</option>
@else 
    <option value="{{ $child_category->id }}">{{ $value." ".$child_category->getTranslation('name') }}</option>
@endif
<!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('categories.child_category', ['child_category' => $childCategory])
    @endforeach
@endif
