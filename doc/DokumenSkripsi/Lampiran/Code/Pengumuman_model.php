<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends CI_Model {
	public function __construct() {
        parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
    }
	
	public function checkEmail(){
		$newEmails = null;
		
		$hostname = getEnv('HOSTNAME_INCOMING_EMAIL');
		$username = getEnv('ANNOUNCEMENT_EMAIL');
		$password = getEnv('ANNOUNCEMENT_PASSWORD');

		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		$emails = imap_search($inbox,'UNSEEN');

		if($emails) {
			$i = 0;
			foreach($emails as $emailNumber) {
				$invalid = false;
				$header = imap_headerinfo($inbox,$emailNumber);
				$from = isset($header->from) ? $header->from : null;
				if($from != null){
					$fromaddress = null;
					foreach($from as $id => $object){
						$fromaddress = isset($object->mailbox) && isset($object->host) ? $object->mailbox . "@" . $object->host : null;
					}
					$bodymsg = '';
					$attachmentExist = 'N';
					$structure = imap_fetchstructure($inbox, $emailNumber);
					if(isset($structure->parts) && is_array($structure->parts)) {
						if(isset($structure->parts[1])){
							$parts1 = $structure->parts[1];
							if(isset($parts1->disposition) && $parts1->disposition == "ATTACHMENT"){
								$attachmentExist = 'Y';
								$parts0 = $structure->parts[0];
								if(isset($parts0->parts[1])){
									$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '1.2'));
								}
								else if(isset($parts0->parts[0])){
									$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '1.1'));
								}
								else{
									$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '1'));
								}
							}
							else{
								$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '2'));
							}
						}
						else{
							$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '1'));
						}
					}
					else{
						$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, '1'));
					}

					if($fromaddress != null){
						$newEmails[$i]['emailFrom'] = $fromaddress;
						$newEmails[$i]['from'] = isset($header->fromaddress) ? $header->fromaddress : $fromaddress;
						if(isset($header->udate)){
							$newEmails[$i]['date'] = date("Y-m-d H:i:s", $header->udate);
						}
						else{
							$invalid = true;
						}
						
						if(isset($header->subject)){
							$newEmails[$i]['subject'] = $header->subject;
						}
						else{
							$invalid = true;
						}

						$newEmails[$i]['body'] = $bodymsg;
						$newEmails[$i]['attachmentExist'] = $attachmentExist;
					}
				}
				if($invalid){
					unset($newEmails[$i]);
				}
				else{
					$i++;
				}
			}
		}
		
		$errors = imap_errors();

		imap_close($inbox);
		
		return $newEmails;
	}
	
	public function proceedEmail($newEmail){
		$isPengumuman = false;
		$this->config->load('pengumuman');
		$terverifikasi = 0;
		$daftarEmailTerverifikasi = $this->config->item('pengirimTerverifikasi');
		foreach($daftarEmailTerverifikasi as $emailTerverifikasi){
			if($newEmail['emailFrom'] == $emailTerverifikasi){
				$terverifikasi = 1;
			}
		}
		
		if($terverifikasi == 1){
			$isPengumuman = true;
			$this->db->insert('Pengumuman', array(
				'namaPengirim' => $newEmail['from'],
				'emailPengirim' => $newEmail['emailFrom'],
				'waktuTerkirim' => $newEmail['date'],
				'subjek' => $newEmail['subject'],
				'isi' => $newEmail['body'],
				'ketersediaanLampiran' => $newEmail['attachmentExist']
			));
			$justInserted = $this->db->select("*")->order_by('id',"desc")->limit(1)->get('Pengumuman')->row();
			$id = $justInserted->id;
			
			$this->load->model('Pengumuman_Line_model');
			$message = "Ada pengumuman baru dari " . $newEmail['from'] . " : '" . $newEmail['subject'] . "'. Silahkan klik link ini untuk melihatnya : " . base_url() . "pengumuman/read/" . $id;
			$this->Pengumuman_Line_model->pushMessageToAllFollowers($message);
		}
		return $isPengumuman;
	}
}
