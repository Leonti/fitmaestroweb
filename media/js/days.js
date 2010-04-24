var setId = 0;
var exType = 0;

$(function() {

	$('#set-edit').dialog({autoOpen:false});
	$('#reps-edit').dialog({autoOpen:false});
    $('#start-session-popup').dialog({autoOpen:false});
    
	$('#exercise-link').click(function(){

        exerciseChooser(addExerciseToSet);
        return false;
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

        $('#start-session-link').show();

		fillSetExercises();
	});


	$('ul#set-list li div.edit-set-box a.edit').live('click', function(){

		showEditSetPopup();
		return false;
	});

	$('ul#set-list li div.edit-set-box a.delete').live('click', function(){

		fancyConfirm('Confirm delete', 'Are you sure you want to delete this day?', function(){

			$.post(baseUrl + 'ajaxpost/deleteset', {id : setId}, function(json){

				if(json.result == 'OK'){
			
					setId = 0;
					fillSets();
				}
			},"json");
		});
		return false;
	});

    // to prevent double sending in programs view
    if(typeof(freeSets) != 'undefined'){

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
    }


	$('.reps').live('click', function(){

		$('#reps-edit form input[name="connector_id"]').val($(this).parent().data('connector_id'));
		$('#reps-table tbody tr').remove();
        exType = $(this).parent().data('ex_type');
        if(exType == 1){
            $('.to-hide').show();
        }else if (exType == 0){
            $('.to-hide').hide();
        }

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

			$.post(baseUrl + 'ajaxpost/savereps', dataString, function(json){

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

        if(typeof(programsConnectorId) != 'undefined'){
            $('#start-session-popup form input[name="programs_connector_id"]').val(programsConnectorId);
        }

        $('#start-session-popup').dialog('open');
        return false;
    });

    $('#start-session-popup form input[type="submit"]').click(function(){
        addSession();
        return false;
    });
	
}) 


function fillSets(){

	$.getJSON(baseUrl + 'json/sets', function(json){

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
	$.getJSON(baseUrl + 'json/setexercises', {id : setId}, function(json){

			$('table#set-exercise-list tbody').remove();
			$('table#set-exercise-list').append($('<tbody>'));

			$.each(json, function(i, jsonrow){

				var tr = $('<tr>');
				tr.data('id', jsonrow.id);
                tr.data('maxWeight', jsonrow.max_weight);
				tr.data('connector_id', jsonrow.connector_id);
                tr.data('ex_type', jsonrow.ex_type);
				tr.append('<td>' + jsonrow.title + '</td><td>' 
					  + jsonrow.desc + '</td><td>'
					  + getExerciseTypeName(jsonrow.ex_type) + '</td><td>' 
					  + jsonrow.group_title + '</td>' );

				var repsTd = $('<td>');
				repsTd.addClass('reps');
				repsTd.data('details', jsonrow.details);

				$.each(jsonrow.details, function(i, detailrow){

                    var toAppend = '';
                    if(jsonrow.ex_type == 1){
                        toAppend = detailrow.reps + 'x' + detailrow.percentage + '%';
                    }else{
                        toAppend = detailrow.reps;
                    }
					repsTd.append('<div>' + toAppend + '</div>');
				});
				tr.append(repsTd);
				
				tr.append('<td><a href = "#" class = "remove-from-set">Remove</a></td>');

				$('table#set-exercise-list tbody').append(tr);
			});
	});

}

function showEditSetPopup(){

	$.getJSON(baseUrl + 'json/setinfo', {id : setId}, function(json){

		$('#set-edit form')[0].reset();
		$('#set-edit form').populate(json[0]);
		$('#set-edit').dialog('option','title', 'Edit day'); 
		$('#set-edit').dialog('open');
	});
}

function addExerciseToSet(exerciseId){

	$.post(baseUrl + 'ajaxpost/setaddexercise', {set_id : setId, exercise_id : exerciseId}, function(json){
		if(json.result == 'OK'){

			fillSetExercises();
		}
	},"json");
}

function appendRepsRow(reps, percentage, id){

	if(typeof(reps) == 'undefined'){

		reps = '';
		percentage = '';
		id = '';
	}

    // draw different rows for different exercise types
    if(exType == 1){
        $('#reps-table').append('<tr><td><input type = "text" name = "reps[]" value = "' + reps + '" /></td><td>x</td>'
            + '<td><input type = "text" name = "percentage[]" value = "' + percentage + '" />'
            + '<input type = "hidden" name = "rep_id[]" value = "' + id + '" /></td>'
            + '<td><a href = "#" class = "rep-remove" tabindex = "-1">x</a></td></tr>');
    }else if(exType == 0){
        $('#reps-table').append('<tr><td><input type = "text" name = "reps[]" value = "' + reps + '" />'
            + '<input type = "hidden" name = "percentage[]" value = "0" />'
            + '<input type = "hidden" name = "rep_id[]" value = "' + id + '" /></td>'
            + '<td><a href = "#" class = "rep-remove" tabindex = "-1">x</a></td></tr>');
    }
}

function removeExerciseFromSet(connectorId){

		fancyConfirm('Confirm delete', 'Are you sure you want to remove exercise from this day?', function(){

			$.post(baseUrl + 'ajaxpost/setdeleteexercise', {id : connectorId}, function(json){

				if(json.result == 'OK'){
			
					fillSetExercises()
				}
			},"json");
		});
		return false;
}

function addSession(){
    var dataString = $('#start-session-popup form').serialize() + '&set_id=' + setId;

    $.post(baseUrl + 'ajaxpost/addsession', dataString, function(json){

        if(json.result == 'OK'){

            $('#start-session-popup').dialog('close');
            window.location.replace("sessions");
        }
    },"json");
}