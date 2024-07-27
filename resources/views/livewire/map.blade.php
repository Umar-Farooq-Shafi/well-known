<div>
    <div
        wire:ignore id="map"
        style="width:500px;height:400px;">
    </div>

    @push('scripts')
        <script async
                src=`https://maps.googleapis.com/maps/api/js?key=@js(env('GOOGLE_MAPS_API_KEY'))&loading=async&callback=initMap`>
        </script>

        <script>
            let map;
            async function initMap() {
                const { Map } = await google.maps.importLibrary("maps");
                map = new Map(document.getElementById("map"), {
                    zoom: 4,
                    center: { lat: @js( $lat ), lng: @js( $lng ) },
                    mapId: "DEMO_MAP_ID",
                });
            }

            /* Initialize map when Livewire has loaded */
            document.addEventListener('livewire:load', function () {
                initMap();
            });
        </script>
    @endpush
</div>
