@extends('layouts.app', ['title' => 'Page'])

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
              <a href="{!! route('pages.index') !!}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Page</h1>
        </div>

        @include('adminlte-templates::common.errors')

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Page</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {!! Form::open(['route' => ['pages.store'], 'method' => 'post']) !!}

                                    @include('pages.fields')

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection