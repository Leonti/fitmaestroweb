
var units = 'kg';
var months = [
"Jan", 
"Feb",
"March", 
"April",
"May", 
"June", 
"July", 
"August", 
"September",
"October",
"November",
"December"];

$(function () {

    $('#get-stats').click(function(){
        getStats();
        return false;
    });
    
    $('#stats-type').change(function(){

        if($('#stats-type').val() == 'exercise_log'){
            $('#exercise-holder').show();
            $('#measurements-type').hide();
            $('#statistics-list').show();
            $('#measurements-log-list').hide();
            
            // if exercise was not previously selected - open selection box
            if(!$('#exercise-holder').data('id')){
                exerciseChooser(changeExercise);
            }
        }else{
            $('#exercise-holder').hide();
            $('#measurements-type').show();
            $('#statistics-list').hide();
            $('#measurements-log-list').show();
        }
    });
    
    $('#exercise-change').click(function(){
        exerciseChooser(changeExercise);
        return false;
    });
    
    getStats();
});

function changeExercise(exerciseId, exerciseTitle){
	
    $('#exercise-holder').data('id', exerciseId);
    $('#exercise-name').text(exerciseTitle);
}

function getStats(){
    var statsType = $('#stats-type').val();
    //var statsType = 'exercise_log';
    
    var startDate = $('#start-date').val();
    var endDate = $('#end-date').val();
    
    var subType = $('#stats-subtype').val();
    //var subType = 'total';
    
    // if type is for exercise stats, if not - null will be assigned
    var exerciseId = $('#exercise-holder').data('id');
    //var exerciseId = 147;
    
    var measurement_type_id = $('#measurements-type').val();
    
    var requestUrl = baseUrl + 'json/statistics/' + statsType;
    $.getJSON(requestUrl, {id : exerciseId, type: subType, startdate: startDate, enddate: endDate, measurement_type_id: measurement_type_id}, function(json){
              
        $('#statistics-list tbody tr').remove();
        
        if(statsType == 'exercise_log'){
            getExercisesChart(json, subType);
        }else if(statsType == 'measurements_log'){
            getMeasurementsChart(json);
        }

    });
}

function getExercisesChart(json, subType){

    $.each(json.stats, function(i, jsonrow){
        var date = new Date(Date.parse(jsonrow.date));

        var statsRow = '';
        if(jsonrow.datas.length > 0){                
           
           statsRow = $('<tr>');
           statsRow.append('<td>' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear() + '</td>');

           var sums = '<div class = "sums">Max value: ' + jsonrow.datas[0].max + '<br />Sum: ' + jsonrow.datas[0].sum + '</div>';
            var sessionTitle = jsonrow.datas[0].session.title;
            var sessionId = jsonrow.datas[0].session.id;
            statsRow.append('<td><div class = "session-name"><a href=' + baseUrl + 'sessions/index/'
            + sessionId + ' target="_blank">' 
            + sessionTitle + '</a>' + sums + '</div></td>');
            var repsTd = $('<td>');
            var weightTd = $('<td class = "to-hide">');
            $.each(jsonrow.datas[0].reps, function(j, repsrow){
                repsTd.append('<div>' + repsrow.reps + '</div>');
                weightTd.append('<div>' + repsrow.weight + ' ' + weightUnits + '</div>');
            });
            statsRow.append(repsTd);
            statsRow.append(weightTd);
        }
        
        $('#statistics-list tbody').append(statsRow);
    });
    
    if(json.exercise.ex_type == 1){
        $('.to-hide').show();
    }else{
        $('.to-hide').hide();
    }
    
    var chart_image = '<img src = "' + json.chart_url + '" />';
    $('#holder').html(chart_image);
}

function getMeasurementsChart(json){
    fillMeasurementsTable(json.data, '#measurements-log-list');
    var chart_image = '<img src = "' + json.chart_url + '" />';
    $('#holder').html(chart_image);
}

// get suffix for value
function getSuff(val){
    return (val==1) ? '' : 's';
}