<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    protected $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size, $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename, $options = [])
    {
        $this->dompdf->stream($filename, $options);
    }
}
