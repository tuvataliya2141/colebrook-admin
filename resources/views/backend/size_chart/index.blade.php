@extends('backend.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
<style>
	.accordion-list .row {
		align-items: center;
	}
	.accordion-list li h3 {
		margin-bottom: 0;
	}
	.accordion-list li .btn-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 5px;
	}
	ul.accordion-list {
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		padding: 20px;
		margin: 0;
		list-style: none;
		background-color: #f9f9fA;
	}
	ul.accordion-list li {
		position: relative !important;
		display: block;
		width: 100%;
		height: auto;
		background-color: #FFF;
		padding: 20px;
		margin: 0 auto 15px auto;
		border: 1px solid #eee;
		border-radius: 5px;
		cursor: pointer;
	}		
	li.active .btn-soft-success{
		transform: rotate(45deg);
	}
	h3 {
		font-weight: 700;
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		padding: 0 0 0 0;
		margin: 0;
		font-size: 15px;
		letter-spacing: 0.01em;
		cursor: pointer;
	}
	div.answer {
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		margin: 0;
		padding: 0;
		cursor: pointer;
	}
  	

</style>

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Size Chart') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('Size Chart') }}</h6>
		<a href="{{ route('size-chart.add') }}" class="btn btn-primary">{{ translate('Add New Size') }}</a>
	</div>
	<div class="card-body">
		<ul class="accordion-list">
			@foreach ($list as $key => $val)
				<li>
					<div class="row">
						<div class="col-md-6">
							<h3 style="font-size: 15px;">{{ $val->name }}</h3>
						</div>
						<div class="col-md-6 text-right d-flex justify-content-end">
							<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('size-chart.edit', $val->id)}}" title="{{ translate('Edit') }}">
								<i class="las la-edit"></i>
							</a>
							<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('size-chart.destroy', $val->id)}}" title="{{ translate('Delete') }}">
								<i class="las la-trash"></i>
							</a>
							<a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm">
								<i class="las la-plus"></i>
							</a>
						</div>
					</div>
					<div class="answer">
						<table class="table aiz-table mb-0">
							<thead>
								<tr>
									<th>{{translate('Size')}}</th>
									<th>{{translate('Title')}}</th>
									<th>{{translate('Value in inches')}}</th>
									<th>{{translate('Value in CM')}}</th>
								</tr>
							</thead>
							<tbody>
								@php $size = json_decode($val->size_values) @endphp
								@foreach ($size as $key => $val)
								<tr>
									<td>{{ $val->size }}</td>
									<td>{{ $val->title }}</td>
									<td>{{ $val->inches_value }}</td>
									<td>{{ $val->cm_value }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</li>
			@endforeach
		</ul>
		
    </table>
	</div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')

<script type="text/javascript">
	$(document).ready(function(){
		$('.accordion-list > li > .answer').hide();
		$('.accordion-list > li .btn-soft-success').click(function() {
			if ($(this).parent().parent().parent().hasClass("active")) {
				$(this).parent().parent().parent().removeClass("active").find(".answer").slideUp();
			} else {
				$(".accordion-list > li.active .answer").slideUp();
				$(".accordion-list > li.active").removeClass("active");
				$(this).parent().parent().parent().addClass("active").find(".answer").slideDown();
			}
			return false;
		});
		
	});
</script>

@endsection
