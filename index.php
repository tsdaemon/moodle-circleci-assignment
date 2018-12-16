<?php
/**
 * Displays information about all the circleci assignment modules in the requested course
 *
 * @package   mod_circleci_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/circleci_assign/locallib.php');
// For this type of page this is the course id.
$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
require_login($course);
$PAGE->set_url('/mod/circleci_assign/index.php', array('id' => $id));
$PAGE->set_pagelayout('incourse');

\mod_assign\event\course_module_instance_list_viewed::create_from_course($course)->trigger();

// Print the header.
$strplural = get_string("modulenameplural", "assign");
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($strplural));

$context = context_course::instance($course->id);

require_capability('mod/assign:view', $context);

$assign = new circleci_assign($context, null, $course);

// Get the assign to render the page.
echo $assign->view('viewcourseindex');
