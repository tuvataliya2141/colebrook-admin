@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All products')}}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Product')}}</span>
            </a>
        </div>
    </div>
</div>
<br>
<div class="card">
    <!-- Code cange by Tarun on 04-fab-22 CR#2 - start -->
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>
            </div>
            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                </div>
            </div>
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type" onchange="sort_products()">
                    <option value="">{{ translate('Sort By') }}</option>
                    <option value="sorting_order,desc" @isset($col_name , $query) @if($col_name=='sorting_order' && $query=='desc' ) selected @endif @endisset>{{translate('Display Priority')}}</option>
                    <option value="rating,desc" @isset($col_name , $query) @if($col_name=='rating' && $query=='desc' ) selected @endif @endisset>{{translate('Rating (High > Low)')}}</option>
                    <option value="rating,asc" @isset($col_name , $query) @if($col_name=='rating' && $query=='asc' ) selected @endif @endisset>{{translate('Rating (Low > High)')}}</option>
                    <option value="num_of_sale,desc" @isset($col_name , $query) @if($col_name=='num_of_sale' && $query=='desc' ) selected @endif @endisset>{{translate('Num of Sale (High > Low)')}}</option>
                    <option value="num_of_sale,asc" @isset($col_name , $query) @if($col_name=='num_of_sale' && $query=='asc' ) selected @endif @endisset>{{translate('Num of Sale (Low > High)')}}</option>
                    <option value="unit_price,desc" @isset($col_name , $query) @if($col_name=='unit_price' && $query=='desc' ) selected @endif @endisset>{{translate('Base Price (High > Low)')}}</option>
                    <option value="unit_price,asc" @isset($col_name , $query) @if($col_name=='unit_price' && $query=='asc' ) selected @endif @endisset>{{translate('Base Price (Low > High)')}}</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
    </form>
    <!-- Code cange by Tarun on 04-fab-22 CR#2 - end -->
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </div>
                    </th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="sm">{{translate('Info')}}</th>
                    <th data-breakpoints="md">{{translate('Total Stock')}}</th>
                    <!-- <th data-breakpoints="lg">{{translate('Todays Deal')}}</th>
                        <th data-breakpoints="sm">{{translate('Published')}}</th>
                        <th data-breakpoints="sm">{{translate('Featured')}}</th> -->
                    {{-- @if(get_setting('Product_sorting_order') == 1)
                        <th data-breakpoints="sm">{{translate('Display Sorting Order')}}</th>
                    @endif --}}
                    <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @if(count($products) > 0)
                    @foreach($products as $key => $product)
                        <tr>
                            <!--<td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>-->
                            <td>
                                <div class="form-group d-inline-block">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" name="id[]" value="{{$product->id}}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </td>
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
                            <td>
                                <strong>{{translate('Num of Sale')}}:</strong> {{ $product->num_of_sale }} {{translate('times')}} </br>
                                <strong>{{translate('Base Price')}}:</strong> {{ single_price($product->unit_price) }} </br>
                                <strong>{{translate('Rating')}}:</strong> {{ $product->rating }} </br>
                            </td>
                            <td>
                                @php
                                $qty = 0;
                                if($product->variant_product) {
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                        echo $stock->variant.' - '.$stock->qty.'<br>';
                                    }
                                }
                                else {
                                    //$qty = $product->current_stock;
                                    $qty = optional($product->stocks->first())->qty;
                                    echo $qty;
                                }
                                @endphp
                                @if($qty <= $product->low_stock_quantity)
                                    <span class="badge badge-inline badge-danger">Low</span>
                                    @endif
                            </td>
                            <!-- <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_todays_deal(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->todays_deal == 1) echo "checked"; ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->published == 1) echo "checked"; ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->featured == 1) echo "checked"; ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td> -->
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )}}" title="{{ translate('Duplicate') }}">
                                    <i class="las la-copy"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('products.destroy', $product->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')

@include('modals.delete_modal')

@endsection

@section('script')

<script type="text/javascript">
    var ids = [];
    $(document).on("change", ".check-all", function() {
        if (this.checked) {
            // Iterate each checkbox
            $('.check-one:checkbox').each(function() {
                this.checked = true;
                ids.push($(this).val());
            });
        } else {
            $('.check-one:checkbox').each(function() {
                this.checked = false;
                ids.pop($(this).val());
            });
        }
    });

    $(document).on("change", ".check-one", function() {
        if (this.checked) {
                this.checked = true;
                ids.push($(this).val());
            } else {
                this.checked = false;
                ids.pop($(this).val());
        }
    });

    $(document).ready(function() {
        //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
    });

    function update_todays_deal(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('products.todays_deal') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        },
        function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully ') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong ') }}');
            }
        });
    }

    function update_published(el) {

        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('products.published') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        },
        function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Published products updated successfully ') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong ') }}');
            }
        });
    }

    function update_approved(el) {

        if (el.checked) {
            var approved = 1;
        } else {
            var approved = 0;
        }
        $.post('{{ route('products.approved') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            approved: approved
        },
        function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Product approval update successfully ') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong ') }}');
            }
        });
    }

    function update_featured(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('products.featured') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        },
        function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate(' Featured products updated successfully ') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate(' Something went wrong ') }}');
            }
        });
    }

    function sort_products(el) {
        $('#sort_products').submit();
    }

    function bulk_delete() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('bulk-product-delete')}}",
            type: 'POST',
            data: {id: ids},
            cache: false,
            success: function(response) {
                if (response == 1) {
                    location.reload();
                }
            }
        });
    }

    function update_sortingorder(el, productid) {
        var sortvalue = el.value;
        $.post('{{ route('products.updtsortorder') }}', {
            _token: '{{ csrf_token() }}',
            id: productid,
            sortvalue: sortvalue
        },
        function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Sorting Order update successfully ') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong ') }}');
            }
        });
    }
</script>
@endsection