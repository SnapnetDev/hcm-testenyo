@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <style type="text/css">
        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            text-align: center;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
            background: #333;
        }


    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page">
        <div class="page-content">
            <!-- Panel -->
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <form enctype="multipart/form-data" method="Post" action="{{ route('verify.staff.post') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Upload Image</label>
                                    <img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150"
                                         src="{{ isset($url) ? asset($url) : asset('global/portraits/female-user.png')}}" alt="..." id='img-upload'>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                      <span class="input-group-btn">
                                          <span class="btn btn-default btn-file">
                                              Browse…
                                              <input type="file" id="imgInp" name="avatar" accept="image/*">
                                          </span>
                                      </span>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <button class=" btn btn-primary"> Submit</button>
                            </form>

                            @if(count($users)>1)
                                <h1 class="page-search-title">Matching Staff with Image Uploaded</h1>
                                <p>The higher the confidence level, the higher the chances of a match</p>
                                <div class="table-responsive">
                                    <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Staff</th>
                                            <th>Confidence Level</th>
                                            <th>Station</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            @if(isset($user['user']['name']))
                                            <tr>
                                                <td>
                                                    <img class="img-circle img-bordered img-bordered-blue text-center" width="50" height="50"
                                                         src="{{ asset('uploads/public/avatar'.$user['user']['image'])}}" alt="..." id='img-upload'>
                                                </td>
                                                <td>
                                                    <a href="{{ route('users.edit',$user['user']['id']) }}">{{ $user['user']['name'] }}</a>
                                                </td>
                                                <td>{{ $user['confidence'] }}</td>
                                                <td>{{ $user['user']['company']['name'] }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            //function for picture change
            $(document).on('change', '.btn-file :file', function () {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function (event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function () {
                readURL(this);
            });

        });
    </script>
@endsection