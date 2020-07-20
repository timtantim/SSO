@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(Auth::user()->sso_manager=='1')
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <center><a href="{{ route('admin.verify.index') }}"><h4>驗證帳號</h4></a></center>
                 </div>
            </div>
        </div>
        @endif
        {{-- <div class="col-md-6  px-md-5 mb-3">
            <div class="card">
                <div class="card-header"></div>
                    <div class="card-body">
                        <center><a href="{{ route('admin.mealcategory.index') }}"><h4>驗證帳號</h4></a></center>
                     </div>
            </div>
        </div>
        <div class="col-md-6  px-md-5 mb-3">
            <div class="card">
                <div class="card-body">
                    <center><a href="{{ route('admin.mealcategory.index') }}"><h4>權限開通通知</h4></a></center>
                 </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection
