
<table >
	<thead>
		<tr>
			<th></th>
			<th>Employee Number</th>
			<th>Employee Name</th>
			<th>Gross pay</th>
			<th>Basic pay</th>
			@foreach($payroll->salary_components as $component)
			<th>{{$component->name}}</th>
			@endforeach
			<th>Personal Allowances</th>
			<th>Personal Deductions</th>
			<th>PAYE</th>
			<th>Net Pay</th>
			
		</tr>
	</thead>
	<tbody>
		@php
			$sn=1;

		@endphp
		@foreach ($payroll->payroll_details as $detail)
		@php
			$allowances=0;
			$deductions=0;
			
		@endphp
			<tr>
				<td>{{$sn}}</td>
			<td>{{$detail->user->emp_num}}</td>
			<td>{{$detail->user->name}}</td>
			<td>{{$detail->gross_pay}}</td>
			<td>{{$detail->basic_pay}}</td>
			@php
			$pdetails=unserialize($detail->sc_details);
			// $num=count($days);
			@endphp
			@foreach($payroll->salary_components as $component)
			<td>
				@if($component->type==1 && isset($pdetails['sc_allowances'][$component->constant]))
				@php
					$allowances+=$pdetails['sc_allowances'][$component->constant];
				@endphp
				{{isset($pdetails['sc_allowances'][$component->constant])?$pdetails['sc_allowances'][$component->constant]:""}}
				@elseif($component->type==0 && isset($pdetails['sc_deductions'][$component->constant]))
				@php
					$deductions+=$pdetails['sc_deductions'][$component->constant];
				@endphp
				{{isset($pdetails['sc_deductions'][$component->constant])?$pdetails['sc_deductions'][$component->constant]:""}}
				@endif
			</td>
			@endforeach
			<td>{{$detail->ssc_allowances}}</td>
			<td>{{$detail->ssc_deductions}}</td>
			<td>{{$detail->paye}}</td>
			<td>{{($detail->basic_pay+$allowances)-($deductions+$detail->paye)}}</td>
			</tr>
			@php
				$sn++;
			@endphp
			@endforeach
		
	</tbody>
</table>