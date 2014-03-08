

var rc_autocompleter_date = 0;
var rc_autocompleter_refresh = 0;
var rc_autocompleter_delay_stamp = 0;
var rc_active;
var whatFile = 0;
var retAuto;
var idAuto;
var dep=0;


function filtros(id, tam){
	document.getElementById(id).style.height = tam+'px'
	document.getElementById(id+'Filtrar').style.display = 'inline';
	document.getElementById(id+'Limpar').style.display = 'inline';
	document.getElementById(id+'Data').style.display = 'inline';
}

function desFiltros(id, tam){
	document.getElementById(id).style.height = tam+'px'
	document.getElementById(id+'Filtrar').style.display = 'none';
	document.getElementById(id+'Limpar').style.display = 'none';
	document.getElementById(id+'Data').style.display = 'none';
	
}


function bloquearCtrlJ(){ // Verificação das Teclas  
    var tecla=window.event.keyCode;   //Para controle da tecla pressionada  
    var ctrl=window.event.ctrlKey;    //Para controle da Tecla CTRL  

    if (ctrl && tecla==74){    //Evita teclar ctrl + j  
        event.keyCode=0;  
        event.returnValue=false;  
    }  
}

function rc_autocompleter_Choice(r, d, codigo) {
	//alert('dfsfsdfsfs');
	document.getElementById(idAuto).value = r;
	document.getElementById(idAuto).style.color = '#666666';
	//alert(retAuto);
    document.forms[form].elements[retAuto].value = codigo;
    rc_invisible(d);
    
    
    
    if(destino != "" && destino != null){
    	//window.location.href = destino+".php?COD_CLN="+codigo+"&NME_CLN="+r;
    	document.forms[form].submit();
    }	
    
}

function deleteTable(){
	var table=document.getElementById("table1");
	var rowCount = table.rows.length;
	for(x=0; x<=(rowCount+1); x++){
		table.deleteRow(0);
	}

}

function deleteRow(i,tpo) {
    var table = document.getElementById('table1');
    var rowCount = (table.rows.length - 1);
    table.deleteRow(i); //not recursive
    for(j=1; j<rowCount; j++){
    	table.rows[j].cells[0].removeAttribute("onclick");
    	table.rows[j].cells[0].setAttribute("onclick", "document.getElementById('delete').value = "+j+";  dispatch(document.getElementById('delete'));");
    	
    	var jString = String(j);
		var jStrpad = str_pad(jString, 3, '0', 'STR_PAD_LEFT');
		//alert(table.row[j].cells[1].innerHTML);
		table.rows[j].cells[1].innerHTML= jStrpad;
		
		
		//RENOMEIA OS INPUTS
		if(j >= i){
			next = j + 1;
			
			document.getElementById("CDG"+j).value = document.getElementById("CDG"+next).value;
			
			document.getElementById("DSC"+next).removeAttribute("name");
			document.getElementById("DSC"+next).setAttribute("name", "DSC"+j);
			document.forms['frmbreg'].elements["DSC"+j].removeAttribute("id");
			document.forms['frmbreg'].elements["DSC"+j].setAttribute("id", "DSC"+j);
			
			if(tpo == 1){
				document.getElementById("QTD"+next).removeAttribute("name");
				document.getElementById("QTD"+next).setAttribute("name", "QTD"+j);
				document.forms['frmbreg'].elements["QTD"+j].removeAttribute("id");
				document.forms['frmbreg'].elements["QTD"+j].setAttribute("id", "QTD"+j);
				
				document.getElementById("UNT"+next).removeAttribute("name");
				document.getElementById("UNT"+next).setAttribute("name", "UNT"+j);
				document.forms['frmbreg'].elements["UNT"+j].removeAttribute("id");
				document.forms['frmbreg'].elements["UNT"+j].setAttribute("id", "UNT"+j);
				
				document.getElementById("VLR"+next).removeAttribute("name");
				document.getElementById("VLR"+next).setAttribute("name", "VLR"+j);
				document.forms['frmbreg'].elements["VLR"+j].removeAttribute("id");
				document.forms['frmbreg'].elements["VLR"+j].setAttribute("id", "VLR"+j);
				
				document.getElementById("DSN"+next).removeAttribute("name");
				document.getElementById("DSN"+next).setAttribute("name", "DSN"+j);
				document.forms['frmbreg'].elements["DSN"+j].removeAttribute("id");
				document.forms['frmbreg'].elements["DSN"+j].setAttribute("id", "DSN"+j);
				
				document.getElementById("TTL"+next).removeAttribute("name");
				document.getElementById("TTL"+next).setAttribute("name", "TTL"+j);
				document.forms['frmbreg'].elements["TTL"+j].removeAttribute("id");
				document.forms['frmbreg'].elements["TTL"+j].setAttribute("id", "TTL"+j);
			}	
		}
    }
    
    if(tpo == 1){
    	totalGeral();
    }	
    
    
}


