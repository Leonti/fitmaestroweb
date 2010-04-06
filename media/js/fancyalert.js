function fancyAlert(titleString, message){

	$('body').append(
	  '<div id="message_container">' +
	  '</div>');
	$('#message_container').attr('title', titleString);
	$('#message_container').html(message);

	$('#message_container').dialog({

		modal: true,
		buttons: {
			Ok: function() {

				$(this).dialog('destroy');
			}
		},
		close: function(){

			$(this).dialog('destroy');
			$('#message_container').remove(); 
		}
	});

} 

function fancyConfirm(titleString, message, callback){

	$('body').append(
	  '<div id="message_container">' +
	  '</div>');
	$('#message_container').attr('title', titleString);
	$('#message_container').html(message);

	$('#message_container').dialog({

		modal: true,
		buttons: {
			Ok: function() {
				if(callback) callback(true);
				$(this).dialog('destroy');
			},
			Cancel: function() {
				$(this).dialog('destroy');
			}

		},
		close: function(){

			$(this).dialog('destroy');
			$('#message_container').remove(); 
		}
	});

} 
