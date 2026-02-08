@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                background: '#f0fdf4', // Green-50
                color: '#166534'      // Green-800
            })
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}",
                background: '#fef2f2', // Red-50
                color: '#991b1b'      // Red-800
            })
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            })

            Toast.fire({
                icon: 'warning',
                title: "Plusieurs erreurs détectées",
                html: '<ul style="text-align: left; margin-left: 10px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                background: '#fffbeb', // Amber-50
                color: '#92400e'      // Amber-800
            })
        });
    </script>
@endif