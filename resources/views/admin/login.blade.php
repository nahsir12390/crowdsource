<x-layouts.app title="Admin Login - CivicTrack">
    <section class="mx-auto flex min-h-[70vh] max-w-md items-center px-4 py-10 sm:px-6 lg:px-8">
        <form action="{{ route('admin.authenticate') }}" method="POST" class="w-full rounded-lg border border-[#d9ded4] bg-white p-6 shadow-sm">
            @csrf
            <p class="text-sm font-semibold text-[#0f6b5b]">Admin access</p>
            <h1 class="mt-1 text-2xl font-bold">Review reports</h1>
            <p class="mt-2 text-sm text-[#647067]">Use the project admin password to manage submitted infrastructure reports.</p>
            <div class="mt-6">
                <label class="text-sm font-semibold">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2" required autofocus>
                <x-field-error name="password" />
            </div>
            <button class="mt-6 w-full rounded-md bg-[#0f6b5b] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#0b584b]">Enter admin</button>
        </form>
    </section>
</x-layouts.app>
