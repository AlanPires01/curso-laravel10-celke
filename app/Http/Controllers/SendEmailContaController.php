<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conta;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\SendMailContaPagar;
use Illuminate\Support\Facades\Mail;
class SendEmailContaController extends Controller
{
    //Listar as contas
    public function SendEmailPendenteConta()
    {
        try{

            //Obter a data atual
            $dataAtual = Carbon::now()->toDateString();

            //Recuperar as contas do banco de dados
            $contas = Conta::whereDate("Vencimento",$dataAtual)
            ->with('situacaoConta')
            ->get();
            
            //Enviar os dados para enviar e-mail
            Mail::to('alanpires@alu.ufc.br')->send(new SendMailContaPagar($contas));
            //Redirecionar de volta à página anterior
            return back()->with('success','E-mail enviado com sucesso');

        }catch(Exception $e){
            Log::warning('E-mail não enviado.',['error'=>$e ->getMessage()]);
            return back()->with('error','E-mail não enviado!');
        }
    }
}
