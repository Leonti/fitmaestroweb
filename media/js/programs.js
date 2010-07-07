var programId = 0;
var programTitle = '';
var programsConnectorId = 0;
var clearTable = false;

$(function() {

    $('#set-edit #title-p').hide();
	$('#program-edit').dialog({autoOpen:false});
    $('#set-edit').dialog({autoOpen:false});

	$('#add-program').click(function(){
		$('#program-edit form')[0].reset();
		$('#program-edit form input[type="hidden"]').val('');
		$('#program-edit').dialog('option','title', 'Add program');
		$('#program-edit').dialog('open');
        return false;
	});

	$('#program-edit form input[type="submit"]').click(function(){

		if($.trim(($('#program-edit form input[name = "title"]').val())) != ''){
			var dataString = $('#program-edit form').serialize();

			$.post(baseUrl + 'ajaxpost/saveprogram', dataString, function(json){

				if(json.result == 'OK'){

					fillPrograms();
				}
			},"json");

			$('#program-edit').dialog('close');
		}

		return false;
	});

	fillPrograms();


    $('#set-edit form input[type="submit"]').click(function(){

        if($.trim(($('#set-edit form input[name = "title"]').val())) != ''){
            var dataString = $('#set-edit form').serialize();

            $.post(baseUrl + 'ajaxpost/saveset', dataString, function(json){

                if(json.result == 'OK'){

                    fillDays();
                }
            },"json");

            $('#set-edit').dialog('close');
        }

        return false;
    });


	$('ul#program-list li div.edit-program-box a.edit').live('click', function(){

		showEditProgramPopup();
		return false;
	});

	$('ul#program-list li div.edit-program-box a.delete').live('click', function(){


		fancyConfirm('Confirm delete', 'Are you sure you want to delete this program?', function(){

			$.post(baseUrl + 'ajaxpost/deleteprogram', {id : programId}, function(json){

				if(json.result == 'OK'){

                    resetPage();
					programId = 0;
					fillPrograms();
				}
			},"json");
		});
		return false;
	});

    $('ul#program-list li div.edit-program-box a.export').live('click', function(){

        showExportProgramPopup();
        return false;
    });


    // entry in program list
	$('ul#program-list li').live('click', function(){

		$('ul#program-list li div.edit-program-box').remove();

        // if we change programs - do not append additional weeks
        if($(this).data('id') != programId){
            clearTable = true;
        }else{
            clearTable = false;
        }
		programId = $(this).data('id');
        programTitle = $(this).data('title');

		$('ul#program-list li').removeClass('selected');

        $(this).addClass('selected');
        $('div#program-description').html($(this).data('desc'));
        $('ul#program-list li.selected div.edit-box').remove();

        exportLink = '';
        // it means it doesn't have a copy in public programs
        if($(this).data('import_id') == 0){
            exportLink = '<a class = "export" href="#"></a>';
        }
        var editBox = '<div class = "edit-program-box edit-box">' 
        + '<a class = "edit" href="#"></a>'
        + '<a class = "delete" href="#"></a>'
        + exportLink + '</div>';

        $('ul#program-list li.selected').append(editBox);

        fillDays();
        return false;
    });

    $('div.set-details').live('click', function(e){
        if(!$(e.target).is('a')){
            setId = $(this).parent().data('set_id');
            programsConnectorId = $(this).parent().data('connector_id');

            // do not allow to start another session
            if($(this).parent().data('session_id')){
                $('#start-session-link').hide();
            }else{
                $('#start-session-link').show();
            }

            $('#program-workout').show();
            fillSetExercises();
            return false;
        }
    });

    $('a.remove-set').live('click', function(){

        removeSet($(this).parent().parent().data('connector_id'));
        return false;
    });

    // day in days "table"
    $('table#days tbody tr td div').live('click', function(e){

        if(!$(e.target).is('a') && !$(e.target).is('.status')){

            // check if it already has set attached
            // if not - add new set
            if($(this).data('set_id')){
                showEditSetDialog($(this).data('set_id'));
            }else{
                
                //since it's a new one - hide workout table
                $('#program-workout').hide();
                showAddSetDialog($(this).data('day_number'));
            }
            return false;
        }
    });

    $('#add-week').click(function(){
        addWeek();
        return false;
    });

$('table#days tbody tr td div div.set-details').live('mouseover', function() {

    if (!$(this).data('init')) {
        $(this).data('init', true);
        $(this).draggable({
            cancel: 'a.ui-icon',
            revert: 'invalid',
            containment: 'ul.calendar',
            helper: 'clone',
            cursor: 'move'
        });
    }
});



});

