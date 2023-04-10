@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Reviews Information')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('reviews.adminStore') }}" method="POST">
                    @csrf
                    <div class="form-group row" id="customer">
                        <label class="col-md-3 col-from-label">
                            {{translate('Customer')}} 
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="user_id" id="user_id" data-live-search="true" required>
                                <option value="">--</option>
                                @foreach ($customer as $val)
                                <option value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="product">
                        <label class="col-md-3 col-from-label">
                            {{translate('Product')}} 
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="product_id" id="product_id" data-live-search="true" required>
                                <option value="">--</option>
                                @foreach ($product as $val)
                                <option value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Rating')}}
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" maxlength="1" onkeypress="return isNumber(event)" placeholder="{{translate('Rating')}}" name="rating" id="rating" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Comment')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <textarea name="comment" rows="5" class="form-control" placeholder="{{translate('Comment')}}" required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            {{translate('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function isNumber(evt){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode < 48 || charCode > 53) {
            return false;
        }
        return true; 
    }
</script>
@endsection
