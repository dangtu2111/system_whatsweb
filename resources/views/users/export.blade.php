<table>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Type</th>
		<th>Verified At</th>
		<th>Created At</th>
	</tr>
	@foreach($users as $user)
	<tr>
		<td>{!! $user->name !!}</td>
		<td>{!! $user->email !!}</td>
		<td>{!! $user->type !!}</td>
		<td>{!! $user->email_verified_at !!}</td>
		<td>{!! $user->created_at !!}</td>
	</tr>
	@endforeach
</table>