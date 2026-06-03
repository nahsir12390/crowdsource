@props(['name'])

@error($name)
    <p class="mt-1 text-sm text-[#a33a32]">{{ $message }}</p>
@enderror