function totalGeral(){
	
	var table=document.getElementById("table1");
	var rowCount = table.rows.length;
	var total = 0;
	
	
	
	for(j=1; j<rowCount; j++){
		valor = document.forms['frmbreg'].elements["TTL"+j].value;
		
		//alert(valor);
		valor = valor.replace('.','');
		valor = valor.replace(',','.');
		valor = parseFloat(valor);
		total = total + valor;
		
	}
	
	desconto = document.forms['frmbreg'].elements["VLR_DSC"].value;
	desconto = desconto.replace('.','');
	desconto = desconto.replace(',','.');
	desconto = parseFloat(desconto);
	
	total = total - desconto;
	
	total = number_format(total, 2, ',', '.');
	document.forms['frmbreg'].elements["VLR_TTL"].value = total;
	
}

function deleteRowFornecedor(i) {
    var table = document.getElementById('table1');
    var rowCount = (table.rows.length - 1);
    table.deleteRow(i); //not recursive
    for(j=1; j<rowCount; j++){
    	table.rows[j].cells[0].removeAttribute("onclick");
    	table.rows[j].cells[0].setAttribute("onclick", "document.getElementById('delete').value = "+j+";  dispatch(document.getElementById('delete'));");
    	
		
		//RENOMEIA OS INPUTS
		if(j >= i){
			next = j + 1;
			
			document.getElementById("CDG"+j).value = document.getElementById("CDG"+next).value;
			
			document.getElementById("PRC"+next).removeAttribute("name");
			document.getElementById("PRC"+next).setAttribute("name", "PRC"+j);
			document.forms['frmbreg'].elements["PRC"+j].removeAttribute("id");
			document.forms['frmbreg'].elements["PRC"+j].setAttribute("id", "PRC"+j);
			
				
		}
    }
    
    	
    
    
}


function deleteRowGrupoCliente(i) {
    var table = document.getElementById('table2');
    var rowCount = (table.rows.length - 1);
    table.deleteRow(i); //not recursive
     
    
    
    for(j=1; j<rowCount; j++){
    	table.rows[j].cells[0].removeAttribute("onclick");
    	table.rows[j].cells[0].setAttribute("onclick", "document.getElementById('delete2').value = "+j+";  dispatch(document.getElementById('delete2'));");
		
		//RENOMEIA OS INPUTS
		if(j >= i){
			next = j + 1;
			
			
			document.getElementById("CDGV"+j).value = document.getElementById("CDGV"+next).value;
			
			
			document.getElementById("PRCV"+next).removeAttribute("name");
			document.getElementById("PRCV"+next).setAttribute("name", "PRCV"+j);
			document.forms['frmbreg'].elements["PRCV"+j].removeAttribute("id");
			document.forms['frmbreg'].elements["PRCV"+j].setAttribute("id", "PRCV"+j);
			
				
		}
    }
    
    	
    
    
}



