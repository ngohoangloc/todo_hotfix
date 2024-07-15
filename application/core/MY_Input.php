<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Input extends CI_Input
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch an item from the POST array and strip HTML tags
     *
     * @param    mixed   $index  Index for item to be fetched from $_POST
     * @param    bool    $xss_clean Whether to apply XSS filtering
     * @return   mixed
     */
    public function post($index = NULL, $xss_clean = NULL)
    {
        $post = parent::post($index, $xss_clean);
        return $this->_strip_html_tags($post);
    }

    /**
     * Fetch an item from the GET array and strip HTML tags
     *
     * @param    mixed   $index  Index for item to be fetched from $_GET
     * @param    bool    $xss_clean Whether to apply XSS filtering
     * @return   mixed
     */
    public function get($index = NULL, $xss_clean = NULL)
    {
        $get = parent::get($index, $xss_clean);
        return $this->_strip_html_tags($get);
    }

    /**
     * Fetch an item from the REQUEST array and strip HTML tags
     *
     * @param    mixed   $index  Index for item to be fetched from $_REQUEST
     * @param    bool    $xss_clean Whether to apply XSS filtering
     * @return   mixed
     */
    public function request($index = NULL, $xss_clean = NULL)
    {
        $request = parent::request($index, $xss_clean);
        return $this->_strip_html_tags($request);
    }

    /**
     * Strip HTML tags from input
     *
     * @param    mixed   $data  Input data
     * @return   mixed
     */
    private function _strip_html_tags($data)
    {

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->_strip_html_tags($value);
            }
        } else {
            $data = isset($data) ? strip_tags(urldecode($data)) : "";
        }
        return $data;
    }
}
