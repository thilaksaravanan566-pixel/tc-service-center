<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Notification Service
 *
 * Supports WATI.io API (recommended for India)
 * Sign up at https://wati.io and get your API token.
 *
 * Usage:
 *   app(WhatsAppService::class)->sendMessage('91XXXXXXXXXX', 'Your service job TC-2026-000123 is ready!');
 *   app(WhatsAppService::class)->sendTemplate('91XXXXXXXXXX', 'service_complete', ['job_id' => 'TC-2026-000123']);
 */
class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiToken;
    protected string $from;

    public function __construct()
    {
        $this->apiUrl   = rtrim(config('services.whatsapp.api_url', ''), '/');
        $this->apiToken = config('services.whatsapp.api_token', '');
        $this->from     = config('services.whatsapp.phone_number', '');
    }

    /**
     * Send a free-form text message (only within 24-hour window)
     */
    public function sendMessage(string $phone, string $message): bool
    {
        $phone = $this->sanitizePhone($phone);

        if (empty($this->apiToken) || empty($this->apiUrl)) {
            Log::warning('WhatsApp: API not configured. Message not sent.', ['phone' => $phone]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl . '/sendSessionMessage/' . $phone, [
                'messageText' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent', ['phone' => $phone]);
                return true;
            }

            Log::error('WhatsApp send failed', [
                'phone'    => $phone,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp exception', ['message' => $e->getMessage()]);
        }

        return false;
    }

    /**
     * Send a pre-approved template message (works anytime)
     *
     * @param string $phone  Country code + number, e.g. "919876543210"
     * @param string $templateName  Approved template name in WATI
     * @param array  $parameters  Key-value pairs for template variables
     */
    public function sendTemplate(string $phone, string $templateName, array $parameters = []): bool
    {
        $phone = $this->sanitizePhone($phone);

        if (empty($this->apiToken) || empty($this->apiUrl)) {
            Log::warning('WhatsApp: API not configured. Template not sent.', ['phone' => $phone]);
            return false;
        }

        $params = [];
        foreach ($parameters as $key => $value) {
            $params[] = ['name' => $key, 'value' => (string) $value];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl . '/sendTemplateMessage', [
                'template_name' => $templateName,
                'broadcast_name' => $templateName . '_broadcast',
                'receivers' => [
                    [
                        'whatsappNumber'  => $phone,
                        'customParams'    => $params,
                    ],
                ],
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp template sent', ['phone' => $phone, 'template' => $templateName]);
                return true;
            }

            Log::error('WhatsApp template failed', [
                'phone'    => $phone,
                'template' => $templateName,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp template exception', ['message' => $e->getMessage()]);
        }

        return false;
    }

    // ─── Convenience Methods ─────────────────────────────────────────────────

    /**
     * Notify customer: service job received
     */
    public function notifyJobReceived(string $phone, string $jobId, string $customerName): bool
    {
        $message = "Dear {$customerName}, your device has been received at Thambu Computers. "
            . "Job ID: {$jobId}. We'll keep you updated. Thank you!";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Notify customer: service job completed
     */
    public function notifyJobCompleted(string $phone, string $jobId, string $amount): bool
    {
        $message = "Your device (Job ID: {$jobId}) service is complete! "
            . "Amount due: ₹{$amount}. Please visit Thambu Computers to collect. "
            . "Track at: https://thambucomputers.com/track/{$jobId}";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Notify dealer: order status update
     */
    public function notifyDealerOrderStatus(string $phone, string $orderId, string $status): bool
    {
        $message = "Thambu Computers: Your order #{$orderId} status updated to: {$status}. "
            . "Login at dealer.thambucomputers.com for details.";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send invoice link to customer
     */
    public function sendInvoiceLink(string $phone, string $customerName, string $invoiceNumber, string $invoiceUrl): bool
    {
        $message = "Dear {$customerName}, your invoice #{$invoiceNumber} from Thambu Computers is ready. "
            . "View/Download: {$invoiceUrl}";

        return $this->sendMessage($phone, $message);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Sanitize phone: strip non-digits, ensure country code
     */
    protected function sanitizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        // Prepend India country code if missing
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        return $phone;
    }
}
