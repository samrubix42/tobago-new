<div x-data="productFormEditor({ initialStep: {{ (int) $currentStep }} })" x-init="initEditorLifecycle()" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ isset($productId) ? 'Update Product' : 'Add New Product' }}
            </h1>
            <p class="text-gray-500 mt-1 text-sm font-medium">
                {{ isset($productId) ? 'Modify the details and manage media.' : 'Provide comprehensive details to list your new product.' }}
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-blue-600 transition"
           wire:navigate
        >
            <i class="ri-arrow-left-line"></i>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-center justify-between shadow-sm relative overflow-hidden">
        <div class="absolute left-0 top-1/2 w-full h-0.5 bg-gray-100 -z-10 translate-y-[-50%]"></div>
        <div class="absolute left-0 top-1/2 h-0.5 bg-blue-500 transition-all duration-500 -z-10 translate-y-[-50%]" 
             style="width: {{ (($currentStep - 1) / 3) * 100 }}%"></div>
        
        @php
            $steps = [
                1 => ['icon' => 'ri-article-line', 'label' => 'General Info'],
                2 => ['icon' => 'ri-price-tag-3-line', 'label' => 'Pricing'],
                3 => ['icon' => 'ri-archive-line', 'label' => 'Inventory'],
                4 => ['icon' => 'ri-image-add-line', 'label' => 'Gallery']
            ];
        @endphp

        @foreach($steps as $step => $info)
            <button 
                wire:click="setStep({{ $step }})"
                @disabled($step > $currentStep && !isset($productId))
                class="flex flex-col items-center gap-2 bg-white px-4 transition-all {{ $step > $currentStep && !isset($productId) ? 'opacity-40 cursor-not-allowed' : 'group' }}"
            >
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 {{ $currentStep == $step ? 'border-blue-500 bg-blue-50 text-blue-600 ring-4 ring-blue-50' : ($currentStep > $step ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-200 bg-white text-gray-400 group-hover:border-blue-300') }}">
                    @if($currentStep > $step)
                        <i class="ri-check-line text-lg font-bold"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-lg"></i>
                    @endif
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider {{ $currentStep == $step ? 'text-blue-600' : 'text-gray-500' }}">
                    {{ $info['label'] }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- Active Step Form Content -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-10 min-h-[350px] relative">
        @if($currentStep == 1 && !isset($productId))
            <!-- Copy Details Tool -->
            <div x-data="{ open: false, search: @entangle('productSearch').live }" class="mb-10 bg-blue-50/50 border border-blue-100 rounded-2xl p-6 relative z-30">
                <div class="flex items-start gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-blue-100 shadow-sm flex items-center justify-center shrink-0">
                        <i class="ri-file-copy-2-line text-2xl text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Copy Existing Product Details</h3>
                        <p class="text-xs text-gray-500 mb-4">Quickly populate fields by selecting a similar product. <span class="text-blue-600 font-semibold italic">(SKU and Images won't be copied)</span></p>
                        
                        <div class="relative max-w-lg">
                            <div class="relative">
                                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" wire:loading.remove wire:target="productSearch"></i>
                                <i class="ri-loader-4-line absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 animate-spin" wire:loading wire:target="productSearch"></i>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="productSearch"
                                       @focus="open = true"
                                       placeholder="Search product name to copy from..."
                                       class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none shadow-sm">
                            </div>

                            @if(!empty($searchProducts))
                                <div x-show="open" @click.outside="open = false" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl z-50 py-2 overflow-hidden">
                                    @foreach($searchProducts as $p)
                                        <button type="button" 
                                                wire:click="$set('copyProductId', {{ $p->id }}); copyProductDetails(); open = false;"
                                                class="w-full text-left px-4 py-3 hover:bg-blue-50 flex items-center gap-3 transition-colors border-b border-gray-50 last:border-0">
                                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                                @if($p->primaryImage->first())
                                                    <img src="{{ asset('storage/' . $p->primaryImage->first()->image) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="ri-image-line text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $p->name }}</div>
                                                <div class="text-[10px] text-gray-500 font-medium uppercase">{{ $p->category->title ?? 'No Category' }} • ₹{{ number_format($p->selling_price, 2) }}</div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($currentStep == 1)
            <!-- Step 1: General Info -->
            <div class="space-y-6 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">Product Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live.debounce.500ms="name" placeholder="Enter product name"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                        @error('name') <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">URL Slug <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="slug" placeholder="e.g. premium-wireless-headphones"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                        @error('slug') <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-1.5">Short Description</label>
                    <textarea wire:model.blur="description" rows="4" placeholder="Provide short product summary..."
                              class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50"></textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-1.5">Feature &amp; Specification (TinyMCE)</label>
                    <div wire:ignore>
                        <textarea id="product-feature-spec-editor" placeholder="Add complete feature and specification content..."></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-1.5">Product SKU <span class="text-xs font-normal text-gray-400 italic">(Leave blank to auto-generate)</span></label>
                                <input type="text" wire:model.blur="sku" placeholder="e.g. TOBAC-GO-001"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50 uppercase">
                                @error('sku') <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-1.5">Select Category <span class="text-red-500">*</span></label>
                                <div x-data="{ 
                                        open: false, 
                                        selectedId: @entangle('category_id').live, 
                                        get selectedTitleHTML() {
                                            if(!this.selectedId) return '<span class=\'text-gray-500\'>Select Category... <span class=\'text-xs italic text-gray-400 ml-1\'>(Optional)</span></span>';
                                            let el = this.$refs.listbox.querySelector('li[data-id=\'' + this.selectedId + '\']');
                                            if(el) {
                                                let p = el.dataset.parent;
                                                let s = el.dataset.self;
                                                if(p) {
                                                    return '<span class=\'text-gray-500 font-normal\'>' + p + '</span><i class=\'ri-arrow-right-s-line text-gray-400 mx-1.5 text-sm\'></i><span class=\'font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded\'>' + s + '</span>';
                                                }
                                                return '<span class=\'font-bold text-gray-800\'>' + s + '</span>';
                                            }
                                            return '<span class=\'text-gray-500\'>Select Category...</span>';
                                        }
                                    }" 
                                    @click.outside="open = false" 
                                    class="relative min-w-full z-20">

                                    <button @click="open = !open" type="button" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-white hover:border-blue-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                        <span x-html="selectedTitleHTML" class="truncate line-clamp-1 flex items-center"></span>
                                        <i class="ri-arrow-down-s-line text-lg text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         x-transition.opacity.duration.200ms
                                         class="absolute left-0 right-0 z-50 mt-2 max-h-60 overflow-auto rounded-xl border border-gray-100 bg-white shadow-xl focus:outline-none py-1.5"
                                         style="display: none;"
                                         x-ref="listbox">
                                        <ul role="listbox" class="text-sm text-gray-700">
                                            <li data-id="" data-self="No Category" class="cursor-pointer select-none px-4 py-2.5 font-medium transition-colors border-b border-gray-50" 
                                                :class="selectedId == '' ? 'bg-red-50 text-red-600' : 'hover:bg-slate-50 text-gray-500'" 
                                                @click="selectedId = ''; open = false">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <i class="ri-forbid-line"></i> Unassign Category
                                                    </div>
                                                    <i class="ri-check-line text-lg" x-show="selectedId == ''"></i>
                                                </div>
                                            </li>
                                            
                                            @php
                                                $parents = $categories->whereNull('parent_id');
                                            @endphp

                                            @foreach($parents as $parent)
                                                <li data-id="{{ $parent->id }}" data-self="{{ addslashes($parent->title) }}" 
                                                    class="cursor-pointer select-none px-4 py-2.5 font-bold transition-colors bg-slate-50/50 mt-1 flex items-center justify-between group border-l-2"
                                                    :class="selectedId == '{{ $parent->id }}' ? 'bg-blue-50/80 text-blue-700 border-blue-500' : 'border-transparent hover:bg-slate-50 hover:text-blue-600 hover:border-blue-300'"
                                                    @click="selectedId = '{{ $parent->id }}'; open = false">
                                                    <div class="flex items-center gap-2">
                                                        <i class="ri-folder-3-fill text-blue-500 group-hover:scale-110 transition-transform"></i> 
                                                        <span>{{ $parent->title }}</span>
                                                    </div>
                                                    <i class="ri-check-line text-lg text-blue-600" x-show="selectedId == '{{ $parent->id }}'"></i>
                                                </li>
                                                
                                                @php
                                                    $children = $categories->where('parent_id', $parent->id);
                                                @endphp
                                                
                                                @foreach($children as $child)
                                                    <li data-id="{{ $child->id }}" data-parent="{{ addslashes($parent->title) }}" data-self="{{ addslashes($child->title) }}" 
                                                        class="cursor-pointer select-none pr-4 pl-6 flex items-center justify-between py-2.5 transition-colors group relative"
                                                        :class="selectedId == '{{ $child->id }}' ? 'bg-blue-50 text-blue-800' : 'hover:bg-blue-50/50 text-slate-600 hover:text-blue-700'"
                                                        @click="selectedId = '{{ $child->id }}'; open = false">
                                                        <div class="flex items-center flex-1">
                                                            <div class="w-5 flex justify-center text-slate-300 ml-1 mr-3 border-l-2 border-b-2 h-5 -mt-5 rounded-bl-lg transition-colors"
                                                                 :class="selectedId == '{{ $child->id }}' ? 'border-blue-400' : 'border-slate-200 group-hover:border-blue-300'"></div> 
                                                            <span class="font-medium" :class="selectedId == '{{ $child->id }}' ? 'text-blue-800' : ''">{{ $child->title }}</span>
                                                        </div>
                                                        <i class="ri-check-line text-lg text-blue-600 transform scale-110" x-show="selectedId == '{{ $child->id }}'"></i>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('category_id') <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5 pt-7">
                        <!-- Pines UI Switch: Featured -->
                        <div x-data="{ switchOn: @entangle('is_featured') }" class="flex items-center space-x-3">
                            <button x-ref="switchButton1" type="button" @click="switchOn = !switchOn"
                                :class="switchOn ? 'bg-blue-600' : 'bg-gray-200'" 
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span :class="switchOn ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                            <label @click="$refs.switchButton1.click()" class="text-sm font-medium cursor-pointer select-none text-gray-800">Featured Product Showcase</label>
                        </div>

                        <!-- Pines UI Switch: Trending -->
                        <div x-data="{ switchOn: @entangle('is_trending') }" class="flex items-center space-x-3">
                            <button x-ref="switchButton2" type="button" @click="switchOn = !switchOn"
                                :class="switchOn ? 'bg-indigo-600' : 'bg-gray-200'" 
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span :class="switchOn ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                            <label @click="$refs.switchButton2.click()" class="text-sm font-medium cursor-pointer select-none text-gray-800">Mark as Trending</label>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 mt-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="ri-search-line text-blue-500"></i> SEO Optimization
                    </h3>
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-1.5">Meta Title</label>
                                <input type="text" wire:model.blur="meta_title" placeholder="SEO optimized title (optional)"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                                <p class="text-[11px] text-gray-400 mt-1">Recommended: 60 characters or less.</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-1.5">Meta Keywords</label>
                                <input type="text" wire:model.blur="meta_keywords" placeholder="keyword1, keyword2, keyword3"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1.5">Meta Description</label>
                            <textarea wire:model.blur="meta_description" rows="3" placeholder="SEO optimized description (optional)"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50"></textarea>
                            <p class="text-[11px] text-gray-400 mt-1">Recommended: 160 characters or less.</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($currentStep == 2)
            <!-- Step 2: Pricing -->
            <div class="space-y-6 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">Purchase Cost (₹) <span class="text-red-500">*</span></label>
                           <input type="number" step="0.01" wire:model.blur="cost_price" placeholder="0.00"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                        @error('cost_price') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-2">Internal cost metric, never visible to users.</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">Retail Price (₹) <span class="text-red-500">*</span></label>
                           <input type="number" step="0.01" wire:model.blur="selling_price" placeholder="0.00"
                               class="w-full rounded-lg border border-blue-300 px-4 py-2.5 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-blue-50/10">
                        @error('selling_price') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">Strike Price (₹)</label>
                           <input type="number" step="0.01" wire:model.blur="compare_price" placeholder="0.00"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                        <p class="text-xs text-gray-400 mt-2">Used as the "original price" for showing discounts.</p>
                    </div>
                </div>
            </div>

        @elseif($currentStep == 3)
            <!-- Step 3: Inventory Management -->
            <div class="space-y-8 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1.5">Valid Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.blur="stock" placeholder="Enter numerical units"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                            @error('stock') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-1.5">Low Stock FOMO Threshold</label>
                            <input type="number" wire:model.blur="hurry_stock" placeholder="e.g. 10"
                                   class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition shadow-sm bg-orange-50/20">
                            <p class="text-xs text-gray-500 mt-2">This is the number shown to users for urgency messaging, for example: "Only 10 left in stock".</p>
                        </div>
                    </div>

                    <div class="bg-red-50/50 border border-red-100 rounded-xl p-6 h-full flex flex-col justify-center shadow-sm">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <i class="ri-prohibited-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-red-900 mb-1">Temporary Unavailability</h4>
                                <p class="text-xs text-red-700/80 mb-4 leading-relaxed">Turn this on to immediately block checkouts for this product regardless of the remaining active numerical stock.</p>
                                
                                <!-- Pines UI Switch: Out of Stock Override -->
                                <div x-data="{ switchOn: @entangle('is_out_of_stock') }" class="flex items-center space-x-3">
                                    <button x-ref="switchButton3" type="button" @click="switchOn = !switchOn"
                                        :class="switchOn ? 'bg-red-600' : 'bg-red-200'" 
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <span :class="switchOn ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                    <label @click="$refs.switchButton3.click()" class="text-sm font-bold cursor-pointer select-none" :class="switchOn ? 'text-red-600' : 'text-gray-500'">Force Out of Stock</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($currentStep == 4)
            <!-- Step 4: Media Gallery & Final Options -->
            <div class="space-y-8 animate-fade-in relative z-10 w-full overflow-visible">
                
                <!-- Modern Drag/Drop Zone -->
                <div x-data="{ isDropping: false }" 
                     @dragover.prevent="isDropping = true" 
                     @dragleave.prevent="isDropping = false" 
                     @drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                     @click="$refs.fileInput.click()"
                     :class="isDropping ? 'border-blue-500 bg-blue-50 ring-4 ring-blue-100' : 'border-blue-200 bg-gradient-to-b from-blue-50/60 to-white hover:border-blue-400 hover:bg-blue-50/70'"
                     class="w-full relative rounded-2xl border-2 border-dashed flex flex-col items-center justify-center p-8 sm:p-10 transition-all duration-300 cursor-pointer group">
                    
                    <div class="w-16 h-16 bg-white shadow-sm border border-blue-100 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                        <i class="ri-upload-cloud-2-line text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900">Upload Product Images</h4>
                    <p class="text-sm text-gray-500 mt-1 text-center">Drag and drop images here, or click to browse from your device.</p>
                    <p class="text-xs text-blue-700/80 mt-2 font-medium">Supports JPG, PNG, WEBP up to 2MB each</p>
                    
                    <input type="file" wire:model="images" x-ref="fileInput" multiple accept="image/*" class="hidden">
                </div>
                @error('images.*') <p class="text-xs text-red-500 font-medium"><i class="ri-alert-line"></i> {{ $message }}</p> @enderror

                @if($images)
                    <div class="flex flex-col gap-4 bg-blue-50/60 border border-blue-100 rounded-2xl p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h5 class="text-xs font-bold uppercase tracking-widest text-blue-600">Pending Uploads</h5>
                            <span class="text-xs text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full font-semibold">{{ count($images) }} selected</span>
                        </div>
                        <div class="flex items-center gap-5 flex-wrap lg:flex-nowrap">
                            <div class="flex flex-wrap gap-4 flex-1">
                                @foreach($images as $img)
                                    <div class="relative w-20 h-20 rounded-xl border border-blue-100 shadow-sm overflow-hidden bg-white">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <button wire:click="uploadImages"
                                    wire:loading.attr="disabled"
                                    class="h-12 px-6 bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-sm hover:bg-blue-700 transition flex items-center gap-2 whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="uploadImages"><i class="ri-upload-2-line"></i> Commit Upload</span>
                                <span wire:loading wire:target="uploadImages"><i class="ri-loader-4-line animate-spin"></i> Processing...</span>
                            </button>
                        </div>
                    </div>
                @endif

                @if(!empty($productImages))
                    <div class="pt-2">
                        <div class="flex flex-wrap justify-between items-end gap-2 mb-4">
                            <h4 class="text-sm font-semibold text-gray-800">Sortable Gallery <span class="text-xs text-gray-500 ml-2">Drag images to reorder storefront position</span></h4>
                            <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-full">{{ count($productImages) }} images</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-5" wire:sort="handleImageSort">
                            @foreach($productImages as $pImg)
                                <div wire:key="img-{{ $pImg['id'] }}"
                                     wire:sort:item="{{ $pImg['id'] }}"
                                     class="group relative w-32 h-32 rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 overflow-hidden transform">
                                    
                                    <img src="{{ asset('storage/' . $pImg['image']) }}" class="w-full h-full object-cover">
                                    
                                    @if($pImg['is_primary'])
                                        <div class="absolute top-2 left-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded shadow-md z-10 flex items-center gap-1">
                                            <i class="ri-star-line text-[10px]"></i> Main
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity bg-blue-900/40 backdrop-blur-[2px] flex items-center justify-center z-20">
                                        <span wire:sort:handle class="w-10 h-10 rounded-full bg-white/90 flex items-center justify-center text-blue-700 cursor-move hover:scale-110 transition-transform" title="Drag to reorder">
                                            <i class="ri-drag-move-fill text-2xl"></i>
                                        </span>
                                    </div>

                                    <div class="absolute inset-x-0 bottom-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-gray-900/80 to-transparent flex justify-center gap-2 z-30">
                                        @if(!$pImg['is_primary'])
                                            <button @click="$dispatch('open-set-primary-modal', { id: {{ $pImg['id'] }} })" class="w-7 h-7 flex items-center justify-center bg-white/90 rounded text-gray-700 hover:text-blue-600 hover:bg-white shadow-sm transition" title="Set Primary">
                                                <i class="ri-star-line text-sm"></i>
                                            </button>
                                        @endif
                                        <button @click="$dispatch('open-delete-image-modal', { id: {{ $pImg['id'] }} })" class="w-7 h-7 flex items-center justify-center bg-white/90 rounded text-red-500 hover:text-red-600 hover:bg-white shadow-sm transition" title="Delete">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-6 border-t border-gray-100">
                    <div class="max-w-md">
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">Visibility Status <span class="text-red-500">*</span></label>
                        <select wire:model.blur="status"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition shadow-sm bg-gray-50/50">
                            <option value="active">Active (Visible to users)</option>
                            <option value="inactive">Inactive (Hidden from storefront)</option>
                            <option value="draft">Draft (Work in progress)</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        @endif

        <!-- Floating Footer Nav -->
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
            <button wire:click="prevStep" 
                    class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-2 rounded-lg hover:bg-gray-50 {{ $currentStep == 1 ? 'invisible' : '' }}">
                <i class="ri-arrow-left-line"></i> Previous Stage
            </button>

            <div class="flex items-center gap-3">
                @if($currentStep < 4)
                    <button wire:click="nextStep"
                            class="px-7 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition flex items-center gap-2 hover:translate-x-1 duration-200">
                        Continue <i class="ri-arrow-right-line"></i>
                    </button>
                @else
                    <button wire:click="saveProduct"
                            wire:loading.attr="disabled"
                            class="px-7 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm shadow-blue-500/20 hover:bg-blue-700 transition flex items-center gap-2 active:scale-95 disabled:opacity-70">
                        <span wire:loading.remove wire:target="saveProduct"><i class="ri-save-3-line"></i> Finalize Overview</span>
                        <span wire:loading wire:target="saveProduct"><i class="ri-loader-4-line animate-spin"></i> Saving...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div x-data="{ show: false, imageId: null }" x-show="show" @open-delete-image-modal.window="show = true; imageId = $event.detail.id" @close-delete-image-modal.window="show = false" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none;" x-cloak>
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show" x-transition.scale.95 class="bg-white rounded-2xl shadow-2xl relative max-w-sm w-full p-8 text-center z-10 transform">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <i class="ri-delete-bin-4-line text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Permanently?</h3>
            <p class="text-gray-500 mb-8 text-sm">This action cannot be undone. The image file will be removed from storage.</p>
            <div class="flex gap-4 justify-center">
                <button @click="show = false" class="flex-1 py-2.5 text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition">Cancel</button>
                <button @click="$wire.deleteImage(imageId); show = false" class="flex-1 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-500/20 transition">Yes, Delete</button>
            </div>
        </div>
    </div>

    <div x-data="{ show: false, imageId: null }" x-show="show" @open-set-primary-modal.window="show = true; imageId = $event.detail.id" @close-set-primary-modal.window="show = false" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none;" x-cloak>
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
        <div x-show="show" x-transition.scale.95 class="bg-white rounded-2xl shadow-2xl relative max-w-sm w-full p-8 text-center z-10 transform">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <i class="ri-star-smile-line text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Change Main Visual?</h3>
            <p class="text-gray-500 mb-8 text-sm">This image will appear on thumbnail catalog listings for your storefront.</p>
            <div class="flex gap-4 justify-center">
                <button @click="show = false" class="flex-1 py-2.5 text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition">Cancel</button>
                <button @click="$wire.setPrimary(imageId); show = false" class="flex-1 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition">Set Focus</button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<script>
    function productFormEditor(config = {}) {
        return {
            tinyId: 'product-feature-spec-editor',
            step: Number(config.initialStep || 1),
            retryCount: 0,
            maxRetries: 8,

            initEditorLifecycle() {
                this.$nextTick(() => this.syncEditorsForStep(true));

                this.$watch('step', () => {
                    this.$nextTick(() => this.syncEditorsForStep(true));
                });

                window.addEventListener('product-step-changed', (event) => {
                    const nextStep = Number(event?.detail?.step);
                    if (!Number.isNaN(nextStep)) {
                        this.step = nextStep;
                    }
                });

                window.addEventListener('update-tinymce-content', (event) => {
                    const content = event.detail.content || '';
                    const editor = window.tinymce && window.tinymce.get('product-feature-spec-editor');
                    if (editor) {
                        editor.setContent(content);
                    }
                });

                document.addEventListener('livewire:navigated', () => {
                    this.step = Number(this.$wire?.get('currentStep') || 1);
                    this.$nextTick(() => this.syncEditorsForStep(true));
                });

                // Re-init after Livewire morphs this component in place.
                if (window.Livewire?.hook) {
                    window.Livewire.hook('morph.updated', ({ el }) => {
                        if (this.$root.contains(el) || this.$root === el) {
                            this.step = Number(this.$wire?.get('currentStep') || this.step);
                            this.$nextTick(() => this.syncEditorsForStep());
                        }
                    });
                }
            },

            syncEditorsForStep(forceSync = false) {
                if (!window.tinymce) return;

                if (Number(this.step) !== 1) {
                    this.destroyEditors();
                    return;
                }

                this.initEditor(forceSync);
            },

            initEditor(forceSync = false) {
                const textarea = document.getElementById(this.tinyId);
                if (!textarea || !textarea.isConnected) {
                    if (this.retryCount < this.maxRetries) {
                        this.retryCount++;
                        setTimeout(() => this.syncEditorsForStep(forceSync), 120);
                    }
                    return;
                }

                this.retryCount = 0;

                const existing = window.tinymce.get(this.tinyId);
                if (existing) {
                    const current = this.$wire.get('feature_and_specifications') || '';
                    if (forceSync && existing.getContent() !== current) {
                        existing.setContent(current);
                    }
                    return;
                }

                window.tinymce.init({
                    selector: `#${this.tinyId}`,
                    menubar: 'file edit view insert format tools table help',
                    branding: false,
                    height: 460,
                    min_height: 420,
                    toolbar_mode: 'sliding',
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount autoresize hr nonbreaking pagebreak directionality emoticons codesample',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist checklist | link image media table | charmap emoticons hr pagebreak nonbreaking | insertdatetime codesample | searchreplace visualblocks fullscreen preview code | removeformat help',
                    content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }',
                    setup: (editor) => {
                        editor.on('init', () => {
                            editor.setContent(this.$wire.get('feature_and_specifications') || '');
                        });

                        const sync = () => this.$wire.set('feature_and_specifications', editor.getContent(), false);
                        editor.on('change keyup input undo redo', sync);
                    },
                });
            },

            destroyEditors() {
                window.tinymce?.get(this.tinyId)?.remove();
                this.retryCount = 0;
            },
        }
    }
</script>
