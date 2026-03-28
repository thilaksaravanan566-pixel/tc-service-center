<?php

namespace App\Services;

use App\Models\ServiceOrder;

/**
 * Barcode & Job ID Service
 *
 * Generates unique, human-readable Job IDs for service orders.
 * Format: TC-YYYY-XXXXXX  (e.g., TC-2026-001542)
 *
 * Barcode rendering uses picqer/php-barcode-generator.
 * Install with: composer require picqer/php-barcode-generator
 *
 * Usage in Controller:
 *   $jobId = app(BarcodeService::class)->generateJobId();
 *   $svgBarcode = app(BarcodeService::class)->renderSvg($jobId);
 *   $pngBase64  = app(BarcodeService::class)->renderBase64Png($jobId);
 */
class BarcodeService
{
    protected string $prefix;

    public function __construct()
    {
        $this->prefix = strtoupper(config('services.barcode.prefix', 'TC'));
    }

    /**
     * Generate a unique Job ID.
     * Format: TC-2026-001542
     */
    public function generateJobId(): string
    {
        $year = now()->format('Y');

        // Get the last service order number this year
        $lastOrder = ServiceOrder::query()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $lastJobId = $lastOrder ? (string) ($lastOrder->job_id ?? '') : '';
        if ($lastOrder && preg_match('/\d{4}-(\d+)$/', $lastJobId, $m)) {
            $sequence = (int) $m[1] + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%s-%06d', $this->prefix, $year, $sequence);
    }

    /**
     * Render barcode as an SVG string.
     * Requires: picqer/php-barcode-generator
     */
    public function renderSvg(string $jobId, int $widthFactor = 2, int $height = 50): string
    {
        if (!class_exists(\Picqer\Barcode\BarcodeGeneratorSVG::class)) {
            return '<!-- Barcode package not installed. Run: composer require picqer/php-barcode-generator -->';
        }

        /** @noinspection PhpUndefinedClassInspection – installed via composer require picqer/php-barcode-generator */
        $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
        return $generator->getBarcode($jobId, $generator::TYPE_CODE_128, $widthFactor, $height);
    }

    /**
     * Render barcode as a base64-encoded PNG (for embedding in Blade).
     * Usage in Blade: <img src="{{ $barcodeBase64 }}" alt="Barcode">
     * Requires: picqer/php-barcode-generator
     */
    public function renderBase64Png(string $jobId, int $widthFactor = 2, int $height = 50): string
    {
        if (!class_exists(\Picqer\Barcode\BarcodeGeneratorPNG::class)) {
            return '';
        }

        /** @noinspection PhpUndefinedClassInspection – installed via composer require picqer/php-barcode-generator */
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $png = $generator->getBarcode($jobId, $generator::TYPE_CODE_128, $widthFactor, $height);
        return 'data:image/png;base64,' . base64_encode($png);
    }

    /**
     * Render barcode as an HTML string (inline SVG for print-ready use).
     * Includes the job ID text below the barcode.
     */
    public function renderHtml(string $jobId): string
    {
        $svg = $this->renderSvg($jobId, 2, 60);

        return <<<HTML
        <div class="barcode-wrapper" style="text-align:center; font-family: monospace;">
            {$svg}
            <div style="font-size: 12px; margin-top: 4px; letter-spacing: 2px;">{$jobId}</div>
        </div>
        HTML;
    }
}