function displayResult(movi, cod,quan, desc, descricao, unitario, historico)
{
	
	var table=document.getElementById("table1");
	var rowCount = table.rows.length;
	var row=table.insertRow(rowCount);




	var rowCountString = String(rowCount);
	var rowCountNext = String(rowCount+1);
	var rowCountStrpad = str_pad(rowCountString, 3, '0', 'STR_PAD_LEFT');
	var form = document.getElementById('frmbreg');
	
	//alert(rowCountString);



	//EXCLUSÃO
	var cell1=row.insertCell(0);
	cell1.setAttribute("id", "tdExcluir");
	//cell1.setAttribute("onclick", "deleteRow("+rowCount+")");
	//cell1.setAttribute("onclick", "$('#demoContainer').mb_expand('../pcte-common/container/ajax_excluir.php',"+rowCount+");");
	cell1.setAttribute("onclick", "document.getElementById('delete').value = "+rowCount+";  dispatch(document.getElementById('delete'));");

	//ÍTEM
	var cell2=row.insertCell(1);
	cell2.setAttribute("id", "tdCenter");
	cell2.innerHTML= rowCountStrpad;

	//CÓDIGO DO PRODUTO
	var cdg = document.createElement("input");
	cdg.name = "CDG"+rowCountString;
	cdg.type = "hidden";
	cdg.setAttribute("id", "CDG"+rowCountString);
	cdg.value = cod;
	form.appendChild(cdg);


	//DESCRIÇÃO
	var cell3=row.insertCell(2);
	cell3.setAttribute("id", "tdLeft");
	var dsc = document.createElement("input");
	dsc.name = "DSC"+rowCountString;
	dsc.type = "text";
	dsc.value = descricao;
	dsc.setAttribute("class", "tdInput");
	dsc.setAttribute("id", "DSC"+rowCountString);
	dsc.setAttribute("readonly", "readonly");
	dsc.setAttribute("onfocus", "this.form.QTD"+rowCountString+".focus();");
	form.appendChild(dsc);
	cell3.appendChild(dsc);



	//HISTÓRICO
	var cell9=row.insertCell(3);
	cell9.setAttribute("id", "tdCenter");
	var hst = document.createElement("input");
	hst.name = "HST"+rowCountString;
	hst.type = "text";
	hst.value = historico;
	hst.setAttribute("class", "tdInput");
	hst.setAttribute("style", "text-align:center; text-transform:uppercase;");
	hst.setAttribute("id", "HST"+rowCountString);
	form.appendChild(hst);
	cell9.appendChild(hst);

	//QUANTIDADE
	var cell4=row.insertCell(4);
	cell4.setAttribute("id", "tdCenter");
	var qtd = document.createElement("input");
	qtd.name = "QTD"+rowCountString;
	qtd.type = "text";
	qtd.value = quan;
	qtd.setAttribute("class", "tdInput");
	qtd.setAttribute("id", "QTD"+rowCountString);
	qtd.setAttribute("style", "text-align:center; width:80px");
	qtd.setAttribute("onkeyup", "quantidade("+rowCountString+", this.form.DSN"+rowCountString+".value, this.form.UNT"+rowCountString+".value, this.value)");
	qtd.setAttribute("onclick", "this.select()");
	//qtd.setAttribute("onkeypress", "return(MascaraQuantidade(this,'.',',',event))");
	form.appendChild(qtd);
	cell4.appendChild(qtd);


	//UNITÁRIO
	var cell5=row.insertCell(5);
	cell5.setAttribute("id", "tdCenter");
	var unt = document.createElement("input");
	unt.name = "UNT"+rowCountString;
	unt.type = "text";
	unt.value = unitario;
	unt.setAttribute("class", "tdInput");
	unt.setAttribute("id", "UNT"+rowCountString);
	unt.setAttribute("style", "text-align:center; width:80px");
	unt.setAttribute("onkeyup", "quantidade("+rowCountString+", this.form.DSN"+rowCountString+".value, this.value, this.form.QTD"+rowCountString+".value)");
	unt.setAttribute("onclick", "this.select()");
	unt.setAttribute("onkeypress", "return(MascaraMoeda(this,'.',',',event))");
	form.appendChild(unt);
	cell5.appendChild(unt);
	
	//VALOR
	var cell6=row.insertCell(6);
	cell6.setAttribute("id", "tdCenter");
	var vlr = document.createElement("input");
	vlr.name = "VLR"+rowCountString;
	vlr.type = "text";
	vlr.value = unitario;
	vlr.setAttribute("class", "tdInput");
	vlr.setAttribute("id", "VLR"+rowCountString);
	vlr.setAttribute("readonly", "readonly");
	vlr.setAttribute("style", "text-align:center; width:80px");
	vlr.setAttribute("onfocus", "this.form.DSN"+rowCountString+".focus();");
	form.appendChild(vlr);
	cell6.appendChild(vlr);
	
	//DESCONTO
	var cell7=row.insertCell(7);
	cell7.setAttribute("id", "tdCenter");
	var dsn = document.createElement("input");
	dsn.name = "DSN"+rowCountString;
	dsn.type = "text";
	dsn.value = desc;
	dsn.setAttribute("class", "tdInput");
	dsn.setAttribute("id", "DSN"+rowCountString);
	dsn.setAttribute("style", "text-align:center; width:80px");
	dsn.setAttribute("onkeypress", "return(MascaraMoeda(this,'.',',',event))");
	dsn.setAttribute("onkeyup", "descontoFunc("+rowCountString+", this.value, this.form.VLR"+rowCountString+".value)");
	form.appendChild(dsn);
	cell7.appendChild(dsn);
	
	
	
	//TOTAL
	var cell8=row.insertCell(8);
	cell8.setAttribute("id", "tdCenter");
	var ttl = document.createElement("input");
	ttl.name = "TTL"+rowCountString;
	ttl.type = "text";
	ttl.value = unitario;
	ttl.setAttribute("class", "tdInput");
	ttl.setAttribute("id", "TTL"+rowCountString);
	ttl.setAttribute("readonly", "readonly");
	ttl.setAttribute("style", "text-align:center; width:80px");
	ttl.setAttribute("onfocus", "this.form.QTD"+rowCountNext+".focus();");
	form.appendChild(ttl);
	cell8.appendChild(ttl);
	
	valo = quantidade(rowCountString, desc, unitario, quan);
	descontoFunc(rowCountString, desc, valo);
	totalGeral();

}

