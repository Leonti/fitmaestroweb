var sessionId = 0;
var exType = 0;
var time24h = true;
var sessionsFilter = 'INPROGRESS';

$(function() {

    $('#reps-edit').dialog({autoOpen:false});
    $('#session-edit').dialog({autoOpen:false});

    $('#sessions-inprogress').addClass('selected');
    
    initialSetup();
    fillSessions();

    if(timeFormat == "ampm"){
        time24h = false;
    }

    $('#start-session').click(function(){
        $('#session-edit form')[0].reset();
        $('#session-edit form input[type="hidden"]').val('');
        $('#session-edit').dialog('option','title', 'Start session'); 
        $('#session-edit').dialog('open');
        return false;
    });

    $('ul#session-list li').live('click', function(){

        $('ul#session-list li div.edit-session-box').remove();
        sessionId = $(this).data('id');;

        $('ul#session-list li').removeClass('selected');

        $(this).addClass('selected');
        $('div#session-description').html($(this).data('desc'));

        var editBox = '<div class = "edit-session-box edit-box">' 
                        + '<a class = "edit" href="#"></a>'
                        + '<a class = "done" href="#"></a>'
                        + '<a class = "delete" href="#"></a></div>';

        $(this).append(editBox);

        fillSessionExercises();
        return false;
    });

    $('#exercise-link').click(function(){

        exerciseChooser(addExerciseToSession);
        return false;
    });

    $('#session-edit form input[type="submit"]').click(function(){

        saveSession();
        return false;
    });

    $('.reps').live('click', function(){

        openRepsDialog($(this));
        return false;
    });

    $('#reps-table tbody tr:last input[type="text"]').live('click', function(){

        appendRepsRow();
    });

    // check chackbox when clicking onthe row
    $('#reps-table tbody tr input[type="text"]').live('click', function(){

        $(this).parent().parent().find('input[type="checkbox"]').attr('checked', 'checked');
        $(this).parent().parent().find('input[type="checkbox"]').trigger('change');
        return false;
    });

    $('#reps-table tbody tr input[type="checkbox"]').live('change', function(){

        if ($(this).attr('checked')) {
            $(this).parent().parent().removeClass('grayed');
        } else {
            $(this).parent().parent().addClass('grayed');
        }
    });

    $('#reps-edit form input[type="submit"]').click(function(){

            var dataString = $('#reps-edit form').serialize();

            $.post(baseUrl + 'ajaxpost/savesessionreps', dataString, function(json){

                if(json.result == 'OK'){

                    fillSessionExercises();
                }
                $('#reps-edit').dialog('close');
            },"json");

            $('#reps-edit').dialog('close');

        return false;
    });

    $('ul#session-list li div.edit-session-box a.edit').live('click', function(){

        showEditSessionPopup();
        return false;
    });


    $('ul#session-list li div.edit-session-box a.done').live('click', function(){

        var saveData = {id: sessionId, status: 'DONE'};
        sendSaveSession(saveData);
        return false;
    });

    $('ul#session-list li div.edit-session-box a.delete').live('click', function(){

        fancyConfirm('Confirm delete', 'Are you sure you want to delete this session?', function(){

            $.post(baseUrl + 'ajaxpost/deletesession', {id : sessionId}, function(json){

                if(json.result == 'OK'){
                    sessionId = 0;
                    fillSessions();
                }
            },"json");
        });
        return false;
    });

    $('#print-plan').click(function(){

        $('#session-exercise-list').jqprint();
        return false;
    });

    $('#sessions-filter li').click(function(){

        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');

        if($(this).attr('id') == 'sessions-inprogress'){
            sessionsFilter = 'INPROGRESS';
        }else if($(this).attr('id') == 'sessions-done'){
            sessionsFilter = 'DONE';
        }else if($(this).attr('id') == 'sessions-all'){
            sessionsFilter = '';
        }

        fillSessions(true);
    });
    
    $('.remove-from-session').live('click', function(){

        removeExerciseFromSession($(this).parent().parent().data('connector_id'));
        return false;
    });

});

function initialSetup(){
    
    // if we are loading the page with id predefined - expand that session
    sessionId = startSessionId;
    sessionsFilter = startSessionFilter;
}

