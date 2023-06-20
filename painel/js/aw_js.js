//<!--
// funções javascript diversas

window.onload = function(){
	
}

function pega_endereco(){
	var indexSelect = document.getElementById("id_cidade_guia").selectedIndex;
	var cidade_id   = document.getElementById("id_cidade_guia").options[indexSelect ].value;
	switch(cidade_id){
		case '1':
			var cidade = "Ribeirao Preto, SP";
			break;
		case '2':
			var cidade = "Guarulhos, SP";
			break;
	}
	var logradouro = document.getElementById('logradouro').value;
	var numero 		 = document.getElementById('numero').value;
	var bairro 		 = document.getElementById('bairro').value;
	var cep 			 = document.getElementById('cep').value;

	var endereco = cidade + ", " + logradouro + ", nº " + numero;
	document.getElementById('address').value = endereco;
}


// Função que gera uma pop-up central
var win= null;
function popupcenter(mypage,myname,w,h,scroll,maximizar){
var winl = (screen.width-w)/2;
var wint = (screen.height-h)/2;
var settings ='height='+h+',';
settings +='width='+w+',';
settings +='top='+wint+',';
settings +='left='+winl+',';
settings +='scrollbars='+scroll+',';
settings +='resizable='+maximizar;
win=window.open(mypage,myname,settings);
if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}



// adiciona onmouseover nas tds da tabela que cria menu superior - módulos
function menu_sup(){
	if (document.getElementById('tb_menu_sup')){
		tds_menu_sup = document.getElementById('tb_menu_sup').getElementsByTagName('TD');
		for (ii=0;ii<tds_menu_sup.length;ii++){
			tds_menu_sup[ii].onmouseover = function(){
				this.className = 'td_menu_sup_over';
			}
			tds_menu_sup[ii].onmouseout = function(){
				this.className = 'td_menu_sup';
			}
		}
	}
}

window.onload = function(){
		menu_sup();
}

// função dos botões anterior e próximo da paginação
function next_pg(blc,pg,form){
	var form = document.getElementById(form);
	form.grid_pg.value = pg;
	form.submit();
}

function prev_pg(blc,pg,form){
	var form = document.getElementById(form);
	form.grid_pg.value = pg;
	form.submit();
}

// função que gera submit da grid
function gen_submit(blc, tipo, form){
	var form = document.getElementById(form);
	if (tipo == 'alt'){
		form.action = 'index.php?blc='+blc+'&acao=Alterar';
		form.submit();
	} else if (tipo == 'del'){
		if (confirm('Deseja realmente apagar esse registro ?')==true){
			form.action = 'index.php?blc='+blc+'&acao=Excluir';
			form.submit();
		}
	} else if (tipo == 'cobrar_fatura'){
		if (confirm('Deseja realmente enviar essa cobrança ?\nO prazo de vencimento será aumentado em 7 dias.')==true){
			form.action = 'index.php?blc='+blc+'&acao=Cobrar';
			form.submit();
		}
	} else if(tipo == 'sim'){
		form.action = 'index.php?blc='+blc+'&acao=Excluir&excluir=1';
		form.submit();
	} else if(tipo == 'nao'){
		form.action = 'index.php?blc='+blc+'&acao=Excluir&excluir=2';
		form.submit();
	} else if(tipo == 'del1'){
		form.action = 'index.php?blc='+blc+'&acao=Excluir';
		form.submit();
	} else if (tipo == 'env_email'){
		form.action = 'index.php?blc='+blc+'&acao=EnviarEmail';
		form.submit();
	}
}

// função gera submit para atendimento
function gen_atendimento(blc, tipo, form){
	var form = document.getElementById(form);
	if (tipo == 'atender'){
		form.action = 'index.php?blc='+blc+'&acao=Atendimento';
		form.submit();
	}
}

function gen_submit_cart(blc, tipo, form){
	var form = document.getElementById(form);
	if (tipo == 'atualizar'){
		form.action = 'index.php?blc='+blc+'&acao=atualizar';
		form.submit();
	} else if (tipo == 'del'){

	}
}

// define submit do form f_janela
// controla janela para maximizar e minimizar
function win_control(link_url){
	document.f_janela.action = link_url;
	document.f_janela.submit();
}

/*
**************************************
* Event Listener Function v1.4       *
* Autor: Carlos R. L. Rodrigues      *
**************************************
*/
addEvent = function(o, e, f, s){
    var r = o[r = "_" + (e = "on" + e)] = o[r] || (o[e] ? [[o[e], o]] : []), a, c, d;
    r[r.length] = [f, s || o], o[e] = function(e){
        try{
            (e = e || event).preventDefault || (e.preventDefault = function(){e.returnValue = false;});
            e.stopPropagation || (e.stopPropagation = function(){e.cancelBubble = true;});
            e.target || (e.target = e.srcElement || null);
            e.key = (e.which + 1 || e.keyCode + 1) - 1 || 0;
        }catch(f){}
        for(d = 1, f = r.length; f; r[--f] && (a = r[f][0], o = r[f][1], a.call ? c = a.call(o, e) : (o._ = a, c = o._(e), o._ = null), d &= c !== false));
        return e = null, !!d;
    }
};

