var sessionId = 0;

$(function() {
    $('#reps-edit').dialog({autoOpen:false});
    fillSessions();

    $('ul#session-list li').live('click', function(){

    $('ul#session-list li div.edit-set-box').remove();
    sessionId = $(this).data('id');;

    $('ul#session-list li').removeClass('selected');

    $(this).addClass('selected');
    $('div#session-description').html($(this).data('notes'));
    $(this).append('<div class = "edit-set-box"><a class = "edit" href="#">Edit</a><a class = "delete" href="#">Delete</a></div>');

    fillSessionExercises();
    });

    $('.reps').live('click', function(){

        $('#reps-edit form input[name="session_id"]').val(sessionId);
        $('#reps-table tbody tr').remove();
        var maxWeight = parseFloat($(this).parent().data('maxWeight'));

        var details = $(this).data('details');
        $.each(details, function(i, detailrow){

            appendRepsRow(detailrow, maxWeight);
        });

        if(details.length == 0){

            appendRepsRow();
        }
        $('#reps-edit').dialog('open');
    });

    $('#reps-table tbody tr:last input[type="text"]').live('click', function(){

        appendRepsRow();
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

            $.post('ajaxpost/savesessionreps', dataString, function(json){

                if(json.result == 'OK'){

                    fillSessionExercises();
                }
                $('#reps-edit').dialog('close');
            },"json");

            $('#reps-edit').dialog('close');

        return false;
    });

});

function fillSessions(){

    $.getJSON('json/sessions', function(json){

        $('ul#session-list li').remove();

        $.each(json, function(i, jsonrow){

            var li = $('<li>');
            li.data('id', jsonrow.id);
            li.data('setId', jsonrow.set_id);
            li.data('notes', jsonrow.notes);
            li.append(jsonrow.title);
            $('ul#session-list').append(li);
            if(sessionId == jsonrow.id){
                li.addClass('selected');
                li.trigger('click');
            }
        });

    });
}

function fillSessionExercises(){

    $('#session-exercises').show();

    $.getJSON('json/sessionexercises', {id : sessionId}, function(json){

            $('table#session-exercise-list tbody').remove();
            $('table#session-exercise-list').append($('<tbody>'));

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
                    var done = '';
                    if(detailrow.log_data){
                        done = detailrow.log_data.id;
                    }
                    repsTd.append('<div>' + detailrow.reps + 'x' + detailrow.percentage + '% ' + done + '</div>');
                });
                tr.append(repsTd);

                tr.append('<td><a href = "#" class = "remove-from-set">Remove</a></td>');

                $('table#session-exercise-list tbody').append(tr);
            });
    });

}

function appendRepsRow(details, maxWeight){

    var reps = '';
    var weight = '';
    var repId = '';
    var logId = '';


    if(typeof(details) != 'undefined'){

        repId = details.id;
        if(details.log_data){
            reps = details.log_data.reps;
            weight = details.log_data.weight;
            logId = details.log_data.id;
        }else{
            reps = details.reps;
            weight = (parseFloat(details.percentage) * maxWeight)/100;
        }
    }

    var grayed = '';
    var checkAttr = '';
    if(!logId){
        grayed = 'class = "grayed"';
    }else{

        // make checkbox checked
        checkAttr = 'checked = "checked"';
    }
    $('#reps-table').append('<tr ' + grayed + '><td><input type = "text" name = "reps[]" value = "' + reps + '" /></td><td>x</td>'
        + '<td><input type = "text" name = "weight[]" value = "' + weight + '" />'
        + '<input type = "hidden" name = "rep_id[]" value = "' + repId + '" /></td>'
        + '<input type = "hidden" name = "log_id[]" value = "' + logId + '" /></td>'
        + '<td><input type = "checkbox" name = done[] ' + checkAttr + ' /></td></tr>');
}