<div class="relative w-auto h-auto" x-data>
    <div
        x-data="{ 
            title: 'Default Toast Notification', 
            description: '',
            type: 'default',
            position: 'bottom-right',
            expanded: false,
            popToast (custom){
                let html = '';
                if(typeof custom != 'undefined'){
                    html = custom;
                }
                toast(this.title, { description: this.description, type: this.type, position: this.position, html: html })
            }
        }"
        x-init="
            window.toast = function(message, options = {}){
                let description = '';
                let type = 'default';
                let position = 'bottom-right';
                let html = '';
                if(typeof options.description != 'undefined') description = options.description;
                if(typeof options.type != 'undefined') type = options.type;
                if(typeof options.position != 'undefined') position = options.position;
                if(typeof options.html != 'undefined') html = options.html;
                
                window.dispatchEvent(new CustomEvent('toast-show', { detail : { type: type, message: message, description: description, position : position, html: html }}));
            }
        "
        class="relative">
    </div>

    <template x-teleport="body">
        <ul
            x-data="{ 
                toasts: [],
                toastsHovered: false,
                expanded: false,
                layout: 'default',
                position: 'bottom-right',
                paddingBetweenToasts: 12,
                deleteToastWithId (id){
                    for(let i = 0; i < this.toasts.length; i++){
                        if(this.toasts[i].id === id){
                            this.toasts.splice(i, 1);
                            break;
                        }
                    }
                },
                burnToast(id){
                    let burnToast = this.getToastWithId(id);
                    if(!burnToast) return;
                    let burnToastElement = document.getElementById(burnToast.id);
                    if(burnToastElement){
                        if(this.toasts.length == 1){
                            if(this.layout=='default'){
                                this.expanded = false;
                            }
                            burnToastElement.classList.remove('translate-y-0');
                            if(this.position.includes('bottom')){
                                burnToastElement.classList.add('translate-y-full');
                            } else {
                                burnToastElement.classList.add('-translate-y-full');
                            }
                        }
                        burnToastElement.classList.add('opacity-0');
                        let that = this;
                        setTimeout(function(){
                            that.deleteToastWithId(id);
                            setTimeout(function(){
                                that.stackToasts();
                            }, 1)
                        }, 300);
                    }
                },
                getToastWithId(id){
                    for(let i = 0; i < this.toasts.length; i++){
                        if(this.toasts[i].id === id){
                            return this.toasts[i];
                        }
                    }
                },
                stackToasts(){
                    this.positionToasts();
                    this.calculateHeightOfToastsContainer();
                    let that = this;
                    setTimeout(function(){
                        that.calculateHeightOfToastsContainer();
                    }, 300);
                },
                positionToasts(){
                    if(this.toasts.length == 0) return;
                    let topToast = document.getElementById( this.toasts[0].id );
                    if(!topToast) return;
                    topToast.style.zIndex = 100;
                    if(this.expanded){
                        if(this.position.includes('bottom')){
                            topToast.style.top = 'auto';
                            topToast.style.bottom = '0px';
                        } else {
                            topToast.style.top = '0px';
                        }
                    }

                    if(this.toasts.length == 1) return;
                    let middleToast = document.getElementById( this.toasts[1].id );
                    if(!middleToast) return;
                    middleToast.style.zIndex = 90;

                    if(this.expanded){
                        let middleToastPosition = topToast.getBoundingClientRect().height +
                                                this.paddingBetweenToasts + 'px';

                        if(this.position.includes('bottom')){
                            middleToast.style.top = 'auto';
                            middleToast.style.bottom = middleToastPosition;
                        } else {
                            middleToast.style.top = middleToastPosition;
                        }

                        middleToast.style.scale = '100%';
                        middleToast.style.transform = 'translateY(0px)';
                        
                    } else {
                        middleToast.style.scale = '94%';
                        if(this.position.includes('bottom')){
                            middleToast.style.transform = 'translateY(-12px)';
                        } else {
                            this.alignBottom(topToast, middleToast);
                            middleToast.style.transform = 'translateY(12px)';
                        }
                    }
                    

                    if(this.toasts.length == 2) return;
                    let bottomToast = document.getElementById( this.toasts[2].id );
                    if(!bottomToast) return;
                    bottomToast.style.zIndex = 80;
                    if(this.expanded){
                        let bottomToastPosition = topToast.getBoundingClientRect().height + 
                                                this.paddingBetweenToasts + 
                                                middleToast.getBoundingClientRect().height +
                                                this.paddingBetweenToasts + 'px';
                        
                        if(this.position.includes('bottom')){
                            bottomToast.style.top = 'auto';
                            bottomToast.style.bottom = bottomToastPosition;
                        } else {
                            bottomToast.style.top = bottomToastPosition;
                        }

                        bottomToast.style.scale = '100%';
                        bottomToast.style.transform = 'translateY(0px)';
                    } else {
                        bottomToast.style.scale = '88%';
                        if(this.position.includes('bottom')){
                            bottomToast.style.transform = 'translateY(-24px)';
                        } else {
                            this.alignBottom(topToast, bottomToast);
                            bottomToast.style.transform = 'translateY(24px)';
                        }
                    }

                    if(this.toasts.length == 3) return;
                    let lastBurnToast = document.getElementById( this.toasts[3].id );
                    if(!lastBurnToast) return;
                    lastBurnToast.style.zIndex = 70;
                    if(this.expanded){
                        let lastBurnToastPosition = topToast.getBoundingClientRect().height + 
                                                this.paddingBetweenToasts + 
                                                middleToast.getBoundingClientRect().height + 
                                                this.paddingBetweenToasts + 
                                                bottomToast.getBoundingClientRect().height + 
                                                this.paddingBetweenToasts + 'px';
                        
                        if(this.position.includes('bottom')){
                            lastBurnToast.style.top = 'auto';
                            lastBurnToast.style.bottom = lastBurnToastPosition;
                        } else {
                            lastBurnToast.style.top = lastBurnToastPosition;
                        }

                        lastBurnToast.style.scale = '100%';
                        lastBurnToast.style.transform = 'translateY(0px)';
                    } else {
                        lastBurnToast.style.scale = '82%';
                        this.alignBottom(topToast, lastBurnToast);
                        lastBurnToast.style.transform = 'translateY(36px)';
                    }

                    lastBurnToast.firstElementChild.classList.remove('opacity-100');
                    lastBurnToast.firstElementChild.classList.add('opacity-0');

                    let that = this;
                    setTimeout(function(){
                        that.toasts.pop();
                    }, 300);

                    if(this.position.includes('bottom')){
                        middleToast.style.top = 'auto';
                    }

                    return;
                },
                alignBottom(element1, element2) {
                    let top1 = element1.offsetTop;
                    let height1 = element1.offsetHeight;
                    let height2 = element2.offsetHeight;
                    let top2 = top1 + (height1 - height2);
                    element2.style.top = top2 + 'px';
                },
                alignTop(element1, element2) {
                    let top1 = element1.offsetTop;
                    element2.style.top = top1 + 'px';
                },
                resetBottom(){
                    for(let i = 0; i < this.toasts.length; i++){
                        if(document.getElementById( this.toasts[i].id )){
                            let toastElement = document.getElementById( this.toasts[i].id );
                            toastElement.style.bottom = '0px';
                        }
                    }
                },
                resetTop(){
                    for(let i = 0; i < this.toasts.length; i++){
                        if(document.getElementById( this.toasts[i].id )){
                            let toastElement = document.getElementById( this.toasts[i].id );
                            toastElement.style.top = '0px';
                        }
                    }
                },
                getBottomPositionOfElement(el){
                    return (el.getBoundingClientRect().height + el.getBoundingClientRect().top);
                },
                calculateHeightOfToastsContainer(){
                    if(this.toasts.length == 0){
                        $el.style.height = '0px';
                        return;
                    }

                    let lastToast = this.toasts[this.toasts.length - 1];
                    let lastToastEl = document.getElementById(lastToast.id);
                    if(!lastToastEl) return;
                    let lastToastRectangle = lastToastEl.getBoundingClientRect();
                    
                    let firstToast = this.toasts[0];
                    let firstToastEl = document.getElementById(firstToast.id);
                    if(!firstToastEl) return;
                    let firstToastRectangle = firstToastEl.getBoundingClientRect();

                    if(this.toastsHovered){
                        if(this.position.includes('bottom')){
                            $el.style.height = ((firstToastRectangle.top + firstToastRectangle.height) - lastToastRectangle.top) + 'px';
                        } else {
                            $el.style.height = ((lastToastRectangle.top + lastToastRectangle.height) - firstToastRectangle.top) + 'px';
                        }
                    } else {
                        $el.style.height = firstToastRectangle.height + 'px';
                    }
                }
            }"
            @set-toasts-layout.window="
                layout=event.detail.layout;
                if(layout == 'expanded'){
                    expanded=true;
                } else {
                    expanded=false;
                }
                stackToasts();
            "
            @toast-show.window="
                event.stopPropagation();
                const payload = event.detail[0] ?? event.detail;
                position = 'bottom-right';

                toasts.unshift({
                    id: 'toast-' + Math.random().toString(16).slice(2),
                    message: payload.message ?? '',
                    description: payload.description ?? '',
                    type: payload.type ?? 'default',
                    html: payload.html ?? null
                });
            "
            @mouseenter="toastsHovered=true;"
            @mouseleave="toastsHovered=false"
            x-init="
                if(layout == 'expanded'){
                    expanded = true;
                }
                stackToasts();
                $watch('toastsHovered', function(value){
                    if(layout == 'default'){
                        if(position.includes('bottom')){
                            resetBottom();
                        } else {
                            resetTop();
                        }

                        if(value){
                            expanded = true;
                            if(layout == 'default'){
                                stackToasts();
                            }
                        } else {
                            if(layout == 'default'){
                                expanded = false;
                                stackToasts();
                                setTimeout(function(){
                                    stackToasts();
                                }, 10)
                            }
                        }
                    }
                });
            "
            class="fixed block w-full px-0 group z-[99999] sm:max-w-sm"
            :class="{ 
                'right-0 top-0 sm:top-0 sm:mt-6 sm:mr-6': position=='top-right', 
                'left-0 top-0 sm:top-0 sm:mt-6 sm:ml-6': position=='top-left', 
                'left-1/2 -translate-x-1/2 top-0 sm:top-0 sm:mt-6': position=='top-center', 
                'right-0 bottom-0 sm:bottom-0 sm:mr-6 sm:mb-6': position=='bottom-right', 
                'left-0 bottom-0 sm:bottom-0 sm:ml-6 sm:mb-6': position=='bottom-left', 
                'left-1/2 -translate-x-1/2 bottom-0 sm:mb-6': position=='bottom-center' 
            }"
            x-cloak>

            <template x-for="(toast, index) in toasts" :key="toast.id">
                <li
                    :id="toast.id"
                    x-data="{
                        toastHovered: false
                    }"
                    x-init="
                        if(position.includes('bottom')){
                            $el.firstElementChild.classList.add('toast-bottom');
                            $el.firstElementChild.classList.add('opacity-0', 'translate-y-full');
                        } else {
                            $el.firstElementChild.classList.add('opacity-0', '-translate-y-full');
                        }
                        setTimeout(function(){
                            setTimeout(function(){
                                if(position.includes('bottom')){
                                    $el.firstElementChild.classList.remove('opacity-0', 'translate-y-full');
                                } else {
                                    $el.firstElementChild.classList.remove('opacity-0', '-translate-y-full');
                                }
                                $el.firstElementChild.classList.add('opacity-100', 'translate-y-0');

                                setTimeout(function(){
                                    stackToasts();
                                }, 10);
                            }, 5);
                        }, 50);
        
                        setTimeout(function(){
                            setTimeout(function(){
                                $el.firstElementChild.classList.remove('opacity-100');
                                $el.firstElementChild.classList.add('opacity-0');
                                if(toasts.length == 1){
                                    $el.firstElementChild.classList.remove('translate-y-0');
                                    $el.firstElementChild.classList.add('-translate-y-full');
                                }
                                setTimeout(function(){
                                    deleteToastWithId(toast.id)
                                }, 300);
                            }, 5);
                        }, 4500); 
                    "
                    @mouseover="toastHovered=true"
                    @mouseout="toastHovered=false"
                    class="absolute w-full duration-300 ease-out select-none sm:max-w-sm"
                    :class="{ 'toast-no-description': !toast.description }">
                    
                    <span
                        class="relative flex flex-col shadow-2xl shadow-black/60 w-full transition-all duration-300 ease-out bg-[#0d0f11]/95 backdrop-blur-xl border-y border-white/10 border-x-0 rounded-none sm:rounded-2xl sm:border sm:border-white/10 sm:max-w-sm group overflow-hidden"
                        :class="{ 'p-4' : !toast.html, 'p-0' : toast.html }">
                        
                        <!-- Top glowing dynamic brand line -->
                        <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r"
                            :class="{
                                'from-emerald-500 to-teal-400': toast.type === 'success',
                                'from-blue-500 to-indigo-400': toast.type === 'info',
                                'from-amber-500 to-orange-400': toast.type === 'warning',
                                'from-rose-500 to-red-500': toast.type === 'danger' || toast.type === 'error',
                                'from-pink-500 to-purple-500': toast.type === 'default'
                            }">
                        </div>

                        <template x-if="!toast.html">
                            <div class="relative flex items-start gap-3.5 py-1 pr-6">
                                <!-- Dynamic Brand/Type Icon Container -->
                                <div class="flex items-center justify-center h-10 w-10 rounded-xl border shrink-0 transition duration-300"
                                    :class="{
                                        'bg-emerald-500/10 border-emerald-500/20 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.15)]': toast.type === 'success',
                                        'bg-blue-500/10 border-blue-500/20 text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.15)]': toast.type === 'info',
                                        'bg-amber-500/10 border-amber-500/20 text-amber-400 shadow-[0_0_15px_rgba(245,158,11,0.15)]': toast.type === 'warning',
                                        'bg-rose-500/10 border-rose-500/20 text-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.15)]': toast.type === 'danger' || toast.type === 'error',
                                        'bg-pink-500/10 border-pink-500/20 text-pink-400 shadow-[0_0_15px_rgba(236,72,153,0.15)]': toast.type === 'default'
                                    }">

                                    <i class="text-xl" :class="{
                                        'ri-checkbox-circle-fill': toast.type === 'success' && !toast.message.toLowerCase().includes('cart'),
                                        'ri-shopping-cart-2-fill': toast.type === 'success' && toast.message.toLowerCase().includes('cart'),
                                        'ri-information-fill': toast.type === 'info',
                                        'ri-error-warning-fill': toast.type === 'warning',
                                        'ri-close-circle-fill': toast.type === 'danger' || toast.type === 'error',
                                        'ri-notification-3-fill': toast.type === 'default'
                                    }"></i>
                                </div>

                                <!-- Message & Description -->
                                <div class="flex-1 min-w-0 py-0.5">
                                    <h4 class="text-sm font-bold text-white tracking-wide leading-tight" x-text="toast.message"></h4>
                                    <p x-show="toast.description" class="mt-1 text-xs text-white/50 leading-relaxed font-medium" x-text="toast.description"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="toast.html">
                            <div x-html="toast.html" class="w-full"></div>
                        </template>

                        <!-- Close button -->
                        <span
                            @click="burnToast(toast.id)"
                            class="absolute right-2.5 top-2.5 p-1 text-white/30 duration-200 rounded-lg cursor-pointer hover:bg-white/5 hover:text-white/80">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </span>
                </li>
            </template>
        </ul>
    </template>
</div>