removeEvent = function(o, e, f, s){
    for(var i = (e = o["_on" + e] || []).length; i;)
        if(e[--i] && e[i][0] == f && (s || o) == e[i][1])
            return delete e[i];
    return false;
};


// Formata Dinheiro
function formatCurrency(o, n, dig, dec){
    o.c = !isNaN(n) ? Math.abs(n) : 2;
    o.dec = dec || ",", o.dig = dig || ".";
    addEvent(o, "keypress", function(e){
        if(e.key > 47 && e.key < 58){
            var o, s = ((o = this).value.replace(/^0+/g, "") + String.fromCharCode(e.key)).replace(/\D/g, ""), l, n;
            (l = s.length) <= (n = o.c) && (s = new Array(n - l + 2).join("0") + s);
            for(var i = (l = (s = s.split("")).length) - n; (i -= 3) > 0; s[i - 1] += o.dig);
            n && n < l && (s[l - ++n] += o.dec);
            o.value = s.join("");
        }
        e.key > 30 && e.preventDefault();
    });
}

function esconde_menu(){
	// esconde menu se o sistema não achar blocos.
	document.getElementById('menu_lateral_bloco').style.display = 'none';	
}


function carregar_template(){
	var indexSelect = document.getElementById("template").selectedIndex;
	var valueSelected = document.getElementById("template").options[indexSelect ].value;
	var action = document.frm_incluir.action;
	action += '&template='+valueSelected;
	if (confirm('Deseja carregar realmente este template?\nOs dados já preenchidos serão perdidos.') && valueSelected != ""){
		document.frm_incluir.action = action;
		document.frm_incluir.submit();
	}
}

function enviar_email(id){
	$.ajax({
		type: "GET",
		url: "inc/blc/sendmail.php",
		data: "id="+id,
		success: function(msg){
			if (msg != 'ERRO_DB' && msg != 'LIMITE_COTA'){
				if (msg == 100){
					$("#barra_envio").css("width", "600px");
					$("#barra_envio").html("100,00%");
					$(".alerta").html("&nbsp;");
				} else {
					var porcentagem = msg.replace(".",",")+"%";
					
					$("#barra_envio").css("width", Math.ceil(msg * 6)+"px");
					$("#barra_envio").html(porcentagem);
					$(".alerta").html("&nbsp;");
					
					if (msg < 100){
						enviar_email(id);
					}
				}
			} else {
				if (msg == 'ERRO_DB'){
					alert('Erro no banco de dados. Informe problema para o administrador do site.');
				} else if (msg == 'LIMITE_COTA'){
					$(".alerta").html("Limite de envios de e-mails por hora alcançado.<br>Aguarde até a próxima hora para continuar enviando.");
				}
			}
		}
	});
}

function defini_forma_pagamento(forma_pagamento){
	$.ajax({
		type: "GET",
		url: "inc/blc/defini_forma_pagamento.php",
		data: "forma_pagamento="+forma_pagamento,
		success: function(msg){
			if (msg.indexOf("#") == 4){ // tem erro
				var erro = msg.split("#");
				switch(erro[1]){
					case 'forma_pagamento_invalida':
						alert('A forma de pagamento escolhida é inválida.');
						break;
					case 'sem_forma_pagamento':
						alert('A forma de pagamento não foi escolhida.');
						break;
				}
			} else {
				if (msg == 'bonus'){
					utilizar_bonus_pagamento();	
				}
			}
			location.href = 'index.php?blc=carrinho';
		}
	});
}

function utilizar_bonus_pagamento(){
	$.ajax({
		type: "GET",
		url: "inc/blc/utilizar_bonus_pagamento.php",
		data: ""
	});
}

function verifica_status_pedido(status){
	if (status == 'PAGO'){
		alert('ATENÇÃO: As comissões serão distribuídas e não será mais possível alterar o status.');
	}
}

function verifica_confirmacao(){
	var nome_jogador 	 = document.getElementById('login_jogador').value;
	var qtd_item	 	 = document.getElementById('qtd_item').value;
	var nome_do_produto	 = document.getElementById('nome_do_produto').value;
	
	if (nome_jogador != '' && qtd_item != ''){
		var msg = 'Atenção: Deseja confirmar a venda de '+nome_do_produto+' ('+qtd_item+') para o jogador(a) '+nome_jogador+'?';
		if (confirm(msg)){
			document.getElementById('frm_form_confirma').submit();
		}	
	} else {
		alert('Preencha todos os campos.');
	}
	
}

posicao_atendimento = 0;
function add_atendimento(){
	if (posicao_atendimento == 0){
		$("#frm_atendimento").show();
		posicao_atendimento = 1;
	} else {
		$("#frm_atendimento").hide();
		posicao_atendimento = 0;
	}
}

//-->
