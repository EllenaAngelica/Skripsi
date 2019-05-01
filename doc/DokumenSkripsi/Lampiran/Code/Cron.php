<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function daily() {
		try {
			$this->load->model('Pengumuman_model');
			$newEmails = $this->Pengumuman_model->checkEmail();
			$numberOfEmailsFound = 0;
			$numberOfAnnouncementEmails = 0;
			if($newEmails != null){
				foreach($newEmails as $newEmail){
					$numberOfEmailsFound = $numberOfEmailsFound + 1;
					$isPengumuman = $this->Pengumuman_model->proceedEmail($newEmail);
					if($isPengumuman){
						$numberOfAnnouncementEmails = $numberOfAnnouncementEmails + 1;
					}
				}
			}
			log_message('info', "Successfully performed cron jobs. The number of new emails found is " . $numberOfEmailsFound . " and the number of new emails that are announcements is " . $numberOfAnnouncementEmails .".");
			http_response_code(200);
			echo json_encode([
                'message' => "Successfully performed cron jobs."
            ]);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			http_response_code(500);
			echo json_encode([
                'message' => $e->getMessage()
            ]);
		}
	}
}

