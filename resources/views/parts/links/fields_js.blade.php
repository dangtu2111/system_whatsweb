new ClipboardJS('.btn');

@if((request()->type ?? @$the_type ?? $link->type) == 'WHATSAPP')
$(".toolbar .item").click(function() {
    if($(this).data("tool") == 'bold') {
        insertAtCursor(yourTextarea, '*', '*');                
    }
    if($(this).data("tool") == 'italic') {
        insertAtCursor(yourTextarea, '_', '_');                
    }
    if($(this).data("tool") == 'strikethrough') {
        insertAtCursor(yourTextarea, '~', '~');
    }
    if($(this).data("tool") == 'code') {
        insertAtCursor(yourTextarea, '```', '```');
    }
    $("#content").keyup();
});

$("#content").keydown(function(e) {
    if(e.ctrlKey) {
        if(e.keyCode == 66) {
            insertAtCursor(yourTextarea, '*', '*');
            return false;
        }
        if(e.keyCode == 73) {
            insertAtCursor(yourTextarea, '_', '_');
            return false;
        }
        if(e.keyCode == 83) {
            insertAtCursor(yourTextarea, '~', '~');
            return false;
        }
    }
});

function content(val) {
    val = val.replace(/</g, '&lt;');
    val = val.replace(/>/g, '&gt;');
    val = val.replace(/\n/g, '<br>');
    val = val.replace(/\*(.+?)\*/g, '<b>$1</b>');
    val = val.replace(/\~(.+?)\~/g, '<s>$1</s>');
    val = val.replace(/\_(.+?)\_/g, '<i>$1</i>');
    val = val.replace(/\`\`\`(.+?)\`\`\`/g, '<code>$1</code>');
    val = val.replace(/([a-zA-Z0-9]+)\.([a-zA-Z]+)/g, '<a href="http://$1.$2">$1.$2</a>');
    // val = val.replace(/([a-zA-Z0-9]+)\:\/\/([a-zA-Z0-9]+)\.([a-zA-Z0-9]+)\.([a-zA-Z0-9]+)/g, '<a href="$1://$2.$3.$4">$1://$2.$3.$4</a>');
    // val = val.replace(/([a-zA-Z0-9]+)\:\/\/([a-zA-Z0-9]+)\.([a-zA-Z0-9]+)/g, '<a href="$1://$2.$3">$1://$2.$3</a>');
    $("#message").html(val);
}

$("#content").on("keyup", function() {
    var val = $(this).val();
    content(val);
});

content($("#content").val());

var yourTextarea = document.getElementById("content");
var insertAtCursor = function(myField, myValueBefore, myValueAfter) {

    if (document.selection) {

        myField.focus();
        document.selection.createRange().text = myValueBefore + document.selection.createRange().text + myValueAfter;


    } else if (myField.selectionStart || myField.selectionStart == '0') {

        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)+ myValueBefore+ myField.value.substring(startPos, endPos)+ myValueAfter+ myField.value.substring(endPos, myField.value.length);

    } 
}

$("#phone_number").on("keyup", function() {
    $("#number").html($("#phone_code").val() + $(this).val())
});

$("#phone_code").on("change", function() {
    $("#number").html($(this).val() + $("#phone_number").val())
});

$("#phone_number").keyup();

$(".chat-change a").click(function() {
    $(".message").removeClass($(".chat-change").attr("data-now"));
    $(".message").addClass($(this).attr("data-value"));
    $(".chat-change").attr("data-now", $(this).attr("data-value"));
    return false;
});

@endif

$("#link-form").submit(function() {
	let me = $(this);

	$.cardProgress(me.closest('.card'));

	me.append($('<input/>', {
		type: 'hidden',
		name: 'type',
		value: '{{ isset($link) ? $link->type : (request()->type ?? @$the_type ?? $link->type ?? 'WHATSAPP') }}'
	}));

	axios.post(me.attr('action'), me.serialize(), {
		headers: {
			'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
		}
	})
	.then(function(res) {
		$("#view-last").show();
		result(res);
	})
	.catch(function(err) {
		if(err.response.status == 422) {
			let errors = err.response.data.errors,
				first_key = Object.keys(errors)[0],
				first_error = errors[first_key][0];

			swal('Aw! You missed something.', first_error, 'error');
		}else{
			swal('Aw! There is something went wrong.', 'Please check your internet connection or contact administration.', 'error');
		}
	})
	.then(function() {
		$("input[name='type']").remove();
		$.cardProgressDismiss(me.closest('.card'));
	});

	return false;
});

$("input[name=slug]").blur(function(event){
	$(this).val($(this).val().toUpperCase());
});
$("input[name=slug]").keydown(function(event){
    var ew = event.keyCode;
    if(ew == 8)
        return true;
    if(ew == 189)
        return true;
    if(48 <= ew && ew <= 57)
        return true;
    if(65 <= ew && ew <= 90)
        return true;
    if(97 <= ew && ew <= 122)
        return true;
    return false;
});

$("#view-last").click(function() {
	$("#modal-result").modal('show');
	return false;
})
