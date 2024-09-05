<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SituacaoConta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Conta;
use App\Http\Requests\ContaRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
class ContaController extends Controller
{
    //Listar as contas
    public function index(Request $request)
    {
        $contas = Conta::when($request->has('nome'),function($whenQuery) use ($request) {
            $whenQuery->where('nome','like','%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','>=',\Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d')); 
        
        })
        ->when($request->filled('data_fim'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','<=',\Carbon\Carbon::parse($request->data_fim)->format('Y-m-d')); 
        
        })
        ->with('situacaoConta')
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

       return view('contas.index',[
        'contas'=>$contas,
        'nome'=>$request->nome,
        'data_inicio'=>$request->data_inicio,
        'data_fim'=>$request->data_fim
        ]);
       // return view('contas.index');
    }
    //Detalhes da 
    public function create()
    {   
        $situacoesContas = SituacaoConta::orderBy('nome','asc')->get();
        return view('contas.create',['situacoesContas'=>$situacoesContas,]);
    }
    
    
    //Carregar o formulário cadastrar nova conta
    public function store(ContaRequest $request)
    {
        $request->validated();

        $conta = Conta::create(
            [
                'nome'=>$request->nome,
                'valor'=>str_replace(',','.',str_replace('.','',$request->valor)),
                'vencimento'=>$request->vencimento,
                'situacao_conta_id'=>$request->situacao_conta_id
            ]
        );
        //dd($conta);
        return redirect()->route('conta.show',['conta'=> $conta->id])->with('success','conta cadastrada com sucesso');
    }

    //Carregar no banco de dados nova 
    public function show(Conta $conta)
    {
        
        return view('contas.show',['conta'=>$conta]);
    }


    //Carregar o formulário editar a conta
    public function edit(Conta $conta)
    {   
        $situacoesContas = SituacaoConta::orderBy('nome','asc')->get();

        return view('contas.edit',['conta'=>$conta,'situacoesContas'=>$situacoesContas]);
    }


    // Editar no banco de dados a conta
    public function update(ContaRequest $request, Conta $conta)
    {
        try{

            $request->validated();
            $conta->update([
                'nome'=>$request->nome,
                'valor'=>str_replace(',','.',str_replace('.','',$request->valor)),
                'vencimento'=>$request->vencimento,
                'situacao_conta_id'=>$request->situacao_conta_id
            ]);

            Log::info('Conta editada com sucesso',['id'=>$conta->id]);
            return redirect()->route('conta.show',['conta'=> $conta->id])->with('success','conta editada com sucesso'); 
            

        }catch(Exception $e){
            Log::warning('Conta não editada',['id'=>$conta->id,'error'=> $e->getMessage()]);
            return back()->withInput()->with('error','Erro ao editar a conta');
        }
      
    }


    //Excluir a conta do banco de dados
    public function destroy(Conta $conta)
    {
        $conta->delete();
        return redirect()->route('conta.index')->with('success','conta apagada com sucesso');
    }

    public function gerarPdf(Request $request){
        $contas = Conta::when($request->has('nome'),function($whenQuery) use ($request) {
            $whenQuery->where('nome','like','%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','>=',\Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d')); 
        
        })
        ->when($request->filled('data_fim'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','<=',\Carbon\Carbon::parse($request->data_fim)->format('Y-m-d')); 
        
        })
        ->orderByDesc('created_at')
        ->get();

        $total = $contas->sum('valor');
        $pdf = PDF::loadView('contas.gerar-pdf',[
            'contas'=>$contas,
            'totalValor'=>$total
            ])
            ->setPaper('a4','portrait');
        return $pdf->download( 'listar-contas.pdf');
    }

    public function changeSituation(Conta $conta){
        
        try{

            $conta->update([
                'situacao_conta_id'=>$conta->situacao_conta_id == 1 ? 2 : 1
            ]);
            Log::info('Situaçao da conta editada com sucesso',['id'=>$conta->id]);
            return back()->withInput()->with('success','Situação da conta editada com sucesso');

        }catch(Exception $e){
            Log::warning('Situaçao da conta não editada',['id'=>$conta->id,'error'=> $e->getMessage()]);
            return back()->withInput()->with('error','Erro ao editar a situação da conta');
        }
    }

    public function gerarCsv(Request $request){
        $contas = Conta::when($request->has('nome'),function($whenQuery) use ($request) {
            $whenQuery->where('nome','like','%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','>=',\Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d')); 
        
        })
        ->when($request->filled('data_fim'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','<=',\Carbon\Carbon::parse($request->data_fim)->format('Y-m-d')); 
        
        })
        ->with('SituacaoConta')
        ->orderBy('vencimento')
        ->get();

        $total = $contas->sum('valor');
        // cria o arquvo temporário
        $csvNomeArquivo = tempnam(sys_get_temp_dir(),'csv' . Str::ulid());
        // Abre o arquivo na forma de leitura
        $arquivoAberto = fopen($csvNomeArquivo,'w');

        
        // CRia o cabeçalho do excel - Usar a função mb_convert_encoding para  converter caracteres especiais
        $cabecalho = ['id','Nome','Vencimento', mb_convert_encoding
        ('Situação','ISO-8859-1','UTF-8'),'Valor'];
        
        //Escrever o cabeçaho no arquivo
        fputcsv($arquivoAberto,$cabecalho,';');

        //Ler os registros recuperados do banco de dados

        foreach($contas as $conta){
            $contaArray =[
                'id'=>$conta->id,
                'nome'=>mb_convert_encoding($conta->nome,'ISO-8859-1','UTF-8'),
                'situacao'=>mb_convert_encoding($conta->situacaoConta->nome,'ISO-8859-1','UTF-8'),
                'vencimento'=>$conta->vencimento,
                'valor'=>number_format($conta->valor,2,',','.')

            ];
            fputcsv($arquivoAberto,$contaArray,';');
        }

        $rodape=['','','','',number_format($total,2,',','.')];
        fputcsv($arquivoAberto,$rodape,';');
        fclose($arquivoAberto);
        return response()->download($csvNomeArquivo,'relatorio_contas_celke' . '.csv');

    }

    public function gerarWord(Request $request){
        $contas = Conta::when($request->has('nome'),function($whenQuery) use ($request) {
            $whenQuery->where('nome','like','%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','>=',\Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d')); 
        
        })
        ->when($request->filled('data_fim'),function($whenQuery) use ($request) {
            $whenQuery->where('vencimento','<=',\Carbon\Carbon::parse($request->data_fim)->format('Y-m-d')); 
        
        })
        ->with('SituacaoConta')
        ->orderBy('vencimento')
        ->get();

        $total = $contas->sum('valor');

        //Cria uma instancia do PhpWord
         $phpWord = new PhpWord();

        //Adicionar conteúdo ao documento
        $section = $phpWord->addSection();

        //Adicionar uma tabela
        $table = $section->addTable();

        //Definir as configurações de borda

        $borderStyle =[
            'borderColor' => '000000',
            'borderSize' => 6,
        ];

        //Adicionar o cabeçalho da tabela
        $table->addRow();
        $table->addCell(2000,$borderStyle)->addText("id");
        $table->addCell(2000,$borderStyle)->addText("Nome");
        $table->addCell(2000,$borderStyle)->addText("Vencimento");
        $table->addCell(2000,$borderStyle)->addText("Situação");
        $table->addCell(2000,$borderStyle)->addText("Valor");
        
        foreach ($contas as $conta){
            //Adicionar a linha da tabela
            $table->addRow();
            $table->addCell(2000,$borderStyle)->addText($conta->id);
            $table->addCell(2000,$borderStyle)->addText($conta->nome);
            $table->addCell(0,$borderStyle)->addText(\Carbon\Carbon::parse($conta->vencimento)->format('d/m/Y'));
            $table->addCell(2000,$borderStyle)->addText($conta->situacaoConta->nome);
            $table->addCell(2000,$borderStyle)->addText(number_format($conta->valor,2,',','.'));
        }

        $table->addRow();
        $table->addCell(2000)->addText();
        $table->addCell(2000)->addText();
        $table->addCell(2000)->addText();
        $table->addCell(2000)->addText();
        $table->addCell(2000,$borderStyle)->addText(number_format($total,2,',','.'));

        //criar o nome do arquivo
        $filename ='realtorio_contas_celke.docx';

        //obter o caminho completo onde será salvo o arquivo
        $savePath = storage_path($filename);

        //Salvar o arquivo
        $phpWord->save($savePath);

        //Forçar o download do arquivo no caminho indicado
        return response()->download($savePath)->deleteFileAfterSend(true);
    }
}
