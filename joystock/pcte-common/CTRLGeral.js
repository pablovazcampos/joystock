var timeCrono; 
var hor = 0;
var min = 0;
var seg = 0;
var startTime = new Date(); 
var start = startTime.getSeconds();

function delet(th, ev){
	var check = Bloqueia_Caracteres(ev);
	
	if(check == false){
		th.value = th.value.substr(0,th.value.length-1);
	}	
	
}

function StartCrono() {
	if (seg + 1 > 59) { 
		min+= 1 ;
	}
	if (min > 59) {
		min = 0;
		hor+= 1;
	}
	var time = new Date(); 
	if (time.getSeconds() >= start) {
		seg = time.getSeconds() - start;
	} 
	else {
		seg = 60 + (time.getSeconds() - start);
	}
	timeCrono= (hor < 10) ? "0" + hor : hor;
	timeCrono+= ((min < 10) ? ":0" : ":") + min;
	timeCrono+= ((seg < 10) ? ":0" : ":") + seg;
	document.forms['frmbreg'].elements['TMP_ITR'].value = timeCrono;
	setTimeout("StartCrono()",1000);
}

function number_format(value, dec, decsep, milsep){
   dec=(typeof(dec)=='undefined'?2:dec);
   decsep=(typeof(decsep)=='undefined'?',':decsep);
   milsep=(typeof(milsep)=='undefined'?'.':milsep);
   //
   value=value.toFixed(dec)
   //
   var localValue=value.toString();
   var arr=localValue.split('.');
   if (arr.length>1)
      arr[1]=(decsep+arr[1]);
   var re=/(\d+)(\d{3})/;
   while (re.test(arr[0]))
      arr[0]=arr[0].replace(re, '$1'+milsep+'$2');
   return arr[0]+arr[1];
}

function saidaConfirmacao(conf, formulario){
	
	senha = formulario.elements['senha'].value;
	if(conf == senha){
		document.getElementById('LBL_MSG2').innerHTML = '';
	}else{
		document.getElementById('LBL_MSG2').innerHTML = 'Confirmação não confere com a senha';
		formulario.elements['confirma'].focus();
		formulario.elements['confirma'].select();
	}
	
}


function confirmacao(conf, formulario){
	
	tam = conf.length;
	senha = formulario.elements['senha'].value;
	tamSenha = senha.substr(0,tam);
	
	if(conf == tamSenha){
		//alert(tamSenha);
		document.getElementById('LBL_MSG2').innerHTML = '';
	}else{
		document.getElementById('LBL_MSG2').innerHTML = 'Confirmação não confere com a senha';
	}
	
	if(conf == senha){
		formulario.elements['BTNLogin'].style.display  = 'inline';
	}else{
		formulario.elements['BTNLogin'].style.display  = 'none';
	}
	
}

function resolucao(height, width){
	h=height-84;
	w=width-16;
	wk=width-16;
	document.getElementById('koolmenu').style.width = wk+'px';
	document.getElementById('main').style.height = h+'px';
	document.getElementById('main').style.width = w+'px';
}


function cnj(valor){
	if(valor!=2){
		$('#cnj').html("CNPJ");
		$("#obj_cnpj").css("visibility", "visible");
		$("#obj_cpf").css("visibility", "hidden");
	}else{
		$('#cnj').html("CPF");
		$("#obj_cpf").css("visibility", "visible");
		$("#obj_cnpj").css("visibility", "hidden");
	}
}

function hasClass(el, name) {
   return new RegExp('(\\s|^)'+name+'(\\s|$)').test(el.className);
}

function addClass(el, name)
{
   if (!hasClass(el, name)) { el.className += (el.className ? ' ' : '') +name; }
}

function removeClass(el, name)
{
   if (hasClass(el, name)) {
      el.className=el.className.replace(new RegExp('(\\s|^)'+name+'(\\s|$)'),' ').replace(/^\s+|\s+$/g, '');
   }
}



