@extends('layouts.admin')

@section('content')

    <div class="card mt-3 mb-4 border-light shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Pesquisar</span>
        </div>

        <div class="card-body">
            <form action="{{route('conta.index')}}">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="nome" >Nome</label>
                        <input type="text"  name="nome" id="nome" class="form-control" value="{{$nome}}" placeholder="Nome da conta"/>                            
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="data_inicio">Data Ínicio</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{$data_inicio}}"/>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="data_fim">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{$data_fim}}"/>
                    </div>

                    <div class="col-md-3 dol-sm-12 mt-3 pt-4 ">
                        <button type="submit" class="btn btn-info btn-sm">Pesquisar</button>
                        <a href="{{route('conta.index')}}" class="btn btn-warning btn-sm">Limpar</a>
                    </div>
                </div>

            </form>


        </div>
    </div>
    <div class="card mt-4 mb-4 border-light shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Listar as contas</span>
            <span>
                <a href="{{ route('conta.create')}}" class="btn btn-success btn-sm">
                    Cadastrar
                </a>

                <a href="{{ route('conta.send-email-pendente')}}" class="btn btn-info btn-sm btnSendEmail">

                    Enviar E-mail
                </a>
                {{-- <a href="{{ route('conta.gerar-pdf')}}" class="btn btn-warning btn-sm">
                    Gerar PDF
                </a> --}}

                <a href="{{url('gerar-pdf-conta?'.request()->getQueryString())}}" class="btn btn-warning btn-sm">
                    Gerar PDF
                </a>


                <a href="{{url('gerar-csv-conta?'.request()->getQueryString())}}" class="btn btn-success btn-sm">
                    Gerar Excel
                </a>


                <a href="{{url('gerar-word-conta?'.request()->getQueryString())}}" class="btn btn-primary btn-sm">
                    Gerar Word
                </a>
            </span>

        </div>
        
        <div class="card-body">
            <x-alert/>

    
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Vencimento</th>
                        <th>Situação</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                @forelse($contas as $conta)
                    <tbody>
                        <tr>
                            <td> {{$conta->id}}</td>
                            <td>{{$conta->nome}}</td>
                            <td>{{'R$'. number_format($conta->valor,2,',','.')}}</td>
                            <td>{{\Carbon\Carbon::parse($conta->vencimento)->tz('America/Sao_Paulo')->format('d/m/Y')}}</td>
                            <td>
                                <a href="{{route('conta.change-situation',['conta'=>$conta->id])}}">
                                    {!!'<span class="badge text-bg-'.$conta->situacaoConta->cor .'">' . $conta->situacaoConta->nome . '</span>' !!}
                                </a>
                                </td>
                            <td class="d-md-flex justify-content-center">
                                <a href="{{ route('conta.show',['conta'=>$conta->id])}}" class="btn btn-primary btn-sm me-1">
                                    Visualizar
                                </a>
                                
                                <a href="{{ route('conta.edit',['conta'=>$conta->id])}}" class="btn btn-warning btn-sm me-1">
                                    Editar
                                </a>
                                
                                <form id="formExcluir{{$conta->id}}" method="POST" action="{{route('conta.destroy',['conta'=>$conta->id])}}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm me-1 btn-delete" type="submit" data-delete-id={{$conta->id}}>Apagar</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                @empty
                    <span  style="color:#f00;">Nenhuma conta cadastrada </span>
                @endforelse
            </table>
            
            {{$contas->links()}}
        </div>
       
    </div>
        
    

@endsection
   