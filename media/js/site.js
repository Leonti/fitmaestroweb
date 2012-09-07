var addExcallback;

var statusesMap = new Array();
statusesMap['INPROGRESS'] = 'In Progress';
statusesMap['DONE'] = 'Done';

$(function() {

    $('#select-exercise').dialog({autoOpen:false, width:'auto'});

    // we need acordion for exercise import
    $('.accordion').accordion( {autoHeight: false} );
    $('#group-select').change(function(){
        var group_id = $(this).val();
        
        $.getJSON(baseUrl + 'json/exercisesbygroup', {id: group_id}, function(json){
            $('#exercise-select').empty();
            $.each(json, function(i, jsonrow){
                $('#exercise-select').append('<option value = "' + jsonrow.id + '">' + jsonrow.title + '</option>');
            });
        });
    });
    
    $('#group-select').change();
    
    $('#select-exercise-button').click(function(){
    	
        addExCallback($('#exercise-select').val(), $('#exercise-select option:selected').text());
        $('#select-exercise').dialog('close');
        return false;
    });
    
    $('#start-date').datepicker();
    $('#end-date').datepicker();
});

function getExerciseTypeName(typeInt){
    if(typeInt == 1){
        return 'With weight';
    }else if(typeInt == 0){
        return 'Own Weight';
    }
}

function exerciseChooser(callback){

    addExCallback = callback;
    $('#select-exercise').dialog('open');
}

function makeZebra(zebra){

    var odd = true;
    zebra.children().each(
        function(){
            if(odd){
                $(this).addClass('odd');
                odd = false;
            }else{
                odd = true;
            }
        });
}

var percentages = {
    calculateReps: function(percentage, maxReps){
        return percentage != 0 ? Math.round(percentage*maxReps/100) : 0;
    },

    calculateWeight: function(percentage, maxWeight){
        var calculatedValue = percentage != 0 ? percentage*maxWeight/100 : 0;
        var steppedNumber = multiplicator != 0 ? Math.round(calculatedValue / multiplicator) * multiplicator
				: Math.round(calculatedValue);
        return steppedNumber;
    }
};