function totalCompra(qtd){
	
	var total = 0.00;
	for(x=1;x<=qtd;x++){
		aux = document.forms['frmbreg'].elements['VLR_TPR'+x].value;
		if(aux!="" && !(aux.match(/^\s+$/))){
			aux = aux.replace('.','');
			aux = aux.replace(',','.');
			valor = parseFloat(aux);
			total += valor;
		}
	}
	
	total = number_format(total, 2, ',', '.');
	//alert(total);
	document.forms['frmbreg'].elements['VLR_TTL'].value = total;
	//document.getElementById('total').innerHTML = total;
	
}


function outrosValores(linha){
	
	var valor     = 0.00;
	var juros     = 0.00;
	var multa     = 0.00;
	var desconto  = 0.00;
	var acrescimo = 0.00;
	var total     = 0.00;
	
	aux = document.forms['frmbreg'].elements['VLR_PRC'+linha].value;
	if(aux!="" && !(aux.match(/^\s+$/))){
		aux = aux.replace('.','');
		aux = aux.replace(',','.');
		valor = parseFloat(aux);
	}	
	
	aux = document.forms['frmbreg'].elements['VLR_JRS'+linha].value;
	if(aux!="" && !(aux.match(/^\s+$/))){
		aux = aux.replace('.','');
		aux = aux.replace(',','.');
		juros = parseFloat(aux);
	}	
	
	aux = document.forms['frmbreg'].elements['VLR_MLT'+linha].value;
	if(aux!="" && !(aux.match(/^\s+$/))){
		aux = aux.replace('.','');
		aux = aux.replace(',','.');
		multa = parseFloat(aux);
	}	
	
	aux = document.forms['frmbreg'].elements['VLR_DSC'+linha].value;
	if(aux!="" && !(aux.match(/^\s+$/))){
		aux = aux.replace('.','');
		aux = aux.replace(',','.');
		desconto = parseFloat(aux);
	}	
	
	aux = document.forms['frmbreg'].elements['VLR_ACR'+linha].value;
	if(aux!="" && !(aux.match(/^\s+$/))){
		aux = aux.replace('.','');
		aux = aux.replace(',','.');
		acrescimo = parseFloat(aux);
	}	
	
	total = valor + juros + multa - desconto + acrescimo;
	total = number_format(total, 2, ',', '.');
	document.forms['frmbreg'].elements['VLR_TPR'+linha].value = total;
	
	totalCompra(document.forms['frmbreg'].elements['QTD_PRC'].value);
		
}

function situacao(valor){
	//alert(valor);
	if(valor == 4 || valor == 5){
		document.getElementById('divSituacao').style.backgroundColor = '#7fb0d0';
		document.forms['frmbreg'].elements['STC_MVM'].style.backgroundColor = '#7fb0d0';
	}else{
		document.getElementById('divSituacao').style.backgroundColor = '#f8d685';
		document.forms['frmbreg'].elements['STC_MVM'].style.backgroundColor = '#f8d685';
	}
}



/*function deletaProduto(valor, linha){
	//alert('ddsfdsfs');
	document.forms['frmbreg'].elements['QTD_PDT'+linha].value = '0,0000';
	document.forms['frmbreg'].elements['VLR_TTL'+linha].value = '0,00';
	xajax_parcelas(document.forms['frmbreg'].elements['QTD_PRC'].value, produtos(), document.forms['frmbreg'].elements['COD_PSS'].value, document.forms['frmbreg'].elements['COD_LJA'].value, document.forms['frmbreg'].elements['DTA_MVM'].value, 0 )
}*/

function mostraParcela(qtd){
	for(f=1;f<=qtd;f++){
		document.getElementById('prc'+f).style.visibility = 'visible';
	}
}




