document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('notificacionesBtn');
    var dropdown = document.getElementById('notificacionesDropdown');

    if (btn && dropdown) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function () {
            dropdown.classList.add('hidden');
        });

        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }
});
