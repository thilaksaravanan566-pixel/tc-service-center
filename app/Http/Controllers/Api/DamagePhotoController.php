// app/Http/Controllers/Api/DamagePhotoController.php
public function store(UploadDamagePhotoRequest $request) {
    $path = $request->file('photo')->store('public/damage_photos'); [cite: 7]
    
    DamagePhoto::create([
        'service_order_id' => $request->order_id,
        'image_path' => $path,
        'description' => $request->description // e.g., "Screen crack"
    ]);

    return back()->with('success', 'TC: Photo uploaded successfully.');
}