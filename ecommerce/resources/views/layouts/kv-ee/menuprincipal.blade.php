<nav>
	<ul class="kv-ee-menu kv-ee-menu-item-wrapper td_menuprincipal" data-dynamic-navigation-element="nav">
        @foreach(tdc::d('td_website_geral_menuprincipal') as $menu_item)
            <li class="">
                <a href="{{ $menu_item->link }}" data-uri-path="{{ $menu_item->link }}" target="_self">{{ $menu_item->descricao }}</a>
            </li>
		@endforeach
	</ul>
	<div class="kv-ee-callbutton">
		<div class="kv-ee-custom-header-buttons">
			<a id="td-btn-carrinho" class="kv-ee-button-cart kv-ee-button-background kv-ee-button-primary">
				<i class="fa fa-shopping-cart"></i>
				<span class=" kv-ee-button-background kv-ee-button-primary td-carrinho-qtdade-itens">0</span>
			</a>
            @include('layouts.kv-ee.carrinho')
		</div>
	</div>
	<div class="kv-menu">
		<div class="kv-ee-menu-icon">
			<div></div>
			<div></div>
			<div></div>
		</div>
	</div>
</nav>