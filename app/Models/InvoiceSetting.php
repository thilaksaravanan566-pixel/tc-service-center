<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_text',
        'footer_message',
        'terms_conditions',
        'show_hsn_sac',
        'show_discount',
        'show_tax_breakup',
        'show_signature',
        'invoice_prefix',
        'invoice_number_length',
        'next_invoice_number',
        'theme_color',
        'font_size',
        'default_template',
        'auto_reset_fy',
    ];
}
