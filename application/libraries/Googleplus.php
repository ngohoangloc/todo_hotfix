<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Googleplus {
	
	public function __construct() {
		
		$CI =& get_instance();
		$CI->config->load('googleplus');
		
		$CI->load->database();
		$clientid = $CI->db->get_where("config" , ['key' => 'clientid'] )->row_object()->value;
		$clientsecret = $CI->db->get_where("config" , ['key' => 'clientsecret'] )->row_object()->value;
		$redirecturi = $CI->db->get_where("config" , ['key' => 'redirecturi'] )->row_object()->value;

		require APPPATH .'third_party/google-login-api/apiClient.php';
		require APPPATH .'third_party/google-login-api/contrib/apiOauth2Service.php';
		
		$this->client = new apiClient();
		$this->client->setApplicationName($CI->config->item('application_name', 'googleplus'));
		$this->client->setClientId($clientid);
		$this->client->setClientSecret($clientsecret);
		$this->client->setRedirectUri($redirecturi);
		$this->client->setDeveloperKey($CI->config->item('api_key', 'googleplus'));
		$this->client->setScopes($CI->config->item('scopes', 'googleplus'));
		$this->client->setAccessType('online');
		$this->client->setApprovalPrompt('auto');
		$this->oauth2 = new apiOauth2Service($this->client);

	}
	
	public function loginURL() {
        return $this->client->createAuthUrl();
    }
	
	public function getAuthenticate() {
        return $this->client->authenticate();
    }
	
	public function getAccessToken() {
        return $this->client->getAccessToken();
    }
	
	public function setAccessToken() {
        return $this->client->setAccessToken();
    }
	
	public function revokeToken() {
        return $this->client->revokeToken();
    }
	
	public function getUserInfo() {
        return $this->oauth2->userinfo->get();
    }
	
}
?>