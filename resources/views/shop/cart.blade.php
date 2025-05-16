@extends('layout')

@section('content')
    <h1>Корзина</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (count($cart) > 0)
        <form method="POST" action="{{ route('cart.update') }}">
            @csrf

            @php
                $total = 0;
            @endphp

            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cart as $id => $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price'], 2) }} грн</td>
                        <td>
                            <input type="number" name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" style="width: 60px;">
                        </td>
                        <td>{{ number_format($subtotal, 2) }} грн</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" align="right"><strong>Итого:</strong></td>
                    <td colspan="2"><strong>{{ number_format($total, 2) }} грн</strong></td>
                </tr>
                </tbody>
            </table>

            <br>
            <button type="submit">Обновить корзину</button>
        </form>
    @else
        <p>Корзина пуста.</p>
    @endif

    <br>
    <a href="{{ route('shop.index') }}">← Назад к покупкам</a>
@endsection
