function result(res) {
	let modal = $("#modal-result");
	modal.modal({
		backdrop: 'static'
	});
	modal.find('input[name="raw_url"]').val(res.data.data.generated_link);
	setTimeout(function() {
		modal.find('input[name="raw_url"]').focus();
	}, 400);
	modal.find('input[name="html_link"]').val(res.data.data.html_link);
	modal.find('#qrcode-image').attr('src', res.data.data.qrcode);
	modal.find('#qrcode-save').attr('href', res.data.data.qrcode_save);
	modal.find('#share-link-facebook').attr('href', res.data.data.share_facebook);
	modal.find('#share-link-twitter').attr('href', res.data.data.share_twitter);
	modal.find('#share-link-whatsapp').attr('href', res.data.data.share_whatsapp);
	modal.find('#share-link-telegram').attr('href', res.data.data.share_telegram);
}
