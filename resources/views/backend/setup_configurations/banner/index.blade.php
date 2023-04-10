@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Website Pages') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('All Pages') }}</h6>
		<a href="{{ route('home-banner.add') }}" class="btn btn-primary">{{ translate('Add New Page') }}</a>
	</div>
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Title')}}</th>
                <th>{{translate('Sub Title')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($list as $key => $val)
        	<tr>
        		<td>{{ $val->id }}</td>
        		<td>{{ $val->title }}</td>
        		<td>{{ $val->sub_title }}</td>
        		<td>{{ $val->url }}</td>
        		<td class="text-right">
					<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('home-banner.edit', ['id'=>$val->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
						<i class="las la-edit"></i>
					</a>
					<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('home-banner.homeBannerDelete', $val->id)}}" title="{{ translate('Delete') }}">
						<i class="las la-trash"></i>
					</a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
