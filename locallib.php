<?php
/**
 * This file contains the definition for the class assignment
 *
 * This class provides all the functionality for the new assign module.
 *
 * @package   mod_circleci_assign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->dirroot . '/mod/circleci_assign/mod_form.php');

/**
 * Standard class for mod_circleci_assign
 *
 * @package   mod_circleci_assign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class circleci_assign extends assign {

    public function __construct($coursemodulecontext, $coursemodule, $course) {
        parent::__construct($coursemodulecontext, $coursemodule, $course);
    }
}
