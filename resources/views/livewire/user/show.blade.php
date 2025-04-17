<div>
    <!-- Értesítés komponens -->
    <div x-data="{ 
        show: false,
        type: '',
        message: '',
        init() {
            Livewire.on('notify', ({ type, message }) => {
                this.show = true;
                this.type = type;
                this.message = message;
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            });
        }
    }">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             :class="{
                'bg-green-500/10 text-green-400': type === 'success',
                'bg-red-500/10 text-red-400': type === 'error'
             }"
             class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50">
            <p x-text="message"></p>
        </div>
    </div>

    {{ $slot }}
</div>
