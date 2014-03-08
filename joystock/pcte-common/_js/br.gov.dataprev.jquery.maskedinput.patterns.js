/**
 * Padrões de máscaras utilizadas pelos projetos da Dataprev.
 * @author Comitê de interfaces (COMINT)
 * @version 1.0
 */

$( document ).ready(
	function()
	{
		jQuery(
			function( $ )
			{
				/* NIT */
				$('input[class*=nitFormat]').mask( "999.99999.99-9" );
				
				

				/* PIS */
				$('input[class*=pisFormat]').mask( "999.99999.99-9" );

				/* PASEP */
				$('input[class*=pasepFormat]').mask( "999.99999.99-9" );

				/* CNPJ */
				$('input[class*=cnpjFormat]').mask( "99.999.999/9999-99" );
				
				/* CNPJ RAIZ */
		       		$('input[class*=cnpjRaizFormat]').mask("99.999.999");

				/* CPF */
				$('input[class*=cpfFormat]').mask( "999.999.999-99" );

				/* CEI */
				$('input[class*=ceiFormat]').mask( "99.999.99999/99" );
				
				/* Competência */
				$('input[class*=competenciaFormat]').mask("99/9999");

				/* Código de Endereçamento Postal (CEP) */
				$('input[class*=cepFormat]').mask( "99999-999" );
				
				/* CNAE */
				$('input[class*=cnaeFormat]').mask( "9999-9?/99" );
				
				/* Cadastro Brasileiro de Ocupações (CBO2002) */
				$('input[class*=cbo2002Format]').mask( "?9999-99" );
				
				/* Cadastro Brasileiro de Ocupações (CBO94) */
				$('input[class*=cbo94Format]').mask( "?*-99.99" );
				
				/* Cadastro Brasileiro de Ocupações (CIUO88) */
				$('input[class*=ciuo88Format]').mask( "?9999" );
				
				/* APS */
				$('input[class*=apsFormat]').mask( "99.999.999" );
				
				/* Número do Benefício */
				$('input[class*=nbFormat]').mask( "999.999.999-9" ); 

				/* DDD */
				$('input[class*=dddFormat]').mask( "99" );

				/* Número telefónico */
				$('input[class*=telefoneFormat]').mask( "9999-9999" );

				/* Ramal */
				$('input[class*=ramalFormat]').mask( "99?99" );

				/* DDD seguido do número telefônico */
				$('input[class*=dddTelefoneFormat]').mask( "(99)9999-9999" );

				/* Data */
				$('input[class*=dataFormat]').mask( "99/99/9999" );

				/* Hora */
				$('input[class*=horaFormat]').mask( "99:99?:99" );
				
				/* Hora2 */
				$('input[class*=hora2Format]').mask( "99:99" );

				/* Data seguido da hora */
				$('input[class*=dataFormat][class*=horaFormat]').unmask().mask( "99/99/9999 99:99" );

				/* Placa de automóvel */
				$('input[class*=placaFormat]').mask( "aaa-9999" );

				/* Convênio do Termo de Adesão */
				$('input[class*=convenioFormat]').mask( "99999.999999/9999-99" );
				
				/* Hora2 */
				$('input[class*=threeFormat]').mask( "999" );
				$('input[class*=fourFormat]').mask( "9999" );
				$('input[class*=fiveFormat]').mask( "99999" );
				$('input[class*=eightFormat]').mask( "99999999" );

				/* Valores em real */
			   	$('input[class*=realFormat]').maskMoney({symbol:"R$",decimal:",",thousands:"."});
			    
			    	/* Valores em moeda */
			   $('input[class*=moedaFormat]').maskMoney({symbol:"",decimal:",",thousands:"."});

			    	/* Valores decimais */
			    $('input[class*=decimal3Format]').maskMoney({symbol:"",decimal:",",thousands:".",precision:3});
			    $('input[class*=decimal4Format]').maskMoney({symbol:"",decimal:",",thousands:".",precision:4});
			}
		);
		/* Valores numéricos */
		$('input[class*=numeroFormat]').validation({ type: "int" });
	}
);