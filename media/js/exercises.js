var groupId = 0;

$(function() {

	$('#exercise-edit').dialog({autoOpen:false, width: 'auto', height: 'auto'});
	$('#group-edit').dialog({autoOpen:false, width: 'auto', height: 'auto'});
        $('#import-exercises').dialog({autoOpen:false, width: 'auto', height: 'auto'});

	$('#add-exercise').click(function(){

		$('#exercise-edit form#main')[0].reset();
                $('#exercise-edit form#file #image-holder').empty();
		if(groupId){

			$('#exercise-edit form#main select[name="group_id"]').val(groupId);
		}
		$('#exercise-edit form#main input[type="hidden"]').val('');
		$('#exercise-edit').dialog('option','title', 'Add exercise');
                $('#exercise-edit form#main select[name="ex_type"]').trigger('change');
		$('#exercise-edit').dialog('open');
        return false;
	});

    $('#import-exercises-link').click(function(){

        $('div#import-exercises').dialog('open');
        return false;
    });

	$('#exercise-edit form#main input[type="submit"]').click(function(){

		if($.trim(($('#exercise-edit form#main input[name = "title"]').val())) != ''){

                        $('#exercise-edit form#main').ajaxSubmit(function(data){
                            var json = $.parseJSON($('<div>' + data + '</div>').html());

                            if(json.result == 'OK'){
                               fillExercises();
                               $('#exercise-edit').dialog('close');
                            }else{
                                if(json.error == 'ext'){
                                    fancyAlert('Invalid image' , 'Only gif, png, jpg images are allowed');
                                }
                            }
                            
                        });

			
		}

		return false;
	});

       $('#exercise-edit form#file #image').change(function(){

        $('#exercise-edit form#file').ajaxSubmit(function(data){
                    var json = $.parseJSON($('<div>' + data + '</div>').html());

                    if(json.result == 'OK'){
                       changeImage(json.file);
                       $('form#main input[name="file_name"]').val(json.file);
                    }else{
                        if(json.error == 'ext'){
                            fancyAlert('Invalid image' , 'Only gif, png, jpg images are allowed');
                        }
                    }
           });
    });


    $('#exercise-edit form#main select[name="ex_type"]').change(function(){
        if($(this).val() == 1){
            $('#exercise-edit form#main p#max-weight').show();
            $('#exercise-edit form#main p#max-reps').hide();
        }else{
            $('#exercise-edit form#main p#max-weight').hide();
            $('#exercise-edit form#main p#max-reps').show();
        }
    });

	fillExercises();

	$('table#exercise-list tbody tr a.edit').live('click', function(){

		showEditExercisePopup($(this).parent().parent().data('id'));
		return false;
	});

    $('table#exercise-list tbody tr a.export').live('click', function(){

        showExportExercisePopup($(this).parent().parent().data('id'));
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
        return false;
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
//alert(groupId);

		if(groupId){

			$('ul#group-list li').removeClass('selected');

            // enable no import checkbox
            $('div#import-exercises form input[name="noimport_id[]"]').attr('disabled', '');
		}else{
			$('ul#group-list li:not(#all-groups)').removeClass('selected');
			$('ul#group-list li#all-groups').addClass('selected');

            // disable no import checkbox if no group is selected
            $('div#import-exercises form input[name="noimport_id[]"]').attr('disabled', 'disabled');
		}
		if(groupId = $(this).data('id')){
			$(this).addClass('selected');
			$('div#group-description').html($(this).data('desc'));
		}else{
			$('div#group-description').html('List of exercises for all groups');
		}
		fillExercises();
        return false;
	});

    $('#import-exercises form input[type="submit"]').click(function(){

        importExercises();
        return false;
    });

});
 