function alteraStatus(valor, linha){
	
	try{
		qtd_prc = document.forms['frmbreg'].elements['QTD_CHQ'+linha].value;
	}catch(err) {
		qtd_prc = 0;
	}
	if(valor == 1){
		for(x=1;x<=qtd_prc;x++){	
			document.getElementById('STS_CHQ'+linha+x)[1].selected = '1';
			document.forms['frmbreg'].elements['STS_CHQ'+linha+x].value = '1';
		}
	}
	
	var prc = document.forms['frmbreg'].elements['QTD_PRC'].value;
	
	for(y=1;y<=prc;y++){
		vlr = document.forms['frmbreg'].elements['STS_PRC'+y].value;
		if(vlr == 2){
			document.getElementById('divStatus').style.backgroundColor = '#f8d685';
			document.getElementById('status').innerHTML = 'ABERTO';
			document.forms['frmbreg'].elements['STS_MVM'].value = 2;
			break;
		}else{
			document.getElementById('divStatus').style.backgroundColor = '#7fb0d0';
			document.getElementById('status').innerHTML = 'FECHADO';
			document.forms['frmbreg'].elements['STS_MVM'].value = 1;
		}
	}
	
}



function teste(form){
	
	/* var sp1 = document.forms.frmbreg.appendChild(document.createElement("input"));
 
 
	 sp1.name = "teste1";
	 sp1.value = "merda";*/
	 
	//var sp1 = document.forms.frmbreg.appendChild(document.createElement("select"));
	//sp1.name = 'STS_CHQ11';
 	//sp1.options.add(new Option('Fechado', 'bosta'));
	//sp1.frmbreg.submit();
	
	 
	 var li = document.createElement("div");
	 li.innerHTML = '<input onblur="document.forms.frmbreg.STS_CHQ11.value = this.value" type="text" name="original">';
	 var sp = document.createElement("input");
	 sp.name = "STS_CHQ11";
	 sp.value = "cagao";
	 sp.id = "outro";
	 //li.appendChild(sp);
	 var sp2 = document.getElementById("teste");
	 sp2.appendChild(li);
	 
	 document.forms.frmbreg.appendChild(sp);
	
	  //alert(document.forms.frmbreg.innerHTML);
	 
	 
	 //document.forms.frmbreg.appendChild(li);
	 //document.forms.frmbreg.appendChild(document.getElementById("vaidarcerto"));
	 //alert(document.forms['frmbreg'].elements['STS_CHQ11'].value);
		
	 
	/*var sp2 = document.getElementById("teste");
	var li = document.createElement('div');
	li.innerHTML = 'seis';
	sp2.appendChild(li);*/
	
	
	
	/*var sp2 = document.getElementById("main");
	 var parentDiv = sp2.parentNode;
	 parentDiv.insertBefore(sp1, sp2);*/
	
	
}

function produtos(){
	
	var total = 0.00;
	var campo;
	var valorCampo = 0.00;
	var aux;
	
	
	/*for (x=1;x<=10;x++){
		campo = String("VLR_TTL"+x);
		aux = document.forms['frmbreg'].elements[campo].value;
		
		if(aux!="" && !(aux.match(/^\s+$/))){
			aux = aux.replace('.','');
			aux = aux.replace(',','.');
			valorCampo = parseFloat(aux);
			total = total + valorCampo;	
		}
		
	}
	//alert(total);
	total = number_format(total, 2, ',', '.');
	document.getElementById('total').innerHTML = total;
	document.forms['frmbreg'].elements['VLR_TTL'].value = total;*/
	
	/*aux = document.forms['frmbreg'].elements['QTD_PRC'].value;
	
	if(aux!="" && !(aux.match(/^\s+$/))){
		fornecedor = document.forms['frmbreg'].elements['COD_PSS'].value;
		data = document.forms['frmbreg'].elements['DTA_MVM'].value;
		xajax_parcelas(aux, total, fornecedor , data);
	}*/
	
	//alert(total);
	return total;
}



function choiceType(th, ev){
	if(typePeople == 1){
		maskCPF(th, ev);
	}else{
		maskCNPJ(th, ev);
	}
}

