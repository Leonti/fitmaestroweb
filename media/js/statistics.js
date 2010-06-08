
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

    
    $('#start-date').datepicker();
    $('#end-date').datepicker();
    $('#get-stats').click(function(){
        getStats();
    });
    
    $('#stats-type').change(function(){

        if($('#stats-type').val() == 'exercise_log'){
            $('#exercise-holder').show();
            
            // if exercise was not previously selected - open selection box
            if(!$('#exercise-holder').data('id')){
                exerciseChooser(changeExercise);
            }
        }else{
            $('#exercise-holder').hide();
        }
    });
    
    $('#exercise-change').click(function(){
        exerciseChooser(changeExercise);
    });
    
    getStats();
});

function changeExercise(exerciseId){

    $('#exercise-holder').data('id', exerciseId);
    $('#exercise-name').text(exerciseId);
}

Raphael.fn.drawGrid = function (x, y, w, h, wv, hv, color) {
    
    color = color || "#000";
    var path = ["M", x, y, "L", x + w, y, x + w, y + h, x, y + h, x, y],
    rowHeight = h / hv,
    columnWidth = w / wv;
    for (var i = 1; i < hv; i++) {
        path = path.concat(["M", x, y + i * rowHeight, "L", x + w, y + i * rowHeight]);
    }
    for (var i = 1; i < wv; i++) {
        path = path.concat(["M", x + i * columnWidth, y, "L", x + i * columnWidth, y + h]);
    }
    return this.path(path.join(",")).attr({stroke: color});
};

window.onload = function () {
    // Grab the data
    var labels = [],
    data = [];
    $("#data tfoot th").each(function () {
        labels.push($(this).html());
    });
    $("#data tbody td").each(function () {
        data.push($(this).html());
    });
    
   // drawChart(labels, data);
    
    /*
    $('#holder').empty();
    drawChart(labels, data);
    */
    }
    
