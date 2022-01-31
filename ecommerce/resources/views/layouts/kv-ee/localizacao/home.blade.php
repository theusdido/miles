<section class="background-id_0adjacent section-1622425700347 caqido52 undefined" style=" position:relative;">
    <a name="section1622425700347"></a>
    <div class="kv-control-placeholder kv-_section" data-kv-control="background"></div>
    <div class="kv-background  " style=" background-color: rgb(45,45,45);">
        <div class="kv-background-inner " style="background-color: rgb(45,45,45);"></div>
    </div>
	<!--  -->
    <div class="position-relative kv-content">
        <div class="kv-control-placeholder kv-_section" data-kv-control="content"></div>
        <div class="kv-ee-section ">
            <div class="kv-ee-container-fluid">
                <div class="kv-ee-row">
                    <div class="kv-ee-col-12 kv-ee-col-lg-7">
                        <div class="kv-ee-maps-container">
                            @include('layouts.kv-ee.googlemaps')
                        </div>
                    </div>
                    <div class="kv-ee-col-12 kv-ee-col-lg-5">
                        <div class="kv-ee-text-content kv-ee-section--lg">
                            <div class="kv-ee-header"></div>
                            <ul class="kv-ee-opening-hours" data-type="openingHours">
                                @foreach(EcommerceMain::HoraAtendimento() as $ha)
                                    <li>
                                        <div class="kv-ee-day">{{ $ha->dia_semana }}</div>
                                        <div class="kv-ee-text-right">
                                            <div class="kv-ee-time">{{ $ha->hora_inicial }} - {{ $ha->hora_final }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="kv-ee-item-footer">
                                <hr />
                                <div class="kv-ee-row">
                                    <div class="kv-ee-col-md-6">
                                        <h2 class="kv-ee-title--xs" data-type="text" tabindex="0">Endereço
                                            <span data-prop="addressTitle" class="ck-editable-element" data-editable="basic" style="display:none;"></span>
                                        </h2>
                                        <p data-type="address">{{ EcommerceMain::EnderecoLoja()->logradouro }}, {{ EcommerceMain::EnderecoLoja()->numero }}<br>Criciúma, SC<br>BR</p>
                                    </div>
                                    <div class="kv-ee-col-md-6">
                                        <h3 class="kv-ee-title--xs" data-type="text" tabindex="0">Telefone
                                            <span data-prop="phoneTitle" class="ck-editable-element" data-editable="basic" style="display:none;"></span>
                                        </h3>
                                        <a class="kv-ee-link" href="tel:+55 {{ EcommerceMain::Loja()->telefone }}" data-type="phone">+55 {{ EcommerceMain::Loja()->telefone }}</a>
                                        <h3 class="kv-ee-title--xs" data-type="text" tabindex="0">E-mail
                                            <span data-prop="mailTitle" class="ck-editable-element" data-editable="basic" style="display:none;"></span>
                                        </h3>
                                        <a class="kv-ee-link" href="mailto:{{ EcommerceMain::Loja()->email }}" data-type="email">{{ EcommerceMain::Loja()->email }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>