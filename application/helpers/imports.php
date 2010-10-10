<?php defined('SYSPATH') OR die('No direct access allowed.');

class imports_Core{

    // importing program
    // 1. program itself should be imported
    // 2. programs_connector
    // 3. sets
    // 4. sets_connector
    // 5. exercises + groups
    public static function importProgram($userId, $importProgramId){

        $programs = new Program_Model($userId);
        $sets = new Set_Model($userId);

        $importProgram = $programs->getPublicItem($importProgramId);

        // 1. importing program itself
        $importedProgramId = $programs->addItem(array(
                                            'title' => $importProgram[0]->title,
                                            'desc' => $importProgram[0]->desc,
                                            'import_id' => $importProgram[0]->id,
                                            ));

        // importing sets
        foreach($programs->getPublicSets($importProgramId) as $importSet){

            $programs->addSetToProgram(array(
                                            'program_id' => $importedProgramId,
                                            'set_id' => self::importSet($userId, $importSet->set_id),
                                            'day_number' => $importSet->day_number,
                                            ));
        }

        return $importedProgramId;
    }

    public static function importSet($userId, $importSetId){

        $sets = new Set_Model($userId);

        $importSet = $sets->getPublicItem($importSetId);

        $importedSetId = $sets->addItem(array(
                                    'title' => $importSet[0]->title,
                                    'desc' => $importSet[0]->desc,
                                    'import_id' => $importSet[0]->id,
                                    ));

        // now lets add exercises
        foreach($sets->getPublicExercises($importSet[0]->id) as $importExercise){

            $importedExerciseId = self::importExercise($userId, $importExercise->id);
            $sets->addToSet($importedSetId, $importedExerciseId);
        }

        return $importedSetId;
    }

    public static function importExercise($userId, $importExerciseId, $defaultGroupId = null, $noImportGroups = null){

        $exercises = new Exercise_Model($userId);
        $groups = new Group_Model($userId);

        // get public exercise from db
        $importExercise = $exercises->getPublicItem($importExerciseId);

        // if $groupId is not specified - look if exercise group is already imported or
        // import it if it's not

        // test if this group is already imported
        $testGroup = $groups->getByPublicId($importExercise[0]->group_id);

        if(count($testGroup) > 0 &&
            !(isset($noImportGroups)
            && in_array($importExercise[0]->group_id, $noImportGroups))){
                    $groupId = $testGroup[0]->id;
        }else{

            // it's not in the system - 2 choices:
            // 1. import group
            // 2. add exercise to current group

            // if user doesn't want to import group we use current group id
            if(isset($noImportGroups) && in_array($importExercise[0]->group_id, $noImportGroups)){
                $groupId = $defaultGroupId;
            }else{
                $importGroup = $groups->getPublicItem($importExercise[0]->group_id);
                $importGroupData = $importGroup[0];

                $groupId = $groups->addItem(array(
                                                'title' => $importGroupData->title,
                                                'desc' => $importGroupData->desc,
                                                'import_id' => $importGroupData->id,
                                                ));
            }
        }

        // now we have groupId - lets add exercise itself
        $importedExerciseId = null;

        // check if this exercise is already imported
        $testExercise = $exercises->getByPublicId($importExercise[0]->id);

        if(count($testExercise) > 0){
            $importedExerciseId = $testExercise[0]->id;
        }else{

            // copy exercise image files and assign them, to new exercise
            $old_file_id = $importExercise[0]->file_id;
            $new_file_id = 0;
            if($old_file_id != 0){
                $new_file_id = files::copyFile($userId, $old_file_id);
            }
            error_log($userId);
            error_log($groupId);

            $importedExerciseId = $exercises->addItem(array(
                                                        'title' => $importExercise[0]->title,
                                                        'desc' => $importExercise[0]->desc,
                                                        'ex_type' => $importExercise[0]->ex_type,
                                                        'file_id' => $new_file_id,
                                                        'group_id' => $groupId,
                                                        'import_id' =>$importExercise[0]->id,
                                                        ));
        }

        return $importedExerciseId;
    }
}