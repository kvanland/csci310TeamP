<?php

include '../vendor/autoload.php';

class PDFParser 
{
    private $parser;

    function __construct() {
        $this->parser = new \Smalot\PdfParser\Parser();
    }

    public function parsePDF($path) {
        try {
            $pdf_contents = file_get_contents($path);
            $pdf = $this->parser->parseContent($pdf_contents);
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return null;
        }
        $text = $pdf->getText();
        return $text;
    }
}

    
