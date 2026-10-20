@extends('layouts.site')

@section('baslik', tema('projeler.sayfa_baslik'))

@section('icerik')
    @include('site.parcalar.sayfa-baslik', [
        'baslik' => tema('projeler.sayfa_baslik'),
        'yol' => 'projeler.sayfa_baslik',
        'kirintilar' => [tema('projeler.sayfa_baslik') => null],
    ])

    <section class="projeler">
        <div class="projeler-ic">
            <div class="container">
                @include('site.parcalar.proje-filtre')

                @if ($projeler->isEmpty())
                    <p class="bos-durum">—</p>
                @else
                    @include('site.parcalar.proje-izgara')
                @endif
            </div>
        </div>
    </section>
@endsection
