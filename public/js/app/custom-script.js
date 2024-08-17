
document.addEventListener('livewire:init', function () {
    Livewire.on('toggleFullscreen', (el, component) => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    });
});
