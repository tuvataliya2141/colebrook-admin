@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Product view by total user')}}</h1>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Product view by total user') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $key => $product)
                <tr>
                    <td>
                        <div class="row gutters-5 w-200px w-md-300px mw-100">
                            <div class="col-auto">
                                <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image" class="size-50px img-fit">
                            </div>
                            <div class="col">
                                <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-right">
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.products_view_by_user', ['id'=>$product->id] )}}" title="{{ translate('View') }}">
                            <i class="las la-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection






