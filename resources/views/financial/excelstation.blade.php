<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
    <thead>
    <tr>
        <th>STATION ID</th>
        <th>{{__('STATION NAME')}}</th>
        <th>{{__('STATION MANAGER')}}</th>
        <th>{{__('No of Staff')}}</th>
        <th>{{__('Max Expected')}}</th>
        <th>{{__('Amount Paid')}}</th>
    </tr>
    </thead>
    <tbody>

    @foreach($companies as $station)
        <tr>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$station->id) }}">{{$station->id}}</a></td>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$station->id) }}">{{$station->name}}</a></td>
            <td>{{\App\User::where('company_id',$station->id)->where('role_id','2')->where('status','1')->first() ? \App\User::where('company_id',$station->id)->where('role_id','2')->where('status','1')->first()->name : 'None' }}</td>
            <td>{{$station->no_of_staff}}</td>
            <td>{{number_format($station->amount_expected,2)}}</td>
            <td>{{number_format($station->amount_received,2)}}</td>
        </tr>
    @endforeach

    </tbody>
</table>