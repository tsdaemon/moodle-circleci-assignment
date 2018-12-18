<?php
/**
 * This file contains the definition for the class assignment
 *
 * This class provides all the functionality for the new assign module.
 *
 * @package   mod_circleciassign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->dirroot . '/mod/circleciassign/mod_form.php');

/**
 * Standard class for mod_circleciassign
 *
 * @package   mod_circleciassign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class circleciassign extends assign {

    public function __construct($coursemodulecontext, $coursemodule, $course) {
        parent::__construct($coursemodulecontext, $coursemodule, $course);
    }
}
