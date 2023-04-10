@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Home Card') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Title')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($list as $key => $val)
        	<tr>
        		<td>{{ $val->id }}</td>
        		<td>{{ $val->title }}</td>
        		<td>{{ $val->url }}</td>
        		<td class="text-right">
					<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('website.home_card_edit', ['id'=>$val->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
						<i class="las la-edit"></i>
					</a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
@endsection