function descontoFunc(linha, desconto, valor){
	
	
	
	valor = valor.replace('.','');
	valor = valor.replace(',','.');
	valor = parseFloat(valor);
	desconto = desconto.replace('.','');
	desconto = desconto.replace(',','.');
	desconto = parseFloat(desconto);
	
	desconto = desconto*0.01;
	
	desconto = valor * desconto; 
	total = valor - desconto; 
	//alert(total);
	
	total = number_format(total, 2, ',', '.');
	document.forms['frmbreg'].elements["TTL"+linha].value = total;
	
	totalGeral();
	
	
}

function quantidade(linha, desconto, unidade, qtd){
	
	unidade = unidade.replace('.','');
	unidade = unidade.replace(',','.');
	unidade = parseFloat(unidade);
	desconto = desconto.replace('.','');
	desconto = desconto.replace(',','.');
	desconto = parseFloat(desconto);
	qtd = qtd.replace(',','.');
	qtd = parseFloat(qtd);
	
	valor = qtd * unidade;
	valorString = number_format(valor, 2, ',', '.');
	document.forms['frmbreg'].elements["VLR"+linha].value = valorString;
	//alert(valor);
	total = valor - desconto; 
	total = number_format(total, 2, ',', '.');
	document.forms['frmbreg'].elements["TTL"+linha].value = total;
	
	totalGeral();
	
	return valorString;
}


function clicando2(field, event){
	
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	
	
	if (keyCode == 13) {
		
		return false;
	}else{
		return true;
	}
	
	
}



function clicando(field, event){
	
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	
	
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++){
			if (field == field.form.elements[i])
				break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
		}
		
		dispatch(document.forms['frmbreg'].elements['BTN_PRD']);
		
		
		return false;
	}else{
		return true;
	}
	
	
}

function rc_autocompleter_click(idProduto,conteudo,d,codigo) {
    document.getElementById(idProduto).value = conteudo;  // preeche o campo dos produtos
    //document.getElementById(cod).value = codigo;
    tabela = document.frmqueixa.filtro.value;
    rc_autocompleter_blur2(d,300);
    if(whatFile == 1){
    	produtoExame(tabela,codigo);
    }else if(whatFile == 2){
    	produtoReceita(tabela,codigo);
    }  
    //xajax_produtosExame(tabela,rc_autocompleter_date,codigo);
    //alert('llllllllllllllllllllllll'); 
}

function produtoExame(tabela,codigo){
	xajax_produtosExame(tabela,rc_autocompleter_date,codigo);
}

function produtoReceita(tabela,codigo){
	xajax_produtosReceita(tabela,rc_autocompleter_date,codigo);
}


function rc_autocompleter_salvar(texto,guia){
	if(guia == 'particular'){
		$('DSC_MDO').value = texto;
		xajax_salvaExameParticular(texto);	
	}else{
		xajax_salvaExameUnimed(rc_autocompleter_date,$('COD_OPR').value,$('NME_COT').value,$('UNM_CNE').value,$('CRT_SLC').value,$('CID_SLC').value,$('IND_CLN').value);	
	}
	
}

