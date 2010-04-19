var addExcallback;
$(function() {

    $('#accordion').accordion( { autoHeight: false } );
    $('#select-exercise').dialog({autoOpen:false});

    $('#accordion li').click(function(){
        var metadata = $(this).metadata();
        addExCallback(metadata.id);
        $('#select-exercise').dialog('close');
    });
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
