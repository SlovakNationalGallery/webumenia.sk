@extends('layouts.master')

@section('title')
ERROR 500 - Fatal Error
@stop

@section('content')

<section class="intro intro500">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 content-section">
                    <div class="text-center">
                        {{-- <h1>ERROR 500</h1> --}}
                        <h2 class="top-margin">NASTALA CHYBA</h2>
                        <p>Prepáčte, ale táto stránka je z technických príčin dočasne nedostupá.</p>
                        <h3><a href="{{URL::to('/')}}">návrat <i class="icon-versus"></i> domov</a></h3>
                        <img src="/images/errors/error.fatal.jpeg" alt="Oznámenie: NedorozUMENIE">
                        <p>
                            <a href="/dielo/SVK:SNG.K_17703">Július Koller &ndash; Oznámenie: NedorozUMENIE</a>
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
