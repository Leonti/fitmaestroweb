var groupId = 0;

$(function() {

	$('#exercise-edit').dialog({autoOpen:false});
	$('#group-edit').dialog({autoOpen:false});

	$('#add-exercise').click(function(){

		$('#exercise-edit form')[0].reset();
		if(groupId){

			$('#exercise-edit form select[name="group_id"]').val(groupId);
		}
		$('#exercise-edit form input[type="hidden"]').val('');
		$('#exercise-edit').dialog('option','title', 'Add exercise');
        $('#exercise-edit form select[name="ex_type"]').trigger('change');
		$('#exercise-edit').dialog('open');
	});

    $('#import-exercises-link').click(function(){

        // disable checkbox if group is not selected
        if(groupId){
            $('div#import-exercises form input[name="noimport[]"]').attr('disabled', '');
        }else{
            $('div#import-exercises form input[name="noimport[]"]').attr('disabled', 'disabled');
        }

        $('div#import-exercises').show();
        return false;
    });

	$('#exercise-edit form input[type="submit"]').click(function(){

		if($.trim(($('#exercise-edit form input[name = "title"]').val())) != ''){
			var dataString = $('#exercise-edit form').serialize();

			$.post(baseUrl + 'ajaxpost/saveexercise', dataString, function(json){

				if(json.result == 'OK'){

					fillExercises();
				}
			},"json");

			$('#exercise-edit').dialog('close');
		}

		return false;
	});

    $('#exercise-edit form select[name="ex_type"]').change(function(){
        if($(this).val() == 1){
            $('#exercise-edit form p#max-weight').show();
            $('#exercise-edit form p#max-reps').hide();
        }else{
            $('#exercise-edit form p#max-weight').hide();
            $('#exercise-edit form p#max-reps').show();
        }
    });

	fillExercises();

	$('table#exercise-list tbody tr a.edit').live('click', function(){

		showEditExercisePopup($(this).parent().parent().data('id'));
		return false;
	});

	$('table#exercise-list tbody tr a.delete').live('click', function(){

		var id = $(this).parent().parent().data('id');

		fancyConfirm('Confirm delete', 'Are you sure you want to delete this exercise?', function(){

			$.post(baseUrl + 'ajaxpost/deleteexercise', {id : id}, function(json){

				if(json.result == 'OK'){

					fillExercises();
				}
			},"json");
		});
		return false;
	});

	$('#add-group').click(function(){
		$('#group-edit form')[0].reset();
		$('#group-edit form input[type="hidden"]').val('');
		$('#group-edit').dialog('option','title', 'Add group'); 
		$('#group-edit').dialog('open');
	});

	$('#group-edit form input[type="submit"]').click(function(){

		if($.trim(($('#group-edit form input[name = "title"]').val())) != ''){
			var dataString = $('#group-edit form').serialize();

			$.post(baseUrl + 'ajaxpost/savegroup', dataString, function(json){

				if(json.result == 'OK'){

					fillGroups();
				}
			},"json");

			$('#group-edit').dialog('close');
		}

		return false;
	});

	fillGroups();

	$('ul#group-list li div.edit-group-box a.edit').live('click', function(){

		showEditGroupPopup();
		return false;
	});

	$('ul#group-list li div.edit-group-box a.delete').live('click', function(){


		fancyConfirm('Confirm delete', 'Are you sure you want to delete this group?', function(){

			$.post(baseUrl + 'ajaxpost/deletegroup', {id : groupId}, function(json){

				if(json.result == 'OK'){
			
					groupId = null;
					fillGroups();
				}
			},"json");
		});
		return false;
	});


	$('ul#group-list li').live('click', function(){

		$('ul#group-list li div.edit-group-box').remove();	
		groupId = $(this).data('id');

		if(groupId){

			$('ul#group-list li').removeClass('selected');
		}else{
			$('ul#group-list li:not(#all-groups)').removeClass('selected');
			$('ul#group-list li#all-groups').addClass('selected');
		}
		if(groupId = $(this).data('id')){

			$(this).addClass('selected');
			$('div#group-description').html($(this).data('desc'));
		}else{
			$('div#group-description').html('List of exercises for all groups');
		}
		fillExercises();
	});

    $('#import-exercises form input[type="submit"]').click(function(){

        importExercises();
        return false;
    });

    $('#close-import').click(function(){
        $('div#import-exercises').hide();
        return false;
    });

});
 