function makeDroppable(){
 
    $('div.workout').droppable({
        accept: 'div.set-details',
        activeClass: 'ui-state-highlight',
        drop: function(ev, ui) {
            moveSet(ui.draggable.parent().data('connector_id'), $(this).data('day_number'))
        }
    });

}

function moveSet(connectorId, dayNumber){

    $.post(baseUrl + 'ajaxpost/moveset', {connector_id: connectorId, day_number: dayNumber}, function(json){

        if(json.result == 'OK'){

            fillDays();
        }
    },"json");
}

function fillPrograms(){

	$.getJSON(baseUrl + 'json/programs', function(json){

		$('ul#program-list li').remove();

		$.each(json, function(i, jsonrow){

			var li = $('<li>');
			li.data('id', jsonrow.id);
            li.data('title', jsonrow.title);
			li.data('desc', jsonrow.desc);
            li.data('import_id', jsonrow.import_id);
			li.append('<div class = "list-title">' + jsonrow.title + '</div>');
			$('ul#program-list').append(li);

            // if we are loading the page with id predefined - expand that session
            if(startProgramId == jsonrow.id){
                programId = startProgramId;

                // we needed it only once
                startProgramId = 0;
            }

			if(programId == jsonrow.id){
				li.addClass('selected');
				li.trigger('click');
			}
		});
        
        makeZebra($('ul#program-list'));
        
        if(programId == 0){
            $('ul#program-list li:last').trigger('click');
        }
	});

}

function fillDays(){

    $.getJSON(baseUrl + 'json/programsets', {id : programId}, function(json){

        $('table#days').show();
        var jsonIt = 0;

        var daysNumber = 7;
        if(json.length > 0){
            daysNumber = json[json.length - 1].day_number;
        }

        var weeks = Math.ceil(daysNumber/7);

        // weeks added with button - if they exist - we have to recreate them
        var extraWeeks = $('table#days tbody tr').size() - weeks;

        $('table#days tbody tr').remove();

        // number of the current day
        var dayCount = 0;
        for(var k = 0; k < weeks; k++){

            var daysData = new Array();

            for(var i = 0; i < 7; i++){
                dayCount++;
                var dayData = new Object;
                var dayContent = $('<div class = "workout">');

                if(json[jsonIt] && json[jsonIt].day_number == dayCount){

                    dayContent.data('set_id', json[jsonIt].set_id);
                    dayContent.data('connector_id', json[jsonIt].id);
                    
                    // create div to bring user to set editing page
                    var setDiv = $('<div>');
                    setDiv.addClass('set-details');

                    dayContent.append('<div class = "edit-box"><a href = "#" class = "remove-set"></a></div>');

                    var sessionStatus = '';
                    var colorClass = '';
                    if(json[jsonIt].session.id){
                        dayContent.data('session_id', json[jsonIt].session.id);
                        sessionStatus = '<a href = "' + baseUrl + 'sessions/index/' + json[jsonIt].session.id + '">' 
                        + statusesMap[json[jsonIt].session.status] + '</a>';
                        if(json[jsonIt].session.status == 'DONE'){
                            colorClass = 'status-done';
                        }else if(json[jsonIt].session.status == 'INPROGRESS'){
                            colorClass = 'in-progress';
                        }
                    }else{
                        sessionStatus = 'Not started';
                        colorClass = 'not-started';
                    }
                    
                    setDiv.append('<div class = "status ' + colorClass + '">' + sessionStatus + '</div>');
                    dayContent.append(setDiv);

                    if(jsonIt < json.length - 1){
                        jsonIt++;
                    }
                }
                
                // add day number in any case
                dayContent.append('<div class = "day-number">' + dayCount + '</div>');
                

                dayData.content = dayContent;
                dayData.day_number = dayCount;
                daysData[i] = dayData;

            }
            appendWeek(daysData);
        }

        if(extraWeeks >0 && !clearTable){

            for(var i = 0; i < extraWeeks; i++){
                addWeek();
            }
        }

        // make cells droppable
        makeDroppable();

    });
}

