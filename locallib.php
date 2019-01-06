<?php
/**
 * This file contains the definition for the library class for circleci submission plugin
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once($CFG->dirroot . '/mod/assign/submission/file/locallib.php');
 require 'vendor/autoload.php';
 use Aws\S3\S3Client;

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
    * Additionaly to file plugin code, store submission to AWS S3 and
    * send request to CircleCI
    *
    * @param stdClass $submission
    * @param stdClass $data
    * @return bool
    */
   public function save(stdClass $submission, stdClass $data) {
     global $USER, $DB;

     // Get submission files
     $fs = get_file_storage();
     $files = $fs->get_area_files($this->assignment->get_context()->id,
                                  'assignsubmission_file',
                                  ASSIGNSUBMISSION_FILE_FILEAREA,
                                  $submission->id,
                                  'id',
                                  false);

      if(count($files) < 1) {
        throw new Exception('At least one file should be submitted');
      }

      if(count($files) > 1) {
        throw new Exception('Only a single file submission is supported');
      }

      // Store submission in S3
      $key = get_config('assignsubmission_circleci', 'aws_key');
      $secret = get_config('assignsubmission_circleci', 'aws_secret');
      $s3 = new S3Client([
          'version' => 'latest',
          'region'  => get_config('assignsubmission_circleci', 'aws_region'),
          'credentials' => [
        	    'key'    => $key,
        	    'secret' => $secret
        	]
      ]);
      $file = array_shift($files);
      $filename = $file->get_filename();
      $key = uniqid() . $filename;
      $bucket = get_config('assignsubmission_circleci', 'aws_bucket');
      $s3_result = $s3->putObject([
      	'Bucket' => get_config('assignsubmission_circleci', 'aws_bucket'),
      	'Body' => $file->get_content(),
        'Key' => $key
      ]);

      // Send CircleCI request with stored file url
      $data = $s3_result->toArray();
      $file_url = $data['ObjectURL'];

      $circleci_url = $this->get_config('circleci_url');
      $circleci_token = $this->get_config('circleci_token');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $circleci_url);
      curl_setopt($ch, CURLOPT_USERNAME, $circleci_token);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Content-Type: application/json',
      ));
      $student_name = fullname($USER);
      $data = array(
        'build_parameters' => array(
          'CIRCLE_JOB' => 'build',
          'FILE_URL' => $file_url,
          'CIRCLE_USERNAME' => $student_name,
          'CIRCLE_PR_USERNAME' => $student_name,
        )
      );
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      $result = json_decode(curl_exec($ch), true);
      $build_url = $result['build_url'];

      // Store results in database
      $circleci_submission = $this->get_circleci_submission($submission->id);

      if ($circleci_submission) {
          $circleci_submission->circleci_job_url = $build_url;
          $circleci_submission->aws_file_url = $file_url;

          $updatestatus = $DB->update_record('assignsubmission_circleci', $circleci_submission);
          return $updatestatus;
      } else {
          $circleci_submission = new stdClass();
          $circleci_submission->circleci_job_url = $build_url;
          $circleci_submission->aws_file_url = $file_url;

          $circleci_submission->submission = $submission->id;
          $circleci_submission->assignment = $this->assignment->get_instance()->id;
          $circleci_submission->id = $DB->insert_record('assignsubmission_circleci', $circleci_submission);
          return $filesubmission->id > 0;
      }
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

   /**
    * Display the job url in the submission status table
    *
    * @param stdClass $submission
    * @param bool $showviewlink Set this to true if the list of files is long
    * @return string
    */
   public function view_summary(stdClass $submission, & $showviewlink) {
       // Get results from database
       $circleci_submission = $this->get_circleci_submission($submission->id);

       $build_url = $circleci_submission->circleci_job_url;
       $html = '<a href="'.$build_url.'">';
       $html .= $build_url;
       $html .= '</a>';

       return $html;
   }

   /**
    * Get CircleCI submission information from the database
    *
    * @param int $submissionid
    * @return mixed
    */
   private function get_circleci_submission($submissionid) {
       global $DB;
       return $DB->get_record('assignsubmission_circleci', array('submission'=>$submissionid));
   }
 }