function fillExercises(){

	if(groupId){

		$.getJSON(baseUrl + 'json/exercisesbygroup', {id : groupId}, function(json){

                // remove if already appended
                $('ul#group-list li.selected div').remove();
				$('ul#group-list li.selected').append('<div class = "edit-group-box"><a class = "edit" href="#">Edit</a><a class = "delete" href="#">Delete</a></div>');
			 	$('table#exercise-list tbody').remove();
				$('table#exercise-list').append($('<tbody>'));
				$('th#groups-th').hide();

				$.each(json, function(i, jsonrow){

					var tr = $('<tr>');
					tr.data('id', jsonrow.id);
					tr.append('<td>' + jsonrow.title + '</td><td>'
						  + jsonrow.desc + '</td><td>' 
						  + getExerciseTypeName(jsonrow.ex_type) + '</td>' 
						  + '<td><a class = "delete" href="#">Delete</a></td><td><a class = "edit" href="#">Edit</a></td>');
					$('table#exercise-list tbody').append(tr);
				});
		});
	}else{

		$.getJSON(baseUrl + 'json/exercises', function(json){

			 	$('table#exercise-list tbody').remove();
				$('table#exercise-list').append($('<tbody>'));
				$('th#groups-th').show();

				$.each(json, function(i, jsonrow){

					var tr = $('<tr>');
					tr.data('id', jsonrow.id);
					tr.append('<td>' + jsonrow.exercise_title + '</td><td>' 
						  + jsonrow.desc +'</td><td>'
						  + getExerciseTypeName(jsonrow.ex_type) +'</td><td>'
						  + jsonrow.group_title 
						  + '</td><td><a class = "delete" href="#">Delete</a></td><td><a class = "edit" href="#">Edit</a></td>');
					$('table#exercise-list tbody').append(tr);
				});
		});
	}


}

function showEditExercisePopup(id){

	$.getJSON(baseUrl + 'json/exerciseinfo', {id : id}, function(json){

		$('#exercise-edit form')[0].reset();
		$('#exercise-edit form').populate(json[0]);
		$('#exercise-edit').dialog('option','title', 'Edit exercise'); 
        $('#exercise-edit form select[name="ex_type"]').trigger('change');
		$('#exercise-edit').dialog('open');
	});
}

function fillGroups(){

	$.getJSON(baseUrl + 'json/groups', function(json){

		$('ul#group-list li:not(#all-groups)').remove();
		$('#exercise-edit form select[name="group_id"] option').remove();

		if(!groupId){

			var allGroups = $('ul#group-list li#all-groups');
			allGroups.addClass('selected');
			allGroups.trigger('click');
		}

		$.each(json, function(i, jsonrow){

			var li = $('<li>');
			li.data('id', jsonrow.id);
			li.data('desc', jsonrow.desc);
			li.append(jsonrow.title);
			$('ul#group-list').append(li);
			if(groupId == jsonrow.id){
				li.addClass('selected');
				li.trigger('click');
			}
			$('#exercise-edit form select[name="group_id"]').append('<option value = "' + jsonrow.id + '">' + jsonrow.title + '</option>');
		});
	});

}

function showEditGroupPopup(){

	$.getJSON(baseUrl + 'json/groupinfo', {id : groupId}, function(json){

		$('#group-edit form')[0].reset();
		$('#group-edit form').populate(json[0]);
		$('#group-edit').dialog('option','title', 'Edit group'); 
		$('#group-edit').dialog('open');
		//alert(json[0].title);
	});
}

function importExercises(){

    $('#import-exercises form input[name="current_group_id"]').val(groupId);
    var dataString = $('#import-exercises form').serialize();

    $.post(baseUrl + 'ajaxpost/importexercises', dataString, function(json){

        if(json.result == 'OK'){

            $('div#import-exercises').hide();
            $('div#import-exercises form input[type="checkbox"]').attr('checked', '');
            // exercises will be filled automatically
            fillGroups();
        }
    },"json");

}