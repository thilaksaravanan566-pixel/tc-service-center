<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'          => 'Order Confirmation',
                'event_trigger' => 'order.confirmed',
                'email_subject' => 'Your Order #{order_id} is Confirmed! 🎉',
                'email_body'    => "<p>Dear {customer_name},</p>
<p>Thank you for your order! We're excited to let you know that your order <strong>#{order_id}</strong> has been confirmed and is being processed.</p>
<p><strong>Order Details:</strong></p>
<ul>
    <li>Order ID: #{order_id}</li>
    <li>Amount: ₹{amount}</li>
    <li>Status: Confirmed</li>
</ul>
<p>You can track your order at any time via your customer dashboard.</p>
<p>For any queries, contact us at {support_phone}.</p>
<p>Warm regards,<br>{company_name} Team</p>",
                'sms_body'      => "Hi {customer_name}! Your Order #{order_id} has been confirmed. Amount: ₹{amount}. Track your order in the dashboard. Questions? Call {support_phone}. - {company_name}",
                'whatsapp_body' => "🛒 *Order Confirmed!*\n\nHi *{customer_name}*,\n\nYour order *#{order_id}* for ₹{amount} is confirmed and being processed.\n\nTrack at: {tracking_link}\n\nFor help: {support_phone}\n\n_{company_name}_",
                'is_active'     => true,
            ],
            [
                'name'          => 'Service Status Update',
                'event_trigger' => 'service.status_updated',
                'email_subject' => 'Service Update: Your Device #{service_id} — Status: {status}',
                'email_body'    => "<p>Dear {customer_name},</p>
<p>This is an update regarding your service request <strong>#{service_id}</strong>.</p>
<p><strong>Current Status:</strong> {status}</p>
<p>Our technicians are working diligently on your device. You can check the full status history in your dashboard.</p>
<p>If you have any questions, please reach us at {support_phone} or reply to this email.</p>
<p>Best regards,<br>{company_name} Service Team</p>",
                'sms_body'      => "Service Update: Job #{service_id} is now '{status}'. For details visit your dashboard or call {support_phone}. - {company_name}",
                'whatsapp_body' => "🔧 *Service Update*\n\nDear *{customer_name}*,\n\nYour device (Job *#{service_id}*) status has been updated to:\n*{status}*\n\nView details: {tracking_link}\nCall us: {support_phone}\n\n_{company_name} Service Center_",
                'is_active'     => true,
            ],
            [
                'name'          => 'Delivery Update',
                'event_trigger' => 'delivery.status_updated',
                'email_subject' => 'Your Delivery is on the Way! 🚚 — Order #{order_id}',
                'email_body'    => "<p>Dear {customer_name},</p>
<p>Great news! Your order <strong>#{order_id}</strong> has been dispatched and is on its way to you.</p>
<p>Track your delivery in real-time using the link below or visit your customer dashboard.</p>
<p>Track Link: {tracking_link}</p>
<p>Questions? Contact us at {support_phone}.</p>
<p>Thank you for choosing {company_name}!</p>",
                'sms_body'      => "Your order #{order_id} has been dispatched! Track here: {tracking_link}. Questions? Call {support_phone}. - {company_name}",
                'whatsapp_body' => "🚚 *Your Order is On Its Way!*\n\nHi *{customer_name}*,\n\nOrder *#{order_id}* has been dispatched.\n\nTrack delivery: {tracking_link}\n\nExpected delivery as per schedule. For help: {support_phone}\n\n_{company_name}_",
                'is_active'     => true,
            ],
            [
                'name'          => 'Warranty Approval',
                'event_trigger' => 'warranty.approved',
                'email_subject' => 'Your Warranty Claim Has Been Approved ✅',
                'email_body'    => "<p>Dear {customer_name},</p>
<p>We're pleased to inform you that your warranty claim for service <strong>#{service_id}</strong> has been <strong>approved</strong>.</p>
<p>Our team will proceed with the repair/replacement under the warranty terms. You'll receive another update once the work begins.</p>
<p>For queries, reach us at {support_phone}.</p>
<p>Thank you for your patience.<br>{company_name} Team</p>",
                'sms_body'      => "Good news! Your warranty claim #{service_id} has been APPROVED. We'll start work shortly. Questions? Call {support_phone}. - {company_name}",
                'whatsapp_body' => "✅ *Warranty Claim Approved!*\n\nDear *{customer_name}*,\n\nYour warranty claim *#{service_id}* has been approved.\n\nOur team will contact you within 24 hours to schedule the repair.\n\nFor help: {support_phone}\n\n_{company_name}_",
                'is_active'     => true,
            ],
            [
                'name'          => 'Customer Registration Welcome',
                'event_trigger' => 'customer.registered',
                'email_subject' => 'Welcome to {company_name}! 🎉',
                'email_body'    => "<p>Dear {customer_name},</p>
<p>Welcome to <strong>{company_name}</strong>! Your account has been created successfully.</p>
<p>You can now book repair services, track orders, manage warranties, and shop spare parts — all from your personal dashboard.</p>
<p>If you need any assistance, our support team is available at {support_phone}.</p>
<p>Happy to have you with us!<br>{company_name} Team</p>",
                'sms_body'      => "Welcome to {company_name}, {customer_name}! Your account is ready. Book services, track orders & more via your dashboard. Help: {support_phone}",
                'whatsapp_body' => "🎉 *Welcome to {company_name}!*\n\nHi *{customer_name}*,\n\nYour account is all set! You can now:\n• Book repair services\n• Track your orders\n• Manage warranties\n• Shop spare parts\n\nNeed help? Call {support_phone}\n\n_{company_name} Team_",
                'is_active'     => true,
            ],
        ];

        foreach ($templates as $tpl) {
            NotificationTemplate::updateOrCreate(
                ['event_trigger' => $tpl['event_trigger']],
                $tpl
            );
        }
    }
}