function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
	
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
   // alert(whichCode);
    if (whichCode == 13 || whichCode == 8 || whichCode == 37 || whichCode == 39 || whichCode == 46 || whichCode == 9 || whichCode == 0) return true;
    key = String.fromCharCode(whichCode); // Valor para o c�digo da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inv�lida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function MascaraQuantidade(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
	
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
   // alert(whichCode);
    if (whichCode == 13 || whichCode == 8 || whichCode == 37 || whichCode == 39 || whichCode == 46 || whichCode == 9 || whichCode == 0) return true;
    key = String.fromCharCode(whichCode); // Valor para o c�digo da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inv�lida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '000' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + '00' + aux;
    if (len == 3) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 4) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 4) {
        aux2 = '';
        for (j = 0, i = len - 5; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 4, len);
    }
    return false;
}

function valida_cnpj(cnpj)
      {
      var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
      digitos_iguais = 1;
      if (cnpj.length < 14 && cnpj.length < 15)
            return false;
      for (i = 0; i < cnpj.length - 1; i++)
            if (cnpj.charAt(i) != cnpj.charAt(i + 1))
                  {
                  digitos_iguais = 0;
                  break;
                  }
      if (!digitos_iguais)
            {
            tamanho = cnpj.length - 2
            numeros = cnpj.substring(0,tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
                  {
                  soma += numeros.charAt(tamanho - i) * pos--;
                  if (pos < 2)
                        pos = 9;
                  }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                  return false;
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0,tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
                  {
                  soma += numeros.charAt(tamanho - i) * pos--;
                  if (pos < 2)
                        pos = 9;
                  }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                  return false;
            return true;
            }
      else
            return false;
} 

function maskCNPJ(th, ev){
	var valido = Bloqueia_Caracteres(ev);
	if(valido){
		th.style.color = "black";
		campo = th.value;
		var i;
		for (i = 0; i < th.form.elements.length; i++)
			if (th == th.form.elements[i])
				break;
		i = (i + 1) % th.form.elements.length;
		if(campo.length > 13){
			cnpj = campo;
			valido = valida_cnpj(cnpj);
			if(valido){
				th.form.elements[i].value = "CNPJ válido";
				th.form.elements[i].style.color = "green";
				th.form.elements[i+1].focus();
				return false;
			}else{
				th.form.elements[i].value = "CNPJ inválido!";
				th.form.elements[i].style.color = "red";
				th.style.color = "red";
				return false;
			}
		}else{
			th.form.elements[i].value = "";
		}
	}else{
		campo = th.value;
		ult = campo.substr(0,campo.length-1);
		th.value = ult;
	}
}

function maskCPF(th, ev){
	var valido = Bloqueia_Caracteres(ev);
	if(valido){
		th.style.color = "black";
		campo = th.value;
		if(campo.length > 10){
			cpf = campo;
			valido = valida_cpf(cpf);
			if(valido){
				var i;
				for (i = 0; i < th.form.elements.length; i++)
					if (th == th.form.elements[i])
						break;
				i = (i + 1) % th.form.elements.length;
				th.form.elements[i].value = "CPF válido";
				th.form.elements[i].style.color = "green";
				th.form.elements[i+1].focus();
				return false;
			}else{
				var i;
				for (i = 0; i < th.form.elements.length; i++)
					if (th == th.form.elements[i])
						break;
				i = (i + 1) % th.form.elements.length;
				th.form.elements[i].value = "CPF inválido!";
				th.form.elements[i].style.color = "red";
				th.style.color = "red";
				return false;
			}
		}
	}else{
		campo = th.value;
		ult = campo.substr(0,campo.length-1);
		th.value = ult;
	}
}

function checkPassword(th){
	if(th.value != document.getElementById('snh').value){
		var i;
		for (i = 0; i < th.form.elements.length; i++)
			if (th == th.form.elements[i])
				break;
		i = (i + 1) % th.form.elements.length;
		th.form.elements[i].value = "Senha não confere!";
		th.form.elements[i].style.color = "red";
		th.style.color = "red";
		return false;
		
	}
	
}


function textCounter(field, countfield, maxlimit) {
	if (field.value.length > maxlimit)
	field.value = field.value.substring(0, maxlimit);
	else 
	countfield.value = maxlimit - field.value.length;
}

function valida_cpf(cpf)
  {
  var numeros, digitos, soma, i, resultado, digitos_iguais;
  digitos_iguais = 1;
  if (cpf.length < 11)
        return false;
  for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
              {
              digitos_iguais = 0;
              break;
              }
  if (!digitos_iguais)
        {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
              soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
              return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
              soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;
        return true;
        }
  else
        return false;
  }



function number_format (number, decimals, dec_point, thousands_sep) {
   
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}



function handleEnter(field, event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		i = (i + 1) % field.form.elements.length;
		field.form.elements[i].focus();
		return false;
	}else{
		return true;
	}	
}

