<?php defined('SYSPATH') OR die('No direct access allowed.');

class imports_Core{

    public static function exportExercise($userId, $exportExerciseId){
        $exercises = new Exercise_Model($userId);
        $groups = new Group_Model($userId);

        $exportGroupId = 0;
        $result = null;

        // get exercise from db
        $exportExercise = $exercises->getItem($exportExerciseId);

        // test if this group is already exported
        $exportGroup = $groups->getItem($exportExercise[0]->group_id);

        // there is an entry of this group in public groups
        if($exportGroup[0]->import_id != 0){
            $exportGroupId = $exportGroup[0]->import_id;
        }else{

            // we need to export current group
            $exportGroupId = $groups->addPublicItem(array(
                                                        'title' => $exportGroup[0]->title,
                                                        'desc' => $exportGroup[0]->desc,
                                                        ));
            // make this id an import_id in internal group
            $groups->updateItem(array('import_id' => $exportGroupId), $exportGroup[0]->id);
        }

        $exportedExerciseId = null;

        // check if it was exported before
        if($exportExercise[0]->import_id != 0){
            $exportedExerciseId = $exportExercise[0]->import_id;
        }else{
            // exporting exercise itself
            $exportedExerciseId = $exercises->addPublicItem(array(
                                                        'title' => $exportExercise[0]->title,
                                                        'desc' => $exportExercise[0]->desc,
                                                        'ex_type' => $exportExercise[0]->ex_type,
                                                        'group_id' => $exportGroupId,
                                                        ));

            // make this id an import_id in internal exercise
            $exercises->updateItem(array('import_id' => $exportedExerciseId), $exportExercise[0]->id);
        }

        return $exportedExerciseId;
    }

    public static function function exportSet($userId, $exportSetId){

        $sets = new Set_Model($userId);

        $exportSet = $sets->getItem($exportSetId);

        $exportedSetId = $sets->addPublicItem(array(
                                    'title' => $exportSet[0]->title,
                                    'desc' => $exportSet[0]->desc,
                                    ));

        // make this id an import_id in internal set
        $sets->updateItem(array('import_id' => $exportedSetId), $exportSet[0]->id);

        // now lets add exercises
        foreach($sets->getExercises($exportSet[0]->id) as $exportExercise){

            $exportedExerciseId = self::exportExercise($userId, $exportExercise->id);
            $sets->addPublicToSet($exportedSetId, $exportedExerciseId);
        }

        return $exportedSetId;
    }

    public static function exportProgram($userId, $exportProgramId){

        $programs = new Program_Model($userId);

        $exportProgram = $programs->getItem($exportProgramId);

        $exportedProgramId = $programs->addPublicItem(array(
                                                            'title' => $exportProgram[0]->title,
                                                            'desc' => $exportProgram[0]->desc,
                                                            ));
        // updating internal program to contain exported id
        $programs->updateItem(array('import_id' => $exportedProgramId), $exportProgram[0]->id);

        foreach($programs->getSets($exportProgram[0]->id) as $exportSet){

            $programs->addPublicSetToProgram(array(
                                    'program_id' => $exportedProgramId,
                                    'set_id' => self::exportSet($userId, $exportSet->set_id),
                                    'day_number' => $exportSet->day_number,
                                        ));
        }

        return $exportedProgramId;

    }


}