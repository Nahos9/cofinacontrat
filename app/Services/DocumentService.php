<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class DocumentService
{
    public function convertDocxToPdf(string $docxPath): ?string
    {
        if (!file_exists($docxPath)) {
            return null;
        }

        $outputDir = dirname($docxPath);
        $escapedInput = escapeshellarg($docxPath);
        $escapedOutDir = escapeshellarg($outputDir);

        $soffice = getenv('SOFFICE_BIN') ?: 'soffice';
        $command = $soffice . ' --headless --norestore --convert-to pdf --outdir ' . $escapedOutDir . ' ' . $escapedInput;
        $output = [];
        $exitCode = 1;
        @exec($command, $output, $exitCode);

        $pdfPath = preg_replace('/\.docx$/i', '.pdf', $docxPath);
        if ($exitCode === 0 && file_exists($pdfPath)) {
            return $pdfPath;
        }

        // Log failure details for diagnostics
        if (function_exists('logger')) {
            logger()->warning('[DocumentService] LibreOffice conversion failed', [
                'docx' => $docxPath,
                'command' => $command,
                'exitCode' => $exitCode,
                'output' => $output,
            ]);
        }

        // Fallback: PHPWord + DomPDF/mPDF/TCPDF if available
        try {
            if (class_exists('Dompdf\\Dompdf')) {
                Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            } elseif (class_exists('Mpdf\\Mpdf')) {
                Settings::setPdfRendererName(Settings::PDF_RENDERER_MPDF);
            } elseif (class_exists('TCPDF')) {
                Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
            }

            $renderer = Settings::getPdfRendererName();
            if ($renderer !== null) {
                $phpWord = IOFactory::load($docxPath, 'Word2007');
                $writer = IOFactory::createWriter($phpWord, 'PDF');
                $writer->save($pdfPath);
                if (file_exists($pdfPath)) {
                    return $pdfPath;
                }
            } else {
                if (function_exists('logger')) {
                    logger()->notice('[DocumentService] No PDF renderer configured (DomPDF/mPDF/TCPDF not found).');
                }
            }
        } catch (\Throwable $e) {
            if (function_exists('logger')) {
                logger()->error('[DocumentService] PHPWord PDF conversion error: ' . $e->getMessage(), [ 'docx' => $docxPath ]);
            }
        }

        return null;
    }

    public function protectPdf(string $pdfPath, ?string $ownerPassword = null): ?string
    {
        if (!file_exists($pdfPath)) {
            return null;
        }

        $ownerPassword = $ownerPassword ?: bin2hex(random_bytes(8));
        $protectedPath = preg_replace('/\.pdf$/i', '.protected.pdf', $pdfPath);

        $qpdf = getenv('QPDF_BIN') ?: 'qpdf';
        $escapedInput = escapeshellarg($pdfPath);
        $escapedOutput = escapeshellarg($protectedPath);
        $escapedPwd = escapeshellarg($ownerPassword);

        // Disallow modifications (modify=none). Permit printing and copying by default.
        $command = $qpdf . ' --encrypt ' . $escapedPwd . ' ' . $escapedPwd . ' 256 --use-aes=yes --modify=none -- ' . $escapedInput . ' ' . $escapedOutput;
        @exec($command, $output, $exitCode);

        if ($exitCode === 0 && file_exists($protectedPath)) {
            return $protectedPath;
        }

        // If qpdf is unavailable, fallback to the original PDF (unprotected)
        return $pdfPath;
    }
}


