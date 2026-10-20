<section class="yetenekler bolum-ust">
    <div class="yetenek-kap">
        <div class="kaydirici otomatik anim" style="--gecikme: .3s">
            @foreach (tema('yetenekler.ogeler', []) as $i => $yetenek)
                <div class="yetenek">
                    <figure>
                        <img src="{{ tema_url($yetenek['gorsel']) }}" alt="{{ $yetenek['ad'] }}" loading="lazy" {{ dt("yetenekler.ogeler.$i.gorsel", 'src') }}>
                    </figure>
                    <h4 {{ dt("yetenekler.ogeler.$i.yuzde") }}>{{ $yetenek['yuzde'] }}</h4>
                    <span {{ dt("yetenekler.ogeler.$i.ad") }}>{{ $yetenek['ad'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