function rc_autocompleter_imprimir(){
	location.href='exame/imprimir.php';
	//xajax_imprimirExameUnimed();
}

function rc_autocompleter_bula(cod,justificativa,margenTop,block) {
	
	if(whatFile == 1){
    	prescricaoExame(cod,justificativa,block,margenTop);
    }else if(whatFile == 2){
    	prescricaoReceita(cod,block,margenTop);
    }
    
}

function prescricaoExame(cod,justificativa,block,margenTop){
	tipo = $('TPO_FLH').value;
	xajax_prescricaoExame(rc_autocompleter_date,cod,justificativa,block,margenTop,tipo);
	
}

function prescricaoReceita(cod,block,margenTop){
	xajax_prescricaoReceita(rc_autocompleter_date,cod,block,margenTop);
}

// observa��o na agenda
function rc_obs(obs,agt,pct) {
	//alert(obs);
	document.getElementById('obs').style.visibility = 'visible';
	document.getElementById('pct').value = pct;
	document.getElementById('agt').value = agt;
	document.getElementById('text').value = obs;
    rc_windows_obs();
}


function rc_novo(d,table) {
	document.getElementById('new').style.visibility = 'visible';
	document.getElementById('title').value = d;
	document.getElementById('table').value = table;
    rc_windows_novo();
}
function rc_salvar(table,d) {
	document.getElementById(d).style.visibility = 'hidden';
	document.getElementById('table').value = table;
    rc_query_salvar();
}

function rc_novo_invisible(d) {
    document.getElementById(d).style.visibility = 'hidden';
}




function rc_autocompleter_focus(d) {
    document.getElementById(d).style.visibility = 'visible';
}

function rc_autocompleter_blur(d) {
    window.setTimeout('rc_autocompleter_blur2(\'' + d + '\')', 300);
    
}
        
function rc_autocompleter_blur2(d,e) {
    document.getElementById(d).style.visibility = 'visible';  //Voltei para ficar visivel
}

function rc_autocompleter_call() {
    var now = new Date().getTime() / 1000;   
    var s = parseInt(now);   
      
    rc_autocompleter_date = now;
}


function rc_autocompleter_return(d, t, v, i) {
	
    if(t >= rc_autocompleter_refresh) {
        rc_autocompleter_refresh = t;
        document.getElementById(d).style.visibility = v;
        document.getElementById(d).innerHTML = i;
    }
}

function dispatch(th){
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,
	0, 0, 0, 0, 0, false, false, false, false, 0, null);
    th.dispatchEvent(evt);
	
}

function rc_autocompleter_move(id1, id2, keyc) {
	
    var child = document.getElementById(id2).childNodes[0].childNodes[0].childNodes;    
    var idx = -1;
	//alert(document.getElementById(id2).childNodes[0].childNodes[0].childNodes[1].innerText);
    for(var i=0; i < child.length; i++) {
        if(child[i].className == 'li_hover') {
            idx = i;
        }	
        
        child[i].className = '';
    }
    //alert(keyc);
    // return
    if(keyc == 13) {
        var text = '';
        
        if(child[idx].innerText) {
            text = child[idx].innerText;
        } else {
            text = child[idx].textContent;        
        }
        
        var evt = document.createEvent("MouseEvents");
  		evt.initMouseEvent("click", true, true, window,
    	0, 0, 0, 0, 0, false, false, false, false, 0, null);
    	//yalert(child[idx].childNodes[0].childNodes[0].nodeName);
        child[idx].childNodes[0].childNodes[0].dispatchEvent(evt)
    } else {
        // up
        if(keyc == 40) {
            if(idx == (child.length - 1)) {
                idx = 0;
            } else {
                idx++;
            }
        
        // down
        } else if(keyc == 38) {
            idx--;
            
            if(idx < 0) {
                idx = (child.length - 1);
            }
        }
        
        child[idx].className = 'li_hover';
    }
}

function rc_microtime () {
    return new Date().getTime();
}

function rc_invisible(d) {
    document.getElementById(d).style.visibility = 'hidden';
}

function getPosicaoElemento(elemID){
    var offsetTrail = document.getElementById(elemID);
    var offsetLeft = 0;
    var offsetTop = 0;
    while (offsetTrail) {
        offsetLeft += offsetTrail.offsetLeft;
        offsetTop += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && 
        typeof document.body.leftMargin != "undefined") {
        offsetLeft += document.body.leftMargin;
        offsetTop += document.body.topMargin;
    }
    return {left:offsetLeft, top:offsetTop};
}


