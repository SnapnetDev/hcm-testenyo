<table class="table table-striped">
	<thead>
		<tr>
			<th>SHIFT DATE</th>
			<th>EMP NUM</th>
			<th>NAME</th>
			<th>STATION</th>
			<th>SHIFT TYPE</th>
		</tr>
	</thead>
	<tbody>
		@foreach($first_april_data as $data)
		<tr>
			<td>{{ $data->sdate }}</td>
			<td>{{ $data->user->emp_num }}</td>
			<td>{{ $data->user->name }}</td>
			<td>{{ $data->user->company->name }}</td>
			<td>{{ $data->shift->shift_type->name }}</td>
		</tr>
		@endforeach
	</tbody>
</table>