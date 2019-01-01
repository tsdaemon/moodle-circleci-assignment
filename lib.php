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
    assignsubmission_file_pluginfile($course. $cm, $context, $filearea, $args, $forcedownload, $options);
}