function rc_autocompleter(e, id, id2, funcao, retorno) {
	
	posicaoTop = getPosicaoElemento(id).top + document.getElementById(id).clientHeight+1;
	posicaoLeft = getPosicaoElemento(id).left+1;
	largura = document.getElementById(id).clientWidth;
	
	
	document.getElementById(id2).style.marginLeft = posicaoLeft+'px';
	document.getElementById(id2).style.marginTop = posicaoTop+'px';
	document.getElementById(id2).style.width = largura+'px';
	
    if(!e) e = window.event; 
    var keyc = e.keyCode || e.which; 
    
    retAuto = retorno;
    //alert(form);
    document.forms[form].elements[retAuto].value = '';
    //alert(form);
    
    idAuto = id;   
    
    rc_autocompleter_focus(id2);
    auto = document.getElementById(id).value;
    
    if(auto == ""){
    	rc_invisible(id2);
    }
    
    if(keyc == 38 || keyc == 40 || keyc == 13) {
        rc_autocompleter_move(id, id2, keyc);
        
    } else {
        rc_autocompleter_delay_stamp = rc_microtime();
        rc_active = window.setTimeout('rc_autocompleter_delay(\'' + rc_autocompleter_delay_stamp + '\', \'' + auto + '\', \'' + funcao + '\')', 100);
    }
    
}



function rc_autocompleter_delay(s,auto,funcao) {
	
    if(rc_autocompleter_delay_stamp + 25 < rc_microtime()) {
        rc_autocompleter_call();
        
        //alert(funcao);
    	//filtro = $('filtro').value;
    	eval("xajax_"+funcao+"('"+auto+"',"+rc_autocompleter_date+")");
    	//
    	
    }    
}

function str_pad (input, pad_length, pad_string, pad_type) {
   
    var half = '',
        pad_to_go;
 
    var str_pad_repeater = function (s, len) {
        var collect = '',
            i;
 
        while (collect.length < len) {
            collect += s;
        }
        collect = collect.substr(0, len);
 
        return collect;
    };
 
    input += '';
    pad_string = pad_string !== undefined ? pad_string : ' ';
 
    if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
        pad_type = 'STR_PAD_RIGHT';
    }
    if ((pad_to_go = pad_length - input.length) > 0) {
        if (pad_type == 'STR_PAD_LEFT') {
            input = str_pad_repeater(pad_string, pad_to_go) + input;
        } else if (pad_type == 'STR_PAD_RIGHT') {
            input = input + str_pad_repeater(pad_string, pad_to_go);
        } else if (pad_type == 'STR_PAD_BOTH') {
            half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
            input = half + input + half;
            input = input.substr(0, pad_length);
        }
    }
 
    return input;
}


function displayResultFornecedor(cod, desc, preco)
{
	
var table=document.getElementById("table1");
var rowCount = table.rows.length;
var row=table.insertRow(rowCount);




var rowCountString = String(rowCount);
var rowCountNext = String(rowCount+1);
var rowCountStrpad = str_pad(rowCountString, 3, '0', 'STR_PAD_LEFT');
var form = document.getElementById('frmbreg');

//alert(rowCountString);



//EXCLUSÃO
var cell1=row.insertCell(0);
cell1.setAttribute("id", "tdExcluir");
//cell1.setAttribute("onclick", "deleteRow("+rowCount+")");
//cell1.setAttribute("onclick", "$('#demoContainer').mb_expand('../pcte-common/container/ajax_excluir.php',"+rowCount+");");
cell1.setAttribute("onclick", "document.getElementById('delete').value = "+rowCount+";  dispatch(document.getElementById('delete'));");

//CÓDIGO DO PRODUTO
var cdg = document.createElement("input");
cdg.name = "CDG"+rowCountString;
cdg.type = "hidden";
cdg.setAttribute("id", "CDG"+rowCountString);
cdg.value = cod;
form.appendChild(cdg);


//DESCRIÇÃO
var cell3=row.insertCell(1);
cell3.setAttribute("id", "tdLeft");
cell3.innerHTML= desc;

//PREÇO
var cell4=row.insertCell(2);
cell4.setAttribute("id", "tdCenter");
var qtd = document.createElement("input");
qtd.name = "PRC"+rowCountString;
qtd.type = "text";
qtd.value = preco;
qtd.setAttribute("class", "tdInput");
qtd.setAttribute("id", "PRC"+rowCountString);
qtd.setAttribute("style", "text-align:center; width:80px");
qtd.setAttribute("onkeypress", "return(MascaraMoeda(this,'.',',',event))");
form.appendChild(qtd);
cell4.appendChild(qtd);

	

}


