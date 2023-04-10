@extends('backend.layouts.app')

@section('content')

<h4 class="text-center text-muted">{{translate('System')}}</h4>
<div class="row">
    @foreach($BusinessSettings as $business_settings)
    {{-- Code cange by Brijesh on 14-march-22 CR#2 - start --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-center">{{translate($business_settings['label'])}}</h5>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, '{{$business_settings['type']}}')" <?php if(get_setting($business_settings['value'] == 1)) echo "checked";?>>
                    
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        
    {{-- Code cange by Brijesh on 14-march-22 CR#2 - end --}}
    @endforeach
 
@endsection
@section('script')
    <script type="text/javascript">
        function updateSettings(el, type){
            if($(el).is(':checked')){
                var value = 1;
            }
            else{
                var value = 0;
            }
            
            $.post('{{ route('business_settings.edit') }}', {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
                if(data == '1'){
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection 