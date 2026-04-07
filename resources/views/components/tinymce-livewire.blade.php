<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tinymceLivewire', (config = {}) => ({
            model: config.model ?? null,
            height: config.height ?? 320,
            editor: null,

            async load() {
                if (window.tinymce) return;

                await new Promise((resolve) => {
                    const existing = document.querySelector('script[data-tinymce-cdn=\"1\"]');
                    if (existing) {
                        if (window.tinymce) return resolve();
                        existing.addEventListener('load', () => resolve(), { once: true });
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = 'https://cdn.tiny.cloud/1/lvah2wnjn9wdw7qz53fo0891g4b9srn7vjdcez1gzntc2ino/tinymce/6/tinymce.min.js';
                    script.referrerPolicy = 'origin';
                    script.dataset.tinymceCdn = '1';
                    script.addEventListener('load', () => resolve(), { once: true });
                    document.head.appendChild(script);
                });
            },

            async init() {
                if (!this.model) return;
                await this.load();

                const textarea = this.$refs.textarea;
                if (!textarea) return;

                if (!textarea.id) {
                    textarea.id = 'tinymce-' + Math.random().toString(36).slice(2);
                }

                if (textarea.dataset.tinymceInitialized === '1') return;

                const existing = window.tinymce?.get(textarea.id);
                if (existing) {
                    this.editor = existing;
                    textarea.dataset.tinymceInitialized = '1';
                    return;
                }

                const initialValue = textarea.value || '';

                window.tinymce.init({
                    target: textarea,
                    height: this.height,
                    menubar: false,
                    plugins: 'lists link image paste help wordcount',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
                    setup: (editor) => {
                        this.editor = editor;

                        editor.on('init', () => {
                            editor.setContent(initialValue);
                        });

                        editor.on('Change KeyUp', () => {
                            this.$wire.set(this.model, editor.getContent());
                        });
                    },
                });

                textarea.dataset.tinymceInitialized = '1';
            },

            destroy() {
                if (!this.editor) return;
                try {
                    this.editor.destroy();
                } catch (e) {
                    // ignore
                }
                this.editor = null;
            },
        }));
    });
</script>
