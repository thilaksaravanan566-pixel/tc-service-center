<!DOCTYPE html>
<html>
<head>
    <title>Track Your Service – TC</title>
</head>
<body style="font-family:Poppins;background:#0d0d0d;color:#fff;padding:30px;">

<h2 style="color:gold;">Service Tracking</h2>

<p><b>Job ID:</b> {{ $order->job_id }}</p>
<p><b>Device:</b> {{ $order->device->type_label }} - {{ $order->device->brand }}</p>
<p><b>Status:</b> {{ strtoupper($order->status) }}</p>

<h3 style="color:gold;">Service Photos</h3>

@foreach($order->damagePhotos as $photo)
    <img src="{{ asset('storage/'.$photo->photo) }}"
         width="180"
         style="border:2px solid gold;margin:5px;">
@endforeach

</body>
</html>