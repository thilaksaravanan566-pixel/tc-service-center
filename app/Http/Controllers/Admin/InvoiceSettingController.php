<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceSetting;
use App\Models\CompanyProfile;

class InvoiceSettingController extends Controller
{
    public function index()
    {
        $setting = InvoiceSetting::first() ?? new InvoiceSetting();
        $company = CompanyProfile::first() ?? new CompanyProfile();

        return view('admin.invoice-settings.index', compact('setting', 'company'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'company_address' => 'nullable|string',
            'company_gst_number' => 'nullable|string',
            'invoice_prefix' => 'nullable|string',
            'invoice_number_length' => 'required|integer',
            'theme_color' => 'required|string',
            'font_size' => 'required|string',
            'default_template' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update Company Profile
        $company = CompanyProfile::first() ?? new CompanyProfile();
        $company->name = $request->input('company_name');
        $company->email = $request->input('company_email');
        $company->phone = $request->input('company_phone');
        $company->address = $request->input('company_address');
        $company->gst_number = $request->input('company_gst_number');

        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $company->logo = 'images/' . $imageName;
        }

        $company->save();

        // Update Invoice Settings
        $setting = InvoiceSetting::first() ?? new InvoiceSetting();
        $setting->header_text = $request->input('header_text');
        $setting->footer_message = $request->input('footer_message');
        $setting->terms_conditions = $request->input('terms_conditions');
        $setting->show_hsn_sac = $request->has('show_hsn_sac');
        $setting->show_discount = $request->has('show_discount');
        $setting->show_tax_breakup = $request->has('show_tax_breakup');
        $setting->show_signature = $request->has('show_signature');
        $setting->invoice_prefix = $request->input('invoice_prefix', 'INV_');
        $setting->invoice_number_length = $request->input('invoice_number_length', 5);
        $setting->theme_color = $request->input('theme_color', '#4f46e5');
        $setting->font_size = $request->input('font_size', '14px');
        $setting->default_template = $request->input('default_template', 'standard');
        $setting->auto_reset_fy = $request->has('auto_reset_fy');
        $setting->save();

        return redirect()->route('admin.invoice-settings.index')->with('success', 'Invoice settings updated successfully.');
    }
}
