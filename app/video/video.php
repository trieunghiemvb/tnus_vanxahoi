<?php

class VideoApp extends AppObject {

// <editor-fold defaultstate="collapsed" desc="Properties & construct">
    public $app_name = "video";
    public $dir_layout = "frontend"; // thư mục chứa các layout
   
    public function __construct() {
        parent::__construct();
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code app">
    public function display() {
        $view = isset($_REQUEST['view']) ? $_REQUEST['view'] : "default";
        switch ($view) {
            case "default":
                $this->hienthi_default();
                break;
            default:
                $this->hienthi_default();
                break;
        }
        $this->view = null;
        parent::display();
    }
    public function hienthi_default() {
        $this->page_title = "MEDIA";
        $this->dir_layout = "frontend";
        $this->layout = "news";
        $this->view = "default";
        parent::display();
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code xử lý chung">
    /**
     * Hàm cắt văn bản ko mất từ, nối thêm dấu
     * @param string $input text to trim
     * @param int $length in characters to trim to
     * @param bool $ellipses if ellipses (...) are to be added
     * @param bool $strip_html if html tags are to be stripped
     * @return string 
     */
    public static function trim_text($input, $length, $ellipses = true, $strip_html = true) {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code view tin tức">
    

// </editor-fold>
}

?>