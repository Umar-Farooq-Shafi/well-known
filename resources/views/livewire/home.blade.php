@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <livewire:side-panel/>

    <main class="mx-16">
        <section class="flex items-center gap-x-24 py-6 justify-center w-full">
            @foreach($audiences as $audience)
                <div class="flex items-center flex-col">
                    <div class="border rounded-full border-gray-300 bg-white p-6">
                        <img class="w-12 h-12 rounded" src="{{ Storage::url('audiences/' . $audience->image_name) }}"
                             alt="icons"/>
                    </div>

                    <p>{{ $audience->audienceTranslations->first()?->name }}</p>
                </div>
            @endforeach
        </section>

        <section class="flex gap-x-2 items-center">
            <h1 class="text-xl">Brows events in</h1>

            <x-heroicon-o-chevron-down class="w-5 h-5 font-bold" />

            <h1 class="text-lg font-semibold text-blue-400 underline">Indian Wells</h1>
        </section>
    </main>
</div>
