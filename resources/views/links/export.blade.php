<table>
	<tr>
		<th>User</th>
		<th>Phone Number</th>
		<th>Phone Code</th>
		<th>Type</th>
		<th>Hit</th>
		<th>URL</th>
		<th>Content</th>
		<th>Created At</th>
	</tr>
	@foreach($links as $link)
	<tr>
		<td>{!! $link->user->name !!}</td>
		<td>{!! $link->phone_number !!}</td>
		<td>{!! $link->phone_code !!}</td>
		<td>{!! $link->type !!}</td>
		<td>{!! $link->hit !!}</td>
		<td>{!! route('slug', $link->slug) !!}</td>
		<td>{!! $link->content !!}</td>
		<td>{!! $link->created_at !!}</td>
	</tr>
	@endforeach
</table>