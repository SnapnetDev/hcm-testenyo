<table class="table table-striped">
    <thead>
    <tr>
        <th>Staff Number</th>
        <th>Staff</th>
        @foreach($dates as $date)
            <th>{{ $date }}</th>
        @endforeach
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $user)
        @php($i=0)
        <tr>
            <td>{{$user->emp_num}}</td>
            <td>{{$user->name}}</td>
            @foreach($user['dates'] as $date)
                <th>{{ $date }}</th>
            @if($date!='')
                @php($i+=$date)
                @endif
            @endforeach
            <td>{{$i}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
