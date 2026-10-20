@props(['yol', 'yildiz' => true, 'cizgi' => null])
<div {{ $attributes->merge(['class' => 'bolum-baslik']) }}>
    <h3 {{ dt($yol) }}>{{ tema($yol) }}</h3>
    <span class="cizgi" @if ($cizgi) style="width: {{ $cizgi }}px" @endif></span>
    @if ($yildiz)
        <x-yildiz />
    @endif
</div>
