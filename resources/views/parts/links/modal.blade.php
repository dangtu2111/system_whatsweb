	<div class="modal fade" tabindex="-1" role="dialog" id="modal-result">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fas fa-check modal-icon bg-success text-white shadow-success"></i> Your link generated successfully</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<ul class="nav nav-pills mt-3 nav-justified">
						<li class="nav-item mr-2"><a href="#result-raw" data-toggle="tab" class="nav-link active">Raw</a></li>
						<li class="nav-item mr-2"><a href="#result-html" data-toggle="tab" class="nav-link">HTML</a></li>
						<li class="nav-item mr-2"><a href="#result-qrcode" data-toggle="tab" class="nav-link">QR Code</a></li>
						<li class="nav-item"><a href="#result-share" data-toggle="tab" class="nav-link">Share</a></li>
					</ul>
					<div class="tab-content mt-2">
						<div class="tab-pane active" id="result-raw">
							<p>Copy the following URL, and paste it wherever you like.</p>
							<div class="input-group">
								<textarea type="text" height="auto" class="form-control" name="raw_url" id="raw_url" readonly="" onfocus="this.setSelectionRange(0, this.value.length)"></textarea>
								<div class="input-group-append">
									<button data-clipboard-target="#raw_url" class="btn btn-primary">Copy</button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="result-html">
							<p>Copy the following URL, and paste it wherever you like.</p>
							<div class="input-group">
								<input type="text" class="form-control" id="html_link" name="html_link" readonly="" onfocus="this.setSelectionRange(0, this.value.length)">
								<div class="input-group-append">
									<button data-clipboard-target="#html_link" class="btn btn-primary">Copy</button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="result-qrcode">
							<div class="text-center">
								<p>Download the following QR Code image.</p>
								<img src="" id="qrcode-image">
								<div class="mt-2">								
									<a href="" class="btn btn-primary" id="qrcode-save">
										Download Image
									</a>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="result-share">
							<div class="row">
								<div class="text-center col-lg-8 offset-lg-2">
									<p>You can share this link with your social media account.</p>
									<a href="" id="share-link-facebook" class="btn btn-social btn-block mb-3 btn-primary"><i class="fab fa-facebook-f"></i> Facebook</a>
									<a href="" id="share-link-twitter" class="btn btn-social btn-block mb-3 btn-twitter"><i class="fab fa-twitter"></i> Twitter</a>
									<a href="" id="share-link-whatsapp" class="btn btn-social btn-block mb-3 btn-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
									<a href="" id="share-link-telegram" class="btn btn-social btn-block mb-3 btn-info"><i class="fab fa-telegram"></i> Telegram</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
