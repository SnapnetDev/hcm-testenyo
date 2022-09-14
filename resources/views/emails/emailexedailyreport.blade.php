<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;

        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            font-size:12px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: left;
            background-color: #36459b;
            color: white;
            font-size:12px;
        }
        tbody {
            page-break-inside: avoid;
        }
        thead {
            display: table-header-group;
            margin-top: 100px;
        }
    </style>
</head>
<body>

<img src="http://enyo.thehcmatrix.com/storage/logo/enyo-logo.png"
     style="height: 2.286rem; background:#fff" title="Orchid Road">
<h1 style="font-size:15px;font-family:Helvetica">{{ $data['subject'] }}</h1>
<br>
<table id="customers">
    <thead>
    <tr>
        <th colspan="9" style="text-align:center">ATTENDANCE</th>
        <th colspan="3" style="text-align:center">WORKING HOURS</th>
        <th colspan="2" style="text-align:center">OTHERS</th>
    </tr>
    <tr>
        <th>S/N</th>
        <th>STATION</th>
        <th>{{__('EARLY')}}</th>
        <th>{{__('LATE')}}</th>
        <th>{{__('ABSENT')}}</th>
        <th>{{__('OFF')}}</th>
        <th>{{__('PRESENT')}}</th>
        <th>{{__('TOTAL')}}</th>
        <th>{{__('ATTENDANCE COMPLIANCE %')}}</th>
        <th>{{__('EXPECTED AVG WORKING HOURS')}}</th>
        <th>{{__('ACTUAL AVG WORKED HOURS')}}</th>
        <th>{{__('WORK HOURS COMPLIANCE %')}}</th>
        <th>{{__('AMOUNT EARNED')}}</th>
        <th>{{__('DEVICE LAST SYNC')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['stations'] as $station)
        @php($tot=$station->earlys+$station->lates+$station->absentees)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$station->name}}</td>
            <td>{{$station->earlys}}</td>
            <td>{{$station->lates}}</td>
            <td>{{$station->absentees}}</td>
            <td>{{$station->offs}}</td>
            <td>{{$station->presents}}</td>
            <td>{{$tot}}</td>
            @if($tot>0)
                <td style={{ (($station->earlys/$tot)*100<100) ? "background-color:#ff6604" : "background-color:#77e94c" }}> {{number_format(($station->earlys/$tot)*100,2)}}%</td>
            @else
                <td style="background-color:#ff6604">0%</td>
            @endif
            <td>{{ number_format($station->exp_hours,2) }}</td>
            <td>{{ number_format($station->avg_hours,2) }}</td>

            @if($tot>0)
                <td style={{ (($station->avg_hours/$station->exp_hours)*100<100) ? "background-color:#ff6604" :
                 ( (($station->avg_hours/$station->exp_hours)*100==100) ? "background-color:#77e94c" : "background-color:#0fb4ff") }}>{{ number_format(($station->avg_hours/$station->exp_hours)*100,2) }}%</td>
            @else
                <td style="background-color:#ff6604">0%</td>
            @endif

            <td>{{number_format($station->amount,2)}}</td>
            @if($station->biometric_serial!=null)
                <td>{{$station->last_seen['created_at']}}</td>
            @else
                <td></td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>

<a href="https://enyo.thehcmatrix.com/executive-report">Click here to see Full Report</a>
</body>
</html>
