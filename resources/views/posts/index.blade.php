@extends('layouts.app', ['title' => 'Posts'])

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Posts</h1>
            <div class="section-header-button">
                <a href="{!! route('posts.create') !!}" class="btn btn-primary">Add New</a>
            </div>
        </div>

        @include('flash::message')

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Posts</h4>
                        </div>
                        <div class="card-body">
                            @include('posts.table')
                        </div>
                        <div class="card-footer bg-whitesmoke">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

