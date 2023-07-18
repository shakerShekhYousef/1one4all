@extends('layouts.app')
@section('content')
    <div class="main-content" id="panel">

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center"
            style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
            <!-- Mask -->
            <span class="mask bg-gradient-default opacity-8"></span>
            <!-- Header container -->
            <div class="container-fluid d-flex align-items-center">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="display-2 text-white">Create user</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header border-0">
                            <h3 class="mb-0">Create user</h3>
                        </div>

                        <div class="card-body">
                            <div id="alertdata" role="alert" hidden></div>
                            <form id="maindata">
                                @csrf

                                <div class="pl-lg-4">
                                    <!--Role type-->
                                    <div class="form-input mb-3">
                                        <label class="form-control-label" for="roletype">{{ __('Role type') }}</label>
                                        <select class="form-control" style="width: 30%" name="roletype" id="roletype">
                                            <option value="1">Admin</option>
                                            <option value="2">Trainer</option>
                                            <option value="3">Player</option>
                                        </select>
                                    </div>
                                    <!--Name-->
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="input-name"
                                            class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Name') }}" value="" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Email-->
                                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="input-email"
                                            class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Email') }}" value="" required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Password-->
                                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <label class="form-control-label"
                                            for="input-password">{{ __('Password') }}</label>
                                        <input type="password" name="password" id="input-password"
                                            class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Password') }}" value="" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Confirm password-->
                                    <div class="form-group">
                                        <label class="form-control-label"
                                            for="input-password-confirmation">{{ __('Confirm Password') }}</label>
                                        <input type="password" name="password_confirmation" id="input-password-confirmation"
                                            class="form-control form-control-alternative"
                                            placeholder="{{ __('Confirm Password') }}" value="" required>
                                    </div>
                                    <!--certificate-->
                                    <div id="certificate" hidden
                                        class="form-group{{ $errors->has('certificate') ? ' has-danger' : '' }}">
                                        <label class="form-control-label"
                                            for="input-certificate">{{ __('certificate') }}</label>
                                        <input type="file" name="certificate" id="input-certificate"
                                            class="form-control form-control-alternative{{ $errors->has('certificate') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('certificate') }}" value="" required>

                                        @if ($errors->has('certificate'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('certificate') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Mobile-->
                                    <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-mobile">{{ __('mobile') }}</label>
                                        <input type="text" name="mobile" id="input-mobile"
                                            class="form-control form-control-alternative{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('mobile') }}" value="" required>

                                        @if ($errors->has('mobile'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('mobile') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Age-->
                                    <div hidden id="agediv"
                                        class="form-group{{ $errors->has('age') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-age">{{ __('Age') }}</label>
                                        <input type="number" name="age" id="input-age"
                                            class="form-control form-control-alternative{{ $errors->has('age') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Age') }}" value="" required>

                                        @if ($errors->has('age'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('age') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Bio-->
                                    <div hidden id="biodiv"
                                        class="form-group{{ $errors->has('bio') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-bio">{{ __('Bio') }}</label>
                                        <textarea name="bio" id="bio" style="height:100%;width: 100%"></textarea>
                                        @if ($errors->has('bio'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('bio') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!--Level-->
                                    <div hidden id="leveldiv"
                                        class="form-group{{ $errors->has('level') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-level">{{ __('Level') }}</label>
                                        <select class="form-control" name="level" id="input-level">
                                            <option value=""></option>
                                            <option value="Beginner">Beginner</option>
                                            <option value="Advanced">Advanced</option>
                                            <option value="Athlete">Athlete</option>
                                            <option value="Rehabilitation">Rehabilitation</option>
                                        </select>
                                    </div>
                                    <!--image-->
                                    <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-image">{{ __('image') }}</label>
                                        <input type="file" name="image" id="input-image"
                                            class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('image') }}" value="" required>

                                        @if ($errors->has('image'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <button type="button" id="buttonsubmit"
                                            class="btn btn-success mt-4">{{ __('Create') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Logout form-->
    @auth()
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth

    @push('js')
        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="{{ asset('public/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
        <!-- Argon JS -->
        <script src="{{ asset('public/assets/js/argon.js?v=1.2.0') }}"></script>

        <!-- datatable -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

        {{-- swal alert --}}
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('#buttonsubmit').click(function(e) {
                e.preventDefault();
                $("#alertdata").empty();
                var formData = new FormData();

                // formData.append('id', "{{ Auth::user()->id }}");
                $('#maindata').serializeArray().forEach(function(field) {
                    formData.append(field.name, field.value);
                });

                var image = $('#input-image')[0].files[0];
                var certificate = $('#input-certificate')[0].files[0];
                formData.append('image', image);
                formData.append('certificate', certificate);

                $.ajax({
                    method: 'post',
                    processData: false,
                    contentType: false,
                    data: formData,
                    enctype: 'multipart/form-data',
                    url: "{{ route('web_create_user') }}",
                    success: function(result) {
                        if (result.success) {
                            $("#alertdata").empty();
                            $("#alertdata").append(
                                "<div class= 'alert alert-success'>" + result.message + "</div>");
                            $("#alertdata").attr('hidden', false);
                            $("#maindata")[0].reset();
                        } else {
                            $("#alertdata").empty();
                            $("#alertdata").append(
                                "<div class= 'alert alert-danger'>" + result.message + "</div>");
                            $("#alertdata").attr('hidden', false);
                        }
                    },
                    error: function(error) {
                        $("#alertdata").empty();
                        $.each(JSON.parse(error.responseText).errors, function(index, value) {
                            $("#alertdata").append(
                                "<div class= 'alert alert-danger'>" +
                                index +
                                "   " + value + "</div>");
                        });
                        $("#alertdata").attr('hidden', false);
                    }
                });
            });
        </script>
        <script>
            $('#roletype').change(function() {
                role_id = $('#roletype').val();
                if (role_id == "2") {
                    $('#certificate').removeAttr('hidden');
                    $('#agediv').attr('hidden', 'hidden');
                    $('#leveldiv').attr('hidden', 'hidden');
                    $('#biodiv').removeAttr('hidden');
                } else if (role_id == "3") {
                    $('#agediv').removeAttr('hidden');
                    $('#leveldiv').removeAttr('hidden');
                    $('#biodiv').attr('hidden', 'hidden');
                    $('#certificate').attr('hidden', 'hidden');
                } else if (role_id == "1") {
                    $('#certificate').attr('hidden', 'hidden');
                    $('#agediv').attr('hidden', 'hidden');
                    $('#biodiv').attr('hidden', 'hidden');
                    $('#leveldiv').attr('hidden', 'hidden');
                }
            })
        </script>

    @endpush('js')
@endsection
