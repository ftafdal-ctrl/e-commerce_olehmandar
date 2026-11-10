<div class="card group hover:shadow-md transition-shadow duration-200">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="aspect-square bg-cream-50 overflow-hidden">
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>
    </a>
    <div class="p-4">
        <p class="text-xs text-mandar-500 font-medium mb-1">{{ $product->category->name }}</p>
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            <h3 class="font-semibold text-gray-800 text-sm leading-snug hover:text-mandar-600 transition line-clamp-2">{{ $product->name }}</h3>
        </a>

        {{-- Harga berdasarkan pilihan berat --}}
        <div class="mt-2 space-y-0.5">
            <p class="text-xs text-gray-500">500 Gram: <span class="font-semibold text-mandar-600">{{ $product->getFormattedPriceForWeight('500g') }}</span></p>
            <p class="text-xs text-gray-500">1 Kilo: <span class="font-semibold text-mandar-600">{{ $product->getFormattedPriceForWeight('1kg') }}</span></p>
        </div>

        @if($product->stock > 0)
            <p class="text-xs text-green-600 mt-0.5">Stok: {{ $product->stock }}</p>
        @else
            <p class="text-xs text-red-500 mt-0.5 font-medium">Stok Habis</p>
        @endif

        @if($product->stock > 0)
            <a href="{{ route('products.show', $product->slug) }}"
               class="mt-3 w-full btn-primary block text-center py-1.5 text-xs">
                Pilih Berat & Beli
            </a>
        @else
            <button type="button" disabled class="mt-3 w-full bg-gray-200 text-gray-400 font-semibold text-xs py-1.5 px-3 rounded-lg cursor-not-allowed">
                Stok Habis
            </button>
        @endif
    </div>
</div>