function parenthesis(input, event){
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	//alert(keyCode);	
	/*if (input.value.length == 1 && keyCode != 8){
		input.value = "("+input.value;
	}*/
	
	if (input.value.length == 2 && keyCode != 8){
		input.value = "("+input.value;
	}
	if (input.value.length == 3 && keyCode != 8){
		input.value = input.value+") ";
	}
	
	if (input.value.length == 9 && keyCode != 8){
		input.value = input.value+"-";
	}
}

//Bloqueio de caracteres permitindo somente num�ricos
function Bloqueia_Caracteres(event){
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	//alert(keyCode);
	if ((keyCode > 47 && keyCode < 58) || (keyCode > 95 && keyCode < 106) || keyCode == 8 || keyCode == 188 ){
 		return keyCode;
 	}else{
 		return false;
 	}
 }
 

 
//Ajusta máscara de Data e só permite digitação de números
function Ajusta_Data(input, event){
	
	document.getElementById('msg').innerHTML = "";
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	//alert(keyCode);
	
	if(keyCode>95)
		keyCode = keyCode-48;
		
	if (input.value.length == 1 && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 51){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			input.value = "";
			return false;
		}	
	}else if (input.value.length == 2 && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 57 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
		if(input.value < 1 ){
			document.getElementById('msg').innerHTML = "Dia inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
		if(input.value > 31 ){
			document.getElementById('msg').innerHTML = "Dia inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 4) && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 49 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,3);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 5) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
		nome = input.value.split(" ");
		pri= nome[0];
	    ult = pri.substr(pri.length-2);
	    if(ult>12 || ult<1){
	    	document.getElementById('msg').innerHTML = "Mês inexistente";
	    	nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
	    }
	    nome = input.value.split(" ");
		pri= nome[0];
		dia = pri.substr(pri.length-5,2);
		mes = pri.substr(pri.length-2);
		if((dia == 31) && (mes==02 || mes==04 || mes==06 || mes==09 || mes==11)){
			document.getElementById('msg').innerHTML = "Mês sem o dia 31";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
		if((dia == 30) && (mes==02)){
			document.getElementById('msg').innerHTML = "Mês sem o dia 30";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 7) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 49 || keyCode > 50 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,6);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 8) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,7);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 9) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,8);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 10) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			document.getElementById('msg').innerHTML = "Caractér inválido";
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,9);
			input.value = ult;
			return false;
		}/*else{
			document.frmbreg.CLN_ATV.focus();
		}*/
	}
	if ((input.value.length == 2 || input.value.length == 5) && (keyCode != 8 && keyCode != 9)){
 		//if(evnt.keyCode == 0){
 			input.value += "/";
 		//}
 	}
 	
}


