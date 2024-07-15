<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Word_filter
{
    private $sensitive_words = array();

    public function __construct()
    {
        $CI = &get_instance();

        $path = APPPATH . 'config/sensitive_words.json';

        if (file_exists($path)) {

            $json = file_get_contents($path);
            $this->sensitive_words = json_decode($json, true);

        }

    }

    public function filter($text)
    {

        foreach ($this->sensitive_words as $word) {

            $replacement = str_repeat('*', mb_strlen($word, 'UTF-8'));
            $text = str_ireplace($word, $replacement, $text);
            
        }

        return $text;

    }
}
