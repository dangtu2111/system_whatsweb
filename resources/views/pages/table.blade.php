<div class="table-responsive">
    <table class="table" id="pages-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Show In Menu</th>
                <th>Sort</th>
                <th>Created At</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{!! $page->title !!}</td>
                <td>{!! ($page->show_in_menu == 1 ? 'Yes' : 'No') !!}</td>
                <td>{!! $page->sort !!}</td>
                <td>{!! $page->created_at->diffForHumans() !!}</td>
                <td>
                    {!! Form::open(['route' => ['pages.destroy', $page->id], 'method' => 'delete', 'data-id' => $page->id]) !!}
                    <div class='btn-group'>
                        <a href="{!! route('pages.edit', [$page->id]) !!}" class='btn btn-sm btn-primary'>Edit</a>
                        {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-sm btn-danger',  'data-confirm' => "Wait wait wait|This action <b>CANNOT</b> be undone, do you want to continue?", 'data-confirm-yes' => "$('form[data-id=\'".$page->id."\']').submit();"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>