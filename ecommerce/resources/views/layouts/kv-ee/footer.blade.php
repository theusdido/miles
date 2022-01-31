<div class="kv-ee-container kv-ee-main">
    @include('layouts.kv-ee.quemsomos.home')    
    <div class="kv-ee-row kv-ee-mt-4">
        <div class="kv-ee-col-sm-6 kv-ee-footer-address" data-type="address">
            <p class="kv-ee-body--sm">{{ EcommerceMain::EnderecoLoja()->logradouro }}, {{ EcommerceMain::EnderecoLoja()->numero }}</p>
            <p class="kv-ee-body--sm">Criciúma, SC, {{ EcommerceMain::EnderecoLoja()->cep }}, BR</p>
        </div>
        <div class="kv-ee-col-sm-6 kv-ee-footer-contact kv-ee-text-sm-right">
            <a href="tel:+55 48 9937-2921" data-type="phone">+55 {{ EcommerceMain::loja()->telefone }}}</a>
            <br>
            <a href="mailto:contato@granuemporio.com.br" data-type="email">{{ EcommerceMain::loja()->email }}</a>
        </div>
    </div>
    <div class="kv-ee-row kv-ee-legal">
        <div class="kv-ee-col-12">
            <p class="kv-ee-body--sm">
                <span data-type="copyright">© 2021 Granú Produtos Naturais</span>
            </p>
        </div>
        <div class="kv-ee-col-12">
            <div class="kv-ee-legal-placeholder"></div>
        </div>
    </div>


</div>
