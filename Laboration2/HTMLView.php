<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:20
 */

class HTMLView {
    public function echoHTML($htmlString) {
        if($htmlString === NULL) {
            throw new \Exception("HTMLView::echoHTLM does not allow body to be null");
        }

        echo "
                <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
                <html xmlns='http://www.w3.org/1999/xhtml'>
                    $htmlString
                </html>
        ";
    }
} 