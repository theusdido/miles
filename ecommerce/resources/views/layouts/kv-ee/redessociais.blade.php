@php($redes = tdc::ru('td_website_geral_redessociais'))
<section class="background-id_0 section-1622425190928 hipolo98" style=" position:relative;">
    <a name="section1622425190928"></a>
    <div class="kv-control-placeholder kv-_section" data-kv-control="background"></div>
    <div class="kv-background  " style=" background-color: rgb(33,33,33);">
        <div class="kv-background-inner " style="background-color: rgb(33,33,33);"></div>
    </div>
    <div class="position-relative kv-content">
        <div class="kv-control-placeholder kv-_section" data-kv-control="content"></div>
        <div class="kv-ee-container kv-ee-section--md kv-ee-align-left kv-ee-shape kv-ee-circle">
            <div class="kv-ee-row">
                <div class="kv-ee-section-text kv-ee-intro kv-ee-col-12">
                    <h2 class="kv-ee-section-title kv-ee-section-title--md" data-type="text" tabindex="0">Siga-nos
                        <span data-prop="title" class="ck-editable-element" data-editable="basic" style="display:none;"></span>
                    </h2>
                </div>
            </div>
            <div class="kv-ee-row" data-type="social">
                <div class="kv-ee-col-lg-2 kv-ee-col-md-3 kv-ee-col-sm-4 kv-ee-col-6 kv-notify-inview">
                    <div class="kv-ee-aspect-ratio">
                        <a href="{{ $redes->facebook }}" target="_blank" class="kv-ee-social-link" aria-label="Social link Facebook">
                            <div class="kv-ee-social-hover"></div>
                            <i class="fa fa-facebook-square"></i>
                        </a>
                    </div>
                    <span class="kv-ee-social-center-text"></span>
                </div>
                <div class="kv-ee-col-lg-2 kv-ee-col-md-3 kv-ee-col-sm-4 kv-ee-col-6 kv-notify-inview">
                    <div class="kv-ee-aspect-ratio">
                        <a href="{{ $redes->instagram }}" target="_blank" class="kv-ee-social-link" aria-label="Social link Instagram">
                            <div class="kv-ee-social-hover"></div>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <span class="kv-ee-social-center-text"></span>
                </div>
            </div>
        </div>
    </div>
</section>