@extends('layouts.app', ['title' => 'User'])

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
              <a href="{!! route('users.index') !!}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>User</h1>
        </div>

        @include('adminlte-templates::common.errors')

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create User</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {!! Form::open(['route' => ['users.store'], 'method' => 'post']) !!}

                                    @include('users.fields')

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection