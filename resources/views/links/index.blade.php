@extends('layouts.' . layout(), ['title' => 'Manage Links'])

@section('plugins_css')
<link rel="stylesheet" href="{{asset('')}}dist/modules/bootstrap-social/bootstrap-social.css">
<link rel="stylesheet" href="{{ asset('dist/modules/select2/dist/css/select2.min.css') }}">
@stop

@section('content')
	<section class="section">
		@if(is_backend())
		<div class="section-header">
			<h1>Links</h1>
            <div class="section-header-button ml-4 ml-md-auto">
                <div class="dropdown">
                    <a href="#" class="btn btn-danger btn-icon icon-left dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-export"></i> Export</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-title">Export Format</div>
                        <a href="{{ route('links.export', ['format' => 'xlsx', 'type' => request()->type, 'user' => request()->user]) }}" class="dropdown-item has-icon"><i class="far fa-file-excel"></i> Xlsx</a>
                        <a href="{{ route('links.export', ['format' => 'csv', 'type' => request()->type, 'user' => request()->user]) }}" class="dropdown-item has-icon"><i class="fas fa-file-csv"></i> CSV</a>
                        <a href="{{ route('links.export', ['format' => 'dompdf', 'type' => request()->type, 'user' => request()->user]) }}" class="dropdown-item has-icon"><i class="far fa-file-pdf"></i> PDF</a>
                        <a href="{{ route('links.export', ['format' => 'html', 'type' => request()->type, 'user' => request()->user]) }}" class="dropdown-item has-icon"><i class="fab fa-html5"></i> HTML</a>
                    </div>
                </div>
            </div>
		</div>
		@endif
		@if(!is_backend())
		<div class="container">
		@endif
			<div class="section-body">
				<div class="row">
					<div class="col-lg-3">
						<div class="card">
							<div class="card-body">
								<ul class="nav nav-pills flex-column">
									<li class="nav-item">
										<a class="nav-link{{$type == '' ? ' active' : ''}}" href="{{ route_type('links.index') }}">
											<i class="fas fa-list"></i> All Links
										</a>
									</li>
									@foreach(link_types() as $t => $link)
									<li class="nav-item">
										<a class="nav-link has-icon{{$t == $type ? ' active' : ''}}" href="{{ route_type('links.index',['type' => $t]) }}">
											<i class="{{ $link['icon'] }}"></i>
											{{ $link['text'] }}
										</a>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
						@if(is_backend())
						<div class="card">
							<div class="card-body">
								<form action="{{ request()->fullUrlWithQuery([]) }}">
									{!! Form::hidden('type', request()->type) !!}
									<div class="form-group">
										<label for="user">User</label>
										<select class="form-control select2" name="user">
											<option value="">All</option>
											@foreach(App\User::all() as $user)
											<option value="{{$user->id}}" {{ request()->user == $user->id ? 'selected' : '' }}>{{$user->name}}</option>
											@endforeach
										</select>
									</div>
									<button type="submit" class="btn btn-primary">Filter</button>
								</form>
							</div>
						</div>
						@endif
					</div>
					<div class="col-lg-9">
						<div class="card card-primary">
							<div class="card-header">
								<h4>Links</h4>
								<div class="card-header-action">
									<div class="dropdown">
										<a href="#" class="btn btn-primary btn-lg btn-icon icon-left has-dropdown dropdown-toggle" data-toggle="dropdown">
											<i class="fas fa-link"></i> Create New Link
										</a>
										<div class="dropdown-menu">
											@foreach(link_types() as $t => $link)
											<a href="{{route_type('links.create', ['type' => $t])}}" class="dropdown-item">{{ $link['text'] }}</a>
											@endforeach
										</div>										
									</div>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table table-responsive">
									<table class="table table-striped">
										<tr>
											@if(is_backend())
											<th>User</th>
											@endif
											<th>Name</th>
											<th>Hit</th>
											<th>Type</th>
											<th>Created At</th>
											<th>Action</th>
										</tr>
										@if(count($links))
										@foreach($links as $link)
										@php 
										$id = encrypt($link->id); 
										@endphp
										<tr>
											@if(is_backend())
											<td>{!! optional($link->user)->name ?? 'Guess' !!}</td>
											@endif
											<td>{!! $link->phone_number == '' ? '-' : $link->phone_number !!}</td>
											<td>{!! $link->hit !!}</td>
											<td>{!! link_types()[$link->type]['text'] !!}</td>
											<td>{!! $link->created_at->diffForHumans() !!}</td>
											<td>
												<a href="#" data-id="{{ $id }}" class="btn btn-primary btn-sm view-link">View</a>
												<a href="{{ route_type('links.edit', $id) }}" class="btn btn-light btn-sm">Edit</a>
												<a href="#" class="btn btn-danger btn-sm" data-confirm="Wait wait wait|This action <b>CANNOT</b> be undone, do you want to continue?" data-confirm-yes="$('form[data-id=\'{{$id}}\']').submit();">Delete</a>
												<form action="{!! route_type('links.destroy', $id) !!}" method="post" data-id="{{ $id }}">
													@csrf
													{!! method_field('DELETE') !!}
												</form>
											</td>
										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="6" class="text-center">No data</td>
										</tr>
										@endif
									</table>
								</div>
							</div>
							@if(count($links) > 9)
							<div class="card-footer">
								{!! $links->links() !!}
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		@if(!is_backend())
		</div>
		@endif
	</section>

	@include('parts.links.modal')
@stop

@section('plugins_js')
<script src="{{ asset('dist/modules/axios.min.js') }}"></script>
<script src="{{ asset('dist/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('vendor/midia/clipboard.js') }}"></script>
<script src="{{ asset('dist/modules/select2/dist/js/select2.min.js') }}"></script>
@stop
@section('scripts')
<script>
	new ClipboardJS('.btn');
	

	$(".view-link").click(function() {
		let me = $(this);
		console.log("Button clicked!"); 
		$.cardProgress(me.closest('.card'));
	
		axios.post(
			'{{ secure_url(route_type('links.show'))}}', 
			{ id: me.attr('data-id') }, 
			{
				headers: {
					'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
				}
			}
		)
		.then(function(res) {
			$.cardProgressDismiss(me.closest('.card'));
			result(res);
		})
		.catch(function(err) {
			$.cardProgressDismiss(me.closest('.card'));

			if(err.response.status == 0) {
				swal('Aw! There is something went wrong.', 'Please check your internet connection or contact administration.', 'error');
			}else if(err.response.status == 419) {
				swal('Aw! There is something went wrong.', 'Fetching data failed, please refresh the page or login with your account again.', 'error');
			}
		});

		return false;
	});

	@if(session()->has('delete'))
	swal('Deleted','Your link has been permanently deleted', 'success');
	@endif

	@include('parts.links.modal_js')
</script>
@stop