@extends('layout.app', ["current" => "produtos" ])

@section('body')


<div class="card border">
    
    <div class="card-body">
        <h5 class="card-title">Cadastro de Produtos</h5>

        <table class="table table-ordered table-hover" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Código</th>
                    <th>Abreviação</th>
                    <th>Status</th>
                    <th>Valor</th>
                    <th>Qtd</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
       
    </div>
    <div class="card-footer">
        
        <button class="btn btn-sm btn-primary" role="button" onClick="novoProduto()">Novo produto</button>
       
        
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="dlgProdutos">
    <div class="modal-dialog" role="document"> 
        <div class="modal-content">
            <form class="form-horizontal" id="formProduto">
                <div class="modal-header">
                    <h5 class="modal-title">Novo produto</h5>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="id" class="form-control">
                    <div class="form-group">
                        <label for="description" class="control-label">Descrição</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="description" maxlength="150" placeholder="Descrição">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="code" class="control-label">Código</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="code" maxlength="10" placeholder="Código">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description" class="control-label">Abreviação</label>
                        <div class="input-group">
                            <input type="text " class="form-control" id="short_description" maxlength="30" placeholder="Abreviação">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="value" class="control-label">Valor</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="value" placeholder="Valor">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="qty" class="control-label">Quantidade</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="qty" placeholder="Quantidade">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        <div class="input-group">
                            <select class="form-control" id="status" >
                                <option value="0">Selecione</option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>    
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
        $("#id").val(""), 
        $("#description").val(""), 
        $("#code").val(""), 
        $("#short_description").val(""), 
        $("#status").val(0), 
        $("#value").val(""), 
        $("#qty").val("") 
        $('#dlgProdutos').modal('show');
    }
    
   
    
    function montarLinha(p) {
        var linha = "<tr>" +
            "<td>" + p.id + "</td>" +
            "<td>" + p.description + "</td>" +
            "<td>" + p.code + "</td>" +
            "<td>" + p.short_description + "</td>" +
            "<td>" + p.status + "</td>" +
            "<td>" + p.value + "</td>" +
            "<td>" + p.qty + "</td>" +
            "<td>" +
              '<button class="btn btn-sm btn-primary" onclick="editar(' + p.id + ')"> Editar </button> ' +
              '<button class="btn btn-sm btn-danger" onclick="remover(' + p.id + ')"> Apagar </button> ' +
            "</td>" +
            "</tr>";
        return linha;
    }
    
    function editar(id) {
        $.getJSON('/api/produtos/'+id, function(data) { 
            console.log(data);
            $('#id').val(data.id);
            $('#description').val(data.description);
            $('#code').val(data.code);
            $('#short_description').val(data.short_description);
            $('#status').val(data.status);
            $('#value').val(data.value);
            $('#qty').val(data.qty);
            $('#dlgProdutos').modal('show');            
        });        
    }
    
    function remover(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/produtos/" + id,
            context: this,
            success: function() {
                console.log('Apagou OK');
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
        $.getJSON('/api/produtos', function(produtos) { 
            for(i=0;i<produtos.length;i++) {
                linha = montarLinha(produtos[i]);
                $('#tabelaProdutos>tbody').append(linha);
            }
        });        
    }
    
    
    function criarProduto() {
    //debugger;
        prod = { 
            id : $("#id").val(), 
            description: $("#description").val(), 
            code: $("#code").val(), 
            short_description: $("#short_description").val(), 
            status: $("#status").val(), 
            value: $("#value").val(), 
            qty: $("#qty").val()  
        };
        
        
        $.ajax({
            type: "POST",
            url: "/api/produtos",
            context: this,
            data: prod,
            success: function(data) {
                prod = JSON.parse(data);
                linha = montarLinha(prod);
                $('#tabelaProdutos>tbody').append(linha);    
            },
            error: function(error) {
                console.log(error);
            }
        });
        
    }
    
    function salvarProduto() {
             
        prod = { 
            id : $("#id").val(), 
            description: $("#description").val(), 
            code: $("#code").val(), 
            short_description: $("#short_description").val(), 
            status: $("#status").val(), 
            value: $("#value").val(), 
            qty: $("#qty").val() 
        };
        $.ajax({
            type: "PUT",
            url: "/api/produtos/" + prod.id,
            context: this,
            data: prod,
            
            success: function(data) {
                
                prod = JSON.parse(data);
                linhas = $("#tabelaProdutos>tbody>tr");
                e = linhas.filter( function(i, e) { 
                    return ( e.cells[0].textContent == prod.id );
                });
                if (e) {
                    e[0].cells[0].textContent = prod.id;
                    e[0].cells[1].textContent = prod.description;
                    e[0].cells[2].textContent = prod.code;
                    e[0].cells[3].textContent = prod.short_description;
                    e[0].cells[4].textContent = prod.status;
                    e[0].cells[5].textContent = prod.value;
                    e[0].cells[6].textContent = prod.qty;
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
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     