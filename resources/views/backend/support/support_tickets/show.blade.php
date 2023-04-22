@extends('backend.layouts.app')

@section('content')
<style>
    .message-right {
        text-align: right;
        background-color: #f1faff;
        max-width: 400px;
        border-radius: 0.475rem;
        padding: 10px;
        margin-left: auto;
        font-size: 15px;
    }
    .message-left {
    text-align: left;
    background-color: #f8f5ff;
    max-width: 400px;
    border-radius: 0.475rem;
    padding: 10px;
    margin-right: auto;
    font-size: 15px;
}
</style>
<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="text-center text-md-left">
                <h5 class="mb-md-0 h5">{{ $ticket->subject }} #{{ $ticket->code }}</h5>
               <div class="mt-2">
                   <span> {{ $ticket->user->name }} </span>
                   <span class="ml-2"> {{ $ticket->created_at }} </span>
                   <span class="badge badge-inline badge-secondary ml-2 text-capitalize"> 
                       {{ translate($ticket->status) }} 
                   </span>
               </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pad-top">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="media">
                                    <a class="media-left" href="#">
                                        @if($ticket->user->avatar_original != null)
                                            <span class="avatar avatar-sm mr-3"><img src="{{ uploaded_asset($ticket->user->avatar_original) }}"></span>
                                        @else
                                            <span class="avatar avatar-sm mr-3"><img src="{{ static_asset('assets/img/avatar-place.png') }}"></span>
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <div class="">
                                            <span class="text-bold h6">{{ $ticket->user->name }}</span>
                                            <p class="text-muted text-sm fs-11">{{ $ticket->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-left">
                                    @php echo $ticket->details; @endphp
                                    <br>
                                    @foreach ((explode(",",$ticket->files)) as $key => $file)
                                        @php $file_detail = \App\Models\Upload::where('id', $file)->first(); @endphp
                                        @if($file_detail != null)
                                            <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                <i class="las la-download text-muted">{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</i>
                                            </a>
                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                    @foreach($ticket->replies as $ticketreply)
                        @if($ticketreply->user_id == Auth::user()->id)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right">
                                            <div class="media">
                                                <div class="media-body" style="text-align: end;">
                                                    <div class="">
                                                        <span class="text-bold h6">{{ $ticketreply->user->name }}</span>
                                                        <p class="text-muted text-sm fs-11">{{$ticketreply->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                <a class="media-left" href="#">
                                                    @if($ticketreply->user->avatar_original != null)
                                                        <span class="avatar avatar-sm ml-3"><img src="{{ uploaded_asset($ticketreply->user->avatar_original) }}"></span>
                                                    @else
                                                        <span class="avatar avatar-sm ml-3"><img src="{{ static_asset('assets/img/avatar-place.png') }}"></span>
                                                    @endif
                                                </a>
                                                
                                            </div>
                                            <div class="message-right">
                                                @php echo $ticketreply->details; @endphp
            
                                                <div class="mt-3">
                                                @foreach ((explode(",",$ticketreply->files)) as $key => $file)
                                                    @php $file_detail = \App\Models\Upload::where('id', $file)->first(); @endphp
                                                    @if($file_detail != null)
                                                        <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                            <i class="las la-paperclip mr-2">{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                                </div>
                                            </div>    
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @else  
                            <li class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="media">
                                            <a class="media-left" href="#">
                                                @if($ticketreply->user->avatar_original != null)
                                                    <span class="avatar avatar-sm mr-3"><img src="{{ uploaded_asset($ticketreply->user->avatar_original) }}"></span>
                                                @else
                                                    <span class="avatar avatar-sm mr-3"><img src="{{ static_asset('assets/img/avatar-place.png') }}"></span>
                                                @endif
                                            </a>
                                            <div class="media-body">
                                                <div class="">
                                                    <span class="text-bold h6">{{ $ticketreply->user->name }}</span>
                                                    <p class="text-muted text-sm fs-11">{{$ticketreply->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-left">
                                            @php echo $ticketreply->details; @endphp

                                            <div class="mt-3">
                                            @foreach ((explode(",",$ticketreply->files)) as $key => $file)
                                                @php $file_detail = \App\Models\Upload::where('id', $file)->first(); @endphp
                                                @if($file_detail != null)
                                                    <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                        <i class="las la-paperclip mr-2">{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</i>
                                                    </a>
                                                @endif
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <form action="{{ route('support_ticket.admin_store') }}" method="post" id="ticket-reply-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id" value="{{$ticket->id}}" required>
                <input type="hidden" name="status" value="{{ $ticket->status }}" required>
                <div class="form-group">
                    <textarea class="aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' name="reply" required></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="attachments" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-dark" onclick="submit_reply('pending')">
                        {{ translate('Submit as') }} 
                        <strong>
                            <span class="text-capitalize"> 
                                {{ translate($ticket->status) }}
                            </span>
                        </strong>
                    </button>
                    <button type="submit" class="btn btn-icon btn-sm btn-dark" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"><i class="las la-angle-down"></i></button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="submit_reply('open')">{{ translate('Submit as') }} <strong>{{ translate('Open') }}</strong></a>
                        <a class="dropdown-item" href="#" onclick="submit_reply('solved')">{{ translate('Submit as') }} <strong>{{ translate('Solved') }}</strong></a>
                      </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="aiz-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>{{ translate('Order Code') }}</th>
                        <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                        <th data-breakpoints="md">{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Amount') }}</th>
                        <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Option') }}</th>
                    </tr>
                </thead>
                <tbody>
                        {{-- @foreach ($tickets as $key => $ticket) --}}
                        @php $order_detail = \App\Models\Order::where('id', $ticket->product_id)->first(); @endphp
                        @if ($order_detail != null)
                            <tr>
                                <td>#{{ $order_detail->code }}</td>
                                <td>{{ count($order_detail->orderDetails) }}</td>
                                <td>{{ $order_detail->user->name }}</td>
                                <td>{{ single_price($order_detail->grand_total) }}</td>
                                <td>@php
                                    $status = $order_detail->delivery_status;
                                    if($order_detail->delivery_status == 'cancelled') {
                                        $status = '<span class="badge badge-inline badge-danger">'.translate('Cancel').'</span>';
                                    }
    
                                    @endphp
                                    {!! $status !!}
                                </td>
                                <td> @if ($order_detail->payment_status == 'paid')
                                    <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                    @else
                                    <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                <td><a class="btn btn-soft-primary btn-sm" href="{{route('cancel_order', encrypt($order_detail->id))}}" title="{{ translate('Order Cancel') }}">
                                        Order Cancel
                                    </a>
                                </td>
                            </tr>
                        @endif
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function submit_reply(status){
            $('input[name=status]').val(status);
            if($('textarea[name=reply]').val().length > 0){
                $('#ticket-reply-form').submit();
            }
        }
    </script>
@endsection
