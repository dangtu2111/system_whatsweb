@extends('layouts.app', ['title' => 'Users'])

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Users</h1>
            <div class="section-header-button">
                <a href="{!! route('users.create') !!}" class="btn btn-primary">Add New</a>
            </div>
            <div class="section-header-button ml-2 ml-md-auto">
                <div class="dropdown">
                    <a href="#" class="btn btn-danger btn-icon icon-left dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-export"></i> Export</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-title">Export Format</div>
                        <a href="{{ route('users.export', ['format' => 'xlsx']) }}" class="dropdown-item has-icon"><i class="far fa-file-excel"></i> Xlsx</a>
                        <a href="{{ route('users.export', ['format' => 'csv']) }}" class="dropdown-item has-icon"><i class="fas fa-file-csv"></i> CSV</a>
                        <a href="{{ route('users.export', ['format' => 'dompdf']) }}" class="dropdown-item has-icon"><i class="far fa-file-pdf"></i> PDF</a>
                        <a href="{{ route('users.export', ['format' => 'html']) }}" class="dropdown-item has-icon"><i class="fab fa-html5"></i> HTML</a>
                    </div>
                </div>
            </div>
        </div>

        @include('flash::message')

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">
                            @include('users.table')
                        </div>
                        @if(count($users) > 9)
                        <div class="card-footer">
                            {!! $users->links() !!}                            
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

