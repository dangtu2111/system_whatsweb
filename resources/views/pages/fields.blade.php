<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control title']) !!}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control slug']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control summernote']) !!}
</div>

<!-- Show In Menu Field -->
<div class="form-group col-sm-6">
    {!! Form::label('show_in_menu', 'Show In Menu:') !!}
    {!! Form::select('show_in_menu', [
        1 => 'Yes',
        0 => 'No'
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Sort Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sort', 'Sort:') !!}
    {!! Form::number('sort', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-md-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('pages.index') !!}" class="btn btn-default">Cancel</a>
</div>

@section('plugins_css')
<link rel="stylesheet" href="{{ url('dist/modules/summernote/summernote-bs4.css') }}">
@stop
@section('plugins_js')
<script src="{{ url('dist/modules/summernote/summernote-bs4.js') }}"></script>
@stop
@section('scripts')
<script>
    $(".title").on("change keyup blur paste",function() {
        var res = $(this).val().trim().toLowerCase();
        res = res.replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        $(".slug").val(res);
    });
</script>
@stop