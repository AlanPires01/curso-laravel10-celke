//Receber o seletor do campo valor

let inputValor = document.getElementById('valor');

if(inputValor){
    inputValor.addEventListener('input',function(){
        let valueValor = this.value.replace(/[^\d]/g,'');
    
        //Adicionar os separadores de milhares
    
        var formattedValor = (valueValor.slice(0,-2).replace(/\B(?=(\d{3})+(?!\d))/g,'.'))+''+valueValor.slice(-2);
    
        //Adicionar a vírgula e até dois dígitos se houver centavos
        formattedValor = formattedValor.slice(0,-2)+','+formattedValor.slice(-2);
    
    
        // Atualizar o valor do campo
        this.value =formattedValor;
    
    
    });
    
}


function confirmExclusao(event, contaId){
    event.preventDefault();
    Swal.fire({
        title:"Tem certeza?",
        text:"Você não poderá reverter isso!",
        icon:'info',
        showCancelButton: true,
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Cancelar",
        confirmButtonColor:"#dc3545",
        cancelButtonColor:"#0d6efd"

    }).then(result=>{
        if(result.isConfirmed){
            document.getElementById(`formExcluir${contaId}`).submit();
        } 
    })
}

document.querySelectorAll('.btn-delete').forEach(function (button){
    button.addEventListener('click', function(event){
        event.preventDefault();
        var deleteId = this.getAttribute('data-delete-id');
        Swal.fire({
            title:"Tem certeza?",
            text:"Você não poderá reverter isso!",
            icon:'info',
            showCancelButton: true,
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            confirmButtonColor:"#dc3545",
            cancelButtonColor:"#0d6efd"
    
        }).then(result=>{
            if(result.isConfirmed){
                document.getElementById(`formExcluir${deleteId}`).submit();
            } 
        })
    })
})
//lucastandy
//REceber o seletor btnSendEmail percorrer a lista de botões
document.querySelectorAll('.btnSendEmail').forEach(function (button){
    button.addEventListener('click', function(event){
       //Adicionar a classe disabled ao botão
       button.classList.add('disabled');

       //Enviar o spinner para o botão
       button.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span role="status">Enviando...</span>';
    })
})
//quando carregar a página execute o select2

$(function(){
    $('.select2').select2({
        theme:'bootstrap-5'
    })
})