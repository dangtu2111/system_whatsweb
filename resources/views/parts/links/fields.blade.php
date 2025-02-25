						<div class="row" id="create-wa-link">
							<div class="{{(request()->type ?? @$the_type ?? $link->type) == 'WHATSAPP' ? 'col-md-6 col-lg-7 mt-0 mt-lg-4' : 'col-lg-10 mt-0 mt-lg-4'}}">
								@if(isset($link))
								<form action="{{ route_type('links.update', [$id]) }}" method="post" id="link-form">
								{!! method_field('PUT') !!}
								@else
								<form action="{{ route_type('links.store') }}" method="post" id="link-form">
								@endif
								@if(isset($show_type))
								<div class="form-group row">
									<div class="col-lg-4"></div>
									<div class="col-lg-8">
										<a href="{{ route(request()->route()->getName(), ['type' => 'WHATSAPP']) }}" class="btn btn-icon icon-left btn-{{(request()->type ?? @$the_type ?? $link->type) == 'SHORTLINK' ? 'outline-' : ''}}success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
										<a href="{{ route(request()->route()->getName(), ['type' => 'SHORTLINK']) }}" class="btn btn-icon icon-left btn-{{(request()->type ?? @$the_type ?? $link->type) == 'WHATSAPP' ? 'outline-' : ''}}primary"><i class="fas fa-link"></i> Shorten Link</a>
									</div>
								</div>
								@endif
								@if((request()->type ?? @$the_type ?? $link->type) == 'WHATSAPP')
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="phone">Phone Code</label>
										<div class="col-lg-8">
											<select class="form-control select2" name="phone_code" id="phone_code">
												@foreach(phone_codes()  as $phone)
													<option value="{{ $phone['dial_code'] }}" {{ $phone['dial_code'] == (isset($link) ? $link->phone_code : setting('general.site_country')) ? 'selected' : '' }}>
														{{ $phone['name'] .'(' . $phone['dial_code'] . ')' }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="phone_number">Phone Number</label>
										<div class="col-lg-8">
											<input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="e.g 812345678" maxlength="15" value="{{ isset($link) ? str_replace(optional($link)->phone_code, '', optional($link)->phone_number) :'' }}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="content">Message</label>
										<div class="col-lg-8">
								            <div class="toolbar">
								                <div class="item" data-tool="bold"><i class="fas fa-bold"></i></div>
								                <div class="item" data-tool="italic"><i class="fas fa-italic"></i></div>
								                <div class="item" data-tool="strikethrough"><i class="fas fa-strikethrough"></i></div>
								                <div class="item" data-tool="code"><i class="fas fa-code"></i></div>
								            </div>
											<textarea name="content" class="form-control" id="content" style="height: 200px !important;">{!! isset($link) ? optional($link)->content : 'Your message here ...' !!}</textarea>
											<div class="form-text">
												Learn more about <a href="https://faq.whatsapp.com/en/android/26000002/" target="_blank">formatting your message</a> on WhatsApp
											</div>
										</div>
									</div>
									@else
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="url">URL</label>
										<div class="col-lg-8">
											<input type="text" name="url" class="form-control" id="url" value="{!! isset($link) ? optional($link)->url : '' !!}">
											<div class="form-text">
												The URL you want to shorten
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="url">Name</label>
										<div class="col-lg-8">
											<input type="text" name="name_phone" class="form-control" id="url" value="{!! isset($link) ? optional($link)->phone_number : '' !!}">
											<div class="form-text">
												The name you want to give to the link
											</div>
										</div>
									</div>
									@if(!isset($link))
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="url">Number</label>
										<div class="col-lg-8">
											<input type="number" name="number" class="form-control" id="number" value="">
											<div class="form-text">
												The number you want to generate to the link
											</div>
										</div>
									</div>
									@endif
									@endif
									@if(setting('features.custom_slug'))
									<div class="form-group row">
										<label class="col-lg-4 col-form-label text-lg-right text-left" for="slug">Custom Slug</label>
										<div class="col-lg-8">
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">
														{!! url('') . '/' !!}
													</div>
												</div>
												<input type="text" name="slug" class="form-control" id="slug" minlength="{!! setting('features.custom_slug_min') !!}" maxlength="{!! setting('features.custom_slug_max') !!}" placeholder="MY_LINK" value="{!! isset($link) ? optional($link)->slug : '' !!}">
												<div class="form-text">
													by default, we generate a unique URL. However, you can use your own character for human-readable URLs.
												</div>
											</div>
										</div>
									</div>
									@endif
									<div class="form-group row">
										<div class="col-lg-4"></div>
										<div class="col-lg-8">
											<button class="btn btn-primary">
												{!! $title !!}
											</button>
											<a href="#" class="ml-4" id="view-last" style="display: none;">View Last Generated Link</a>
										</div>
									</div>
									@if(!auth()->check())
									<div class="form-group row">
										<div class="col-lg-4"></div>
										<div class="col-lg-8">
											<i class="fas fa-exclamation-triangle mr-1"></i> Get your link statistics by <a href="{{ route('register') }}">creating an account</a>.
										</div>
									</div>
									@endif
								</form>
							</div>
							@if((request()->type ?? @$the_type ?? $link->type) == 'WHATSAPP')
							<div class="col-md-5 text-center">
						        <div class="phone text-left">
						            <div class="chat">
						        	<div class="chat-change d-flex justify-content-center" data-now="sent">
						        		<a href="#" class="btn btn-primary" data-value="received">Receiver</a>
						        		<a href="#" class="btn btn-success" data-value="sent">Sender</a>
						        	</div>
						              <div class="chat-container">
						                <div class="user-bar">
						                  <div class="back">
						                    <i class="zmdi zmdi-arrow-left"></i>
						                  </div>
						                  <div class="avatar">
						                    <img src="{{url('dist/img/avatar/avatar-1.png')}}" alt="Avatar">
						                  </div>
						                  <div class="name" id="number">
						                    <span>+1234567</span>
						                  </div>
						                  <div class="actions img">
						                  </div>
						                </div>
						                <div class="conversation">
						                  <div class="conversation-container">
						                    <div class="message sent">
						                    <span id="message"></span>
						                      <span class="metadata">
						                          <span class="time">{{date('h:i A')}}</span><span class="tick"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"/></svg></span>
						                      </span>
						                    </div>
						                  </div>
						                </div>
						              </div>
						            </div>            
						        </div>
							</div>
							@endif
						</div>
