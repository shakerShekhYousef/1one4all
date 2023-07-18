@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-2 text-white">{{ __('Hello') . ' '. $user->name }}</h1>
                <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you\'ve made with your work and manage your
                    projects or assigned tasks</p>
            </div>
        </div>
    </div>
</div> 

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Edit Profile') }}</h3>
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
                                        placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}"
                                        required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--Email-->
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <input readonly type="email" name="email" id="input-email"
                                        class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Email') }}"
                                        value="{{ old('email', $user->email) }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--Mobile-->
                                <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-mobile">{{ __('mobile') }}</label>
                                    <input type="text" name="mobile" id="input-mobile"
                                        class="form-control form-control-alternative{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('mobile') }}"
                                        value="{{ old('mobile', $user->mobile) }}" required>

                                    @if ($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--Age-->
                                <div class="form-group{{ $errors->has('age') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-age">{{ __('Age') }}</label>
                                    <input type="text" name="age" id="input-age"
                                        class="form-control form-control-alternative{{ $errors->has('age') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Age') }}" value="{{ old('age', $user->age) }}"
                                        required>

                                    @if ($errors->has('age'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('age') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--Bio-->
                                <div class="form-group{{ $errors->has('bio') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-bio">{{ __('Bio') }}</label>
                                    <input type="bio" name="bio" id="input-bio"
                                        class="form-control form-control-alternative{{ $errors->has('bio') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Bio') }}" value="{{ old('bio', $user->bio) }}"
                                        required>

                                    @if ($errors->has('bio'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('bio') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--Level-->
                                @if (Auth::user()->isPlayer())
                                    <div class="form-group{{ $errors->has('level') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-level">{{ __('Level') }}</label>
                                        <input type="level" name="level" id="input-level"
                                            class="form-control form-control-alternative{{ $errors->has('level') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Level') }}"
                                            value="{{ old('level', $user->level) }}" required>

                                        @if ($errors->has('level'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('level') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <!--image-->
                                <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-image">{{ __('image') }}</label>
                                    <input type="file" name="image" id="input-image"
                                        class="form-control form-control-alternative{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('image') }}"
                                        value="{{ old('image', $user->image) }}" required>

                                    @if ($errors->has('image'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <button type="button" id="buttonsubmit"
                                        class="btn btn-success mt-4">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
    @push('js')
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
                formData.append('image', image);

                $.ajax({
                    method: 'post',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    enctype: 'multipart/form-data',
                    url: "{{ route('web_edit_user') }}/" + "{{ $user->id }}",
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
                        $.each(error.responseJSON.errors, function(index, value) {
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
    @endpush
@endsection
