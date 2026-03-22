<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        $templates = NotificationTemplate::orderBy('name')->get();
        return view('admin.customization.notifications.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        return view('admin.customization.notifications.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);

        $request->validate([
            'email_subject'  => 'nullable|string|max:500',
            'email_body'     => 'nullable|string',
            'sms_body'       => 'nullable|string|max:400',
            'whatsapp_body'  => 'nullable|string|max:700',
            'is_active'      => 'nullable|boolean',
        ]);

        $template->update([
            'email_subject' => $request->email_subject,
            'email_body'    => $request->email_body,
            'sms_body'      => $request->sms_body,
            'whatsapp_body' => $request->whatsapp_body,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', "Template '{$template->name}' updated successfully.");
    }

    public function toggle($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);

        return back()->with('success', "Notification '" . $template->name . "' " . ($template->is_active ? 'enabled' : 'disabled') . '.');
    }
}
