<table class="table table-striped">
    <thead>
    <tr>
        <th>Clock In</th>
        <th>Clock Out</th>
    </tr>
    </thead>
    <tbody>
    @foreach($attendancedetails as $detail)
        <tr>
            <td>{{date(' h:i:s a',strtotime($detail->clock_in))}}</td>
            <td>
                @if($detail->clock_out)
                    {{date(' h:i:s a',strtotime($detail->clock_out))}}
                @else
                    No Clock Out
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>