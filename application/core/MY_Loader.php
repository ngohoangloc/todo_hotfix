<?php

/**
 * /application/core/MY_Loader.php
 *
 */
class MY_Loader extends CI_Loader
{

    public function template($template_name, $vars = array(), $return = FALSE)
    {
        if ($return) :
            $content  = $this->view('templates/header', $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view('templates/footer', $vars, $return);

            return $content;
        else :
            $this->view('templates/header', $vars);
            $this->view($template_name, $vars);
            $this->view('templates/footer', $vars);
        endif;
    }


    public function au($template_name, $vars = array(), $return = FALSE)
    {
        if ($return) :
            $content  = $this->view('templates/au/header', $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view('templates/au/footer', $vars, $return);

            return $content;
        else :
            $this->view('templates/au/header', $vars);
            $this->view($template_name, $vars);
            $this->view('templates/au/footer', $vars);
        endif;
    }

    public function admin($template_name, $vars = array(), $return = FALSE)
    {

        if ($return) :
            $content  = $this->view('templates/au/header', $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view('templates/au/footer', $vars, $return);

            return $content;
        else :
            $this->view('templates/admin/header', $vars);
            $this->view('templates/admin/navbar', $vars);
            $this->view('templates/admin/sidebar', $vars);
            $this->view($template_name, $vars);
            $this->view('templates/admin/footer', $vars);
        endif;
    }

    public function layoutevo($template_name, $vars = array(), $return = FALSE)
    {


        if ($return) :
            $content  = $this->view('templates/layoutevo/header', $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view('templates/layoutevo/footer', $vars, $return);

            return $content;
        else :

            $this->view('templates/layoutevo/header', $vars);
            $this->view($template_name, $vars);
            $this->view('templates/layoutevo/footer', $vars);
        endif;
    }
}
