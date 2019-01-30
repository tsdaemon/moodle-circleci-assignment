<?php
/**
 * This file contains the definition for the library class for circleci submission plugin
 *
 * @package   assignsubmission_circleci
 * @copyright 2018 tsdaemon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 // require_once($CFG->dirroot . '/mod/assign/submission/file/locallib.php');
 require 'vendor/autoload.php';
 use Aws\S3\S3Client;

 defined('MOODLE_INTERNAL') || die();

 define('ASSIGNSUBMISSION_CIRCLECI_FILEAREA', 'submission_circleci');

 /**
  * Library class for CircleCI submission plugin extending file plugin class
  *
  * @package   assignsubmission_circleci
  * @copyright 2018 tsdaemon
  * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */
 class assign_submission_circleci extends assign_submission_plugin {

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
           $circleci_job = $this->get_config('circleci_job');
       } else {
           $circleci_url = '';
           $circleci_job = 'build';
           $circleci_token = '';
       }
       $circleci_url = (string)$circleci_url;
       $circleci_token = (string)$circleci_token;
       $circleci_job = (string)$circleci_job;

       $name = get_string('circleci_url', 'assignsubmission_circleci');
       $mform->addElement('text', 'assignsubmission_circleci_url', $name, array('size'=>'64'));
       $mform->addHelpButton('assignsubmission_circleci_url',
                             'circleci_url',
                             'assignsubmission_circleci');
       $mform->setDefault('assignsubmission_circleci_url', $circleci_url);
       $mform->disabledIf('assignsubmission_circleci_url', 'assignsubmission_circleci_enabled', 'notchecked');

       $name = get_string('circleci_token', 'assignsubmission_circleci');
       $mform->addElement('text', 'assignsubmission_circleci_token', $name, array('size'=>'64'));
       $mform->addHelpButton('assignsubmission_circleci_token',
                             'circleci_token',
                             'assignsubmission_circleci');
       $mform->setDefault('assignsubmission_circleci_token', $circleci_token);
       $mform->disabledIf('assignsubmission_circleci_token',
                          'assignsubmission_circleci_enabled',
                          'notchecked');

      $name = get_string('circleci_job', 'assignsubmission_circleci');
      $mform->addElement('text', 'assignsubmission_circleci_job', $name, array('size'=>'64'));
      $mform->addHelpButton('assignsubmission_circleci_job',
                            'circleci_job',
                            'assignsubmission_circleci');
      $mform->setDefault('assignsubmission_circleci_job', $circleci_job);
      $mform->disabledIf('assignsubmission_circleci_job',
                         'assignsubmission_circleci_enabled',
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
       $this->set_config('circleci_job', $data->assignsubmission_circleci_job);

       return true;
   }

   /**
    * File format options
    *
    * @return array
    */
   private function get_file_options() {
       $fileoptions = array(
         'subdirs' => 1,
         'maxbytes' => get_config('assignsubmission_file', 'maxbytes'),
         'accepted_types' => '',
         'maxfiles' => 1,
         'return_types' => (FILE_INTERNAL | FILE_CONTROLLED_LINK)
       );
       return $fileoptions;
   }

   /**
    * Add elements to submission form
    *
    * @param mixed $submission stdClass|null
    * @param MoodleQuickForm $mform
    * @param stdClass $data
    * @return bool
    */
   public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
       global $OUTPUT;

       $fileoptions = $this->get_file_options();

       $submissionid = $submission ? $submission->id : 0;

       $data = file_prepare_standard_filemanager($data,
                                                 'files',
                                                 $fileoptions,
                                                 $this->assignment->get_context(),
                                                 'assignsubmission_circleci',
                                                 ASSIGNSUBMISSION_CIRCLECI_FILEAREA,
                                                 $submissionid);
       $mform->addElement('filemanager', 'files_filemanager', $this->get_name(), null, $fileoptions);

       return true;
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

     // Store uploaded files
     $fileoptions = $this->get_file_options();

     $data = file_postupdate_standard_filemanager($data,
                                                  'files',
                                                  $fileoptions,
                                                  $this->assignment->get_context(),
                                                  'assignsubmission_circleci',
                                                  ASSIGNSUBMISSION_CIRCLECI_FILEAREA,
                                                  $submission->id);

     // Get submission files
     $fs = get_file_storage();
     $files = $fs->get_area_files($this->assignment->get_context()->id,
                                  'assignsubmission_circleci',
                                  ASSIGNSUBMISSION_CIRCLECI_FILEAREA,
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
      $circleci_job = $this->get_config('circleci_job');

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
          'CIRCLE_JOB' => $circleci_job,
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
          return $circleci_submission->id > 0;
      }
   }

   /**
    * The assignment has been deleted - cleanup
    *
    * @return bool
    */
   public function delete_instance() {
       global $DB;
       // Will throw exception on failure.
       $DB->delete_records('assignsubmission_circleci',
                           array('assignment'=>$this->assignment->get_instance()->id));

       return true;
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

       if (!is_null($circleci_submission)) {
           $build_url = $circleci_submission->circleci_job_url;
           $html = '<a href="'.$build_url.'">';
           $html .= $build_url;
           $html .= '</a>';

           $html_files = $this->assignment->render_area_files('assignsubmission_circleci',
                                                       ASSIGNSUBMISSION_CIRCLECI_FILEAREA,
                                                       $submission->id);

           return $html . $html_files;
       }
       return '';
   }

   /**
    * Produce a list of files suitable for export that represent this feedback or submission
    *
    * @param stdClass $submission The submission
    * @param stdClass $user The user record - unused
    * @return array - return an array of files indexed by filename
    */
   public function get_files(stdClass $submission, stdClass $user) {
       $result = array();
       $fs = get_file_storage();

       $files = $fs->get_area_files($this->assignment->get_context()->id,
                                    'assignsubmission_circleci',
                                    ASSIGNSUBMISSION_CIRCLECI_FILEAREA,
                                    $submission->id,
                                    'timemodified',
                                    false);

       foreach ($files as $file) {
           // Do we return the full folder path or just the file name?
           if (isset($submission->exportfullpath) && $submission->exportfullpath == false) {
               $result[$file->get_filename()] = $file;
           } else {
               $result[$file->get_filepath().$file->get_filename()] = $file;
           }
       }
       return $result;
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

   /**
    * Return true if there are no submission files
    * @param stdClass $submission
    */
   public function is_empty(stdClass $submission) {
       return $this->count_files($submission->id, ASSIGNSUBMISSION_CIRCLECI_FILEAREA) == 0;
   }

   /**
    * Count the number of files
    *
    * @param int $submissionid
    * @param string $area
    * @return int
    */
   private function count_files($submissionid, $area) {
       $fs = get_file_storage();
       $files = $fs->get_area_files($this->assignment->get_context()->id,
                                    'assignsubmission_circleci',
                                    $area,
                                    $submissionid,
                                    'id',
                                    false);

       return count($files);
   }

   /**
    * Get file areas returns a list of areas this plugin stores files
    * @return array - An array of fileareas (keys) and descriptions (values)
    */
   public function get_file_areas() {
       return array(ASSIGNSUBMISSION_CIRCLECI_FILEAREA=>$this->get_name());
   }
 }
