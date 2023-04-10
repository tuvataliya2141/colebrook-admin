@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('User List')}}</h1>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('User List') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Phone')}}</th>
                    <th>{{translate('Total visit')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $key => $user)
                <tr>
                    <td>
                        <span class="text-muted text-truncate-2">{{ $user->name }}</span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2">{{ $user->email }}</span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2">{{ $user->phone }}</span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2">{{ $user->total_visit }}</span>    
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection






