@if(session()->has('success'))
    <script>
        document.addEventListener('DOMContentLoaded',()=>{
            Swal.fire({
                title: 'Pronto!',
                text: '{{(session('success'))}}',
                icon: 'success',
            })
        })
    </script>
@endif

@if(session()->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded',()=>{
            Swal.fire({
                title: 'Erro!',
                text: '{{(session('error'))}}',
                icon: 'error',
            })
        })
    </script>
@endif

@if ($errors->any())
    @php
        $mensagem='';   
        foreach($errors->all() as $error){
            $mensagem .=$error . '<br>';
        }
        
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Error!',
                html: `{!! $mensagem !!}`, // Alterado para 'html'
                icon: 'error'
            });
        });
    </script>

@endif
