@php
    use Illuminate\Support\Facades\Session;
@endphp

<!--Alert Section Start Here-->
<div class="row">
    <div class="col-12">
        @if($errors->first())
            <div class="alert alert-danger alert-dismissible">
                {{$errors->first()}}
            </div>
        @endif
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible">
                {{ Session::get('success') }}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible">
                {{ Session::get('error') }}
            </div>
        @endif
        @if(Session::has('delete'))
            <div class="alert alert-danger alert-dismissible">
                {{ Session::get('delete') }}
            </div>
        @endif
        @if(Session::has('warning'))
            <div class="alert alert-warning">
                {{ Session::get('warning') }}
            </div>
        @endif
    </div>
</div>
<!--Alert Section End Here-->