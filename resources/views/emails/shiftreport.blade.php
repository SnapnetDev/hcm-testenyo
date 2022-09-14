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
     style="height: 2.286rem; background:#fff" title="Enyo">
<h1 style="font-size:15px;font-family:Helvetica">{{ $data['subject'] }}</h1>
<br>
<table id="customers">
    <thead>
    <tr>
        <th>S/N</th>
        <th>STATION</th>
        <th>NEXT WEEK SHIFT</th>
        <th>STATION LAST SHIFT</th>
        <th>DEVICE LAST SYNC</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['stations'] as $station)
        @php($tot=$station->earlys+$station->lates+$station->absentees)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$station->name}}</td>
            <td style={{$station->ready=='YES' ? 'background-color:#77e94c' : 'background-color:#ff6604'}}>{{ $station->ready }}</td>
            <td>{{$station->date}}</td>
            <td>{{$station->sync}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