// actually adds a week to the table using provided data
function appendWeek(daysData){

    var week = $('<tr>');
    for(var i = 0; i < 7; i++){

        var day = $('<td class = "no-pad">');
        var dayDiv = daysData[i].content;

        dayDiv.data('day_number', daysData[i].day_number);

        day.append(dayDiv);
        week.append(day);
    }

    $('table#days tbody').append(week);
}

function showEditProgramPopup(){

	$.getJSON(baseUrl + 'json/programinfo', {id : programId}, function(json){

		$('#program-edit form')[0].reset();
		$('#program-edit form').populate(json[0]);
		$('#program-edit').dialog('option','title', 'Edit program');
		$('#program-edit').dialog('open');
		//alert(json[0].title);
	});
}

function showAddSetDialog(dayNumber){

    $('#set-edit form')[0].reset();
    $('#set-edit form input[name="id"]').val('');
    $('#set-edit form input[name="title"]').val(programTitle + ' - day ' + dayNumber);
    $('#set-edit form input[name="program_id"]').val(programId);
    $('#set-edit form input[name="day_number"]').val(dayNumber); 
    $('#set-edit').dialog('option','title', 'Add workout'); 
    $('#set-edit').dialog('open');
}

function showEditSetDialog(setId){

    $.getJSON(baseUrl + 'json/setinfo', {id : setId}, function(json){

        $('#set-edit form')[0].reset();
        $('#set-edit form input[name="program_id"]').val('');
        $('#set-edit form input[name="day_number"]').val('');
        $('#set-edit form').populate(json[0]);
        $('#set-edit').dialog('option','title', 'Edit workout'); 
        $('#set-edit').dialog('open');
    });
}

// adds an empty week to the table
function addWeek(){
    var weeks = $('table#days tbody tr').size();

    var daysData = new Array();

    // starting new week
    dayCount = weeks*7;
    for(var i = 0; i < 7; i++){

        dayCount++;
        var dayData = new Object;
        var dayContent = $('<div class = "workout">');
        dayContent.append('<div class = "day-number">' + dayCount + '</div>');

        dayData.content = dayContent;
        dayData.day_number = dayCount;
        daysData[i] = dayData;
    }
    appendWeek(daysData);
}

function moveSet(connectorId, dayNumber){

    $.post(baseUrl + 'ajaxpost/moveset', {connector_id: connectorId, day_number: dayNumber}, function(json){

        if(json.result == 'OK'){

            fillDays();
        }
    },"json");
}

function removeSet(connectorId){

    fancyConfirm('Confirm delete', 'Are you sure you want to delete this workout?', function(){
        $.post(baseUrl + 'ajaxpost/removefromprogram', {connector_id: connectorId}, function(json){

            if(json.result == 'OK'){

                fillDays();
            }
        },"json");
    });
}

// hides elements which supposed to be hidden
// empties the table and so on
function resetPage(){
    $('#program-description').html('');
    $('#desc-holder').hide();
    $('#set-exercises').hide();
    $('#days').hide();
}

function showExportProgramPopup(){

    $.getJSON(baseUrl + 'json/programinfo', {id : programId}, function(json){

        fancyConfirm('Confirm export', 'Are you sure you want to export this program - "' + json[0].title + '"?', function(){

            $.post(baseUrl + 'ajaxpost/exportprogram', {id : programId}, function(json){

                if(json.result == 'OK'){

                    fillPrograms();
                }
            },"json");
        });
    });
}