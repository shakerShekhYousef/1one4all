@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center"
        style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
        <!-- Mask -->
        <span class="mask bg-gradient-default opacity-8"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="display-2 text-white">{{ $trainer->name }} profile.</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <input id="trainerid" value="{{ $trainer->id }}" hidden>
    </div>
    <div class="row">
        <div class="col-xl-7 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-end">
                        <img src="{{ asset($trainer->profile_pic) }}" alt="" style="width: 20%; height: 20%;">
                    </div>
                </div>
                <div id="alertdata" role="alert" hidden></div>
                <div class="card-body">
                    <form id="maindata">
                        @csrf

                        <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="pl-lg-4">
                            <!--Name-->
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="input-name"
                                    class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Name') }}" value="{{ $trainer->name }}" readonly autofocus>
                            </div>
                            <!--Email-->
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                <input type="email" name="email" id="input-email"
                                    class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Email') }}" value="{{ $trainer->email }}" readonly>
                            </div>
                            <!--Mobile-->
                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-mobile">{{ __('mobile') }}</label>
                                <input type="text" name="mobile" id="input-mobile"
                                    class="form-control form-control-alternative{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('mobile') }}" value="{{ $trainer->mobile }}" readonly>
                            </div>
                            <!--Age-->
                            <div class="form-group{{ $errors->has('age') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-age">{{ __('Age') }}</label>
                                <input type="text" name="age" id="input-age"
                                    class="form-control form-control-alternative{{ $errors->has('age') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Age') }}" value="{{ $trainer->age }}" readonly>
                            </div>
                            <!--Bio-->
                            <div class="form-group{{ $errors->has('bio') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-bio">{{ __('Bio') }}</label>
                                <input type="bio" name="bio" id="input-bio"
                                    class="form-control form-control-alternative{{ $errors->has('bio') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Bio') }}" value="{{ $trainer->bio }}" readonly>
                            </div>
                            <div class="text-center">
                                <button type="button" id="approve"
                                    class="btn btn-success mt-4">{{ __('Back') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-5 order-xl-1">
            <div style="padding-top: 30%">
                <h2 class="mb-3">Certificate</h2>
                <img src="{{ $trainer->certificate() != null ? asset($trainer->certificate()->image) : null }}" alt=""
                    style="width: 90%; height: 100%">
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
    </div>
    @push('js')
        <script>
            $('#approve').click(function() {
                window.location.href = "{{ route('web_users_index') }}";
            });
        </script>
    @endpush
@endsection
