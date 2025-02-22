@extends('layouts.app', ['title' => 'Pages'])

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pages</h1>
            <div class="section-header-button">
                <a href="{!! route('pages.create') !!}" class="btn btn-primary">Add New</a>
            </div>
        </div>

        @include('flash::message')

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pages</h4>
                        </div>
                        <div class="card-body">
                            @include('pages.table')
                        </div>
                        @if(count($pages) > 9)
                        <div class="card-footer bg-whitesmoke">
                            {!! $pages->links() !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

