@extends('layouts.admin')

@section('content')

<h2 style="color:gold;">Service Details</h2>

<p><b>Job ID:</b> {{ $order->job_id }}</p>
<p><b>Customer:</b> {{ $order->customer->name }}</p>
<p><b>Device:</b> {{ $order->device->type_label }} - {{ $order->device->brand }}</p>
<p><b>Problem Reported:</b> {{ $order->problem }}</p>

<hr>

{{-- STATUS UPDATE --}}
<form method="POST" action="{{ route('admin.services.status',$order->id) }}">
@csrf
<select name="status">
    <option value="received" @selected($order->status=='received')>Received</option>
    <option value="inspection" @selected($order->status=='inspection')>Inspection</option>
    <option value="packing" @selected($order->status=='packing')>Packing</option>
    <option value="shipping" @selected($order->status=='shipping')>Shipping</option>
    <option value="out_for_delivery" @selected($order->status=='out_for_delivery')>Out for Delivery</option>
    <option value="delivered" @selected($order->status=='delivered')>Delivered</option>
</select>
<button type="submit">Update Status</button>
</form>

<hr>

{{-- DAMAGE PHOTO UPLOAD --}}
<h3 style="color:gold;">Damage Photos</h3>

<form method="POST"
      enctype="multipart/form-data"
      action="{{ url('/admin/services/'.$order->id.'/damage-photo') }}">
@csrf

<select name="type">
    <option value="before">Before Service</option>
    <option value="after">After Service</option>
</select>

<br><br>

<input type="file" name="photo" required>

<br><br>

<button type="submit">Upload Photo</button>
</form>

@if($order->damagePhotos->count())
    <div style="margin-top:15px;">
        @foreach($order->damagePhotos as $photo)
            <img src="{{ asset('storage/'.$photo->photo) }}"
                 width="150"
                 style="border:2px solid gold;margin:5px;">
        @endforeach
    </div>
@endif

<hr>

{{-- ENGINEER COMMENT --}}
<h3 style="color:gold;">Engineer / Technician Comment</h3>

<form method="POST"
      action="{{ route('admin.services.engineer.comment',$order->id) }}">
@csrf

<textarea name="engineer_comment"
          rows="4"
          style="width:100%;"
          placeholder="Describe what was fixed">{{ $order->engineer_comment }}</textarea>

<br><br>

<button type="submit">Save Comment</button>
</form>

@if($order->engineer_comment)
<hr>
<h4 style="color:gold;">Problem Solved</h4>
<p>{{ $order->engineer_comment }}</p>
@endif

<hr>

{{-- INVOICE --}}
<a href="{{ route('admin.invoice.create',$order->id) }}"
   style="padding:8px 14px;background:gold;color:black;text-decoration:none;">
Generate Invoice
</a>

@endsection