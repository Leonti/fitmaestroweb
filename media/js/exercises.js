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
		$('#exercise-edit').dialog('open');
	});

	$('#exercise-edit form input[type="submit"]').click(function(){

		if($.trim(($('#exercise-edit form input[name = "title"]').val())) != ''){
			var dataString = $('#exercise-edit form').serialize();

			$.post('ajaxpost/saveexercise', dataString, function(json){

				if(json.result == 'OK'){

					fillExercises();
				}
			},"json");

			$('#exercise-edit').dialog('close');
		}

		return false;
	});

	fillExercises();

	$('table#exercise-list tbody tr a.edit').live('click', function(){

		showEditExercisePopup($(this).parent().parent().data('id'));
		return false;
	});

	$('table#exercise-list tbody tr a.delete').live('click', function(){

		var id = $(this).parent().parent().data('id');

		fancyConfirm('Confirm delete', 'Are you sure you want to delete this exercise?', function(){

			$.post('ajaxpost/deleteexercise', {id : id}, function(json){

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

			$.post('ajaxpost/savegroup', dataString, function(json){

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

			$.post('ajaxpost/deletegroup', {id : groupId}, function(json){

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

});
 
function fillExercises(){

	if(groupId){

		$.getJSON('json/exercisesbygroup', {id : groupId}, function(json){

				$('ul#group-list li.selected').append('<div class = "edit-group-box"><a class = "edit" href="#">Edit</a><a class = "delete" href="#">Delete</a></div>');
			 	$('table#exercise-list tbody').remove();
				$('table#exercise-list').append($('<tbody>'));
				$('th#groups-th').hide();

				$.each(json, function(i, jsonrow){

					var tr = $('<tr>');
					tr.data('id', jsonrow.id);
					tr.append('<td>' + jsonrow.title + '</td><td>'
						  + jsonrow.desc + '</td><td>' 
						  + jsonrow.ex_type + '</td>' 
						  + '<td><a class = "delete" href="#">Delete</a></td><td><a class = "edit" href="#">Edit</a></td>');
					$('table#exercise-list tbody').append(tr);
				});
		});
	}else{

		$.getJSON('json/exercises', function(json){

			 	$('table#exercise-list tbody').remove();
				$('table#exercise-list').append($('<tbody>'));
				$('th#groups-th').show();

				$.each(json, function(i, jsonrow){

					var tr = $('<tr>');
					tr.data('id', jsonrow.id);
					tr.append('<td>' + jsonrow.exercise_title + '</td><td>' 
						  + jsonrow.desc +'</td><td>'
						  + jsonrow.ex_type +'</td><td>'
						  + jsonrow.group_title 
						  + '</td><td><a class = "delete" href="#">Delete</a></td><td><a class = "edit" href="#">Edit</a></td>');
					$('table#exercise-list tbody').append(tr);
				});
		});
	}


}

function showEditExercisePopup(id){

	$.getJSON('json/exerciseinfo', {id : id}, function(json){

		$('#exercise-edit form')[0].reset();
		$('#exercise-edit form').populate(json[0]);
		$('#exercise-edit').dialog('option','title', 'Edit exercise'); 
		$('#exercise-edit').dialog('open');
	});
}

function fillGroups(){

	$.getJSON('json/groups', function(json){

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

	$.getJSON('json/groupinfo', {id : groupId}, function(json){

		$('#group-edit form')[0].reset();
		$('#group-edit form').populate(json[0]);
		$('#group-edit').dialog('option','title', 'Edit group'); 
		$('#group-edit').dialog('open');
		//alert(json[0].title);
	});
}