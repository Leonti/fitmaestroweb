var measurementTypeId = 0;
var time24h = true;

$(function() {
    $('#measurement-edit').dialog({autoOpen:false});
    
    fillMeasurementTypes();

    if(timeFormat == "ampm"){
        time24h = false;
    }
    
    $('#add-measurement').click(function(){
        $('#measurement-edit form')[0].reset();
        $('#measurement-edit form input[type="hidden"]').val('');
        $('#measurement-edit').dialog('option','title', 'Add measurement'); 
        $('#measurement-edit').dialog('open');
        return false;
    });
    
    $('ul#measurement-types-list li').live('click', function(){
        
        $('ul#measurement-types-list li div.edit-measurement-type-box').remove();
        measurementTypeId = $(this).data('id');;
        
        $('ul#measurement-types-list li').removeClass('selected');
        
        $(this).addClass('selected');
        $('div#measurement-type-description').html($(this).data('desc'));
        
        var editBox = '<div class = "edit-measurement-type-box edit-box">' 
        + '<a class = "edit" href="#"></a>'
        + '<a class = "done" href="#"></a>'
        + '<a class = "delete" href="#"></a></div>';
        
        $(this).append(editBox);
        
        fillMeasurementLog();
        return false;
    });
    
    // save measurement info - add or edit depending on id value
    // decided in ajaxpost
    $('#measurement-edit form input[type="submit"]').click(function(){
        
        var dataString = $('#measurement-edit form').serialize();
        $.post(baseUrl + 'ajaxpost/savemeasurement', dataString, function(json){
            
            if(json.result == 'OK'){
                
                fillMeasurementTypes();
            }
            $('#measurement-edit').dialog('close');
        },"json");
    
        $('#measurement-edit').dialog('close');
    
        return false;
    });
    
    // edit measurement type
    $('ul#measurement-types-list li div.edit-measurement-type-box a.edit').live('click', function(){
        
        showEditMeasurementTypePopup();
        return false;
    });
    
    // delete measurement type
    $('ul#measurement-types-list li div.edit-measurement-type-box a.delete').live('click', function(){
        
        fancyConfirm('Confirm delete', 'Are you sure you want to delete this measurement?', function(){
            
            $.post(baseUrl + 'ajaxpost/deletemeasurement', {id : measurementTypeId}, function(json){
                
                if(json.result == 'OK'){
                    measurementTypeId = 0;
                    fillMeasurementTypes();
                }
            },"json");
        });
        return false;
    });
    
    // adding new entry
    $('a#add-measurement-entry').click(function(){
        var editRow = $('<tr>');
        editRow.data('id', '');
        editRow.append('<td><input type = "text" name = "value" /></td>'
                        + '<td><input type = "text" name = "date" class="time" /></td><td></td>');
        $('table#measurements-log-list tbody').prepend(editRow);
        attachDatepicker();
        return false;
    });
    
    // processing entry changes
    $('input[name="value"]').live('blur', saveMeasurmentEntry);
    $('input[name="date"]').live('change', saveMeasurmentEntry);
    
    $('table#measurements-log-list tbody tr a.edit').live('click', showEditEntry);
        
    $('table#measurements-log-list tbody tr a.delete').live('click', showDeleteEntry);
    
    $('#get-stats').click(function(){
        fillMeasurementLog();
        return false;
    });
    
});

function showEditEntry(){
    //alert($(this).parent().parent().data('id'));
    var row = $(this).parent().parent();
    var valueTd = $('td', row).eq(0);
    var dateTd = $('td', row).eq(1);
    valueTd.html('<input type = "text" name = "value" value = "' + valueTd.html() + '" />');
    dateTd.html('<input type = "text" name = "date" class="time" value = "' + row.data('date') + '" />');
    attachDatepicker();
    return false;
}

function showDeleteEntry(){
    var id = $(this).parent().parent().data('id');

    fancyConfirm('Confirm delete', 'Are you sure you want to delete this entry?', function(){
        
        $.post(baseUrl + 'ajaxpost/deleteMeasurementEntry', {id : id}, function(json){
            
            if(json.result == 'OK'){
                
                fillMeasurementLog();
            }
        },"json");
            });
    return false;
}


function attachDatepicker(){
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

function fillMeasurementTypes(){
    $.getJSON(baseUrl + 'json/measurement_types', function(json){
        
        $('ul#measurement-types-list li').remove();
        
        $.each(json, function(i, jsonrow){
            
            var li = $('<li>');
            li.data('id', jsonrow.id);
            li.data('desc', jsonrow.desc);
            li.append('<div class = "list-title">' + jsonrow.title + '</div>');
            $('ul#measurement-types-list').append(li);
            
            if(measurementTypeId == jsonrow.id){
                li.addClass('selected');
                li.trigger('click');
            }
        });
        makeZebra($('ul#measurement-types-list'));
        
        if(measurementTypeId == 0){
            $('ul#measurement-types-list li:first').trigger('click');
        }
    });
}

function fillMeasurementLog(){
    
    var startDate = $('#start-date').val();
    var endDate = $('#end-date').val();
    
    $.getJSON(baseUrl + 'json/statistics/measurements_log', {startdate: startDate, enddate: endDate, measurement_type_id: measurementTypeId}, function(json){

        fillMeasurementsTable(json.data, '#measurements-log-list');
    });
}

function fillMeasurementsTable(data, table){

    $('table' + table + ' tbody').remove();
    $('table' + table).append($('<tbody>'));
    
    if(data.stats.length > 0){
        var units = data.units;
        $.each(data.stats, function(i, jsonrow){
            
            var tr = $('<tr>');
            tr.data('id', jsonrow.id);
            tr.data('date', jsonrow.date);
            
            var value = jsonrow.value;
            
            // date will need to be properly formatted
            var date = jsonrow.date;
            
            tr.append('<td>' + date + '</td><td>' + value + ' ' + units + '</td>');
            tr.append('<td class = "no-pad no-right"><a class = "edit" href="#"></a></td><td class = "no-pad no-right" ><a class = "delete" href="#"></a></td>');
            
            $('table' + table + ' tbody').append(tr);
        });
    }else{
        $('table' + table + ' tbody').append('<tr><td colspan = "4" class = "center">No log entries added yet!</td></tr>');
    }
    makeZebra($('table' + table + ' tbody'));
}

function showEditMeasurementTypePopup(){
    $.getJSON(baseUrl + 'json/measurementinfo', {id : measurementTypeId}, function(json){
        
        $('#measurement-edit form')[0].reset();
        $('#measurement-edit form').populate(json[0]);
        $('#measurement-edit').dialog('option','title', 'Edit measurement'); 
        $('#measurement-edit').dialog('open');
    });
}

function saveMeasurmentEntry(){
    var value = $('input[name="value"]', $(this).parent().parent()).val();
    var date = $('input[name="date"]', $(this).parent().parent()).val();
    var id = $(this).parent().parent().data('id');
    
    if(value && date){
        $.post(baseUrl + 'ajaxpost/saveMeasurementEntry', 
               {id : id, value: value, date: date, type_id: measurementTypeId}, 
               function(json){
                   
                   if(json.result == 'OK'){
                       fillMeasurementLog();
                   }
               },"json");
    }
}

