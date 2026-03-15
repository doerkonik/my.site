@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="pt-60 pb-60 bg--light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 text-center" style="font-weight:800; color:#0f2421;">{{ __($pageTitle) }}</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('First Name') <span class="text--danger">*</span></label>
                                        <input type="text" class="form-control form--control h-45" name="firstname" value="{{ $user->firstname }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Last Name') <span class="text--danger">*</span></label>
                                        <input type="text" class="form-control form--control h-45" name="lastname" value="{{ $user->lastname }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('E-mail Address')</label>
                                        <input class="form-control form--control h-45" value="{{ $user->email }}" readonly style="opacity:0.7;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Mobile Number')</label>
                                        <input class="form-control form--control h-45" value="{{ $user->mobile }}" readonly style="opacity:0.7;">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('Address')</label>
                                        <input type="text" class="form-control form--control h-45" name="address" value="{{ @$user->address }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('State')</label>
                                        <input type="text" class="form-control form--control h-45" name="state" value="{{ @$user->state }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Zip Code')</label>
                                        <input type="text" class="form-control form--control h-45" name="zip" value="{{ @$user->zip }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('City')</label>
                                        <input type="text" class="form-control form--control h-45" name="city" value="{{ @$user->city }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Country')</label>
                                        <input class="form-control form--control h-45" value="{{ @$user->country_name }}" disabled style="opacity:0.7;">
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn--base w-100" style="padding:0.75rem; font-weight:800;">@lang('Save Changes')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection