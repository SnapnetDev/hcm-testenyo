@php
  $pdetails=unserialize($detail->details);
  // $num=count($days);
@endphp
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payslip</title>
   
    
      <style type="text/css">
      body {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
            width: 18cm;
            height: 29.7cm; 
            padding: 0px;
          }
         
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
          }

           td,  th {
              border: 1px solid #dddddd;
              text-align: left;
              padding: 8px;
          }

           tr:nth-child(even) {
              background-color: #dddddd;
          }
          h1,h4 {
                font-family: arial,sans-serif;
            }
            #header td, #header th {
              border: 0px solid #dddddd;
              text-align: left;
              padding: 8px;
          }

          
      </style>
  </head>
  <body>
    <center><h1 style="text-transform: uppercase;">{{companyInfo()->name}}</h1></center>
   <hr style="height:5px;background-color:#f00;">
    <table style="width:100%" id="header">
      <tr>
        <td style="width:33%">
          {{companyInfo()->address}}
       <br>
       08139248042
       <br>
      {{ companyInfo()->email}}
       <br>
       www.snapnet.com.ng
        </td>
        <td style="width:34%">
          <center style="background: #337ab7;color: #fff;"><h2>PAYSLIP</h2></center>
        </td>
        <td style="width:33%">
          {{-- <img style="width: 150px;height:auto;" src="{{ asset('storage/'.$logo) }}" class="img-responsive"> --}}
        </td>
      </tr>
    </table>

   <center><h4 class="bg-primary text-center" style="background: #337ab7;color: #fff; padding: 5px 0px;">Payslip of {{$detail->user->name}} in {{date("F", mktime(0, 0, 0, $detail->payroll->month, 10))}} {{$detail->payroll->year}}</h4></center>
  <table class="payslip" style="width:100%;">
    <thead class="head">
      <tr class="bg-primary">
        <th style="width: 33%;"></th>
        <th style="width: 34%; text-align: center;"  class="bg-primary"><span>Employee Details</span></th>
        <th style="width: 33%;"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          GRADE:Level @if($detail->user->promotionHistories) {{$detail->user->promotionHistories()->latest()->first()->grade->level }}@endif
        </td>
        <td>
          DEPT:
        </td>
        <td>
          REF ID:
        </td>
      </tr>
    </tbody>
  </table>
  <div class="table-responsive">
<table class="table table-striped ">
  
  <tbody>
    <tr>
      
      <th style="width: 70%;">Gross Pay</th>
      <td style="text-align: right">N{{number_format($detail->gross_pay,2)}}</td>
      
    </tr>
  </tbody>
  
</table>
<h4>Allowances</h4>
<table class="table table-striped ">
  
  <tbody>
    <tr>
      
      <th style="width: 70%;">Basic Pay</th>
      <td style="text-align: right">N{{number_format($detail->basic_pay,2)}}</td>
      
    </tr>
    @foreach($pdetails['allowances'] as $key=>$allowance)
    <tr>
      
      <th style="width: 70%;">{{$pdetails['component_names'][$key]}}</th>
      <td style="text-align: right">N{{number_format($allowance,2)}}</td>
      
    </tr>
    @endforeach
  </tbody>
  
</table>

<h4>Deductions</h4>
<table class="table table-striped ">
  
  <tbody>
    @foreach($pdetails['deductions'] as $key=>$deduction)
    <tr>
      
      <th style="width: 70%;">{{$pdetails['component_names'][$key]}}</th>
      <td style="text-align: right">-N{{number_format($deduction,2)}}</td>
      
    </tr>
    @endforeach
    <tr>
      <th style="width: 70%;">Income Tax</th>
      <td style="text-align: right">-N{{number_format($detail->paye,2)}}</td>
    </tr>
  </tbody>
  
</table>
<hr>

<table class="table table-striped ">
  
  <tbody>
   
    <tr>
      
      <th style="width: 70%;">Net Salary</th>
      <th style="text-align: right">N{{number_format(($detail->basic_pay+$detail->allowances)-($detail->deductions+$detail->paye),2)}}</th>
      
    </tr>
   
  </tbody>
  
</table>
</div>
  </body>
</html>
