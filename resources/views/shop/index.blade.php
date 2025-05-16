@extends('layout')

@section('content')
    <h1>Список товаров</h1>
    <div style="display: flex; flex-wrap: wrap;">
        @foreach ($products as $product)
            <div style="border: 1px solid #ddd; padding: 10px; margin: 10px; width: 200px;">
                <img src="{{ $product->image }}" style="width: 100%;">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p><strong>{{ $product->price }} грн</strong></p>
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" style="width: 60px;">
                    <button type="submit">В корзину</button>
                </form>
            </div>
        @endforeach
    </div>
    <a href="{{ route('cart.index') }}">Перейти в корзину</a>
@endsection
