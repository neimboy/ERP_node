<div>
    <div class="mb-4">
        <x-input-label for="Documento" value="Documento" />
        <x-text-input id="Documento" name="Documento" value="{{ old('Documento', $cliente->Documento ?? '') }}" class="mt-1 block w-full" maxlength="8" />
        <x-input-error :messages="$errors->get('Documento')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="Nombre" value="Nombre" />
        <x-text-input id="Nombre" name="Nombre" value="{{ old('Nombre', $cliente->Nombre ?? '') }}" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('Nombre')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="Correo" value="Correo" />
        <x-text-input id="Correo" name="Correo" value="{{ old('Correo', $cliente->Correo ?? '') }}" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('Correo')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="Telefono" value="Teléfono" />
        <x-text-input id="Telefono" name="Telefono" value="{{ old('Telefono', $cliente->Telefono ?? '') }}" class="mt-1 block w-full" maxlength="9" />
        <x-input-error :messages="$errors->get('Telefono')" class="mt-2" />
    </div>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form[data-cliente-form]');
            forms.forEach(form => {
                const doc = form.querySelector('[name="Documento"]');
                const tel = form.querySelector('[name="Telefono"]');

                function attachCounter(input, max) {
                    if (!input) return;
                    input.setAttribute('maxlength', max);

                    // counter element
                    let counter = input.parentNode.querySelector('.char-counter');
                    if (!counter) {
                        counter = document.createElement('div');
                        counter.className = 'text-sm text-gray-500 mt-1 char-counter';
                        input.parentNode.appendChild(counter);
                    }

                    const onInput = () => {
                        let v = input.value || '';
                        if (v.length > max) {
                            input.value = v.slice(0, max);
                            v = input.value;
                        }
                        const remaining = max - v.length;
                        counter.textContent = remaining + ' caracteres restantes';
                        counter.style.color = remaining === 0 ? '#dc2626' : '#6b7280';
                    };

                    input.addEventListener('input', onInput);
                    // initialize
                    onInput();
                }

                attachCounter(doc, 8);
                attachCounter(tel, 9);

                form.addEventListener('submit', function (ev) {
                    if (doc && doc.value && doc.value.length > 8) {
                        ev.preventDefault();
                        alert('El campo Documento no puede tener más de 8 caracteres.');
                        doc.focus();
                        return false;
                    }
                    if (tel && tel.value && tel.value.length > 9) {
                        ev.preventDefault();
                        alert('El campo Teléfono no puede tener más de 9 caracteres.');
                        tel.focus();
                        return false;
                    }
                });
            });
        });
    </script>
    @endpush
@endonce
</div>
