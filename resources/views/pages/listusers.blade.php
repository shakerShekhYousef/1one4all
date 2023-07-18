@extends('layouts.app')
@section('content')
    <div class="main-content" id="panel">

        <!-- Main content -->
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
                            <h1 class="display-2 text-white">List users</h1>
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
                                <div id="alertdiv" role="alert" hidden></div>
                                <h3 class="mb-0">Users list</h3>
                            </div>

                            <!-- Table filteration-->
                            <label for="roletype" class="ml-5 mt-5"><B>Role type</B></label>
                            <select class="form-control mb-5 ml-5" name="roletype" id="roletype" style="width: 30%">
                                <option value="">All</option>
                                <option value="2">Trainer</option>
                                <option value="3">Player</option>
                            </select>

                            <!-- Light table -->
                            <table class="table align-items-center table-flush" id="userstable" width='100%'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Age</th>
                                        <th>Type</th>
                                        <th>Bio</th>
                                        <th>Level</th>
                                        <th>Approved</th>
                                        <th>User id</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
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

        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="{{ asset('public/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('public/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
        <!-- Argon JS -->
        <script src="{{ asset('public/assets/js/argon.js?v=1.2.0') }}"></script>

        <!--Get table data-->
        <script>
            var table = $('#userstable').DataTable({
                "scrollX": true,
                "scrollCollapse": true,
                'ordering': false,
                "dom": "fBrtip",
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('web_get_users') }}",
                    data: function(d) {
                        d.roletype = $('#roletype').val();
                        d.search = $('.dataTables_filter input').val();
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
                        data: 'name',
                        name: 'name',
                        width: 200
                    },
                    {
                        data: 'email',
                        name: 'email',
                        width: 200
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        width: 200
                    },
                    {
                        data: 'age',
                        name: 'age',
                        width: 150
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        width: 150
                    },
                    {
                        data: 'bio',
                        name: 'bio',
                        width: 250
                    },
                    {
                        data: 'level',
                        name: 'level',
                        width: 200
                    },
                    {
                        data: 'Approved',
                        name: 'Approved',
                        width: 100
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        width: 100,
                        visible: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true,
                        width: 300
                    }
                ]
            });

            $('#roletype').change(function() {
                table.draw();
            });

            // Delete a record
            $('body').on('click', '.delete', function() {
                var id = table.row(this.closest('tr')).data()['user_id'];
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
                            url: "{{ route('web_delete_user') }}",
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

            // Approve trainer index
            $('body').on('click', '.approve', function() {
                var id = table.row(this.closest('tr')).data()['user_id'];
                window.location.href = "{{ route('web_approve_trainer_index') }}/" + id;
            });

            // Show user index
            $('body').on('click', '.showinfo', function() {
                var id = table.row(this.closest('tr')).data()['user_id'];
                window.location.href = "{{ route('web_show_user_index') }}/" + id;
            });

            // Edit user index
            $('body').on('click', '.editinfo', function() {
                var id = table.row(this.closest('tr')).data()['user_id'];
                window.location.href = "{{ route('web_other_edit') }}/" + id;
            });

            // Cancel approve trainer
            $('body').on('click', '.deapprove', function() {
                var id = table.row(this.closest('tr')).data()['user_id'];
                // ajax
                $.ajax({
                    type: "get",
                    url: "{{ route('web_deapprove_trainer') }}/" + id,
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
            });
        </script>
    </div>
@endsection
