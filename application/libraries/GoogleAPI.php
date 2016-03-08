<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter GoogleAPI
 */
class GoogleAPI {

    /**
     * CI
     *
     * CodeIgniter instance
     * @var 	object
     */
    private $_ci;

    /**
     * Client
     *
     * Google client instance
     * @var 	object
     */
    private $_client;

    /**
     * Client ID
     *
     * @var 	string
     */
    private $_clientId;

    /**
     * Json File
     *
     * Json Auth Config File
     * @var		string
     */
    private $_jsonFile;

    /**
     * Redirect URI
     *
     * URI to redirect to after Facebook connection
     * @var		string
     */
    private $_redirectUri;

    /**
     * Permissions
     *
     * List permission
     * @var		array
     */
    private $_scope;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Load Config File
        $this->_ci =& get_instance();
        $this->_ci->load->config('google');

        $this->_clientId            = $this->_ci->config->item('google_clientId');
        $this->_jsonFile            = $this->_ci->config->item('google_jsonFile');
        $this->_redirectUri         = $this->_ci->config->item('google_redirectUri');
        $this->_scope         	    = $this->_ci->config->item('google_scope');
    }

    /**
     * Init Google Client
     */
    public function init() {
        $this->_client = new Google_Client();
        $this->_client->setAuthConfigFile($this->_jsonFile);
        $this->_client->addScope($this->_scope);
        $this->_client->setRedirectUri($this->_redirectUri);
        $this->_client->setAccessType('offline');
        $this->_client->setApprovalPrompt('force');
    }

    /**
     * Get Google login url
     */
    public function getLoginUrl() {
        $this->init();

        return $this->_client->createAuthUrl();
    }

    /**
     * Get token
     */
    public function getToken($code) {
        $this->init();

        $this->_client->authenticate($code);

        return $this->_client->getAccessToken();
    }

    /**
     * Get a new Access Token from a refresh Token
     */
    public function getNewToken($refreshToken) {
        $this->init();
        return $this->_client->fetchAccessTokenWithRefreshToken($refreshToken);
    }
}
