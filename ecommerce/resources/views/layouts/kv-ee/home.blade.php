@extends('layouts.kv-ee.template')

@section('content')
<content class="kv-page" name="page-home">
    <div class="kv-page-content kv-ee-with-navigation-3">

        <div class="kv-control-placeholder kv-_page" data-kv-control="0"></div>
            @include('layouts.kv-ee.section.header')
    
        <div class="kv-control-placeholder kv-_page" data-kv-control="1"></div>
        <section class="background-id_0m12 section-2 meruhy39 undefined kv-full-page" style=" position:relative;">
            {!! tdc::ru('td_website_geral_home')->texto !!}
        </section>

        <div class="kv-control-placeholder kv-_page" data-kv-control="2"></div>
            @include('layouts.kv-ee.blog.home')

        <div class="kv-control-placeholder kv-_page" data-kv-control="3"></div>
            @include('layouts.kv-ee.localizacao.home')

        <div class="kv-control-placeholder kv-_page" data-kv-control="4"></div>
            @include('layouts.kv-ee.redessociais')

        <div class="kv-control-placeholder kv-_page" data-kv-control="5"></div>
            @include('layouts.kv-ee.section.footer')
    </div>
</content>
<script>window._page={"mainPage":true,"title":"Home","uriPath":"index"};</script>
@endsection('content')