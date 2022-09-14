<img class="img-circle img-bordered img-bordered-blue text-center"
     width="150" height="150" src="{{$data['facial_request']['image_url']}}" alt="..." id='img-upload'>

@if(count($data['users'])>1)
    <h1 class="page-search-title">Matching Staff with Image Uploaded</h1>
    <ul class="list-group list-group-full list-group-dividered">
        @foreach($data['users'] as $user)
            @if(isset($user['user']['name']))
                <li class="list-group-item flex-column align-items-start">
                    <img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ asset('uploads/public/avatar'.$user['user']['image'])}}" alt="..." id='img-upload'>
                    <h4><a href="{{ route('users.edit',$user['user']['id']) }}">{{ $user['user']['name'] }}</a></h4>
                    <p> {{ $user['confidence'] }}</p>
                    <p>{{ $user['user']['company']['name'] }}</p>
                </li>
            @endif
        @endforeach
    </ul>
@else
    <h1 class="page-search-title">No Match Found</h1>
@endif
