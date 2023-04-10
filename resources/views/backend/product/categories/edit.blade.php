@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Category Information')}}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (\App\Models\Language::all() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('categories.edit', ['id'=>$category->id, 'lang'=> $language->code] ) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
    	            <input type="hidden" name="lang" value="{{ $lang }}">
                	@csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="name" value="{{ $category->getTranslation('name', $lang) }}" class="form-control" id="name" placeholder="{{translate('Name')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Parent Category')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" onchange="checkCat(this.value);" name="parent_id" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" data-selected="{{ $category->parent_id }}">
                                <option value="0">{{ translate('No Parent') }}</option>
                                @foreach ($categories as $acategory)
                                    <option value="{{ $acategory->id }}">{{ $acategory->getTranslation('name') }}</option>
                                    @foreach ($acategory->childrenCategories as $childCategory)
                                        @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
                        $check = \App\Models\Category::where('level', '0')->where('id', $category->parent_id)->first();
                    @endphp
                    @if($check)
                        <div class="form-group row" id="orderBy">
                    @else
                        <div class="form-group row" id="orderBy" style="display: none;">
                    @endif
                        <label class="col-md-3 col-form-label">{{translate('Order By')}}</label>
                        <div class="col-md-9">
                            {{-- Code cange by Brijesh on 28-April-22 CR#2 - start --}}
                            <select class="select2 form-control aiz-selectpicker" name="order_by" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">{{ translate('No Order By') }}</option>
                                <option value="By Price" <?php if ($category->order_by == 'By Price') echo "selected"; ?>>By Price</option>
                                <option value="By Occasion" <?php if ($category->order_by == 'By Occasion') echo "selected"; ?>>By Occasion</option>
                                <option value="By Type" <?php if ($category->order_by == 'By Type') echo "selected"; ?>>By Type</option>
                                <option value="By Colour" <?php if ($category->order_by == 'By Colour') echo "selected"; ?>>By Colour</option>
                                <option value="Special Flowers" <?php if ($category->order_by == 'Special Flowers') echo "selected"; ?>>Special Flowers</option>
                                <option value="Festive Special" <?php if ($category->order_by == 'Festive Special') echo "selected"; ?>>Festive Special</option>
                                <option value="Flower Cities" <?php if ($category->order_by == 'Flower Cities') echo "selected"; ?>>Flower Cities</option>
                                <option value="By Recipient" <?php if ($category->order_by == 'By Recipient') echo "selected"; ?>>By Recipient</option>
                                <option value="By Flovour" <?php if ($category->order_by == 'By Flovour') echo "selected"; ?>>By Flovour</option>
                                <option value="By Cities" <?php if ($category->order_by == 'By Cities') echo "selected"; ?>>By Cities</option>
                                <option value="By Theme" <?php if ($category->order_by == 'By Theme') echo "selected"; ?>>By Theme</option>
                                <option value="Cakes Cities" <?php if ($category->order_by == 'Cakes Cities') echo "selected"; ?>>Cakes Cities</option>
                                <option value="Plants By Name" <?php if ($category->order_by == 'Plants By Name') echo "selected"; ?>>Plants By Name</option>
                                <option value="Others" <?php if ($category->order_by == 'Others') echo "selected"; ?>>Others</option>
                                <option value="Plants Cities" <?php if ($category->order_by == 'Plants Cities') echo "selected"; ?>>Plants Cities</option>
                                <option value="More Gifts" <?php if ($category->order_by == 'More Gifts') echo "selected"; ?>>More Gifts</option>
                                <option value="For Him" <?php if ($category->order_by == 'For Him') echo "selected"; ?>>For Him</option>
                                <option value="For Her" <?php if ($category->order_by == 'For Her') echo "selected"; ?>>For Her</option>
                                <option value="Gift Cities" <?php if ($category->order_by == 'Gift Cities') echo "selected"; ?>>Gift Cities</option>
                                <option value="Flower Combos" <?php if ($category->order_by == 'Flower Combos') echo "selected"; ?>>Flower Combos</option>
                                <option value="Cake Combos" <?php if ($category->order_by == 'Cake Combos') echo "selected"; ?>>Cake Combos</option>
                                <option value="Exclusive Combos" <?php if ($category->order_by == 'Exclusive Combos') echo "selected"; ?>>Exclusive Combos</option>
                                <option value="Plant Combos" <?php if ($category->order_by == 'Plant Combos') echo "selected"; ?>>Plant Combos</option>
                                <option value="Chocolate Combos" <?php if ($category->order_by == 'Chocolate Combos') echo "selected"; ?>>Chocolate Combos</option>
                                <option value="By Relation" <?php if ($category->order_by == 'By Relation') echo "selected"; ?>>By Relation</option>
                                <option value="Same Day Delivery" <?php if ($category->order_by == 'Same Day Delivery') echo "selected"; ?>>Same Day Delivery</option>
                                <option value="Gift Types" <?php if ($category->order_by == 'Gift Types') echo "selected"; ?>>Gift Types</option>
                                <option value="Digital" <?php if ($category->order_by == 'Digital') echo "selected"; ?>>Digital</option>
                                <option value="Festivals" <?php if ($category->order_by == 'Festivals') echo "selected"; ?>>Festivals</option>
                                <option value="Special Occasions" <?php if ($category->order_by == 'Special Occasions') echo "selected"; ?>>Special Occasions</option>
                                <option value="Overseas Gifts" <?php if ($category->order_by == 'Overseas Gifts') echo "selected"; ?>>Overseas Gifts</option>
                            </select>
                            {{-- Code cange by Brijesh on 28-April-22 CR#2 - end --}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Ordering Number')}}
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order_level" value="{{ $category->order_level }}" class="form-control" id="order_level" placeholder="{{translate('Order Level')}}">
                            <small>{{translate('Higher number has high priority')}}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                        <div class="col-md-9">
                            <select name="digital" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                <option value="0" @if ($category->digital == '0') selected @endif>{{translate('Physical')}}</option>
                                <option value="1" @if ($category->digital == '1') selected @endif>{{translate('Digital')}}</option>
                            </select>
                        </div>
                    </div>
    	            <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Banner')}} <small>({{ translate('200x200') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="banner" class="selected-files" value="{{ $category->banner }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Icon')}} <small>({{ translate('32x32') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="icon" class="selected-files" value="{{ $category->icon }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Meta Title')}}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title" value="{{ $category->meta_title }}" placeholder="{{translate('Meta Title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Meta Description')}}</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ $category->meta_description }}</textarea>
                        </div>
                    </div>
                    {{-- Code cange by Brijesh on 05-April-22 CR#2 - start --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('SEO Description')}}</label>
                        <div class="col-md-9">
                            <textarea name="seo_description" rows="5" class="aiz-text-editor">{{ $category->seo_description }}</textarea>
                        </div>
                    </div>
                    {{-- Code cange by Brijesh on 05-April-22 CR#2 - end --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                        </div>
                    </div>
                    @if (get_setting('category_wise_commission') == 1)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Commission Rate')}}</label>
                            <div class="col-md-9 input-group">
                                <input type="number" lang="en" min="0" step="0.01" id="commision_rate" name="commision_rate" value="{{ $category->commision_rate }}" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Filtering Attributes')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" data-selected="{{ $category->attributes->pluck('id') }}" multiple>
                                @foreach (\App\Models\Attribute::all() as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
<script>
    function checkCat(val){
        $.ajax({
            type:"POST",
            url: '{{ route('categories.checkcat') }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "val": val
            },
            success: function(data){
                if(data.status == true){
                    $('#orderBy').show();
                } else {
                    $('#orderBy').hide();
                }
            }
        });
    }
</script>
