@extends('layouts.app', ['title' => 'Post'])

@section('content')
	<section class="section">
		<div class="section-header">
			<div class="section-header-back">
			  <a href="{!! route('posts.index') !!}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
			</div>
			<h1>Post</h1>
		</div>

		@include('adminlte-templates::common.errors')

		<div class="section-body">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4>Edit Post</h4>
						</div>
						<div class="card-body">
							<div class="row"> 
								{!! Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'patch']) !!}

									  @include('posts.fields')

								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection