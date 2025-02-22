@extends('layouts.app', ['title' => 'Settings'])

@section('plugins_css')
	<link rel="stylesheet" href="{{asset('dist/modules/codemirror/lib/codemirror.css')}}">
	<link rel="stylesheet" href="{{asset('dist/modules/codemirror/theme/duotone-dark.css')}}">
	<link rel="stylesheet" href="{{ asset('dist/modules/select2/dist/css/select2.min.css') }}">
	{!! midia_css() !!}
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Settings</h1>
        </div>

        @include('flash::message')

        <div class="section-body">
            <div class="row">
                <div class="col-lg-4">
                	<div class="card">
                		<div class="card-header">
                			<h4>Settings</h4>
                		</div>
                		<div class="card-body">
		                	<ul class="nav nav-pills flex-column">
		                		<li class="nav-item">
		                			<a href="{{ route('settings.index', ['type' => 'general']) }}" class="nav-link{{$type == 'general' ? ' active' : ''}}">
		                				General
		                			</a>
		                			<a href="{{ route('settings.index', ['type' => 'features']) }}" class="nav-link{{$type == 'features' ? ' active' : ''}}">
		                				Features
		                			</a>
		                			<a href="{{ route('settings.index', ['type' => 'seo']) }}" class="nav-link{{$type == 'seo' ? ' active' : ''}}">
		                				SEO
		                			</a>
		                			<a href="{{ route('settings.index', ['type' => 'integration']) }}" class="nav-link{{$type == 'integration' ? ' active' : ''}}">
		                				Integration
		                			</a>
		                		</li>
		                	</ul>
                		</div>
                	</div>
                </div>
                <div class="col-lg-8">
                	<form method="post" action="{!! route('settings.update', $type) !!}" id="setting-form" enctype="multipart/form-data">
                		@csrf
                		{!! method_field('PUT') !!}
	                    <div class="card">
	                        <div class="card-header">
	                            <h4>{{ucwords($type)}}</h4>
	                        </div>
	                        <div class="card-body">
	                            @if($type == 'general')
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="site_name">Site Name</label>
	                            		<div class="col-md-10">
	                            			<input type="text" name="site_name" class="form-control" id="site_name" value="{!! setting($type . '.site_name') !!}">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="site_tagline">Tagline</label>
	                            		<div class="col-md-10">
	                            			<input type="text" name="site_tagline" class="form-control" id="site_tagline" value="{!! setting($type . '.site_tagline') !!}">
	                            		</div>
	                            	</div>
									<div class="form-group row">
										<label class="col-md-2 col-form-label text-md-right text-left" for="site_country">Country</label>
										<div class="col-md-10">
											<select class="form-control select2" name="site_country" id="site_country">
												@foreach(phone_codes()  as $phone)
													<option value="{{ $phone['dial_code'] }}"{!! setting($type . '.site_country') == $phone['dial_code'] ? ' selected' : '' !!}>
														{{ $phone['name'] .'(' . $phone['dial_code'] . ')' }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="site_logo">Site Logo</label>
	                            		<div class="col-md-10">
	                            			<div class="input-group">
		                            			<input type="text" name="site_logo" class="form-control" id="site_logo" value="{{ setting('general.site_logo') }}">
		                            			<div class="input-group-append">
		                            				<button type="button" class="btn btn-primary midia" data-input="site_logo">Pick File</button>
		                            			</div>
	                            			</div>
	                            		</div>
	                            	</div>
	                        	@elseif($type == 'features')
		                        	<div class="section-title mt-0">Social Login</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_lw_google">Login with Google</label>
	                            		<div class="col-md-9">
	                            			<select class="form-control" id="features_lw_google" name="login_with_google">
	                            				<option value="1" {{ setting('features.login_with_google') == 1 ? 'selected' : '' }}>Active</option>
	                            				<option value="0" {{ setting('features.login_with_google') == 0 ? 'selected' : '' }}>Not Active</option>
	                            			</select>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_lw_facebook">Login with Facebook</label>
	                            		<div class="col-md-9">
	                            			<select class="form-control" id="features_lw_facebook" name="login_with_facebook">
	                            				<option value="1" {{ setting('features.login_with_facebook') == 1 ? 'selected' : '' }}>Active</option>
	                            				<option value="0" {{ setting('features.login_with_facebook') == 0 ? 'selected' : '' }}>Not Active</option>
	                            			</select>
	                            		</div>
	                            	</div>

		                        	<div class="section-title">Register</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_register">Open Register?</label>
	                            		<div class="col-md-9">
	                            			<select class="form-control" id="features_register" name="open_register">
	                            				<option value="1" {{ setting('features.open_register') == 1 ? 'selected' : '' }}>Active</option>
	                            				<option value="0" {{ setting('features.open_register') == 0 ? 'selected' : '' }}>Not Active</option>
	                            			</select>
	                            		</div>
	                            	</div>

		                        	<div class="section-title">Generator</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_custom_slug">Custom URL</label>
	                            		<div class="col-md-9">
	                            			<select class="form-control" id="features_custom_slug" name="custom_slug">
	                            				<option value="1" {{ setting('features.custom_slug') == 1 ? 'selected' : '' }}>Active</option>
	                            				<option value="0" {{ setting('features.custom_slug') == 0 ? 'selected' : '' }}>Not Active</option>
	                            			</select>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_custom_slug_min">Custom URL Min.</label>
	                            		<div class="col-md-9">
	                            			<input value="{!! setting('features.custom_slug_min') !!}" type="number" class="form-control" id="features_custom_slug_min" name="custom_slug_min">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_custom_slug_max">Custom URL Max.</label>
	                            		<div class="col-md-9">
	                            			<input value="{!! setting('features.custom_slug_max') !!}" type="number" class="form-control" id="features_custom_slug_max" name="custom_slug_max">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_qrcode_size">QR Code Size</label>
	                            		<div class="col-md-9">
	                            			<input value="{!! setting('features.qr_code_size') !!}" type="number" class="form-control" id="features_qrcode_size" name="qr_code_size">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_shortlink_button">Shortlink Button Image</label>
	                            		<div class="col-md-9">
	                            			<div class="input-group">
		                            			<input type="text" name="shortlink_button_image" class="form-control" id="shortlink_button_image" value="{{ setting('features.shortlink_button_image') }}">
		                            			<div class="input-group-append">
		                            				<button type="button" class="btn btn-primary midia" data-input="shortlink_button_image">Pick File</button>
		                            			</div>
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_shortlink_button_alt">Shortlink Button Alt</label>
	                            		<div class="col-md-9">
	                            			<input value="{!! setting('features.shortlink_button_alt') !!}" type="text" class="form-control" id="features_shortlink_button_alt" name="shortlink_button_alt">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_whatsapp_button">WhatsApp Button Image</label>
	                            		<div class="col-md-9">
	                            			<div class="input-group">
		                            			<input type="text" name="whatsapp_button_image" class="form-control" id="whatsapp_button_image" value="{{ setting('features.whatsapp_button_image') }}">
		                            			<div class="input-group-append">
		                            				<button type="button" class="btn btn-primary midia" data-input="whatsapp_button_image">Pick File</button>
		                            			</div>
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-3 text-left text-md-right" for="features_whatsapp_button_alt">WhatsApp Button Alt</label>
	                            		<div class="col-md-9">
	                            			<input value="{!! setting('features.whatsapp_button_alt') !!}" type="text" class="form-control" id="features_whatsapp_button_alt" name="whatsapp_button_alt">
	                            		</div>
	                            	</div>
	                        	@elseif($type == 'seo')
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="seo_desc">Description</label>
	                            		<div class="col-md-10">
	                            			<textarea class="form-control" name="description" id="seo_desc">{!! setting('seo.description') !!}</textarea>
	                            			<div class="form-text">
	                            				Tell search engine about your site.
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="seo_keywords">Keywords</label>
	                            		<div class="col-md-10">
	                            			<textarea class="form-control" name="keywords" id="seo_keywords">{!! setting('seo.keywords') !!}</textarea>
	                            			<div class="form-text">
	                            				Keywords you want to use, separated by commas. Avoid using keywords that are less relevant.
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="seo_home_h1">Home H1</label>
	                            		<div class="col-md-10">
	                            			<input type="text" class="form-control" name="home_h1" id="seo_home_h1" value="{!! setting('seo.home_h1') !!}">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="seo_home_description">Home Description</label>
	                            		<div class="col-md-10">
	                            			<input type="text" class="form-control" name="home_description" id="seo_home_description" value="{!! setting('seo.home_description') !!}">
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="seo_image">Image Preview</label>
	                            		<div class="col-md-10">
	                            			<div class="input-group">
		                            			<input type="text" name="image" class="form-control" id="seo_image" value="{{ setting('seo.image') }}">
		                            			<div class="input-group-append">
		                            				<button type="button" class="btn btn-primary midia" data-input="seo_image">Pick File</button>
		                            			</div>
	                            			</div>
	                            		</div>
	                            	</div>
	                        	@elseif($type == 'integration')
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="integration_analytics">Google Analytics</label>
	                            		<div class="col-md-8">
	                            			<textarea name="google_analytics" class="form-control codeeditor" id="integration_analytics">{!! setting('integration.google_analytics') !!}</textarea>
	                            			<div class="form-text">
	                            				Your Google Analytics Tracking Code. <a href="https://support.google.com/analytics/answer/7476135?hl=en" target="_blank">How to get the code?</a>
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="integration_fbpixel">Facebook Pixel</label>
	                            		<div class="col-md-8">
	                            			<textarea name="facebook_pixel" class="form-control codeeditor" id="integration_fbpixel">{!! setting('integration.facebook_pixel') !!}</textarea>
	                            			<div class="form-text">
	                            				Your Facebook Pixel Code. <a href="https://web.facebook.com/business/help/314143995668266?_rdc=1&_rdr" target="_blank">How to get the code?</a>
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="integration_beforehead">Code Before the <code>&lt;/head></code> tag</label>
	                            		<div class="col-md-8">
	                            			<textarea name="before_head" class="form-control codeeditor" id="integration_beforehead">{!! setting('integration.before_head') !!}</textarea>
	                            			<div class="form-text">
	                            				You can integrate something else, such as Crisp, Tawk.to, Userback, or the like. Place your code in the field above and it will be placed before the &lt;/head> tag.
	                            			</div>
	                            		</div>
	                            	</div>
	                            	<div class="form-group row">
	                            		<label class="col-md-2 text-left text-md-right" for="integration_beforebody">Code Before the <code>&lt;/body></code> tag</label>
	                            		<div class="col-md-8">
	                            			<textarea name="before_body" class="form-control codeeditor" id="integration_beforebody">{!! setting('integration.before_body') !!}</textarea>
	                            			<div class="form-text">
	                            				You can integrate something else, such as Crisp, Tawk.to, Userback, or the like. Place your code in the field above and it will be placed before the &lt;/body> tag.
	                            			</div>
	                            		</div>
	                            	</div>
	                            @endif
	                        </div>
	                        <div class="card-footer bg-whitesmoke text-right">
						        @if(is_demo())
	                        	<button type="button" class="btn btn-primary disabled">Save Changes</button>
	                        	@else
	                        	<button type="submit" class="btn btn-primary">Save Changes</button>
	                        	@endif
	                        </div>
	                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('plugins_js')
<script src="{{asset('dist/modules/codemirror/lib/codemirror.js')}}"></script>
<script src="{{asset('dist/modules/codemirror/mode/javascript/javascript.js')}}"></script>
<script src="{{asset('dist/modules/upload-preview/assets/js/jquery.uploadPreview.min.js')}}"></script>
<script src="{{ asset('dist/modules/select2/dist/js/select2.min.js') }}"></script>
<script src="{{asset('dist/modules/sweetalert/sweetalert.min.js')}}"></script>
{!! midia_js() !!}
@stop

@section('scripts')
<script>
	$(".midia").midia({
		base_url: '{{ url('') }}',
		file_name: 'fullname'
	});

	$.uploadPreview({
	  input_field: "#image-upload",   // Default: .image-upload
	  preview_box: "#image-preview",  // Default: .image-preview
	  label_field: "#image-label",    // Default: .image-label
	  label_default: "Choose File",   // Default: Choose File
	  label_selected: "Change File",  // Default: Change File
	  no_label: false,                // Default: false
	  success_callback: null          // Default: null
	});

	$("#setting-form").submit(function() {
		let me = $(this),
			formdata = new FormData(this);

			console.log(formdata.entries())
		$.ajax({
			url: me.attr('action'),
			type: 'POST',
			data: formdata,
			processData: false,
			contentType: false,
			cache: false,
			headers: {
				'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),
			},
			beforeSend: function() {
				$.cardProgress(me.find('.card'));
			},
			success: function(res) {
				if(res.success == true) {
					swal('Success', res.message, 'success');
				}
			},
			complete: function() {
				$.cardProgressDismiss(me.find('.card'));
			},
			error: function(err) {
				console.log(err)
				if(err.status == 422) {
					let errors = err.responseJSON.errors,
						first_key = Object.keys(errors)[0],
						first_error = errors[first_key][0];

					swal('Aw! You missed something.', first_error, 'error');
				}else{
					swal('Aw! There is something went wrong.', 'Please check your internet connection or contact administration.', 'error');
				}
			}
		})

		return false;
	});
</script>
@stop