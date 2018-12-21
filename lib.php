<?php
/**
 * This file contains the moodle hooks for the circle CI assign module.
 *
 * It reuses most functions from the assign module
 *
 * @package   mod_circleciassign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/assign/lib.php');

/**
 * Adds an assignment instance
 *
 * This is done by calling the add_instance() method of the assignment type class
 * @param stdClass $data
 * @param mod_circleciassign_mod_form $form
 * @return int The instance id of the new assignment
 */
function circleciassign_add_instance(stdClass $data, mod_circleciassign_mod_form $form = null) {
    global $CFG;
    require_once($CFG->dirroot . '/mod/circleciassign/locallib.php');

    $assignment = new circleciassign(context_module::instance($data->coursemodule), null, null);
    return $assignment->add_instance($data, true);
}

/**
 * delete an assignment instance
 * @param int $id
 * @return bool
 */
function circleciassign_delete_instance($id) {
    return assign_delete_instance($id);
}	

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all assignment submissions and feedbacks in the database
 * and clean up any related data.
 *
 * @param stdClass $data the data submitted from the reset course.
 * @return array
 */
function circleciassign_reset_userdata($data) {
    return assign_reset_userdata($data);
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every assignment event in the site is checked, else
 * only assignment events belonging to the course specified are checked.
 *
 * @param int $courseid
 * @param int|stdClass $instance Assign module instance or ID.
 * @param int|stdClass $cm Course module object or ID (not used in this module).
 * @return bool
 */
function circleciassign_refresh_events($courseid = 0, $instance = null, $cm = null) {
    return assign_refresh_events($courseid, $instance, $cm);
}

/**
 * This actually updates the normal and completion calendar events.
 *
 * @param  stdClass $assign Assignment object (from DB).
 * @param  stdClass $course Course object.
 * @param  stdClass $cm Course module object.
 */
function circleciassign_prepare_update_events($assign, $course = null, $cm = null) {
    return assign_prepare_update_events($assign, $course, $cm);
}

// TODO: rename all functions below with circle_assign and replace their context with proxy to assign function

/**
 * Removes all grades from gradebook
 *
 * @param int $courseid The ID of the course to reset
 * @param string $type Optional type of assignment to limit the reset to a particular assignment type
 */
function circleciassign_reset_gradebook($courseid, $type='') {
    return circleciassign_reset_gradebook($courseid, $type);
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the assignment.
 * @param moodleform $mform form passed by reference
 */
function circleciassign_reset_course_form_definition(&$mform) {
    return assign_reset_course_form_definition($mform);
}

/**
 * Course reset form defaults.
 * @param  object $course
 * @return array
 */
function circleciassign_reset_course_form_defaults($course) {
    return assign_reset_course_form_defaults($course);
}

/**
 * Update an assignment instance
 *
 * This is done by calling the update_instance() method of the assignment type class
 * @param stdClass $data
 * @param stdClass $form - unused
 * @return object
 */
function circleciassign_update_instance(stdClass $data, $form) {
    return assign_update_instance($data, $form);
}

/**
 * This function updates the events associated to the assign.
 * If $override is non-zero, then it updates only the events
 * associated with the specified override.
 *
 * @param assign $assign the assign object.
 * @param object $override (optional) limit to a specific override
 */
function circleciassign_update_events($assign, $override = null) {
	return assign_update_events($assign, $override);
}

/**
 * Return the list if Moodle features this module supports
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, null if doesn't know
 */
function circleciassign_supports($feature) {
    return assign_supports($feature);
}

/**
 * Lists all gradable areas for the advanced grading methods gramework
 *
 * @return array('string'=>'string') An array with area names as keys and descriptions as values
 */
function circleciassign_grading_areas_list() {
    return assign_grading_areas_list();
}


/**
 * extend an assigment navigation settings
 *
 * @param settings_navigation $settings
 * @param navigation_node $navref
 * @return void
 */
function circleciassign_extend_settings_navigation(settings_navigation $settings, navigation_node $navref) {
	return assign_extend_settings_navigation($settings, $navref);
}

/**
 * Add a get_coursemodule_info function in case any assignment type wants to add 'extra' information
 * for the course (see resource).
 *
 * Given a course_module object, this function returns any "extra" information that may be needed
 * when printing this activity in a course listing.  See get_array_of_activities() in course/lib.php.
 *
 * @param stdClass $coursemodule The coursemodule object (record).
 * @return cached_cm_info An object on information that the courses
 *                        will know about (most noticeably, an icon).
 */
function circleciassign_get_coursemodule_info($coursemodule) {
	return assign_get_coursemodule_info($coursemodule);
}

/**
 * Callback which returns human-readable strings describing the active completion custom rules for the module instance.
 *
 * @param cm_info|stdClass $cm object with fields ->completion and ->customdata['customcompletionrules']
 * @return array $descriptions the array of descriptions for the custom rules.
 */
function circlecimod_assign_get_completion_active_rule_descriptions($cm) {
	return mod_assign_get_completion_active_rule_descriptions($cm);
}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function circleciassign_page_type_list($pagetype, $parentcontext, $currentcontext) {
	return assign_page_type_list($pagetype, $parentcontext, $currentcontext);
}

/**
 * Print an overview of all assignments
 * for the courses.
 *
 * @deprecated since 3.3
 * @todo The final deprecation of this function will take place in Moodle 3.7 - see MDL-57487.
 * @param mixed $courses The list of courses to print the overview for
 * @param array $htmlarray The array of html to return
 * @return true
 */
function circleciassign_print_overview($courses, &$htmlarray) {
    return assign_print_overview($courses, $htmlarray);
}

/**
 * This api generates html to be displayed to students in print overview section, related to their submission status of the given
 * assignment.
 *
 * @deprecated since 3.3
 * @todo The final deprecation of this function will take place in Moodle 3.7 - see MDL-57487.
 * @param array $mysubmissions list of submissions of current user indexed by assignment id.
 * @param string $sqlassignmentids sql clause used to filter open assignments.
 * @param array $assignmentidparams sql params used to filter open assignments.
 * @param stdClass $assignment current assignment
 *
 * @return bool|string html to display , false if nothing needs to be displayed.
 * @throws coding_exception
 */
function circleciassign_get_mysubmission_details_for_print_overview(&$mysubmissions, $sqlassignmentids, $assignmentidparams,
                                                            $assignment) {
	return assign_get_mysubmission_details_for_print_overview($mysubmissions, $sqlassignmentids, $assignmentidparams,
                                                            $assignment);
}

/**
 * This api generates html to be displayed to teachers in print overview section, related to the grading status of the given
 * assignment's submissions.
 *
 * @deprecated since 3.3
 * @todo The final deprecation of this function will take place in Moodle 3.7 - see MDL-57487.
 * @param array $unmarkedsubmissions list of submissions of that are currently unmarked indexed by assignment id.
 * @param string $sqlassignmentids sql clause used to filter open assignments.
 * @param array $assignmentidparams sql params used to filter open assignments.
 * @param stdClass $assignment current assignment
 * @param context $context context of the assignment.
 *
 * @return bool|string html to display , false if nothing needs to be displayed.
 * @throws coding_exception
 */
function circleciassign_get_grade_details_for_print_overview(&$unmarkedsubmissions, $sqlassignmentids, $assignmentidparams,
                                                     $assignment, $context) {
	return assign_get_grade_details_for_print_overview($unmarkedsubmissions, $sqlassignmentids, $assignmentidparams,
                                                     $assignment, $context);
}

/**
 * Print recent activity from all assignments in a given course
 *
 * This is used by the recent activity block
 * @param mixed $course the course to print activity for
 * @param bool $viewfullnames boolean to determine whether to show full names or not
 * @param int $timestart the time the rendering started
 * @return bool true if activity was printed, false otherwise.
 */
function circleciassign_print_recent_activity($course, $viewfullnames, $timestart) {
	return assign_print_recent_activity($course, $viewfullnames, $timestart);
}

/**
 * Returns all assignments since a given time.
 *
 * @param array $activities The activity information is returned in this array
 * @param int $index The current index in the activities array
 * @param int $timestart The earliest activity to show
 * @param int $courseid Limit the search to this course
 * @param int $cmid The course module id
 * @param int $userid Optional user id
 * @param int $groupid Optional group id
 * @return void
 */
function circleciassign_get_recent_mod_activity(&$activities,
                                        &$index,
                                        $timestart,
                                        $courseid,
                                        $cmid,
                                        $userid=0,
                                        $groupid=0) {
	return assign_get_recent_mod_activity($activities,
                                        $index,
                                        $timestart,
                                        $courseid,
                                        $cmid,
                                        $userid,
                                        $groupid);
}

/**
 * Print recent activity from all assignments in a given course
 *
 * This is used by course/recent.php
 * @param stdClass $activity
 * @param int $courseid
 * @param bool $detail
 * @param array $modnames
 */
function circleciassign_print_recent_mod_activity($activity, $courseid, $detail, $modnames) {
    return assign_print_recent_mod_activity($activity, $courseid, $detail, $modnames);
}
    
/**
 * Checks if a scale is being used by an assignment.
 *
 * This is used by the backup code to decide whether to back up a scale
 * @param int $assignmentid
 * @param int $scaleid
 * @return boolean True if the scale is used by the assignment
 */
function circleciassign_scale_used($assignmentid, $scaleid) {
    return assign_scale_used($assignmentid, $scaleid);
}

/**
 * Checks if scale is being used by any instance of assignment
 *
 * This is used to find out if scale used anywhere
 * @param int $scaleid
 * @return boolean True if the scale is used by any assignment
 */
function circleciassign_scale_used_anywhere($scaleid) {
    return assign_scale_used_anywhere($scaleid);
}

/**
 * List the actions that correspond to a view of this module.
 * This is used by the participation report.
 *
 * Note: This is not used by new logging system. Event with
 *       crud = 'r' and edulevel = LEVEL_PARTICIPATING will
 *       be considered as view action.
 *
 * @return array
 */
function circleciassign_get_view_actions() {
    return assign_get_view_actions();
}

/**
 * List the actions that correspond to a post of this module.
 * This is used by the participation report.
 *
 * Note: This is not used by new logging system. Event with
 *       crud = ('c' || 'u' || 'd') and edulevel = LEVEL_PARTICIPATING
 *       will be considered as post action.
 *
 * @return array
 */
function circleciassign_get_post_actions() {
    return assign_get_post_actions();
}

/**
 * Call cron on the assign module.
 */
function circleciassign_cron() {
    return assign_cron();
}

/**
 * Returns all other capabilities used by this module.
 * @return array Array of capability strings
 */
function circleciassign_get_extra_capabilities() {
    return assign_get_extra_capabilities();
}

/**
 * Create grade item for given assignment.
 *
 * @param stdClass $assign record with extra cmidnumber
 * @param array $grades optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function circleciassign_grade_item_update($assign, $grades=null) {
    return assign_grade_item_update($assign, $grades);
}

/**
 * Return grade for given user or all users.
 *
 * @param stdClass $assign record of assign with an additional cmidnumber
 * @param int $userid optional user id, 0 means all users
 * @return array array of grades, false if none
 */
function circleciassign_get_user_grades($assign, $userid=0) {
    return assign_get_user_grades($assign, $userid);
}

/**
 * Update activity grades.
 *
 * @param stdClass $assign database record
 * @param int $userid specific user only, 0 means all
 * @param bool $nullifnone - not used
 */
function circleciassign_update_grades($assign, $userid=0, $nullifnone=true) {
    return assign_update_grades($assign, $userid, $nullifnone);
}

/**
 * List the file areas that can be browsed.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array
 */
function circleciassign_get_file_areas($course, $cm, $context) {
    return assign_get_file_areas($course, $cm, $context);
}

/**
 * File browsing support for assign module.
 *
 * @param file_browser $browser
 * @param object $areas
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return object file_info instance or null if not found
 */
function circleciassign_get_file_info($browser,
                              $areas,
                              $course,
                              $cm,
                              $context,
                              $filearea,
                              $itemid,
                              $filepath,
                              $filename) {
    return assign_get_file_info($browser,
                              $areas,
                              $course,
                              $cm,
                              $context,
                              $filearea,
                              $itemid,
                              $filepath,
                              $filename) ;
}

/**
 * Prints the complete info about a user's interaction with an assignment.
 *
 * @param stdClass $course
 * @param stdClass $user
 * @param stdClass $coursemodule
 * @param stdClass $assign the database assign record
 *
 * This prints the submission summary and feedback summary for this student.
 */
function circleciassign_user_complete($course, $user, $coursemodule, $assign) {
    return assign_user_complete($course, $user, $coursemodule, $assign);
}

/**
 * Rescale all grades for this activity and push the new grades to the gradebook.
 *
 * @param stdClass $course Course db record
 * @param stdClass $cm Course module db record
 * @param float $oldmin
 * @param float $oldmax
 * @param float $newmin
 * @param float $newmax
 */
function circleciassign_rescale_activity_grades($course, $cm, $oldmin, $oldmax, $newmin, $newmax) {
    return assign_rescale_activity_grades($course, $cm, $oldmin, $oldmax, $newmin, $newmax);}

/**
 * Print the grade information for the assignment for this user.
 *
 * @param stdClass $course
 * @param stdClass $user
 * @param stdClass $coursemodule
 * @param stdClass $assignment
 */
function circleciassign_user_outline($course, $user, $coursemodule, $assignment) {
    return assign_user_outline($course, $user, $coursemodule, $assignment);
}

/**
 * Obtains the automatic completion state for this module based on any conditions
 * in assign settings.
 *
 * @param object $course Course
 * @param object $cm Course-module
 * @param int $userid User ID
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool True if completed, false if not, $type if conditions not set.
 */
function circleciassign_get_completion_state($course, $cm, $userid, $type) {
    return assign_get_completion_state($course, $cm, $userid, $type);
}

/**
 * Serves intro attachment files.
 *
 * @param mixed $course course or id of the course
 * @param mixed $cm course module or id of the course module
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function circleciassign_pluginfile($course,
                $cm,
                context $context,
                $filearea,
                $args,
                $forcedownload,
                array $options=array()) {
    return assign_pluginfile($course,
                $cm,
                $context,
                $filearea,
                $args,
                $forcedownload,
                $options);
    }

/**
 * Serve the grading panel as a fragment.
 *
 * @param array $args List of named arguments for the fragment loader.
 * @return string
 */
function circlecimod_assign_output_fragment_gradingpanel($args) {
	return mod_assign_output_fragment_gradingpanel($args);
}
    
/**
 * Check if the module has any update that affects the current user since a given time.
 *
 * @param  cm_info $cm course module data
 * @param  int $from the time to check updates from
 * @param  array $filter  if we need to check only specific updates
 * @return stdClass an object with the different type of areas indicating if they were updated or not
 * @since Moodle 3.2
 */
function circleciassign_check_updates_since(cm_info $cm, $from, $filter = array()) {
	return assign_check_updates_since($cm, $from, $filter);
}

/**
 * Is the event visible?
 *
 * This is used to determine global visibility of an event in all places throughout Moodle. For example,
 * the ASSIGN_EVENT_TYPE_GRADINGDUE event will not be shown to students on their calendar.
 *
 * @param calendar_event $event
 * @param int $userid User id to use for all capability checks, etc. Set to 0 for current user (default).
 * @return bool Returns true if the event is visible to the current user, false otherwise.
 */
function circlecimod_assign_core_calendar_is_event_visible(calendar_event $event, $userid = 0) {
    return mod_assign_core_calendar_is_event_visible($event, $userid);
}

/**
 * This function receives a calendar event and returns the action associated with it, or null if there is none.
 *
 * This is used by block_myoverview in order to display the event appropriately. If null is returned then the event
 * is not displayed on the block.
 *
 * @param calendar_event $event
 * @param \core_calendar\action_factory $factory
 * @param int $userid User id to use for all capability checks, etc. Set to 0 for current user (default).
 * @return \core_calendar\local\event\entities\action_interface|null
 */
function circlecimod_assign_core_calendar_provide_event_action(calendar_event $event,
                                                       \core_calendar\action_factory $factory,
                                                       $userid = 0) {
	return mod_assign_core_calendar_provide_event_action($event,
                                                       $factory,
                                                       $userid);

}
/**
 * Callback function that determines whether an action event should be showing its item count
 * based on the event type and the item count.
 *
 * @param calendar_event $event The calendar event.
 * @param int $itemcount The item count associated with the action event.
 * @return bool
 */
function circlecimod_assign_core_calendar_event_action_shows_item_count(calendar_event $event, $itemcount = 0) {
	return mod_assign_core_calendar_event_action_shows_item_count($event, $itemcount);
}
/**
 * This function calculates the minimum and maximum cutoff values for the timestart of
 * the given event.
 *
 * It will return an array with two values, the first being the minimum cutoff value and
 * the second being the maximum cutoff value. Either or both values can be null, which
 * indicates there is no minimum or maximum, respectively.
 *
 * If a cutoff is required then the function must return an array containing the cutoff
 * timestamp and error string to display to the user if the cutoff value is violated.
 *
 * A minimum and maximum cutoff return value will look like:
 * [
 *     [1505704373, 'The due date must be after the sbumission start date'],
 *     [1506741172, 'The due date must be before the cutoff date']
 * ]
 *
 * If the event does not have a valid timestart range then [false, false] will
 * be returned.
 *
 * @param calendar_event $event The calendar event to get the time range for
 * @param stdClass $instance The module instance to get the range from
 * @return array
 */
function circlecimod_assign_core_calendar_get_valid_event_timestart_range(\calendar_event $event, \stdClass $instance) {
	return mod_assign_core_calendar_get_valid_event_timestart_range($event, $instance);
}
/**
 * This function will update the assign module according to the
 * event that has been modified.
 *
 * @throws \moodle_exception
 * @param \calendar_event $event
 * @param stdClass $instance The module instance to get the range from
 */
function circlecimod_assign_core_calendar_event_timestart_updated(\calendar_event $event, \stdClass $instance) {
	return mod_assign_core_calendar_event_timestart_updated($event, $instance);
}