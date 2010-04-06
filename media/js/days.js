var setId = 0;

$(function() {

	$('#accordion').accordion( { autoHeight: false } );
	$('#select-exercise').dialog({autoOpen:false});
	$('#set-edit').dialog({autoOpen:false});
	$('#reps-edit').dialog({autoOpen:false});
    $('#start-session-popup').dialog({autoOpen:false});
    
	$('#exercise-link').click(function(){

		$('#select-exercise').dialog('open');
	});

	$('#accordion li').click(function(){

		var metadata = $(this).metadata();
		addExerciseToSet(metadata.id);
	});

	$('#add-set').click(function(){
		$('#set-edit form')[0].reset();
		$('#set-edit form input[type="hidden"]').val('');
		$('#set-edit').dialog('option','title', 'Add set'); 
		$('#set-edit').dialog('open');
	});

	fillSets();

	$('ul#set-list li').live('click', function(){

		$('ul#set-list li div.edit-set-box').remove();	
		setId = $(this).data('id');

		$('ul#set-list li').removeClass('selected');

		$(this).addClass('selected');
		$('div#set-description').html($(this).data('desc'));
		$(this).append('<div class = "edit-set-box"><a class = "edit" href="#">Edit</a><a class = "delete" href="#">Delete</a></div>');

		fillSetExercises();
	});


	$('ul#set-list li div.edit-set-box a.edit').live('click', function(){

		showEditSetPopup();
		return false;
	});

	$('ul#set-list li div.edit-set-box a.delete').live('click', function(){

		fancyConfirm('Confirm delete', 'Are you sure you want to delete this day?', function(){

			$.post('ajaxpost/deleteset', {id : setId}, function(json){

				if(json.result == 'OK'){
			
					setId = 0;
					fillSets();
				}
			},"json");
		});
		return false;
	});

	$('#set-edit form input[type="submit"]').click(function(){

		if($.trim(($('#set-edit form input[name = "title"]').val())) != ''){
			var dataString = $('#set-edit form').serialize();

			$.post('ajaxpost/saveset', dataString, function(json){

				if(json.result == 'OK'){

					fillSets();
				}
			},"json");

			$('#set-edit').dialog('close');
		}

		return false;
	});

	$('.reps').live('click', function(){

		 $('#reps-edit form input[name="connector_id"]').val($(this).parent().data('connector_id'));
		 $('#reps-table tbody tr').remove();

		 var details = $(this).data('details');
		 $.each(details, function(i, detailrow){

			  appendRepsRow(detailrow.reps, detailrow.percentage, detailrow.id);
		 });
	  
		 if(details.length == 0){

			  appendRepsRow();
		 }
		 $('#reps-edit').dialog('open');
	});

	$('#reps-table tbody tr:last input[type="text"]').live('click', function(){

		appendRepsRow();
	});

	$('#reps-edit form input[type="submit"]').click(function(){

			var dataString = $('#reps-edit form').serialize();

			$.post('ajaxpost/savereps', dataString, function(json){

				if(json.result == 'OK'){

					fillSetExercises();
				}
				$('#reps-edit').dialog('close');
			},"json");

			$('#set-edit').dialog('close');

		return false;
	});

	$('.rep-remove').live('click', function(){

		// if it's the last one - just clear the values
		if($('#reps-table tbody tr').length == 1){

			$('#reps-edit form input[type="text"]').val('');
			$('#reps-edit form input[name="rep_id[]"]').val('');
		}else{

			$(this).parent().parent().remove();
		}
	});

	$('.remove-from-set').live('click', function(){

		removeExerciseFromSet($(this).parent().parent().data('connector_id'));
	});

    $('#start-session-link').click(function(){
        var currentTime = new Date()
        var month = currentTime.getMonth() + 1
        var day = currentTime.getDate()
        var year = currentTime.getFullYear()
        $('#start-session-popup form input[name="title"]').val(month + "/" + day + "/" + year);

        $('#start-session-popup').dialog('open');
    });

    $('#start-session-popup form input[type="submit"]').click(function(){
        addSession();
        return false;
    });
	
}) 


function fillSets(){

	$.getJSON('json/sets', function(json){

		$('ul#set-list li').remove();

		$.each(json, function(i, jsonrow){

			var li = $('<li>');
			li.data('id', jsonrow.id);
			li.data('desc', jsonrow.desc);
			li.append(jsonrow.title);
			$('ul#set-list').append(li);
			if(setId == jsonrow.id){
				li.addClass('selected');
				li.trigger('click');
			}
		});

	});
}

function fillSetExercises(){

	$('#set-exercises').show();
    $('#start-session-link').show();
	$.getJSON('json/setexercises', {id : setId}, function(json){

			$('table#set-exercise-list tbody').remove();
			$('table#set-exercise-list').append($('<tbody>'));

			$.each(json, function(i, jsonrow){

				var tr = $('<tr>');
				tr.data('id', jsonrow.id);
                tr.data('maxWeight', jsonrow.max_weight);
				tr.data('connector_id', jsonrow.connector_id);
				tr.append('<td>' + jsonrow.title + '</td><td>' 
					  + jsonrow.desc + '</td><td>'
					  + jsonrow.ex_type + '</td><td>' 
					  + jsonrow.group_title + '</td>' );

				var repsTd = $('<td>');
				repsTd.addClass('reps');
				repsTd.data('details', jsonrow.details);

				$.each(jsonrow.details, function(i, detailrow){

					repsTd.append('<div>' + detailrow.reps + 'x' + detailrow.percentage + '%</div>');
				});
				tr.append(repsTd);
				
				tr.append('<td><a href = "#" class = "remove-from-set">Remove</a></td>');

				$('table#set-exercise-list tbody').append(tr);
			});
	});

}

function showEditSetPopup(){

	$.getJSON('json/setinfo', {id : setId}, function(json){

		$('#set-edit form')[0].reset();
		$('#set-edit form').populate(json[0]);
		$('#set-edit').dialog('option','title', 'Edit day'); 
		$('#set-edit').dialog('open');
	});
}

function addExerciseToSet(exerciseId){

	$.post('ajaxpost/setaddexercise', {set_id : setId, exercise_id : exerciseId}, function(json){
		if(json.result == 'OK'){

			fillSetExercises();
		}
	},"json");

	$('#select-exercise').dialog('close');
}

function appendRepsRow(reps, percentage, id){

	if(typeof(reps) == 'undefined'){
      
		reps = '';
		percentage = '';
		id = '';
	}

	$('#reps-table').append('<tr><td><input type = "text" name = "reps[]" value = "' + reps + '" /></td><td>x</td>'
		+ '<td><input type = "text" name = "percentage[]" value = "' + percentage + '" />'
		+ '<input type = "hidden" name = "rep_id[]" value = "' + id + '" /></td>'
		+ '<td><a href = "#" class = "rep-remove" tabindex = "-1">x</a></td></tr>');
}

function removeExerciseFromSet(connectorId){

		fancyConfirm('Confirm delete', 'Are you sure you want to remove exercise from this day?', function(){

			$.post('ajaxpost/setdeleteexercise', {id : connectorId}, function(json){

				if(json.result == 'OK'){
			
					fillSetExercises()
				}
			},"json");
		});
		return false;
}

function addSession(){
    var dataString = $('#start-session-popup form').serialize() + '&set_id=' + setId;

    $.post('ajaxpost/addsession', dataString, function(json){

        if(json.result == 'OK'){

            $('#start-session-popup').dialog('close');
            window.location.replace("sessions");
        }
    },"json");
}