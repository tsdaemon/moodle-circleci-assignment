<?php
/**
 * This file contains the forms to create and edit an instance of this module
 *
 * @package   mod_circleciassign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

require_once($CFG->dirroot . '/mod/assign/mod_form.php');

/**
 * CircleCI assignment settings form.
 *
 * @package   mod_circleciassign
 * @copyright 2018 Anatolii Stehnii
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_circleciassign_mod_form extends mod_assign_mod_form {

    /**
     * Called to define CircleCI assignment moodle form
     *
     * @return void
     */
    public function definition() {
        parent::definition();

        global $CFG, $COURSE, $DB, $PAGE;
        $mform = $this->_form;

        $mform->addElement('text', 'circleci_url', get_string('circleci_url', 'circleciassign'), array('size'=>'64'));
        $mform->addRule('circleci_url', null, 'required', null, 'client');

        $mform->addElement('text', 'circleci_token', get_string('circleci_token', 'circleciassign'), array('size'=>'64'));
        $mform->addRule('circleci_token', null, 'required', null, 'client');

        $this->standard_coursemodule_elements();
        $this->apply_admin_defaults();

        $this->add_action_buttons();
    }
}
