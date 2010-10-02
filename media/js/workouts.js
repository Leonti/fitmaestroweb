var setId = 0;

$(function() {

	$('#set-edit').dialog({autoOpen:false, width:'auto'});
	$('#reps-edit').dialog({autoOpen:false, width:'auto'});
        $('#start-session-popup').dialog({autoOpen:false, width:'auto'});

	$('#exercise-link').click(function(){

        exerciseChooser(addExerciseToSet);
        return false;
	});

	$('#add-set').click(function(){
            $('#set-edit form')[0].reset();
            $('#set-edit form input[type="hidden"]').val('');
            $('#set-edit').dialog('option','title', 'Add workout');
            $('#set-edit').dialog('open');
            return false;
	});

	fillSets();

	$('ul#set-list li').live('click', function(){

            $('ul#set-list li div.edit-set-box').remove();
            setId = $(this).data('id');

            $('ul#set-list li').removeClass('selected');

            $(this).addClass('selected');
            $('div#set-description').html($(this).data('desc'));
            var editBox = '<div class = "edit-set-box edit-box">'
            + '<a class = "edit" href="#"></a>'
            + '<a class = "delete" href="#"></a></div>';
            $(this).append(editBox);

            fillSetExercises();
            return false;
	});


	$('ul#set-list li div.edit-set-box a.edit').live('click', function(){

		showEditSetPopup();
		return false;
	});

	$('ul#set-list li div.edit-set-box a.delete').live('click', function(){

		fancyConfirm('Confirm delete', 'Are you sure you want to delete this workout?', function(){

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
            
            var exType = $(this).parent().data('ex_type');
            $('#reps-edit').data('exType', exType);
            $('#reps-edit').data('maxWeight', $(this).parent().data('maxWeight'));
            $('#reps-edit').data('maxReps', $(this).parent().data('maxReps'));

            if(exType == 1){
                $('.to-hide').show();
                $('.to-hide-reps').hide();
            }else if (exType == 0){
                $('.to-hide').hide();
                $('.to-hide-reps').show();
            }


             var details = $(this).data('details');
             $.each(details, function(i, detailrow){

                      appendRepsRow(detailrow.reps, detailrow.percentage, detailrow.id);
             });

             if(details.length == 0){

                      appendRepsRow();
             }
             $('#reps-edit').dialog('open');
            return false;
	});

	$('#reps-table tbody tr:last input[type="text"]').live('click', function(){

            appendRepsRow();
            return false;
	});

        $('#reps-table tbody tr input[name="percentage[]"]').live('keyup', function(){
            updateCalculation(this);
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
		return false;
	});

	$('.remove-from-set').live('click', function(){

            removeExerciseFromSet($(this).parent().parent().data('connector_id'));
            return false;
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
	
}); 


function updateCalculation(textField){
    
    var exType = $('#reps-edit').data('exType');
    var maxWeight = parseFloat($('#reps-edit').data('maxWeight'));
    var maxReps = parseInt($('#reps-edit').data('maxReps'));

    var percentage = parseFloat($(textField).val() != '' ? $(textField).val() : 0);

    if(exType == 1){
        
        $('.calculated-weight', $(textField).parent().parent()).text(percentages.calculateWeight(percentage, maxWeight) + ' ' + weightUnits);
    }else{
        $('.calculated-reps', $(textField).parent().parent()).text(percentages.calculateReps(percentage, maxReps));
    }
}

function fillSets(){

	$.getJSON(baseUrl + 'json/sets', function(json){

		$('ul#set-list li').remove();

		$.each(json, function(i, jsonrow){

			var li = $('<li>');
			li.data('id', jsonrow.id);
			li.data('desc', jsonrow.desc);
            li.append('<div class = "list-title">' + jsonrow.title + '</div>');
			$('ul#set-list').append(li);
			if(setId == jsonrow.id){
				li.addClass('selected');
				li.trigger('click');
			}
		});

        makeZebra($('ul#set-list'));
        if(setId == 0){
            $('ul#set-list li:last').trigger('click');
        }

	});
}

function fillSetExercises(){

	$.getJSON(baseUrl + 'json/setexercises', {id : setId}, function(json){
			$('table#set-exercise-list tbody').remove();
			$('table#set-exercise-list').append($('<tbody>'));

            if(json.length >0){
                $.each(json, function(i, jsonrow){

                    var tr = $('<tr>');
                    tr.data('id', jsonrow.id);
                    tr.data('maxWeight', jsonrow.max_weight);
                    tr.data('maxReps', jsonrow.max_reps);
                    tr.data('connector_id', jsonrow.connector_id);
                    tr.data('ex_type', jsonrow.ex_type);
                    tr.append('<td>' + jsonrow.title + '</td><td>' 
                        + jsonrow.desc + '</td><td class = "center">'
                        + getExerciseTypeName(jsonrow.ex_type) + '</td><td>' 
                        + jsonrow.group_title + '</td>' );

                    var repsTd = $('<td>');
                    repsTd.addClass('reps');
                    repsTd.data('details', jsonrow.details);

                    $.each(jsonrow.details, function(i, detailrow){

                        percentage = parseFloat(detailrow.percentage);
                        var toAppend = '';
                        if(jsonrow.ex_type == 1){
                            var calculatedWeight = percentages.calculateWeight(percentage, parseFloat(jsonrow.max_weight));
                            toAppend = detailrow.reps + 'x' + detailrow.percentage + '% ' + calculatedWeight + ' ' + weightUnits;
                        }else{
                            var calculatedReps = percentages.calculateReps(percentage, parseInt(jsonrow.max_reps));
                            toAppend = detailrow.percentage + '% ' + calculatedReps;
                        }
                        repsTd.append('<div>' + toAppend + '</div>');
                    });
                    tr.append(repsTd);
                    tr.append('<td class = "no-pad"><a href = "#" class = "remove-from-set delete"></a></td>');

                    $('table#set-exercise-list tbody').append(tr);
                });
            }else{
                $('table#set-exercise-list tbody').append('<tr><td colspan = 6 class = "center">No exercises added yet!</td></tr>');
            }
            makeZebra($('table#set-exercise-list tbody'));
	});

}

function showEditSetPopup(){

	$.getJSON(baseUrl + 'json/setinfo', {id : setId}, function(json){

		$('#set-edit form')[0].reset();
		$('#set-edit form').populate(json[0]);
		$('#set-edit').dialog('option','title', 'Edit workout'); 
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


    var exType = $('#reps-edit').data('exType');
    var maxWeight = parseFloat($('#reps-edit').data('maxWeight'));
    var maxReps = parseInt($('#reps-edit').data('maxReps'));

    var percentageVal = parseFloat(percentage != '' ? percentage : 0);

    // draw different rows for different exercise types
    if(exType == 1){

        var weightVal = percentages.calculateWeight(percentageVal, maxWeight);
        $('#reps-table').append('<tr><td><input type = "text" name = "reps[]" class = "number" value = "' + reps + '" /></td><td>x</td>'
        + '<td><input type = "text" name = "percentage[]" class = "number" value = "' + percentage + '" /> %'
        + '<input type = "hidden" name = "rep_id[]" value = "' + id + '" /></td>'
        + '<td><span class="calculated-weight">' + weightVal + ' ' + weightUnits + '</span></td>'
        + '<td><a href = "#" class = "rep-remove" tabindex = "-1">x</a></td></tr>');
        
    }else{

        var repsVal = percentages.calculateReps(percentageVal, maxReps);
        $('#reps-table').append('<tr><td><input type = "text" name = "percentage[]" class = "number" value = "' + percentage + '" /> %'
        + '<input type = "hidden" name = "rep_id[]" value = "' + id + '" /><input type = "hidden" name = "reps[]" class = "number" value = "0" /></td>'
        + '<td><span class="calculated-reps">' + repsVal + '</span></td>'
        + '<td><a href = "#" class = "rep-remove" tabindex = "-1">x</a></td></tr>');
    }


}

function removeExerciseFromSet(connectorId){

		fancyConfirm('Confirm delete', 'Are you sure you want to remove exercise from this workout?', function(){

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