<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
    <thead>
    <tr>
        <th>EMPID</th>
        <th>{{__('NAME')}}</th>
        <th>{{__('STATION')}}</th>
        <th>{{__('ROLE')}}</th>
        <th>{{__('DAYS WORKED')}}</th>
        <th>{{__('DAYS ABSENT')}}</th>
        <th>{{__('Max Expected')}}</th>
        <th>{{__('Amount Paid')}}</th>
    </tr>
    </thead>
    <tbody>

    @foreach($stations as $station)
        @foreach($station['reports'] as $repo)
            <tr>
                <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$repo->user->id) }}">{{$repo->user->emp_num}}</a></td>
                <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$repo->user->id) }}">{{$repo->user->name}}</a></td>
                <td>{{ $repo->user->company->name }}</td>
                <td>{{$repo->role_id}}</td>
                <td>{{$repo->days_worked}}</td>
                <td>{{$repo->absent}}</td>
                <td>{{number_format($repo->amount_expected,2)}}</td>
                <td>{{number_format($repo->amount_received,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{number_format($station->amount_expected,2)}}</td>
            <td>{{number_format($station->amount_received,2)}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach

    </tbody>
</table>