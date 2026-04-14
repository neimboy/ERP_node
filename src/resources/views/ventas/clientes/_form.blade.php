<div>
    <div class="mb-4">
        <x-input-label for="Documento" value="Documento" />
        <x-text-input id="Documento" name="Documento" value="{{ old('Documento', $cliente->Documento ?? '') }}" class="mt-1 block w-full" />
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
        <x-text-input id="Telefono" name="Telefono" value="{{ old('Telefono', $cliente->Telefono ?? '') }}" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('Telefono')" class="mt-2" />
    </div>
</div>
