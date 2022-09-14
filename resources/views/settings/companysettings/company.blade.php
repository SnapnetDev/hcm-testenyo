<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Companies')}}</li>
        <li class="breadcrumb-item active">{{__('You are Here')}}</li>
    </ol>
    <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium" id="time"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Stations</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addCompanyModal">Add Station
                        </button>

                    </div>
                </div>
                <div class="panel-body">

                    <table id="rolestable" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true"
                           class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>Name:</th>
                            <th>Email:</th>
                            <th>Address:</th>
                            <th>State:</th>
                            <th>Serial:</th>
                            <th>Status:</th>
                            <th>Device Last Seen:</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>{{$company->name}}</td>
                                <td>{{$company->email}}</td>
                                <td>{{$company->address}}</td>
                                <td>{{$company->state->name}}</td>
                                <td>{{$company->biometric_serial}}</td>
                                <td>{{$company->status}}</td>
                                <td>{{isset($company->last_seen)? $company->last_seen->created_at->format('d M, Y H:i s') : 'not seen'}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a class="dropdown-item" id="{{$company->id}}"
                                               onclick="prepareCEditData(this.id)"><i class="fa fa-pencil"
                                                                                      aria-hidden="true"></i>&nbsp;Edit
                                                Company</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
                {{--<div class="panel-footer">
                    <form>
                        <div class="form-group">
                            <h4>Select Parent Company</h4>
                            <select class="form-control" onchange="changeParentCompany(this.value)">
                                @forelse($companies as $company)
                                    <option value="{{$company->id}}" {{$company->is_parent==1?'selected':''}}>{{$company->name}}</option>
                                @empty
                                    <option value="0">Please Create a company</option>
                                @endforelse

                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info">Save Changes</button>
                        </div>
                    </form>
                </div>--}}
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">States</h3>
                </div>
                <div class="panel-body">

                    <table id="rolestable" data-toggle="table" data-query-params="queryParams" data-mobile-responsive="true" data-height="400" data-pagination="true" data-search="true"
                           class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>Name:</th>
                            <th>Rep:</th>
                            <th>No of Stations:</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($states as $state)
                            <tr>
                                <td>{{$state->name}}</td>
                                <td>{{$state->rep->name}}</td>
                                <td>{{$state->stations_count}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a class="dropdown-item" id="{{$state->id}}"
                                               onclick="prepareSEditData(this.id)"><i class="fa fa-pencil"
                                                                                      aria-hidden="true"></i>&nbsp;Edit
                                                State</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Branches</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addBranchModal">Add Branch</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="rolestable" data-toggle="table" data-query-params="queryParams" data-mobile-responsive="true" data-height="400" data-pagination="true" data-search="true"
                           class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>Name:</th>
                            <th>SSM/ Area Manager</th>
                            <th>No of Stations:</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td>{{$branch->name}}</td>
                                <td>{{$branch->manager->name}}</td>
                                <td>{{$branch->stations_count}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a class="dropdown-item" id="{{$branch->id}}"
                                               onclick="prepareBEditData(this.id)"><i class="fa fa-pencil"
                                                                                      aria-hidden="true"></i>&nbsp;Edit
                                                Branch</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Regions</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addRegionModal">Add Region</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="rolestable" data-toggle="table" data-query-params="queryParams" data-mobile-responsive="true" data-height="400" data-pagination="true" data-search="true"
                           class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>Name:</th>
                            <th>No of Branches:</th>
                            <th>Area Manager</th>
                            <th>Regional Lead</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($regions as $region)
                            <tr>
                                <td>{{$region->name}}</td>
                                <td>{{$region->branches_count}}</td>
                                <td>{{$region->area_manager->name}}</td>
                                <td>{{$region->regional_lead->name}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a class="dropdown-item" id="{{$region->id}}"
                                               onclick="prepareREditData(this.id)"><i class="fa fa-pencil"
                                                                                      aria-hidden="true"></i>&nbsp;Edit
                                                Region</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
{{-- Add Company Modal --}}
@include('settings.companysettings.modals.addcompany')
{{-- edit company modal --}}
@include('settings.companysettings.modals.editcompany')
{{-- add branch modal --}}
@include('settings.companysettings.modals.addbranch')
{{-- edit branch modal --}}
@include('settings.companysettings.modals.editbranch')
{{-- add branch modal --}}
@include('settings.companysettings.modals.addregion')
{{-- edit branch modal --}}
@include('settings.companysettings.modals.editregion')
{{-- edit branch modal --}}
@include('settings.companysettings.modals.editstate')


<!-- End Page -->
<script type="text/javascript">

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
                $('#img-uploade').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInpe").change(function () {
        readURL(this);
    });


    $(function () {

        $('.datatable').DataTable();
        $('#addCompanyForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            console.log(formdata)
            $.ajax({
                url: '{{route('companies.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    toastr.success("Changes saved successfully", 'Success');
                    // location.reload();
                    $('#addCompanyModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
        $('#editCompanyForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('companies.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    toastr["success"]("Changes saved successfully", 'Success');
                    //location.reload();
                    $('#editCompanyModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
    });

    function prepareCEditData(company_id) {
        $.get('{{ url('/settings/companies') }}/' + company_id, function (data) {
            console.log(data);
            $('#editname').val(data.name);
            $('#editid').val(data.id);
            $('#editemail').val(data.email);
            $('#editaddress').val(data.address);
            $('#edituser_id').val(data.user_id);
            $('#editstate_id').val(data.state_id);
            $('#editbranch_id').val(data.branch_id);
            $('#editbiometric').val(data.biometric_serial);
            $('#editcstatus').val(data.status);
            $('#editpay_full_days').val(data.pay_full_days);

            $('#img-uploade').attr('src', "{{url('')}}/storage/logo" + data.logo);
        });
        $('#editCompanyModal').modal();
    }

    function changeParentCompany(company_id) {
        event.preventDefault();
        $.get('{{ url('/settings/companies/parent') }}/' + company_id, function (data) {

            if (data == 'success') {
                toastr["success"]("Changes saved successfully", 'Success');
            } else {
                toastr["error"]("No company was selected", 'Error');
            }
        });
    }

</script>
<script type="text/javascript">
    $(function() {
        $('#addBranchForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            console.log(formdata)
            $.ajax({
                url: '{{route('branches.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    toastr.success("Changes saved successfully", 'Success');
                    // location.reload();
                    $('#addBranchModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
        $('#editBranchForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('branches.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    toastr["success"]("Changes saved successfully", 'Success');
                    //location.reload();
                    $('#editBranchModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
    });

    function prepareBEditData(branch_id){
        $.get('{{ url('/settings/branch') }}/'+branch_id,function(data){
            console.log(data);

            $('#editbname').val(data.name);
            $('#editbemail').val(data.email);
            $('#editbaddress').val(data.address);
            $('#editbid').val(data.id);
            $('#editbcompany_id').val(data.company_id);
            $('#editbuser').val(data.manager_id);
            $('#editbregion').val(data.region_id);
        });
        $('#editBranchModal').modal();
    }
    function deleteBranch(branch_id){

        alertify.confirm('Are you sure you want to delete this branch ?', function(){
            $.get('{{ url('settings/branches/delete') }}/'+branch_id,{
                    branch_id:branch_id
                },
                function(data, status){
                    if(data=="success"){
                        toastr.success('Branch Deleted Successfully');
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                        return;
                    }
                    toastr.error(data);
                });
        });

    }

</script>


<script type="text/javascript">
    $(function() {

        $('#addRegionForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            console.log(formdata)
            $.ajax({
                url: '{{route('region.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    toastr.success("Changes saved successfully", 'Success');
                    // location.reload();
                    $('#addRegionModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
        $('#editRegionForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('region.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    toastr["success"]("Changes saved successfully", 'Success');
                    //location.reload();
                    $('#editRegionModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
    });

    function prepareREditData(region_id){
        $.get('{{ url('/settings/region') }}/'+region_id,function(data){
            console.log(data);
            $('#editrname').val(data.name);
            $('#editregion_id').val(data.id);
            $('#editruser').val(data.manager_id);
        });
        $('#editRegionModal').modal();
    }

</script>

<script type="text/javascript">
    $(function() {

        $('#editStateForm').on('submit', function (event) {
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('state.store')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    toastr["success"]("Changes saved successfully", 'Success');
                    //location.reload();
                    $('#editStateModal').modal('toggle');
                    $("#ldr").load('{{route('companies')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });
            return event.preventDefault();
        });
    });

    function prepareSEditData(state_id){
        $.get('{{ url('/settings/state') }}/'+state_id,function(data){
            console.log(data);
            $('#editsname').val(data.name);
            $('#editsstate_id').val(data.id);
            $('#editrep_id').val(data.rep_id);
        });
        $('#editStateModal').modal();
    }

</script>

