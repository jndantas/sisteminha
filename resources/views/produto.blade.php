@extends('layouts.app', ["current" => "produtos"])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Cadastro de Produtos</div>
        <table class="table table-ordered table-hover" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <button class="btn btn-sn btn-primary" role="button" onClick="novoProduto()">Novo Produto</a>
    </div>
    </div>
        <div class="modal" tabindex="-1" role="dialog" id="dlgProdutos" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content ">
            <form class="form-horizontal" id="formProduto">
            <div class="modal-header">
                <h5 class="modal-title">Novo Produto</h5>
            </div>
            <div class="modal-body">

            <input type="hidden" id="id" class="form-control">
            <div class="form-group">
                <label for="nomeProduto" class="control-label">Nome do Produto</label>
                <div class="input-group">
                 <input type="text" class="form-control" id="nomeProduto" placeholder="Nome do Produto">
                </div>
            </div>

            <div class="form-group">
                <label for="descricaoProduto" class="control-label">Descrição do Produto</label>
                <div class="input-group">
                 <input type="text" class="form-control" id="descricaoProduto" placeholder="Descrição do Produto">
                </div>
            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>
        </div>
@endsection

@section('javascript')
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }

    });

    function novoProduto() {
        $('#id').val('');
        $('#nomeProduto').val('');
        $('#descricaoProduto').val('');
        $('#dlgProdutos').modal('show');
    }


    function montarLinha(p) {
        var linha = "<tr>" +
            "<td>" + p.id + "</td>" +
            "<td>" + p.nome + "</td>" +
            "<td>" + p.descricao + "</td>" +
            "<td>" +
             '<button class="btn btn-sm btn-primary" onclick="editar(' + p.id+ ')"> Editar </button> ' +
             '<button class="btn btn-sm btn-danger" onclick="remover(' +p.id+ ')"> Apagar </button> ' +
            "</td>" +
            "</tr>";
        return linha;
    }

    function editar(id) {
         $.getJSON('/api/produtos/'+id, function(data){
            console.log(data);
        $('#id').val(data.id);
        $('#nomeProduto').val(data.nome);
        $('#descricaoProduto').val(data.descricao);
        $('#dlgProdutos').modal('show');
    });
    }

    function remover(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/produtos/" + id,
            context: this,
            success: function() {
                console.log('Apagou Ok');
                linhas = $("#tabelaProdutos>tbody>tr");
                e = linhas.filter( function(i, elemento) {
                   return elemento.cells[0].textContent == id;
                });
                if (e)
                    e.remove();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function carregarProdutos() {
        $.getJSON('/api/produtos', function(produtos){

           for(i=0;i<produtos.length;i++) {
               linha = montarLinha(produtos[i]);
               $('#tabelaProdutos>tbody').append(linha);
           }

        });
    }

    function criarProduto(){
        prod = {
            nome: $("#nomeProduto").val(),
            descricao: $("#descricaoProduto").val(),

        };
        $.post("/api/produtos", prod, function(data) {
            produto = JSON.parse(data);
            linha = montarLinha(produto);
            $('#tabelaProdutos>tbody').append(linha);

        });
    }

    function salvarProduto() {
    prod = {
            id: $("#id").val(),
            nome: $("#nomeProduto").val(),
            descricao: $("#descricaoProduto").val(),
        }
                $.ajax({
            type: "PUT",
            url: "/api/produtos/" + prod.id,
            context: this,
            data: prod,
            success: function(data) {
                linhas = $("#tabelaProdutos>tbody>tr");
                e = linhas.filter( function (i, e) {
                   return( e.cells[0].textContent == prod.id );
                    });
                if (e) {
                    e[0].cells[0].textContent = prod.id;
                    e[0].cells[1].textContent = prod.nome;
                    e[0].cells[2].textContent = prod.descricao;
                }

            },
            error: function(error) {
                console.log(error);
            }
                });
    }


    $("#formProduto").submit( function(event){
        event.preventDefault();
        if ($("#id").val() != '')
             salvarProduto();
        else
            criarProduto();

        $("#dlgProdutos").modal('hide');
    });


    $(function(){
        carregarProdutos();
    });
</script>
@endsection