function fillSessions(select_last){

    $.getJSON(baseUrl + 'json/sessions', {status: sessionsFilter}, function(json){

        $('ul#session-list li').remove();

        $.each(json, function(i, jsonrow){

            var li = $('<li>');
            li.data('id', jsonrow.id);
            li.data('status', jsonrow.status);
            li.data('desc', jsonrow.desc);
            li.append('<div class = "list-title">' + jsonrow.title + '</div>');
            $('ul#session-list').append(li);

            if(sessionId == jsonrow.id){
                li.addClass('selected');
                li.trigger('click');
            }
        });
        makeZebra($('ul#session-list'));

        if(sessionId == 0 || select_last){
            $('ul#session-list li:last').trigger('click');
        }
    });

}

function fillSessionExercises(){

    $.getJSON(baseUrl + 'json/sessionexercises', {id : sessionId}, function(json){

            $('table#session-exercise-list tbody').remove();
            $('table#session-exercise-list').append($('<tbody>'));

            if(json.length > 0){
                $.each(json, function(i, jsonrow){

                    var tr = $('<tr>');
                    tr.data('id', jsonrow.id);
                    tr.data('maxWeight', jsonrow.max_weight);
                    tr.data('connector_id', jsonrow.sessions_connector_id);
                    tr.data('ex_type', jsonrow.ex_type);
                    tr.data('details', jsonrow.details);
                    tr.append('<td>' + jsonrow.title + '</td><td>'
                        + jsonrow.desc + '</td><td>'
                        + getExerciseTypeName(jsonrow.ex_type) + '</td><td>'
                        + jsonrow.group_title + '</td>' );

                    var repsTd = $('<td>');
                    repsTd.addClass('reps');

                    var doneTd = $('<td>');
                    doneTd.addClass('reps');

                    $.each(jsonrow.details, function(i, detailrow){

                        var done = 'not done';

                        var toAppend = '';
                        var toAppendDone = 'not done';

                        if(jsonrow.ex_type == 1){
                            toAppend = detailrow.reps + 'x' + detailrow.percentage + '%';

                            if(detailrow.log_data){
                                toAppendDone = detailrow.log_data.reps + 'x' + detailrow.log_data.weight + ' kg';
                            }
                        }else{
                            toAppend = detailrow.reps;

                            if(detailrow.log_data){
                                toAppendDone = detailrow.log_data.reps;
                            }
                        }

                        if(detailrow.log_data){
                            done = detailrow.log_data.reps + 'x' + detailrow.log_data.weight + ' kg';
                        }
                        if(detailrow.id != 0){
                            repsTd.append('<div>' + toAppend + '</div>');
                        }else{
                            repsTd.append('<div>extra</div>');
                        }

                        doneTd.append('<div>' + toAppendDone + '</div>');

                    });
                    tr.append(repsTd);
                    tr.append(doneTd);

                    tr.append('<td class = "non-printable no-pad"><a href = "#" class = "remove-from-session delete"></a></td>');

                    $('table#session-exercise-list tbody').append(tr);
                });
            }else{
                $('table#session-exercise-list tbody').append('<tr><td colspan = "7" class = "center">No exercises added yet!</td></tr>');
            }
            makeZebra($('table#session-exercise-list tbody'));
    });

}

function showEditSessionPopup(){

    $.getJSON(baseUrl + 'json/sessioninfo', {id : sessionId}, function(json){

        $('#session-edit form')[0].reset();
        $('#session-edit form').populate(json[0]);
        $('#session-edit').dialog('option','title', 'Edit session'); 
        $('#session-edit').dialog('open');
    });
}

