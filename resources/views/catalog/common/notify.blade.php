<div class="ex_notifications"></div>
<script>
    let notifications = document.querySelector('.ex_notifications');
    function createToast(type, icon, title, text){
        let newToast = document.createElement('div');
        newToast.innerHTML = `
            <div class="ex_toast ${type}">
                <i class="${icon}"></i>
                <div class="ex_content">
                    <span>${text}</span>
                </div>
                <i class="fa-solid fa-xmark" onclick="(this.parentElement).remove()"></i>
            </div>`;
        notifications.appendChild(newToast);
        newToast.timeOut = setTimeout(()=>newToast.remove(), 5000)
    }

    function kbNotify(types, texts) {
        const options = {
            success: ['ex_success', 'fa-solid fa-circle-check', 'Success'],
            error: ['ex_error', 'fa-solid fa-circle-exclamation', 'Error'],
            danger: ['ex_error', 'fa-solid fa-circle-exclamation', 'Error'],
            warning: ['ex_warning', 'fa-solid fa-triangle-exclamation', 'Warning'],
            info: ['ex_info', 'fa-solid fa-circle-info', 'Info']
        };
        const [type, icon, title] = options[types] || [];
        createToast(type, icon, title, texts);
    }
</script>
@if (session()->has('error') || session()->has('info') || session()->has('success') || session()->has('warning') || session()->has('danger'))
    <script>
        $(document).ready(function() {
            @if (session()->has('info'))
            kbNotify('info', '{{ session()->get('info') }}');
            @endif
            @if (session()->has('success'))
            kbNotify('success', '{{ session()->get('success') }}');
            @endif
            @if (session()->has('warning'))
            kbNotify('warning', '{{ session()->get('warning') }}');
            @endif
            @if (session()->has('error'))
            kbNotify('error', '{{ session()->get('error') }}');
            @endif
            @if (session()->has('danger'))
            kbNotify('error', '{{ session()->get('danger') }}');
            @endif
        });
    </script>
@endif
