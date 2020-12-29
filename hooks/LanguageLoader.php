<?php

class LanguageLoader
{
    function initialize()
    {
        $ci =& get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->uri->segment(1);
       
        if ($siteLang) {
            switch ($siteLang) {
                case "en":
                    $uri = 'english';
                    break;
                case "it":
                    $uri = 'italian';
                    break;
                case "de":
                    $uri = 'german';
                    break;
                 case "ar":
                    $uri = 'arabic';
                    break;

                default:
                    $uri = 'english';

            }
            $ci->lang->load('about', $uri);
        } else {
            $ci->lang->load('about', 'english');
        }

    }
}