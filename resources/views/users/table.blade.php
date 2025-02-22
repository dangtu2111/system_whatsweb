<div class="table-responsive">
    <table class="table" id="users-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Verified At</th>
                <th>Created At</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{!! $user->name !!}</td>
                <td>{!! $user->email !!}</td>
                <td>{!! user_type_html($user->type) !!}</td>
                <td>{!! $user->email_verified_at ? date('Y-m-d', strtotime($user->email_verified_at)) : '<div class="badge badge-warning">Unverified</div>' !!}</td>
                <td>{!! $user->created_at->diffForHumans() !!}</td>
                <td>
                    {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'data-id' => $user->id]) !!}
                    <div class='btn-group'>
                        <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-sm btn-primary'>Edit</a>
                        {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'data-confirm' => "Wait wait wait|This action <b>CANNOT</b> be undone, do you want to continue?", 'data-confirm-yes' => "$('form[data-id=\'".$user->id."\']').submit();"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