function fillExercises(){

    var requestUrl = '';
    if(groupId){
        requestUrl = baseUrl + 'json/exercisesbygroup';
    }else{
        requestUrl = baseUrl + 'json/exercises';
    }

    $.getJSON(requestUrl, {id : groupId}, function(json){

            if(groupId){
                // remove if already appended
                $('ul#group-list li.selected div.edit-box').remove();
                var editBox = '<div class = "edit-group-box edit-box">' 
                                + '<a class = "edit" href="#"></a>'
                                + '<a class = "delete" href="#"></a></div>';
                $('ul#group-list li.selected').append(editBox);
                $('table#exercise-list tbody').remove();
                $('table#exercise-list').append($('<tbody>'));
                $('th#groups-th').hide();
            }else{
                $('table#exercise-list tbody').remove();
                $('table#exercise-list').append($('<tbody>'));
                $('th#groups-th').show();
            }


            if(json.length > 0){
                $.each(json, function(i, jsonrow){

                    var tr = $('<tr>');
                    tr.data('id', jsonrow.id);
                    var exportLink = '<td></td>';

                    // it means it doesn't have a copy in public exercises
                    if(jsonrow.import_id == 0){
                        exportLink = '<td class = "no-pad"><a class = "export" href="#"></a></td>';
                    }

                    var groupTd = '';
                    if(!groupId){
                        groupTd = '<td>' + jsonrow.group_title + '</td>';
                    }

                    tr.append('<td>' + jsonrow.title + '</td><td>'
                            + jsonrow.desc + '</td><td class = "center">' 
                            + getExerciseTypeName(jsonrow.ex_type) + '</td>'
                            + groupTd
                            + '<td class = "no-pad no-right"><a class = "edit" href="#"></a></td><td class = "no-pad no-right" ><a class = "delete" href="#"></a></td>' + exportLink);

                    $('table#exercise-list tbody').append(tr);
                });
            }else{
                $('table#exercise-list tbody').append('<tr><td colspan = 7 class = "center">No exercises added yet!</td></tr>');
            }

            makeZebra($('table#exercise-list tbody'));
        });

}

function showEditExercisePopup(id){

	$.getJSON(baseUrl + 'json/exerciseinfo', {id : id}, function(json){
            $('#exercise-edit form#main input[name="title"]').empty()
		$('#exercise-edit form')[0].reset();
                $('form#main input[name="file_name"]').val('');
		$('#exercise-edit form#main').populate(json[0]);

               if(typeof(json[0].filename) != 'undefined'){
                    changeImage(json[0].filename);
                }else{
                    $('#image-holder').empty();
                }
                
		$('#exercise-edit').dialog('option','title', 'Edit exercise');
                $('#exercise-edit form#main select[name="ex_type"]').trigger('change');
		$('#exercise-edit').dialog('open');
	});
}

function changeImage(file_name){
     $('#image-holder').html('<img id = "exercise-image" src = "' + baseUrl + 'files/' + file_name + '" />');
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
			li.append('<div class = "list-title">' + jsonrow.title + '</div>');
			$('ul#group-list').append(li);
			if(groupId == jsonrow.id){
				li.addClass('selected');
				li.trigger('click');
			}
			$('#exercise-edit form#main select[name="group_id"]').append('<option value = "' + jsonrow.id + '">' + jsonrow.title + '</option>');
		});

                
                makeZebra($('ul#group-list'));
                $('.scrollpane-wrapper').jScrollPane();
	});

}

function showEditGroupPopup(){

	$.getJSON(baseUrl + 'json/groupinfo', {id : groupId}, function(json){

		$('#group-edit form')[0].reset();
		$('#group-edit form').populate(json[0]);
		$('#group-edit').dialog('option','title', 'Edit group'); 
		$('#group-edit').dialog('open');
	});
}

function importExercises(){

    $('#import-exercises form input[name="current_group_id"]').val(groupId);
    var dataString = $('#import-exercises form').serialize();

    $.post(baseUrl + 'ajaxpost/importexercises', dataString, function(json){

        if(json.result == 'OK'){

            $('div#import-exercises').dialog('close');
            $('div#import-exercises form input[type="checkbox"]').attr('checked', '');
            // exercises will be filled automatically
            fillGroups();
        }
    },"json");

}

function showExportExercisePopup(id){

    $.getJSON(baseUrl + 'json/exerciseinfo', {id : id}, function(json){

        fancyConfirm('Confirm export', 'Are you sure you want to export this exercise - "' + json[0].title + '"?', function(){

            $.post(baseUrl + 'ajaxpost/exportexercise', {id : id}, function(json){

                if(json.result == 'OK'){

                    fillExercises();
                }
            },"json");
        });
    });
}