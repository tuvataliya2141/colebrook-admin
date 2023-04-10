@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Edit Page Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<form class="p-4" action="{{ route('home-banner.update') }}" method="POST" enctype="multipart/form-data">
		@csrf
		{{-- <input type="hidden" name="_method" value="PATCH"> --}}
		<input type="hidden" name="banner_id" value="{{ $banner->id }}">
		
		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Home Banner') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Banner Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $banner->title }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Banner Sub Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Sub Title')}}" name="sub_title" value="{{ $banner->sub_title }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Banner URL')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('URL')}}" name="url" value="{{ $banner->url }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Banner Image')}}</label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
						<div class="input-group-prepend">
							<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
						</div>
						<div class="form-control file-amount">{{ translate('Choose File') }}</div>
						<input type="hidden" name="photo" class="selected-files" value="{{ $banner->photo }}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update Banner') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
