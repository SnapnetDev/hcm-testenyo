<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        #customers {
            font-family: Helvetica;
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
            background-color: #211ee8;
            color: white;
            font-size:12px;
            font-family:Helvetica;
        }
    </style>
</head>
<body style="font-family:Helvetica">

<h1 style="font-size:15px;font-family:Helvetica">Branch Name: {{ $data['branch_name'] }}</h1>
<h1 style="font-size:15px;font-family:Helvetica">FIELD STAFF ATTENDANCE REPORT FOR {{ $data['date'] }}</h1>
<br>
<table id="customers">
    <tr>
        <th>S/N</th>
        <th>STATION</th>
        <th>{{__('EARLY')}}</th>
        <th>{{__('OFF')}}</th>
        <th>{{__('LATE')}}</th>
        <th>{{__('ABSENT')}}</th>
        <th>{{__('PRESENT')}}</th>
        <th>{{__('TOTAL')}}</th>
        <th>{{__('AMOUNT')}}</th>
        
        <th >{{__('Last Sync')}}</th>
    </tr>
    @foreach($data['stations'] as $station)
          @php($tot=$station->earlys+$station->lates+$station->absentees)
          
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$station->name}}</td>
            <td>{{$station->earlys}}</td>
            <td> {{$station->offs}}</td>
            <td> {{$station->lates}}</td>
            <td> {{$station->absentees}}</td>
            <td> {{$station->presents}}</td>
            <td> {{$tot}}</td>
            <td> {{number_format($station->amount,2)}}</td>
            <td> {{$station->last_seen['created_at']}}</td>
        </tr>
    @endforeach
</table>

<a href="https://enyo.thehcmatrix.com/executive-report">Click here to see Full Report</a>
</body>
</html>