function Ajusta_DataBorda(input, event){
	
	input.style.borderColor = '#5CACEE';
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	//alert(keyCode);
	if(keyCode>95)
		keyCode = keyCode-48;
		
	if (input.value.length == 1 && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 51){
			input.style.borderColor = 'red';
			input.value = "";
			return false;
		}	
	}else if (input.value.length == 2 && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 57 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
		if(input.value < 1 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
		if(input.value > 31 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(pri.length-2,1);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 4) && (keyCode != 8 && keyCode != 9)){
		if(keyCode < 48 || keyCode > 49 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,3);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 5) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
		nome = input.value.split(" ");
		pri= nome[0];
	    ult = pri.substr(pri.length-2);
	    if(ult>12 || ult<1){
	    	input.style.borderColor = 'red';
	    	nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
	    }
	    nome = input.value.split(" ");
		pri= nome[0];
		dia = pri.substr(pri.length-5,2);
		mes = pri.substr(pri.length-2);
		if((dia == 31) && (mes==02 || mes==04 || mes==06 || mes==09 || mes==11)){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
		if((dia == 30) && (mes==02)){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,4);
			input.value = ult;
			return false;
		}
	}else if ((input.value.length == 7) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 49 || keyCode > 50 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,6);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 8) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,7);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 9) && (keyCode != 8 && keyCode != 9)) {
		if(keyCode < 48 || keyCode > 57 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,8);
			input.value = ult;
		return false;
		}
	}else if ((input.value.length == 10) && (keyCode != 8 && keyCode != 9 && keyCode != 13)) {
		
		if(keyCode < 48 || keyCode > 57 ){
			input.style.borderColor = 'red';
			nome = input.value.split(" ");
			pri= nome[0];
		    ult = pri.substr(0,9);
			input.value = ult;
			return false;
		}/*else{
			document.frmbreg.CLN_ATV.focus();
		}*/
	}else if ((input.value.length == 11) && (keyCode != 8 && keyCode != 9)) {
		nome = input.value.split(" ");
		pri= nome[0];
	    ult = pri.substr(0,10);
		input.value = ult;
		return false;
		
	}
	if ((input.value.length == 2 || input.value.length == 5) && (keyCode != 8 && keyCode != 9)){
 		//if(evnt.keyCode == 0){
 			input.value += "/";
 		//}
 	}
 	
}



function dataInvalida(input, event){
	nome = input.value.split(" ");
	pri= nome[0];
	dia = pri.substr(0,2);
	mes = pri.substr(3,2);
	ano = pri.substr(6,4);
	//alert(input.value);
	if((dia<0 || dia>31 || mes<0 || mes>12 || ano<1000 || ano>2999) && input.value != ""){
		document.getElementById('msg').innerHTML = "Data Inválida";
		input.focus();
	}else{
		document.getElementById('msg').innerHTML = "";
	}
	
}



function tipoAtendimento(tipo){
	
	if(tipo == 3){
		document.forms['frmbreg'].elements['DTA_ITR'].readOnly=false;
	}else{
		document.forms['frmbreg'].elements['DTA_ITR'].readOnly=true;
	}
	document.getElementById('iniciar').style.visibility = 'visible';	
	
}



function dataInvalidaBorda(input, event){
	nome = input.value.split(" ");
	pri= nome[0];
	dia = pri.substr(0,2);
	mes = pri.substr(3,2);
	ano = pri.substr(6,4);
	//alert(input.value);
	if((dia<0 || dia>31 || mes<0 || mes>12 || ano<1000 || ano>2999) && input.value != ""){
		input.style.borderColor = 'red';
		input.focus();
	}else{
		input.style.borderColor = '#5CACEE';
	}
	
}

function Ajusta_CID10(input, evnt){
	if (input.value.length == 3){
 		if(evnt.keyCode == 0){
 			input.value += "-";
 		}
 	}
 	
 	return Bloqueia_Caracteres(evnt);
}

//Ajusta m�scara de Hora e s� permite digita��o de n�meros
function Ajusta_Hora(input, evnt){
	if (input.value.length == 2){
 		//if(evnt.keyCode == 0){
 			input.value += ":";
 		//}
 	}
 	
 	return Bloqueia_Caracteres(evnt);
}	