function drawChart(labels, data, statsType, subType, exercise){
    
    $('#holder').empty();
    
    // Draw
    var width = 775,
    height = 250,
    leftgutter = 0,
    bottomgutter = 40,
    topgutter = 20,
    colorhue = .6 || Math.random(),
    color = "hsb(" + [colorhue, 1, .75] + ")",
    r = Raphael("holder", width, height),
    txt = {font: '12px Fontin-Sans, Arial', fill: "#000"},
    txt1 = {font: '10px Fontin-Sans, Arial', fill: "#000"},
    txt2 = {font: '12px Fontin-Sans, Arial', fill: "#000"},
    X = (width - leftgutter) / labels.length,
    max = Math.max.apply(Math, data),
    Y = (height - bottomgutter - topgutter) / max;
    r.drawGrid(leftgutter + X * .5, topgutter, width - leftgutter - X, height - topgutter - bottomgutter, 10, 10, "#333");
    var path = r.path().attr({stroke: color, "stroke-width": 4, "stroke-linejoin": "round"}),
    bgp = r.path().attr({stroke: "none", opacity: .3, fill: color}).moveTo(leftgutter + X * .5, height - bottomgutter),
    frame = r.rect(10, 10, 100, 40, 5).attr({fill: "#fff", stroke: "#474747", "stroke-width": 2}).hide(),
    label = [],
    is_label_visible = false,
    leave_timer,
    blanket = r.set();
    label[0] = r.text(60, 10, "24 hits").attr(txt).hide();
    label[1] = r.text(60, 40, "22 September 2008").attr(txt1).attr({fill: color}).hide();
    
    for (var i = 0, ii = labels.length; i < ii; i++) {
        var y = Math.round(height - bottomgutter - Y * data[i]),
        x = Math.round(leftgutter + X * (i + .5)),
        t = r.text(x, height - 26, labels[i].getDate()).attr(txt).toBack();
        bgp[i == 0 ? "lineTo" : "cplineTo"](x, y, 10);
        path[i == 0 ? "moveTo" : "cplineTo"](x, y, 10);
        
        // in the beginning of the chart or on start of month - display the month name
        if(i ==0 || labels[i].getDate() == 1){
            var month_t = r.text(x, height - 6, months[labels[i].getMonth()]).attr(txt).toBack();
        }

        var rad = 5;
        if(data[i] == 0 ){
            rad = 1;
        }
        var dot = r.circle(x, y, rad).attr({fill: color, stroke: color});
        
        blanket.push(r.rect(leftgutter + X * i, 0, X, height - bottomgutter).attr({stroke: "none", fill: "#fff", opacity: 0}));
        var rect = blanket[blanket.length - 1];
        (function (x, y, data, lbl, dot) {
            if(data != 0){
                var timer, i = 0;
                $(rect.node).hover(function () {
                    clearTimeout(leave_timer);
                    var newcoord = {x: +x + 7.5, y: y - 19};
                    if (newcoord.x + 100 > width) {
                        newcoord.x -= 114;
                    }
                    frame.show().animate({x: newcoord.x, y: newcoord.y}, 200 * is_label_visible);
                    
                    var caption = '';
                    if(statsType == 'exercise_log'){
                        switch(subType){
                            case 'max':
                                
                                // with weight so label should be something like kgs
                                if(exercise.ex_type == 1){
                                    caption = data + ' ' + units + ((data % 10 == 1) ? "" : "s");
                                    
                                    // just reps
                                }else{
                                    caption = data + ' reps';
                                }

                            break;
                            
                            case 'total':
                                
                                // with weight so label should be something like kgs*reps
                                if(exercise.ex_type == 1){
                                    caption = data + ' ' + units + ((data % 10 == 1) ? "" : "s") + '*reps';
                                // just reps
                                }else{
                                    caption = data + ' ' + 'reps';
                                }
                                
                            break;
                        }
                    
                    }else if(statsType == 'weight_log'){
                    
                    
                    }
                    
                    date_caption = lbl.getDate() + ' ' + months[lbl.getMonth()] + ' ' + lbl.getFullYear();
                    
                    label[0].attr({text: caption}).show().animateWith(frame, {x: +newcoord.x + 50, y: +newcoord.y + 12}, 200 * is_label_visible);
                    label[1].attr({text: date_caption}).show().animateWith(frame, {x: +newcoord.x + 50, y: +newcoord.y + 27}, 200 * is_label_visible);
                    dot.attr("r", 7);
                    is_label_visible = true;
                }, function () {
                    dot.attr("r", 5);
                    leave_timer = setTimeout(function () {
                        frame.hide();
                        label[0].hide();
                        label[1].hide();
                        is_label_visible = false;
                        // r.safari();
                    }, 1);
                });
            }    
        })(x, y, data[i], labels[i], dot);
        
        
        
    }
    bgp.lineTo(x, height - bottomgutter).andClose();
    frame.toFront();
    label[0].toFront();
    label[1].toFront();
    blanket.toFront();
}
    
    
function getStats(){
    //var statsType = $('#stats-type').val();
    var statsType = 'exercise_log';
    
    var startDate = $('#start-date').val();
    var endDate = $('#end-date').val();
    
    var subType = $('#stats-subtype').val();
    //var subType = 'total';
    
    // if type is for exercise stats, if not - null will be assigned
    var exerciseId = $('#exercise-holder').data('id');
    //var exerciseId = 147;
    
    var requestUrl = baseUrl + 'json/statistics/' + statsType;
    
    $.getJSON(requestUrl, {id : exerciseId, type: subType, startdate: startDate, enddate: endDate}, function(json){

        var labels = [],
              data = [];
              
        $('#statistics-list tbody tr').remove();
        $.each(json.stats, function(i, jsonrow){
            var date = new Date(Date.parse(jsonrow.date));
            labels.push(date);

            var statsRow = $('<tr>');
            statsRow.append('<td>' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear() + '</td>');

            
            if(jsonrow.datas.length > 0){                
                data.push(jsonrow.datas[0].data);
                // fill with relevant data
                
                var sessionTitle = jsonrow.datas[0].session.title;
                var sessionId = jsonrow.datas[0].session.id;
                statsRow.append('<td><a href=' + baseUrl + 'sessions/index/' 
                                + sessionId + ' target="_blank">' 
                                + sessionTitle + '</a></td>');
                var repsTd = $('<td>');
                var weightTd = $('<td class = "to-hide">');
                $.each(jsonrow.datas[0].reps, function(j, repsrow){
                    repsTd.append('<div>' + repsrow.reps + '</div>');
                    weightTd.append('<div>' + repsrow.weight + '</div>');
                });
                statsRow.append(repsTd);
                statsRow.append(weightTd);
            }else{
                data.push(0);
                
                // empty cells
                statsRow.append('<td>--</td><td>--</td><td class = "to-hide">--</td>');
            }
            
            $('#statistics-list tbody').append(statsRow);
        });
        
        var exercise;
        if(statsType == 'exercise_log'){
            exercise = json.exercise;
            if(json.exercise.ex_type == 1){
                $('.to-hide').show();
            }else{
                $('.to-hide').hide();
            }
        }
        
        drawChart(labels, data, statsType, subType, exercise);
    });
}

// get suffix for value
function getSuff(val){
    return (val==1) ? '' : 's';
}