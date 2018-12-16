<?php

/**
 * assignment_base is the base class for assignment types
 *
 * This class provides all the functionality for an assignment
 *
 * @package   mod_circleci_assignment
 */

/**
 * Adds an CircleCI assignment instance
 *
 * Only used by generators so we can create old assignments to test the upgrade.
 *
 * @param stdClass $circleci_assignment
 * @param mod_circleci_assignment_mod_form $mform
 * @return int intance id
 */
function circleci_assignment_add_instance($circleci_assignment, $mform = null) {
    global $DB;

    $circleci_assignment->timemodified = time();
    $circleci_assignment->courseid = $circleci_assignment->course;
    $returnid = $DB->insert_record("circleci_assignment", $circleci_assignment);
    $circleci_assignment->id = $returnid;
    return $returnid;
}

/**
 * Deletes an CircleCI assignment instance
 *
 * @param $id
 */
function circleci_assignment_delete_instance($id){
    global $CFG, $DB;

    if (! $circleci_assignment = $DB->get_record('circleci_assignment', array('id'=>$id))) {
        return false;
    }

    $result = true;
    // Now get rid of all files
    $fs = get_file_storage();
    if ($cm = get_coursemodule_from_instance('circleci_assignment', $circleci_assignment->id)) {
        $context = context_module::instance($cm->id);
        $fs->delete_area_files($context->id);
    }

    if (! $DB->delete_records('circleci_assignment_submissions', array('assignment'=>$circleci_assignment->id))) {
        $result = false;
    }

    if (! $DB->delete_records('event', array('modulename'=>'circleci_assignment', 'instance'=>$circleci_assignment->id))) {
        $result = false;
    }

    if (! $DB->delete_records('circleci_assignment', array('id'=>$circleci_assignment->id))) {
        $result = false;
    }

    grade_update('mod/circleci_assignment', $circleci_assignment->course, 'mod', 'circleci_assignment', $circleci_assignment->id, 0, NULL, array('deleted'=>1));

    return $result;
}

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, null if doesn't know
 */
function circleci_assignment_supports($feature) {
    switch($feature) {
        default: return null;
    }
}
