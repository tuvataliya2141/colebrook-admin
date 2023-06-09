@extends('backend.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css">
    <style>
        .label-info {
            background-color: #5bc0de;
            display: inline;
            padding: 0.2em 0.6em 0.3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25em;
        }
        .bootstrap-tagsinput {
            width: 100%;
            padding: 9px 6px;
        }
    </style>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{translate('PIN Code wise Delivery')}}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.locationAdd') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header" style="justify-content: center;">
                                        <h5 class="mb-0 h6 text-center">{{translate('Inhouse')}}</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-from-label">{{translate('Country Level')}}</label>
                                    <div class="col-sm-12">
                                        <select name="country_level" id="country_level" class="form-control aiz-selectpicker"  data-live-search="true">
                                            <option value="">Select Country..</option>
                                            @foreach ($country as $key => $con)
                                                <option value="{{ $con->id }}">{{ $con->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-from-label">{{translate('State Level')}}</label>
                                    <div class="col-sm-12">
                                        <select name="state_level" id="state_level" class="form-control aiz-selectpicker"  data-live-search="true">
                                            <option value="">Select State..</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-from-label">{{translate('City Level')}}</label>
                                    <div class="col-sm-12">
                                        <select name="city_level" id="city_level" class="form-control aiz-selectpicker"  data-live-search="true">
                                            <option value="">Select City..</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-from-label">{{translate('PINCode')}}</label>
                                    <div class="col-sm-12">
                                        <input type="text" id="#inputTag" name="pin_code" data-role="tagsinput" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-from-label">{{translate('Product')}}</label>
                                    <div class="col-sm-12">
                                        <select class="form-control aiz-selectpicker" multiple data-live-search="true" name="product[]" id="product" data-live-search="true">
                                            @foreach ($product as $pro)
                                                <option value="{{ $pro->id }}">{{ $pro->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-from-label">{{translate('Additional Shippingcost')}}</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="additional_shippingcost" class="form-control" placeholder="Additional Shippingcost">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Add') }}</button>
    					</div>
                    </form>
                </div>
                <hr>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th data-breakpoints="sm">{{translate('PINCode')}}</th>
                                <th data-breakpoints="sm">{{translate('Countries Name')}}</th>
                                <th data-breakpoints="md">{{translate('State Name')}}</th>
                                <th data-breakpoints="lg">{{translate('City Name')}}</th>
                                <th data-breakpoints="sm">{{translate('Product Name')}}</th>
                                <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($pinData as $key => $pin)
                            <tr>
                                <td>{{ $i }}</td>
                                @if($pin->pin_code)
                                    <td>{{ $pin->pin_code }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                @if($pin->co_name)
                                    <td>{{ $pin->co_name }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                @if($pin->state_name)
                                    <td>{{ $pin->state_name }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                @if($pin->city_name)
                                    <td>{{ $pin->city_name }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                @if($pin->pro_name)
                                    <td>{{ $pin->pro_name }}</td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('business_settings.locationEdit', $pin->id )}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('business_settings.locationDestroy', $pin->id)}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @php $i ++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="aiz-pagination">
                        {{ $pinData->appends(request()->input())->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js" ></script>
    <script type="text/javascript">
        $("#inputTag").tagsinput('items');
        $(document).ready(function() {
            $('#country_level').on('change', function() {
                var country_id = this.value;
                $("#state_level").html('');
                $.ajax({
                    url:"{{route('get_states.index')}}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#state_level').html('<option value="">Select State</option>'); 
                        $.each(result.states,function(key,value){
                            $('#state_level').selectpicker("refresh");
                            $("#state_level").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        $('#city_level').html('<option value="">Select State First</option>'); 
                    }
                });
            });    
            $('#state_level').on('change', function() {
                var state_id = this.value;
                $("#city_level").html('');
                $.ajax({
                    url:"{{route('get_cities.index')}}",
                    type: "POST",
                    data: {
                    state_id: state_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#city_level').html('<option value="">Select City</option>'); 
                        $.each(result.cities,function(key,value){
                            $('#city_level').selectpicker("refresh");
                            $("#city_level").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            });
        });
    </script>
@endsection
