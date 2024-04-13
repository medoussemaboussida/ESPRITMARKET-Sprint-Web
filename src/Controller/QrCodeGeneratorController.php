<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Label\Font\NotoSans;

 
class QrCodeGeneratorController extends AbstractController
{

    #[Route('/generate-qr-code/{content}', name: 'generate_qr_code')]
    public function generateQrCodeUri(string $content): Response
    {
        // Créer une instance de QrCode avec le contenu spécifié
        $qrCode = new QrCode($content);

        // Retourner une réponse HTTP contenant le QR code
        return new QrCodeResponse($qrCode);
    }
}