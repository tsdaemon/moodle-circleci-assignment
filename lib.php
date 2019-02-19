<?php
/**
 * This file contains the moodle hooks for the submission CircleCI plugin
 *
 * It reuses most functions from the assign module
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

 require_once($CFG->dirroot . '/mod/assign/submission/file/lib.php');

/**
 * Serves assignment submissions and other files.
 *
 * @param mixed $course course or id of the course
 * @param mixed $cm course module or id of the course module
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options - List of options affecting file serving.
 * @return bool false if file not found, does not return if found - just send the file
 */
function assignsubmission_circleci_pluginfile($course,
                                          $cm,
                                          context $context,
                                          $filearea,
                                          $args,
                                          $forcedownload,
                                          array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_login($course, false, $cm);
    $itemid = (int)array_shift($args);
    $record = $DB->get_record('assign_submission',
                              array('id'=>$itemid),
                              'userid, assignment, groupid',
                              MUST_EXIST);
    $userid = $record->userid;
    $groupid = $record->groupid;

    require_once($CFG->dirroot . '/mod/assign/locallib.php');

    $assign = new assign($context, $cm, $course);

    if ($assign->get_instance()->id != $record->assignment) {
        return false;
    }

    if ($assign->get_instance()->teamsubmission &&
        !$assign->can_view_group_submission($groupid)) {
        return false;
    }

    if (!$assign->get_instance()->teamsubmission &&
        !$assign->can_view_submission($userid)) {
        return false;
    }

    $relativepath = implode('/', $args);

    $fullpath = "/{$context->id}/assignsubmission_circleci/$filearea/$itemid/$relativepath";

    $fs = get_file_storage();
    if (!($file = $fs->get_file_by_hash(sha1($fullpath))) || $file->is_directory()) {
        return false;
    }

    // Download MUST be forced - security!
    send_stored_file($file, 0, 0, true, $options);
}
