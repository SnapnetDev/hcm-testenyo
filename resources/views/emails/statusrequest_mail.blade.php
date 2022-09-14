A request has been made for the following Staff status to be changed
to 
@if($data['status']=='1')
    Active
@elseif($data['status']=='2')
    Suspended
@elseif($data['status']=='3')
    Resigned
@elseif($data['status']=='4')
    Disengaged
@endif
<br>
{{  $data['mail'] }}
