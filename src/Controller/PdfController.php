<?php

namespace App\Controller;

use App\Entity\Purchase;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\Pdf\PdfService;
use App\Repository\PurchaseRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    private PurchaseRepository $purchaseRepo;

    public function __construct(PurchaseRepository $purchaseRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
    }

    /**
     * @Route("/pdf", name="pdf_index")
     */
    public function index(): Response
    {
        // https://github.com/dompdf/dompdf
        // https://ourcodeworld.com/articles/read/799/how-to-create-a-pdf-from-html-in-symfony-4-using-dompdf
        // https://openclassrooms.com/forum/sujet/symfony-5-dompdf-probleme-de-render

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsHtml5ParserEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($this->render('pdf/invoice.html.twig'));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('test.pdf', [
            'Attachment' => false,
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * @Route("/pdf/invoice/download/{uuid}", name="pdf_invoice_download")
     */
    public function invoiceDownload(string $uuid, PdfService $pdfService): Response
    {
        $purchase = $this->purchaseRepo->findOneBy([
            'uuid' => $uuid
        ]);

        if (!$purchase) {
            throw $this->createNotFoundException("This purchase doesn't exists, check your Uuid.");
        }
        if ($purchase->getStatus() === Purchase::PAIEMENT_EN_ATTENTE) {
            throw $this->createAccessDeniedException('This purchase is awaiting for payment.');
        }


        $pdfService->getDownloadFilePdf('pdf/invoice.html.twig', $purchase->getPdfFilename(), [
            'purchase' => $purchase
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * @Route("/pdf/invoice/show/{uuid}", name="pdf_invoice_show")
     */
    public function invoiceBrowser(string $uuid, PdfService $pdfService): Response
    {
        $purchase = $this->purchaseRepo->findOneBy([
            'uuid' => $uuid
        ]);

        if (!$purchase) {
            throw $this->createNotFoundException("This purchase doesn't exists, check your Uuid.");
        }
        if ($purchase->getStatus() === Purchase::PAIEMENT_EN_ATTENTE) {
            throw $this->createAccessDeniedException('This purchase is awaiting for payment.');
        }


        $pdfService->getBrowserPdf('pdf/invoice.html.twig', $purchase->getPdfFilename(), [
            'purchase' => $purchase
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf'
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
