<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengumumanLine extends CI_Controller {

	public function webhook(){
		try{			
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				http_response_code(405);
				error_log('Method not allowed');
				exit();
			}
			
			$httpPostRequestBody = file_get_contents('php://input');

			if (strlen($httpPostRequestBody) === 0) {
				http_response_code(400);
				error_log('Missing request body');
				exit();
			}
			
			$xLineSignature = $_SERVER['HTTP_X_LINE_SIGNATURE'];
			
			if (empty($xLineSignature)) {
				http_response_code(400); // Bad Request, Signature is Missing
			}
			else{
				$this->load->model('Pengumuman_Line_model');
				$this->Pengumuman_Line_model->proceedWebhook($httpPostRequestBody, $xLineSignature);
			}
			http_response_code(200);
		}
		catch(Exception $e){
			http_response_code(500);
		}
	}
}

