(() => {
    let selector_campo_nota_avaliacao = '#tabela-lista-alunos tbody .nota-avaliacao';
    $(document).ready(function(){
        //loadAlunos();
        carregarNotas();

        $('#btn-salvar').click(function(){
            salvarNotas();
        });
    });

    function loadAlunos(){
        $.ajax({
            url:session.urlmiles,
            data:{
                op:'lista-alunos',
                controller:'escola/notas',
                avaliacao:$('#avaliacao').val()
            },
            dataType:"json",
            complete:function(res){
                addAluno(res.responseJSON);
            },
            beforeSend:function(){

            }
        });
    }

    function addAluno(alunos){
        let numero = 0;
        alunos.forEach(function(aluno){
            const tr = $('<tr>');
            const td_numero = $('<td class="td-numero text-center">');
            const td_matricula = $('<td class="td-matricula text-center">');
            const td_estudante = $('<td class="td-estudante">');
            const td_nota = $('<td class="td-nota text-center">');
            const input_nota = $('<input type="text" class="form-control text-center nota-avaliacao formato-numerodecimal" />')

            td_numero.html(++numero);
            td_matricula.html(aluno.matricula);
            td_estudante.html(aluno.nome);

            input_nota.attr("data-id",aluno.id);
            input_nota.val("");
            td_nota.append(input_nota);

            tr.append(td_numero);
            tr.append(td_matricula);
            tr.append(td_estudante);        
            tr.append(td_nota);

            $('#tabela-lista-alunos tbody').append(tr);
        });

        setFormatoNumeroDecimal(1);
        $(selector_campo_nota_avaliacao).on("keyup change", function() {
            const valor = parseFloat($(this).val().replace(",", "."));
            if (valor <= 0){
                statusFormControl($(this),'default');
            }else{
                statusFormControl($(this),valor > 10.00 ? 'error' : 'success');
            }
                
            //if (valorNumerico > 10.00) {
                //$(this).val("10,00");
                //$(this).addClass('has-error');
                
            //}
    });    
    }

    function salvarNotas(){
        let notas_avaliacao = [];
        $(selector_campo_nota_avaliacao).each(function(){
            notas_avaliacao.push({
            estudante: $(this).data('id'),
            nota:$(this).val()
            });        
        });

        $.ajax({
            url:session.urlmiles,
            data:{
                op:'salvar',
                controller:'escola/notas',
                avaliacao:$('#avaliacao').val(),
                notas:notas_avaliacao
            },
            dataType:"json",
            complete:function(res){
                console.log(res.responseJSON);
            },
            beforeSend:function(){

            }
        });
    }

    function carregarNotas(){
        $.ajax({
            url:session.urlmiles,
            data:{
                op:'carregar-notas',
                controller:'escola/notas',
                avaliacao:$('#avaliacao').val()
            },
            dataType:"json",
            complete:function(res){
                const rs = res.responseJSON;                
                addAluno(rs.alunos);
                addNotas(rs.notas);
            },
            beforeSend:function(){

            }
        });
    }

    function addNotas(notas){
        notas.forEach(function(nota){
            let campo_nota = $(`.nota-avaliacao[data-id=${nota.aluno}]`);
            campo_nota.val(nota.nota.toFixed(1));
            campo_nota.focus();
        });
        $('#avaliacao').focus();
    }    
})();