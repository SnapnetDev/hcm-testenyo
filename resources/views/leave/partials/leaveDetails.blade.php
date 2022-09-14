{{-- <h4>Employee Name:{{$leave_request->user->name}}</h4>
<h4>Leave Type:{{$leave_request->leave->name}}</h4>
<h4>Start Date:{{date("F j, Y", strtotime($leave_request->start_date))}}</h4>
<h4>End Date:{{date("F j, Y", strtotime($leave_request->end_date))}}</h4>
<h4>Priority:<span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span></h4>
<h4>Approval Status:<span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->statusS==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span></h4>
<h4>With Pay:{{$leave_request->paystatus==0?'without pay':'with pay'}}</h4> --}}
<div class="table-responsive">
	<h4>Approval Details ({{$leave_request->workflow->name}})</h4>
	<p>Uploaded Document: <a href="{{ file_exists(public_path('uploads/leave'.$leave_request->absence_doc))?asset('uploads/leave'.$leave_request->absence_doc):''}}" target="_blank" class="btn btn-info">View</a></p>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Stage</th>
			<th>Approver</th>
			<th>Comment</th>
			<th>Status</th>
			<th>Time in Stage</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach($leave_request->leave_approvals as $approval)
		<tr>
			<td>{{$approval->stage->name}}</td>
			<td>{{$approval->approver_id>0?$approval->approver->name:""}}</td>
			<td>{{$approval->comment}}</td>
			<td><span class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span></td>
			<td>{{ $approval->created_at==$approval->updated_at?\Carbon\Carbon::parse($approval->created_at)->diffForHumans():\Carbon\Carbon::parse($approval->created_at)->diffForHumans($approval->updated_at) }}</td>
		</tr>
		@endforeach
	</tbody>
	
</table>
</div 