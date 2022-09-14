<table class="table table-striped">
	<thead>
		<tr>
			<th>Exemption Type</th>
			<th>Reason</th>
		</tr>
	</thead>
	<tbody>
		@foreach($exemptions as $exemption)
		<tr>
			<td>{{ $exemption->type }}</td>
			<td>{{ $exemption->reason }}</td>
		</tr>
		@endforeach
	</tbody>
</table>