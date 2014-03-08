@extends ('layouts.default')

@section('content')
<p>if you can see this, you're logged in </p>

<p>{{ link_to_route('logout', 'Logout') }}</p>
@endsection