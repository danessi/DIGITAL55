@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pedidos</h1>
    <!-- Enlace al Dashboard -->
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Dashboard</a>
    <ul>
        @foreach($orders as $order)
            <li><a href="{{ route('orders.show', $order->id) }}">{{ $order->id }} - {{ $order->status }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
