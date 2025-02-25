function result(res) {
	let modal = $("#modal-result");
	modal.modal({
		backdrop: 'static'
	});
	let responseData = res.data.data;

	// Kiểm tra nếu responseData là một mảng
	if (Array.isArray(responseData)) {
		modal.find('textarea[name="raw_url"]').val(
			responseData.flatMap(item => item.generated_link.map(link => `https://${link}`)).join("\n")
		);
	} else if (typeof responseData === "object" && responseData !== null) {
		// Nếu data là một object có thuộc tính `generated_link`
		modal.find('textarea[name="raw_url"]').val(
			responseData.generated_link.map(link => `https://${link}`).join("\n")
		);
	}



	setTimeout(function() {
		modal.find('textarea[name="raw_url"]').focus();
	}, 400);
	modal.find('input[name="html_link"]').val(res.data.data.html_link);
	modal.find('#qrcode-image').attr('src', res.data.data.qrcode);
	modal.find('#qrcode-save').attr('href', res.data.data.qrcode_save);
	modal.find('#share-link-facebook').attr('href', res.data.data.share_facebook);
	modal.find('#share-link-twitter').attr('href', res.data.data.share_twitter);
	modal.find('#share-link-whatsapp').attr('href', res.data.data.share_whatsapp);
	modal.find('#share-link-telegram').attr('href', res.data.data.share_telegram);
}
