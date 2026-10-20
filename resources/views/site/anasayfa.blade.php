@extends('layouts.site')

@section('icerik')
    @if (tema('hero.aktif')) @include('site.bolumler.hero') @endif
    @if (tema('yetenekler.aktif')) @include('site.bolumler.yetenekler') @endif
    @if (tema('hakkimda.aktif')) @include('site.bolumler.hakkimda') @endif
    @if (tema('projeler.aktif') && $projeler->isNotEmpty()) @include('site.bolumler.projeler') @endif
    @if (tema('hizmetler.aktif')) @include('site.bolumler.hizmetler') @endif
    @if (tema('referanslar.aktif')) @include('site.bolumler.referanslar') @endif
    @if (tema('kayan_yazi.aktif')) @include('site.bolumler.kayan-yazi') @endif
    @if (tema('fiyatlar.aktif')) @include('site.bolumler.fiyatlar') @endif
    @if (tema('markalar.aktif')) @include('site.bolumler.markalar') @endif
    @if (tema('blog.aktif')) @include('site.bolumler.blog') @endif
    @if (tema('iletisim.aktif')) @include('site.bolumler.iletisim') @endif
@endsection
