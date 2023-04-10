@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('Product Reviews')}}</h1>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row flex-grow-1">
            <div class="col-auto">
                <h1 class="h3">{{translate('Product Reviews')}}</h1>
            </div>
            
            
            
            <div class="col-md-6 col-xl-4 ml-auto mr-0">
                <form class="" id="sort_by_rating" action="{{ route('reviews.index') }}" method="GET">
                    <div class="" style="min-width: 200px;">
                        <select class="form-control aiz-selectpicker" name="rating" id="rating" onchange="filter_by_rating()">
                            <option value="">{{translate('Filter by Rating')}}</option>
                            <option value="rating,desc">{{translate('Rating (High > Low)')}}</option>
                            <option value="rating,asc">{{translate('Rating (Low > High)')}}</option>
                        </select>
                    </div>
                </form>
            </div>
            <!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
            <div class="col text-right">
                <a href="{{ route('reviews.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Reviews')}}</span>
                </a>
            </div>
            <!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Product Owner')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th>{{translate('Rating')}}</th>
                    <th data-breakpoints="lg">{{translate('Comment')}}</th>
                    <th data-breakpoints="lg">{{translate('Featured')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $key => $review)
                    @if ($review->product != null && $review->user != null)
                        <tr>
                            <td>{{ ($key+1) + ($reviews->currentPage() - 1)*$reviews->perPage() }}</td>
                            <td>
                                {{ $review->product->getTranslation('name') }}
                            </td>
                            <td>{{ $review->product->added_by }}</td>
                            <td>{{ $review->user->name }} ({{ $review->user->email }})</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td><label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_published(this)" value="{{ $review->id }}" type="checkbox" <?php if($review->status == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            </td>
                            <!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('reviews.edit',$review->id)}}" title="{{ translate('Edit') }}">
                                    <i class="las la-pen"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('reviews.delete', $review->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                            <!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $reviews->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection
<!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
@section('modal')
    @include('modals.delete_modal')
@endsection
<!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
@section('script')
    <script type="text/javascript">
        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('reviews.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published reviews updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function filter_by_rating(el){
            var rating = $('#rating').val();
            if (rating != '') {
                $('#sort_by_rating').submit();
            }
        }
    </script>
@endsection
