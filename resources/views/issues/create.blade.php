<x-layouts.app title="Submit Report - CivicTrack">
    <section class="bg-[#123d36]">
        <div class="mx-auto max-w-5xl px-4 py-10 text-white sm:px-6 lg:px-8">
            <p class="text-sm font-semibold text-[#9bd4c8]">New infrastructure report</p>
            <h1 class="mt-2 text-3xl font-bold sm:text-4xl">Submit an issue</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#d9eee9]">
                Tell us what happened, where it is, and add a photo if you have one. The location can be a street, landmark, hostel, classroom block, or any place people can recognize.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('issues.store') }}" method="POST" enctype="multipart/form-data" class="overflow-hidden rounded-lg border border-[#d9ded4] bg-white shadow-sm">
            @csrf
            <div class="grid gap-0 lg:grid-cols-[1fr_320px]">
                <div class="space-y-8 p-5 sm:p-6">
                    <div>
                        <h2 class="text-lg font-bold">Issue details</h2>
                        <p class="mt-1 text-sm text-[#647067]">Start with the problem and the place people can find it.</p>
                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold">Issue title</label>
                                <input name="title" value="{{ old('title') }}" placeholder="Example: Broken drainage cover near main gate" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]" required>
                                <x-field-error name="title" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Category</label>
                                <select name="category" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]" required>
                                    <option value="">Choose category</option>
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-field-error name="category" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Photo evidence</label>
                                <input type="file" name="photo" accept="image/*" class="mt-1 w-full rounded-md border border-dashed border-[#aeb9af] bg-[#f9faf7] px-3 py-2.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#0f6b5b] file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white">
                                <x-field-error name="photo" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold">Description</label>
                                <textarea name="description" rows="6" placeholder="Explain what is wrong, how serious it is, and anything that can help the team verify it." class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]" required>{{ old('description') }}</textarea>
                                <x-field-error name="description" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-[#edf0e9] pt-8">
                        <h2 class="text-lg font-bold">Location</h2>
                        <p class="mt-1 text-sm text-[#647067]">No coordinates needed. A simple, clear description is enough.</p>
                        <div class="mt-5">
                            <label class="text-sm font-semibold">Where is the issue?</label>
                            <input name="location" value="{{ old('location') }}" placeholder="Example: Beside science block, opposite the cafeteria" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]" required>
                            <x-field-error name="location" />
                        </div>

                        <input id="latitude" type="hidden" name="latitude" value="{{ old('latitude') }}">
                        <input id="longitude" type="hidden" name="longitude" value="{{ old('longitude') }}">

                        <div class="mt-4 rounded-lg border border-[#d9ded4] bg-[#f9faf7] p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold">Add phone location automatically</p>
                                    <p id="locationStatus" class="mt-1 text-xs text-[#647067]">Optional, but helpful when the issue is hard to describe.</p>
                                </div>
                                <button type="button" id="useLocationButton" class="rounded-md border border-[#0f6b5b] px-3 py-2 text-sm font-semibold text-[#0f6b5b] hover:bg-[#e9f8f4]">
                                    Use my current location
                                </button>
                            </div>
                        </div>
                        <x-field-error name="latitude" />
                        <x-field-error name="longitude" />
                    </div>

                    <div class="border-t border-[#edf0e9] pt-8">
                        <h2 class="text-lg font-bold">Contact details</h2>
                        <p class="mt-1 text-sm text-[#647067]">These details help admins follow up if they need more information.</p>
                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold">Your name</label>
                                <input name="reporter_name" value="{{ old('reporter_name') }}" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]" required>
                                <x-field-error name="reporter_name" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Phone number</label>
                                <input name="reporter_phone" value="{{ old('reporter_phone') }}" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]">
                                <x-field-error name="reporter_phone" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold">Email</label>
                                <input type="email" name="reporter_email" value="{{ old('reporter_email') }}" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2.5 outline-none focus:border-[#0f6b5b] focus:ring-2 focus:ring-[#b9ddc7]">
                                <x-field-error name="reporter_email" />
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="border-t border-[#d9ded4] bg-[#f9faf7] p-5 sm:p-6 lg:border-l lg:border-t-0">
                    <h2 class="text-lg font-bold">Good reports include</h2>
                    <div class="mt-5 space-y-4 text-sm text-[#526057]">
                        <div class="rounded-lg border border-[#d9ded4] bg-white p-4">
                            <p class="font-semibold text-[#1f2522]">Clear location</p>
                            <p class="mt-1">Use landmarks people already know.</p>
                        </div>
                        <div class="rounded-lg border border-[#d9ded4] bg-white p-4">
                            <p class="font-semibold text-[#1f2522]">Useful photo</p>
                            <p class="mt-1">Capture the issue and nearby surroundings.</p>
                        </div>
                        <div class="rounded-lg border border-[#d9ded4] bg-white p-4">
                            <p class="font-semibold text-[#1f2522]">Simple explanation</p>
                            <p class="mt-1">Say what happened and who it affects.</p>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#d9ded4] bg-[#fbfcf9] p-5 sm:flex-row sm:justify-end sm:p-6">
                <a href="{{ route('issues.index') }}" class="rounded-md border border-[#cbd4ca] px-4 py-2.5 text-center text-sm font-semibold">Cancel</a>
                <button class="rounded-md bg-[#0f6b5b] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#0b584b]">Submit report</button>
            </div>
        </form>
    </section>

    <script>
        const button = document.getElementById('useLocationButton');
        const statusText = document.getElementById('locationStatus');
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');

        button?.addEventListener('click', () => {
            if (!navigator.geolocation) {
                statusText.textContent = 'Location is not available on this device. You can still type the location above.';
                return;
            }

            button.disabled = true;
            button.textContent = 'Getting location...';
            statusText.textContent = 'Please allow location access when your browser asks.';

            navigator.geolocation.getCurrentPosition((position) => {
                latitude.value = position.coords.latitude.toFixed(7);
                longitude.value = position.coords.longitude.toFixed(7);
                statusText.textContent = 'Location added. You can now submit the report.';
                button.textContent = 'Location added';
            }, () => {
                statusText.textContent = 'Could not get your location. Please type a clear location above.';
                button.disabled = false;
                button.textContent = 'Use my current location';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });
        });
    </script>
</x-layouts.app>
