<?php
/**
 * This file contains the definition for the library class for circleci submission plugin
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once($CFG->dirroot . '/mod/assign/submission/file/locallib.php');

 defined('MOODLE_INTERNAL') || die();

 /**
  * Library class for CircleCI submission plugin extending file plugin class
  *
  * @package   assignsubmission_circleci
  * @copyright 2018 tsdaemon
  * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */
 class assign_submission_circleci extends assign_submission_file {

   /**
    * Get the name of the CircleCI submission plugin
    * @return string
    */
   public function get_name() {
       return get_string('circleci', 'assignsubmission_circleci');
   }

   /**
    * Get the default setting for CircleCI submission plugin
    *
    * @param MoodleQuickForm $mform The form to add elements to
    * @return void
    */
   public function get_settings(MoodleQuickForm $mform) {
       global $CFG, $COURSE;

       if ($this->assignment->has_instance()) {
           $circleci_url = $this->get_config('circleci_url');
           $circleci_token = $this->get_config('circleci_token');
       } else {
           $circleci_url = '';
           $circleci_token = $this->get_config('assignsubmission_circleci', 'token');
       }
       $circleci_url = (string)$circleci_url;
       $circleci_token = (string)$circleci_token;

       $name = get_string('circleci_url', 'assignsubmission_circleci');
       $mform->addElement('text', 'assignsubmission_circleci_url', $name, array('size'=>'64'));
       $mform->addHelpButton('assignsubmission_circleci_url',
                             'circleci_url',
                             'assignsubmission_circleci');
       $mform->setDefault('assignsubmission_circleci_url', $circleci_url);
       $mform->disabledIf('assignsubmission_circleci_url', 'assignsubmission_circleci_enabled', 'notchecked');
       $mform->disabledIf('assignsubmission_circleci_url', 'assignsubmission_file_enabled', 'notchecked');

       $name = get_string('circleci_token', 'assignsubmission_circleci');
       $mform->addElement('text', 'assignsubmission_circleci_token', $name, array('size'=>'64'));
       $mform->addHelpButton('assignsubmission_circleci_token',
                             'circleci_token',
                             'assignsubmission_circleci');
       $mform->setDefault('assignsubmission_circleci_token', $circleci_token);
       $mform->disabledIf('assignsubmission_circleci_token',
                          'assignsubmission_circleci_enabled',
                          'notchecked');
       $mform->disabledIf('assignsubmission_circleci_token',
                          'assignsubmission_file_enabled',
                          'notchecked');
   }

   /**
    * Save the settings for CircleCI submission plugin
    *
    * @param stdClass $data
    * @return bool
    */
   public function save_settings(stdClass $data) {
       $this->set_config('circleci_token', $data->assignsubmission_circleci_token);
       $this->set_config('circleci_url', $data->assignsubmission_circleci_url);

       return true;
   }

   /**
    * Add elements to submission form. The same as for file submission
    *
    * @param mixed $submission stdClass|null
    * @param MoodleQuickForm $mform
    * @param stdClass $data
    * @return bool
    */
   public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
     return parent::get_form_elements($submission, $mform, $data);
   }

   /**
    * The assignment has been deleted - cleanup
    *
    * @return bool
    */
   public function delete_instance() {
       $result = parent::delete_instance();
       if ($result) {
         global $DB;
         // Will throw exception on failure.
         $DB->delete_records('assignsubmission_circleci',
                             array('assignment'=>$this->assignment->get_instance()->id));

         return true;
       }
       return $result;
   }
 }