function appendRepsRow(details, maxWeight){

    var lastDate = $('#reps-table tbody tr:last input[name="done[]"]').val(); 
    if(typeof(lastDate) == 'undefined'){
        lastDate = $('#reps-edit').data('formatted_time');
    }else{
        lastDate = addDiff(lastDate, $('#reps-edit select[name="autostep"]').val());
    }

    var reps = '';
    var weight = '';
    var repId = '';
    var logId = '';
    var done = '';


    if(typeof(details) != 'undefined'){

        repId = details.id;
        if(details.log_data){
            reps = details.log_data.reps;
            weight = details.log_data.weight;
            done = details.log_data.done;
            logId = details.log_data.id;
        }else{
            reps = details.reps;
            weight = (parseFloat(details.percentage) * maxWeight)/100;
        }
    }else{
        done = lastDate;
    }

    var grayed = '';
    var checkAttr = '';
    if(!logId){
        grayed = 'class = "grayed"';
    }else{

        // make checkbox checked
        checkAttr = 'checked = "checked"';
    }

    if(exType == 1){
        $('#reps-table tbody').append('<tr ' + grayed + '><td><input type = "text" name = "reps[]" value = "' + reps + '" /></td><td>x</td>'
            + '<td><input type = "text" name = "weight[]" value = "' + weight + '" /></td>'
            + '<td><input class = "time" type = "text" name = "done[]" value = "' + done + '" />'
            + '<input type = "hidden" name = "rep_id[]" value = "' + repId + '" />'
            + '<input type = "hidden" name = "log_id[]" value = "' + logId + '" /></td>'
            + '<td><input type = "checkbox" name = isDone[] ' + checkAttr + ' /></td></tr>');
    }else if(exType == 0){
        $('#reps-table tbody').append('<tr ' + grayed + '><td><input type = "text" name = "reps[]" value = "' + reps + '" /></td>'
            + '<td><input type = "hidden" name = "weight[]" value = "0" />'
            + '<input class = "time" type = "text" name = "done[]" value = "' + done + '" />'
            + '<input type = "hidden" name = "rep_id[]" value = "' + repId + '" />'
            + '<input type = "hidden" name = "log_id[]" value = "' + logId + '" /></td>'
            + '<td><input type = "checkbox" name = isDone[] ' + checkAttr + ' /></td></tr>');
    }


    $('.time').datepicker({  
         duration: '',  
         showTime: true,  
         constrainInput: false,  
         stepMinutes: 1,  
         stepHours: 1,  
         altTimeField: '',  
         time24h: time24h  
      });  
}

function addExerciseToSession(exerciseId){


    $.post(baseUrl + 'ajaxpost/sessionaddexercise', {session_id : sessionId, exercise_id : exerciseId}, function(json){
        if(json.result == 'OK'){

            fillSessionExercises();
        }
    },"json");

}

function saveSession(){

    if($.trim(($('#session-edit form input[name = "title"]').val())) != ''){
        var dataString = $('#session-edit form').serialize();
        sendSaveSession(dataString);

        $('#session-edit').dialog('close');
    }
}

function sendSaveSession(data){

        $.post(baseUrl + 'ajaxpost/savesession', data, function(json){

            if(json.result == 'OK'){

                // if we set session to 'DONE' - it disappears from list, so we must reset
                if(data.status && data.status == 'DONE'){
                    sessionId = 0;
                }
                fillSessions();
            }
        },"json");
}

function openRepsDialog(repsTd){
    $('#reps-edit form input[name="session_id"]').val(sessionId);
    $('#reps-edit form input[name="exercise_id"]').val(repsTd.parent().data('id'));
    $('#reps-table tbody tr').remove();

    exType = repsTd.parent().data('ex_type');
    var maxWeight = parseFloat(repsTd.parent().data('maxWeight'));

    var details = repsTd.parent().data('details');

    $.getJSON(baseUrl + 'json/getdatetime', function(json){

        $('#reps-edit').data('formatted_time', json.formatted_time);

        $.each(details, function(i, detailrow){

            appendRepsRow(detailrow, maxWeight);
        });

        if(details.length == 0){

            appendRepsRow();
        }

        if(exType == 1){
            $('.to-hide').show();
        }else if (exType == 0){
            $('.to-hide').hide();
        }

        $('#reps-edit').dialog('open');

    });
}

function addDiff(time, diff){

    formattedTime = 'error';
    $.ajax({
        url: baseUrl + 'json/getdatetime',
        async: false,
        dataType: 'json',
        data: {time: time, diff: diff},
        success: function(json){
            formattedTime = json.formatted_time;
        }
    });

    return formattedTime;
}

function removeExerciseFromSession(connectorId){

    fancyConfirm('Confirm delete', 'Are you sure you want to remove exercise from this session?', function(){

        $.post(baseUrl + 'ajaxpost/sessiondeleteexercise', {id : connectorId}, function(json){

            if(json.result == 'OK'){

                fillSessionExercises();
            }
        },"json");
            });
    return false;
}
