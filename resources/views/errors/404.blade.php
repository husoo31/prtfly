@extends('layouts.site')

@section('baslik', tema('hata.baslik'))

@section('icerik')
    <section class="hata-sayfa">
        <div class="container">
            <div class="kod">404</div>
            <h2 class="mt-3" {{ dt('hata.baslik') }}>{{ tema('hata.baslik') }}</h2>
            <p class="mt-3 mb-5" {{ dt('hata.aciklama') }}>{{ tema('hata.aciklama') }}</p>
            <a href="{{ route('anasayfa') }}" class="btn-hap dolu">
                <span {{ dt('hata.buton_metin') }}>{{ tema('hata.buton_metin') }}</span> <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>
    </section>
@endsection
