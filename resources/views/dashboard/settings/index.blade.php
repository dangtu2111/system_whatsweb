@extends('layouts.app_frontend', ['title' => 'Settings'])

@section('content')
	<section class="section">
		<div class="container">
			<div class="section-body">
				<div class="card card-primary">
					<div class="card-header">
						<h4>Settings</h4>
					</div>
					<div class="card-body">
						<form action="{{ route('dashboard.settings.update') }}" method="put" id="setting-form">
							<div class="form-group row">
								<label class="col-md-4 col-form-label text-md-right text-left" for="name">Name</label>
								<div class="col-md-5">
									<input type="text" class="form-control" name="name" value="{{ user()->name }}" id="name">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-4 col-form-label text-md-right text-left" for="email">Email</label>
								<div class="col-md-5">
									<div class="form-control" id="email">{{ user()->email }}</div>
									<div class="form-text">Email can't be changed</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-4 col-form-label text-md-right text-left" for="password">Password</label>
								<div class="col-md-5">
									<input type="password" class="form-control" name="password" id="password">
									<div class="form-text">
										Leave if not change
									</div>
								</div>
							</div>
							<div class="form-group row align-items-center">
								<div class="col-md-4 col-form-label text-md-right text-left"></div>
								<div class="col-md-5">
									@if(config('whatsweb.demo') && user()->id == 1)
									<button class="btn btn-primary disabled" type="button">Save Changes</button>
									@else
									<button class="btn btn-primary">Save Changes</button>
									@endif
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop

@section('plugins_js')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('dist/modules/sweetalert/sweetalert.min.js')}}"></script>
@stop

@section('scripts')
<script>
	$("#setting-form").submit(function() {
		let me = $(this);

		$.cardProgress(me.closest('.card'));

		axios.put(me.attr('action'), me.serialize())
		.then(function(res) {
			$.cardProgressDismiss(me.closest('.card'));

			if(res.data.success == true) {
				swal('Success', res.data.message, 'success');
			}
		})
		.catch(function(err) {
			$.cardProgressDismiss(me.closest('.card'));
			if(err.response.status == 422) {
				let errors = err.response.data.errors,
					first_key = Object.keys(errors)[0],
					first_error = errors[first_key][0];

				swal('Aw! You missed something.', first_error, 'error');
			}
		});
		return false;
	});
</script>
@stop