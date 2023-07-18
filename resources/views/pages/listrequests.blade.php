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
            <div id="alertdiv"></div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header border-0">
                            <h3 class="mb-0">Users requests list</h3>
                        </div>

                        <!-- Table filteration-->
                        <label for="requesttype" class="ml-5 mt-5"><B>Request type</B></label>
                        <select class="form-control mb-5 ml-5" name="requesttype" id="requesttype" style="width: 30%">
                            <option value="">All</option>
                            @foreach ($requeststypes as $key => $item)
                                <option value={{ $key }}>{{ $key }}</option>
                            @endforeach
                        </select>

                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="userstable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Body</th>
                                        <th>Request type</th>
                                        <th>Trainer name</th>
                                        <th>Trainer email</th>
                                        <th>Player name</th>
                                        <th>Player email</th>
                                        <th>user_id</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
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
@endsection

@push('js')
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

    <!--Get table data-->
    <script>
        var table = $('#userstable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('web_get_All_Requests') }}",
                data: function(d) {
                    d.requesttype = $('#requesttype').val();
                    d.search = $('input[type="search"]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: 70,
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'player_name',
                    name: 'player_name',
                    width: 200
                },
                {
                    data: 'body',
                    name: 'body',
                    width: 200
                },
                {
                    data: 'request_type',
                    name: 'request_type',
                    width: 200
                },
                {
                    data: 'trainer_name',
                    name: 'trainer_name',
                    width: 250
                },
                {
                    data: 'trainer_email',
                    name: 'trainer_email',
                    width: 200
                },
                {
                    data: 'player_name',
                    name: 'player_name',
                    width: 250
                },
                {
                    data: 'player_email',
                    name: 'player_email',
                    width: 200
                },
                {
                    data: 'id',
                    name: 'id',
                    width: 200,
                    // visiable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true,
                    width: 200
                }
            ]
        });

        $('#requesttype').change(function() {
            table.draw();
        });

        // Delete a record
        $('body').on('click', '.delete', function() {
            var id = table.row(this.closest('tr')).data()['id'];
            swal({
                title: 'Are you sure?',
                text: 'Data will be permanantly deleted!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function(value) {
                if (value) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // ajax
                    $.ajax({
                        type: "delete",
                        url: "{{ route('web_delete_request') }}",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                $('#alertdiv').empty();
                                $('#alertdiv').append(
                                    "<div class= 'alert alert-success'>" +
                                    result
                                    .message +
                                    "</div>");
                                $('#alertdiv').attr('hidden', false);
                                table.clear().draw();
                            } else {
                                $('#alertdiv').empty();
                                $('#alertdiv').append(
                                    "<div class= 'alert alert-danger'>" +
                                    result
                                    .message +
                                    "</div>");
                                $('#alertdiv').attr('hidden', false);
                            }
                        },
                        error: function(erorr) {
                            console.log(erorr);
                        }
                    });
                }
            });
        });
    </script>
@endpush
