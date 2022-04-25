<?php

namespace App\Service\Pdf;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

// https://github.com/dompdf/dompdf
// https://ourcodeworld.com/articles/read/799/how-to-create-a-pdf-from-html-in-symfony-4-using-dompdf

class PdfService
{
    private Environment $twig;
    private Options $pdfOptions;
    private Dompdf $pdf;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->pdfOptions = new Options;
        $this->pdf = new Dompdf;
    }

    private function setOptions(bool $IsHtml5ParserEnabled = true): Options
    {
        $this->pdfOptions->set('defaultFont', 'Arial');
        $this->pdfOptions->setIsHtml5ParserEnabled($IsHtml5ParserEnabled);
        $this->pdfOptions->setIsRemoteEnabled(true); // For CSS
        return $this->pdfOptions;
    }

    private function createPdf(string $html, ?array $twigParams): void
    {
        $this->pdf->setOptions($this->setOptions());
        $this->pdf->loadHtml($this->twig->render($html, $twigParams));
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
    }

    public function getBrowserPdf(string $html, string $filename = 'doc', ?array $twigParams): void
    {
        $this->createPdf($html, $twigParams);
        $this->pdf->stream("$filename.pdf", ['Attachment' => false]);
    }

    public function getDownloadFilePdf(string $html, string $filename = 'doc', ?array $twigParams): void
    {
        $this->createPdf($html, $twigParams);
        $this->pdf->stream("$filename.pdf", ['Attachment' => true]);
    }
}
