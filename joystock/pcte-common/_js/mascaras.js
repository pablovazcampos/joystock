function Mascara(objeto, evt, mask) {
 

var LetrasU = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var LetrasL = 'abcdefghijklmnopqrstuvwxyz';
var Letras  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
var Numeros = '0123456789';
var Fixos  = '$().-:/ ';
var Charset = " !\"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_/`abcdefghijklmnopqrstuvwxyz{|}~çÇ";
var dvAgencia = 'xX0123456789'; //alterado - Italo

evt = (evt) ? evt : (window.event) ? window.event : "";
var value = objeto.value;

if (evt) {
 
 var ntecla = (evt.which) ? evt.which : evt.keyCode;
 tecla = Charset.substr(ntecla - 32, 1);
 if (ntecla < 32) return true;

 var tamanho = value.length;
 if (tamanho >= mask.length) return false;

 var pos = mask.substr(tamanho,1);
 while (Fixos.indexOf(pos) != -1) {
  value += pos;
  tamanho = value.length;
  if (tamanho >= mask.length) return false;
  pos = mask.substr(tamanho,1);
 }

 switch (pos) {
   case '#' : if (Numeros.indexOf(tecla) == -1) return false; break;
   case 'A' : if (LetrasU.indexOf(tecla) == -1) return false; break;
   case 'a' : if (LetrasL.indexOf(tecla) == -1) return false; break;
   case 'Z' : if (Letras.indexOf(tecla) == -1) return false; break;
   case 'D' : if (dvAgencia.indexOf(tecla) == -1) return false; break; //alterado - Italo
   case '*' : objeto.value = value; return true; break;
   default : return false; break;
 }
}
objeto.value = value;
return true;
}

function MaskDDD(objeto,evt){
	return Mascara(objeto, evt, '##');
}

function MaskCNPJ(objeto, evt) {
	return Mascara(objeto, evt, '##.###.###/####-##');
}

function MaskCEP(objeto, evt) {
	return Mascara(objeto, evt, '#####-###');
}

function MaskTelefone(objeto, evt) {
	return Mascara(objeto, evt, '####-####');
}

function MaskCPF(objeto, evt) {
	return Mascara(objeto, evt, '###.###.###-##');
}

function MaskNIT(objeto, evt) {
	return Mascara(objeto, evt, '###.#####.##-#');
}

function MaskAGENCIA(objeto, evt) {
	return Mascara(objeto, evt, '####-#');
}

function MaskRG(objeto, evt) {
	return Mascara(objeto, evt, '###########');
}

function MaskORGAOEMISSOR(objeto, evt) {
	return Mascara(objeto, evt, 'ZZZ-ZZ');
}

function MaskValorRecursoXXX(objeto, evt) {
	return Mascara(objeto, evt, '*.##');
}

function MaskContaCorrente(objeto, evt){
	return Mascara(objeto, evt, '####DD' );
}

function MaskNumero(objeto, evt){
	return Mascara(objeto, evt, '#####' );
}

function MaskAno(objeto, evt){
	return Mascara(objeto, evt, '####' );
}

function MaskNumeroRamal(objeto, evt){
	return Mascara(objeto, evt, '############' );
}

function MaskNitSomenteNumero(objeto, evt){
	return Mascara(objeto, evt, '###########' );
}
function MaskCNPJSomenteNumero(objeto, evt){
	return Mascara(objeto, evt, '##############' );
}