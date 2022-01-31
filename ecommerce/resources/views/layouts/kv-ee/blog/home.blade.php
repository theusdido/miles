<section class="background-id_0 section-1622425090785 blogfe01" style=" position:relative;">
    <a name="section1622425090785"></a>
    <div class="kv-background  " style=" background-color: rgb(33,33,33);">
        <div class="kv-background-inner " style="background-color: rgb(33,33,33);"></div>
    </div>
    <div class="position-relative kv-content">
        <div class="kv-ee-section kv-ee-section--lg kv-ee-align-left kv-ee-image-landscape kv-content" data-sectiontype="blog-featured">
			<div class="kv-ee-container">
				<div class="kv-ee-row">
					<div class="kv-ee-col-12 kv-ee-section-text">
						<h2 class="kv-ee-section-title kv-ee-section-title--md" data-type="text" tabindex="0">Acompanhe nossas dicas e receitas<span data-prop="title" class="ck-editable-element" data-editable="basic" style="display:none;"></span></h2> 
					</div>
				</div>
				<div class="kv-ee-posts-container">
					<div class="kv-ee-row kv-ee-alignment">
						@foreach(EcommerceMain::Blog() as $index => $post)
							@if($index == 0)
                                <div class="kv-ee-col-lg-6 kv-ee-item">
                                    <a data-href="{{ $post->href }}" href="{{ $post->href }}" data-id="{{ $post->id }}" class="kv-ee-blog-post-first">
                                        <div class="kv-ee-post-image" style="background-image: url('{{ $post->imagem }}');"></div>
                                        <div class="kv-ee-post-text">
                                            <h3 class="kv-ee-post-title kv-ee-title--md">{{ $post->titulo }}</h3>
                                            <span class="kv-ee-post-date">{{ $post->datahora }}</span>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <a data-href="{{ $post->href }}" href="{{ $post->href }}" class="kv-ee-blog-post kv-ee-item kv-ee-col-lg-3">
                                    <div class="kv-ee-image-container">
                                        <div class="kv-ee-post-image" style="background-image: url('{{ $post->imagem }}');"></div>
                                    </div>
                                    <div class="kv-ee-text-wrapper">
                                        <h3 class="kv-ee-post-title kv-ee-title--xs">{{ $post->titulo }}</h3>
                                        <div class="kv-ee-post-footer-wrapper">
                                            <div class="kv-ee-post-footer">
                                                <span class="kv-ee-post-date">{{ $post->datahora }}</span>
                                                <div class="kv-ee-button-link">Ler mais</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
						@endforeach
					</div>
				</div>
				<div class="blog--add-post--button"></div>
				<div class="kv-ee-row">
					<div class="kv-ee-col-12">
						<hr class="kv-ee-line">
						<div class="kv-ee-button-link">
							<a data-href="/blog" href="/blog" class="">Ver mais publicações</a>
							<i class="fa fa-chevron-right"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>