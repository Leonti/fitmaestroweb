var programId = 0;
var programsConnectorId = 0;
var clearTable = false;

$(function() {

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
					programId = null;
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

		$('ul#program-list li').removeClass('selected');

        $(this).addClass('selected');
        $('div#program-description').html($(this).data('desc'));
        $('ul#program-list li.selected div').remove();

        exportLink = '';
        // it means it doesn't have a copy in public programs
        if($(this).data('import_id') == 0){
            exportLink = '<a class = "export" href="#">Export</a>';
        }
        $('ul#program-list li.selected').append('<div class = "edit-program-box"><a class = "edit" href="#">Edit</a><a class = "delete" href="#">Delete</a>' + exportLink + '</div>');

        fillDays();
        return false;
    });

    $('a.set-details').live('click', function(){

        setId = $(this).parent().data('set_id');
        programsConnectorId = $(this).parent().data('connector_id');

        // do not allow to start another session
        if($(this).parent().data('session_id')){
            $('#start-session-link').hide();
        }else{
            $('#start-session-link').show();
        }

        fillSetExercises();
        return false;
    });

    $('a.remove-set').live('click', function(){

        removeSet($(this).parent().data('connector_id'));
        return false;
    });

    // day in days "table"
    $('table#days tbody tr td div').live('click', function(e){

        if($(e.target).is('a')){

        }else{

            // check if it already has set attached
            // if not - add new set
            if($(this).data('set_id')){
                showEditSetDialog($(this).data('set_id'));
            }else{
                showAddSetDialog($(this).data('day_number'));
            }
        }
    });

    $('#add-week').click(function(){
        addWeek();
        return false;
    });

$('table#days tbody tr td div a').live('mouseover', function() { 
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
 
    $('table#days tbody tr td div').droppable({
        accept: 'table#days tbody tr td div a',
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
			li.data('desc', jsonrow.desc);
            li.data('import_id', jsonrow.import_id);
			li.append(jsonrow.title);
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
                var dayContent = $('<div>');

                if(json[jsonIt] && json[jsonIt].day_number == dayCount){

                    // create link to bring user to set editing page
                    var setLink = $('<a href = "#">');
                    setLink.addClass('set-details');
                    dayContent.data('set_id', json[jsonIt].set_id);
                    dayContent.data('connector_id', json[jsonIt].id);
                    setLink.append(json[jsonIt].title);
                    dayContent.append(setLink);
                    dayContent.append('<br /><a href = "#" class = "remove-set">Remove</a><br />');

                    if(json[jsonIt].session.id){
                        dayContent.append(
                            '<a href = "' + baseUrl + 'sessions/index/' + json[jsonIt].session.id + '">' 
                            + json[jsonIt].session.status + '</a>');
                        dayContent.data('session_id', json[jsonIt].session.id);
                    }

                    if(jsonIt < json.length - 1){
                        jsonIt++;
                    }
                }else{
                    dayContent.append(dayCount);
                }

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

        var day = $('<td>');
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
    $('#set-edit form input[name="program_id"]').val(programId);
    $('#set-edit form input[name="day_number"]').val(dayNumber); 
    $('#set-edit').dialog('option','title', 'Add plan'); 
    $('#set-edit').dialog('open');
}

function showEditSetDialog(setId){

    $.getJSON(baseUrl + 'json/setinfo', {id : setId}, function(json){

        $('#set-edit form')[0].reset();
        $('#set-edit form input[name="program_id"]').val('');
        $('#set-edit form input[name="day_number"]').val('');
        $('#set-edit form').populate(json[0]);
        $('#set-edit').dialog('option','title', 'Edit plan'); 
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
        var dayContent = $('<div>');
        dayContent.append(dayCount);

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

    fancyConfirm('Confirm delete', 'Are you sure you want to delete this plan?', function(){
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