function displayResultKits(cod, desc, quantidade)
{
	
	
	
var table=document.getElementById("table1");
var rowCount = table.rows.length;
var row=table.insertRow(rowCount);




var rowCountString = String(rowCount);
var rowCountNext = String(rowCount+1);
var rowCountStrpad = str_pad(rowCountString, 3, '0', 'STR_PAD_LEFT');
var form = document.getElementById('frmbreg');

//alert(rowCountString);



//EXCLUSÃO
var cell1=row.insertCell(0);
cell1.setAttribute("id", "tdExcluir");
//cell1.setAttribute("onclick", "deleteRow("+rowCount+")");
//cell1.setAttribute("onclick", "$('#demoContainer').mb_expand('../pcte-common/container/ajax_excluir.php',"+rowCount+");");
cell1.setAttribute("onclick", "document.getElementById('delete').value = "+rowCount+";  dispatch(document.getElementById('delete'));");




//CÓDIGO DO PRODUTO
var cdg = document.createElement("input");
cdg.name = "CDG"+rowCountString;
cdg.type = "hidden";
cdg.setAttribute("id", "CDG"+rowCountString);
cdg.value = cod;
form.appendChild(cdg);

//alert(rowCountString);



//DESCRIÇÃO
var cell3=row.insertCell(1);
cell3.setAttribute("id", "tdLeft");
cell3.innerHTML= desc;

//PREÇO
var cell4=row.insertCell(2);
cell4.setAttribute("id", "tdCenter");
var qtd = document.createElement("input");
qtd.name = "PRC"+rowCountString;
qtd.type = "text";
qtd.value = quantidade;
qtd.setAttribute("class", "tdInput");
qtd.setAttribute("id", "PRC"+rowCountString);
qtd.setAttribute("style", "text-align:center; width:80px");
qtd.setAttribute("onkeypress", "return(MascaraMoeda(this,'.',',',event))");
form.appendChild(qtd);
cell4.appendChild(qtd);

	

}

function displayResultGrupoClientes(cod, desc, preco)
{

var table=document.getElementById("table2");
var rowCount = table.rows.length;
var row=table.insertRow(rowCount);




var rowCountString = String(rowCount);
var rowCountNext = String(rowCount+1);
var rowCountStrpad = str_pad(rowCountString, 3, '0', 'STR_PAD_LEFT');
var form = document.getElementById('frmbreg');

//alert(rowCountString);



//EXCLUSÃO
var cell1=row.insertCell(0);
cell1.setAttribute("id", "tdExcluir");
//cell1.setAttribute("onclick", "deleteRow("+rowCount+")");
//cell1.setAttribute("onclick", "$('#demoContainer').mb_expand('../pcte-common/container/ajax_excluir.php',"+rowCount+");");
cell1.setAttribute("onclick", "document.getElementById('delete2').value = "+rowCount+";  dispatch(document.getElementById('delete2'));");

//CÓDIGO DO PRODUTO
var cdg = document.createElement("input");
cdg.name = "CDGV"+rowCountString;
cdg.type = "hidden";
cdg.setAttribute("id", "CDGV"+rowCountString);
cdg.value = cod;
form.appendChild(cdg);


//DESCRIÇÃO
var cell3=row.insertCell(1);
cell3.setAttribute("id", "tdLeft");
cell3.innerHTML= desc;

//PREÇO
var cell4=row.insertCell(2);
cell4.setAttribute("id", "tdCenter");
var qtd = document.createElement("input");
qtd.name = "PRCV"+rowCountString;
qtd.type = "text";
qtd.value = preco;
qtd.setAttribute("class", "tdInput");
qtd.setAttribute("id", "PRCV"+rowCountString);
qtd.setAttribute("style", "text-align:center; width:80px");
qtd.setAttribute("onkeypress", "return(MascaraMoeda(this,'.',',',event))");
form.appendChild(qtd);
cell4.appendChild(qtd);

	

}


