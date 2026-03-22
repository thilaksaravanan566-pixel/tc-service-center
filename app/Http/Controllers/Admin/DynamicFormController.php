<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicForm;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DynamicFormController extends Controller
{
    public function index()
    {
        $forms = DynamicForm::withCount('fields')->latest()->get();
        return view('admin.customization.forms.index', compact('forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        /** @var DynamicForm $form */
        $form = DynamicForm::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.forms.show', $form->id)->with('success', 'Form created successfully. Now add fields.');
    }

    public function show($id)
    {
        $form = DynamicForm::with('fields')->findOrFail($id);
        return view('admin.customization.forms.show', compact('form'));
    }

    public function addField(Request $request, $id)
    {
        $form = DynamicForm::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,select,radio,checkbox,file,date',
            'options' => 'nullable|string', // Comma separated for select/radio
            'is_required' => 'nullable|boolean'
        ]);

        $options = null;
        if (in_array($request->type, ['select', 'radio', 'checkbox']) && $request->options) {
            $options = array_map('trim', explode(',', $request->options));
        }

        $form->fields()->create([
            'label' => $request->label,
            'name' => Str::slug($request->label, '_'),
            'type' => $request->type,
            'options' => $options,
            'is_required' => $request->has('is_required'),
            'order' => $form->fields()->count() + 1
        ]);

        return back()->with('success', 'Field added successfully.');
    }

    public function destroyField($id, $field_id)
    {
        $field = FormField::where('dynamic_form_id', $id)->findOrFail($field_id);
        $field->delete();

        return back()->with('success', 'Field removed.');
    }

    public function destroy($id)
    {
        $form = DynamicForm::findOrFail($id);
        $form->delete();

        return redirect()->route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }
}
