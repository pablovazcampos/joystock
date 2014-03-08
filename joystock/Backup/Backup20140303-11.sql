
#
# Criação da Tabela : upcscdc_tb
#

CREATE TABLE `upcscdc_tb` (
  `COD_CDC` int(11) NOT NULL AUTO_INCREMENT,
  `TPO_CDC` varchar(1) NOT NULL,
  `DSC_CDC` varchar(50) NOT NULL,
  `COD_CTT` varchar(20) NOT NULL,
  `CDC_LNC` varchar(1) NOT NULL,
  `PSC_RNK` int(11) NOT NULL,
  `IMG_CDC` varchar(50) NOT NULL,
  PRIMARY KEY (`COD_CDC`),
  UNIQUE KEY `COD_CDC` (`COD_CDC`),
  KEY `DSC_CDC` (`DSC_CDC`),
  KEY `TPO_CDC` (`TPO_CDC`),
  KEY `COD_CTT` (`COD_CTT`),
  KEY `CDC_LNC` (`CDC_LNC`),
  KEY `PSC_RNK` (`PSC_RNK`),
  KEY `IMG_CDC` (`IMG_CDC`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=227 COMMENT='CONTAS' ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcscdc_tb VALUES ( '1', '1', 'CR�DITO', 'root', '1', '0', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '2', '2', 'D�BITO', 'root', '1', '1', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '3', '1', 'VENDA', '1', '1', '0', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '4', '2', 'COMPRA', '2', '1', '0', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '5', '2', 'MASSAS', '4', '0', '0', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '6', '1', 'MASSAS', '3', '0', '0', 'tri.png');
INSERT INTO upcscdc_tb VALUES ( '7', '1', 'Massa Aliment�cia de Milho Tivva', '6', '0', '0', 'square_greenS.gif');
INSERT INTO upcscdc_tb VALUES ( '8', '2', 'Massa Aliment�cia de Milho Tivva', '5', '0', '0', 'square_greenS.gif');
INSERT INTO upcscdc_tb VALUES ( '9', '1', 'PRODUTO TESTE', '6', '0', '0', 'square_greenS.gif');
INSERT INTO upcscdc_tb VALUES ( '10', '2', 'PRODUTO TESTE', '5', '0', '0', 'square_greenS.gif');
INSERT INTO upcscdc_tb VALUES ( '11', '1', 'PRODUTO AGRUPADO', '6', '0', '0', 'square_greenS.gif');
INSERT INTO upcscdc_tb VALUES ( '12', '2', 'PRODUTO AGRUPADO', '5', '0', '0', 'square_greenS.gif');

#
# Criação da Tabela : upcscln_tb
#

CREATE TABLE `upcscln_tb` (
  `COD_CLN` int(11) NOT NULL AUTO_INCREMENT,
  `ATV_CLN` varchar(1) DEFAULT NULL,
  `NME_CLN` varchar(80) NOT NULL,
  `TPO_PSS` varchar(1) NOT NULL,
  `CNJ_CLN` varchar(20) DEFAULT NULL,
  `END_CLN` varchar(80) DEFAULT NULL,
  `NMR_END` varchar(10) DEFAULT NULL,
  `CMP_CLN` varchar(80) DEFAULT NULL,
  `BRR_CLN` varchar(80) DEFAULT NULL,
  `CDD_CLN` varchar(80) DEFAULT NULL,
  `EST_CLN` varchar(2) DEFAULT NULL,
  `CEP_CLN` varchar(10) DEFAULT NULL,
  `TEL_001` varchar(30) DEFAULT NULL,
  `CEL_CLN` varchar(30) DEFAULT NULL,
  `FAX_CLN` varchar(30) DEFAULT NULL,
  `EML_CLN` varchar(80) DEFAULT NULL,
  `DTA_CDS` date NOT NULL,
  `COD_USR` int(11) NOT NULL,
  `ISS_CLN` varchar(1) DEFAULT NULL,
  `ISS_FRN` varchar(1) DEFAULT NULL,
  `COD_GCL` int(11) DEFAULT NULL,
  `OBS_CLN` blob,
  `EXC_UPCSCLN` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_CLN`),
  UNIQUE KEY `COD_CLN` (`COD_CLN`),
  KEY `COD_GCL` (`COD_GCL`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcscln_tb VALUES ( '1', '1', 'Pablo Campos da Silva', '1', '12458778555555', 'RUA MARING�', '301', '', 'BELVEDERE', 'DIVIN�POLIS', '11', '35501-312', '(37)3212-5458', '(37)9958-2320', '(37)3254-5878', 'pablo@gmx.com.br', '2014-02-24', '1', '1', '', '1', '', '');
INSERT INTO upcscln_tb VALUES ( '2', '1', 'EMPRESA DE COSM�TICOS LTDA', '1', '12345678955225', 'RUA ANT�NIO OL�MPIO DE MORAIS', '125', '', 'CENTRO', 'DIVIN�POLIS', '11', '35501-315', '(37)3212-5458', '(37)3255-6558', '', 'cosmeticos@gmail.com', '2014-02-24', '1', '', '1', '0', '', '');

#
# Criação da Tabela : upcsgcl_tb
#

CREATE TABLE `upcsgcl_tb` (
  `COD_GCL` int(11) NOT NULL AUTO_INCREMENT,
  `DSC_GCL` varchar(80) NOT NULL,
  `EXC_UPCSGCL` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_GCL`),
  UNIQUE KEY `COD_GCL` (`COD_GCL`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsgcl_tb VALUES ( '1', 'VAREJO', '');
INSERT INTO upcsgcl_tb VALUES ( '2', 'ATACADO', '');

#
# Criação da Tabela : upcsgpp_tb
#

CREATE TABLE `upcsgpp_tb` (
  `COD_GPP` int(11) NOT NULL AUTO_INCREMENT,
  `DSC_GPP` varchar(80) NOT NULL,
  `COD_CRD` int(11) DEFAULT NULL,
  `COD_DBT` int(11) DEFAULT NULL,
  `EXC_UPCSGPP` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_GPP`),
  UNIQUE KEY `COD_GPP` (`COD_GPP`),
  UNIQUE KEY `COD_CRD` (`COD_CRD`),
  UNIQUE KEY `COD_DBT` (`COD_DBT`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsgpp_tb VALUES ( '1', 'MASSAS', '6', '5', '');

#
# Criação da Tabela : upcsgus_tb
#

CREATE TABLE `upcsgus_tb` (
  `COD_GUS` int(11) NOT NULL AUTO_INCREMENT,
  `NME_GUS` varchar(80) DEFAULT NULL,
  `EXC_UPCSGUS` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_GUS`),
  UNIQUE KEY `COD_GUS` (`COD_GUS`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsgus_tb VALUES ( '1', 'Administrador', '');

#
# Criação da Tabela : upcsguu_tb
#

CREATE TABLE `upcsguu_tb` (
  `COD_GUS` int(11) NOT NULL,
  `COD_USR` int(11) NOT NULL,
  KEY `COD_GUS` (`COD_GUS`),
  KEY `COD_USR` (`COD_USR`),
  CONSTRAINT `upcsguu_tb_fk` FOREIGN KEY (`COD_GUS`) REFERENCES `upcsgus_tb` (`COD_GUS`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsguu_tb_fk1` FOREIGN KEY (`COD_USR`) REFERENCES `upcsusr_tb` (`COD_USR`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsguu_tb VALUES ( '1', '1');
INSERT INTO upcsguu_tb VALUES ( '1', '2');

#
# Criação da Tabela : upcskit_tb
#

CREATE TABLE `upcskit_tb` (
  `COD_KIT` int(11) NOT NULL AUTO_INCREMENT,
  `DSC_KIT` varchar(100) NOT NULL,
  `EXC_UPCSKIT` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_KIT`),
  UNIQUE KEY `COD_KIT` (`COD_KIT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#


#
# Criação da Tabela : upcskpr_tb
#

CREATE TABLE `upcskpr_tb` (
  `COD_KIT` int(11) NOT NULL,
  `COD_PRD` int(11) NOT NULL,
  `QTD_PRD` decimal(11,4) DEFAULT NULL,
  KEY `COD_KIT` (`COD_KIT`),
  KEY `COD_PRD` (`COD_PRD`),
  CONSTRAINT `upcskpr_tb_fk1` FOREIGN KEY (`COD_PRD`) REFERENCES `upcsprd_tb` (`COD_PRD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcskpr_tb VALUES ( '3', '1', '1.0000');
INSERT INTO upcskpr_tb VALUES ( '3', '2', '1.0000');

#
# Criação da Tabela : upcsmcc_tb
#

CREATE TABLE `upcsmcc_tb` (
  `COD_MCC` int(11) NOT NULL AUTO_INCREMENT,
  `TPO_MVM` varchar(1) NOT NULL,
  `COD_CDC` int(11) NOT NULL,
  `VLR_MVM` float(9,2) NOT NULL,
  `DTA_CDS` date NOT NULL,
  `EXC_UPCSMCC` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_MCC`),
  UNIQUE KEY `COD_MCC` (`COD_MCC`),
  KEY `COD_CDC` (`COD_CDC`),
  KEY `TPO_MVM` (`TPO_MVM`),
  KEY `VLR_MVM` (`VLR_MVM`),
  CONSTRAINT `upcsmcc_tb_fk` FOREIGN KEY (`COD_CDC`) REFERENCES `upcscdc_tb` (`COD_CDC`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='MOVIMENTA��O DAS CONTAS' ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsmcc_tb VALUES ( '1', '1', '8', '450.00', '2014-02-24', '');

#
# Criação da Tabela : upcsmmn_tb
#

CREATE TABLE `upcsmmn_tb` (
  `COD_MMN` int(11) NOT NULL AUTO_INCREMENT,
  `COD_PRD` int(11) NOT NULL,
  `COD_USR` int(11) NOT NULL,
  `DTA_CDS` date NOT NULL,
  `DTA_MMN` date NOT NULL,
  `OBS_MMN` blob,
  `QTD_PRD` decimal(11,4) DEFAULT NULL,
  `TPO_MMN` varchar(1) NOT NULL,
  `EXC_UPCSMMN` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_MMN`),
  UNIQUE KEY `COD_MMN` (`COD_MMN`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsmmn_tb VALUES ( '1', '1', '2', '2014-02-24', '2014-02-24', 'teste', '50.0000', '2', '');

#
# Criação da Tabela : upcsmnu_tb
#

CREATE TABLE `upcsmnu_tb` (
  `COD_MNU` int(11) NOT NULL AUTO_INCREMENT,
  `NME_MNU` varchar(80) NOT NULL,
  `PAI_MNU` varchar(80) DEFAULT NULL,
  `IDN_MNU` varchar(80) DEFAULT NULL,
  `ACT_MNU` varchar(150) DEFAULT NULL,
  `IMG_MNU` varchar(150) DEFAULT NULL,
  `IFR_MNU` varchar(3) DEFAULT NULL,
  `ORD_MNU` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`COD_MNU`),
  UNIQUE KEY `COD_MNU` (`COD_MNU`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=910 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsmnu_tb VALUES ( '1', 'Sair', 'root', 'sair', 'javascript:location.href=\"../login.php?new=1\"', 'sair.png', '', '99');
INSERT INTO upcsmnu_tb VALUES ( '2', 'Cadastros', 'root', 'cadastros', 'javascript:return false;', 'oDrafts.gif', '', '1');
INSERT INTO upcsmnu_tb VALUES ( '3', 'Configura��o', 'root', 'configuracao', 'javascript:return false;', 'config.png', '', '4');
INSERT INTO upcsmnu_tb VALUES ( '4', 'Produtos', 'cadastros', 'produtos', 'javascript:return false;', 'produtos.png', '', '4');
INSERT INTO upcsmnu_tb VALUES ( '5', 'Clientes', 'cadastros', 'clientes', '', 'clientes.png', '2', '2');
INSERT INTO upcsmnu_tb VALUES ( '6', 'Usu�rios', 'cadastros', 'usuarios', '', 'kdmconfig.png', '5', '9');
INSERT INTO upcsmnu_tb VALUES ( '7', 'Permiss�es', 'configuracao', 'permissoes', '', 'cad.png', '4', '7');
INSERT INTO upcsmnu_tb VALUES ( '8', 'Fornecedores', 'cadastros', 'fornecedores', '', 'personal.png', '1', '3');
INSERT INTO upcsmnu_tb VALUES ( '9', 'Categorias de Produtos', 'cadastros', 'grupoProdutos', '', 'Pixadex.png', '6', '5');
INSERT INTO upcsmnu_tb VALUES ( '10', 'Unidade de Medida', 'cadastros', 'unidadeMedida', '', 'unidadeMedida.png', '7', '8');
INSERT INTO upcsmnu_tb VALUES ( '11', 'Produtos Simples', 'produtos', 'produtosSimples', '', 'produtos.png', '3', '5');
INSERT INTO upcsmnu_tb VALUES ( '12', 'Produtos Agrupados', 'produtos', 'produtosAgrupados', '', 'agrupados.png', '8', '6');
INSERT INTO upcsmnu_tb VALUES ( '13', 'Movimenta��o', 'root', 'movimentacao', 'javascript:return false;', 'caixa.png', '', '2');
INSERT INTO upcsmnu_tb VALUES ( '14', 'Compra', 'movimentacao', 'compra', '', 'compra.png', '9', '3');
INSERT INTO upcsmnu_tb VALUES ( '15', 'Venda', 'movimentacao', 'venda', '', 'dinheiro.png', '10', '4');
INSERT INTO upcsmnu_tb VALUES ( '16', 'Pesquisa', 'movimentacao', 'pesq', '', 'xpLens.gif', '12', '5');
INSERT INTO upcsmnu_tb VALUES ( '17', 'Grupo de Clientes', 'cadastros', 'grupoClientes', '', 'grupoClientes.png', '11', '6');
INSERT INTO upcsmnu_tb VALUES ( '18', 'Fluxo de Caixa', 'root', 'fluxoCaixa', '', 'xpLens.gif', '13', '7');
INSERT INTO upcsmnu_tb VALUES ( '19', 'Relat�rios', 'root', 'relatorios', 'javascript:return false;', 'ordens.png', '', '8');
INSERT INTO upcsmnu_tb VALUES ( '20', 'Vendas', 'relatorios', 'vendas', 'javascript:return false;', 'comercial.png', '', '9');
INSERT INTO upcsmnu_tb VALUES ( '21', 'Invent�rio', 'relatorios', 'inventario', '', 'oInboxF.gif', '15', '94');
INSERT INTO upcsmnu_tb VALUES ( '22', 'Agregado', 'vendas', 'agregado', '', 'novos.png', '14', '9');
INSERT INTO upcsmnu_tb VALUES ( '23', 'Detalhado', 'vendas', 'detalhado', '', 'oDrafts.gif', '16', '9');
INSERT INTO upcsmnu_tb VALUES ( '24', 'Movimenta��o Manual', 'movimentacao', 'movimentacaManual', '', 'caixa.png', '17', '4');
INSERT INTO upcsmnu_tb VALUES ( '25', 'Compras', 'relatorios', 'compras', 'javascript:return false;', 'page.gif', '', '91');
INSERT INTO upcsmnu_tb VALUES ( '26', 'Agregado', 'compras', 'comprasAgregado', '', 'novos.png', '18', '92');
INSERT INTO upcsmnu_tb VALUES ( '27', 'Detalhado', 'compras', 'comprasDetalhado', '', 'oDrafts.gif', '19', '93');

#
# Criação da Tabela : upcsmpr_tb
#

CREATE TABLE `upcsmpr_tb` (
  `COD_MVM` int(11) NOT NULL,
  `COD_PRD` int(11) NOT NULL,
  `QTD_PRD` float(9,4) NOT NULL,
  `VLR_DPR` float(9,2) DEFAULT NULL,
  `VLR_TPR` float(9,2) DEFAULT NULL,
  `TPO_MVM` varchar(1) NOT NULL,
  `VLR_UNT` float(9,2) NOT NULL,
  `COD_MCC` int(11) DEFAULT NULL,
  `HST_PRD` varchar(100) DEFAULT NULL,
  KEY `COD_MVM` (`COD_MVM`),
  KEY `COD_PRD` (`COD_PRD`),
  CONSTRAINT `upcsmpr_tb_fk` FOREIGN KEY (`COD_MVM`) REFERENCES `upcsmvm_tb` (`COD_MVM`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsmpr_tb_fk1` FOREIGN KEY (`COD_PRD`) REFERENCES `upcsprd_tb` (`COD_PRD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PRODUTOS DA MOVIMENTA��O' ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsmpr_tb VALUES ( '1', '1', '450.0000', '0.00', '450.00', '1', '1.00', '1', '');

#
# Criação da Tabela : upcsmvm_tb
#

CREATE TABLE `upcsmvm_tb` (
  `COD_MVM` int(11) NOT NULL AUTO_INCREMENT,
  `TPO_MVM` varchar(1) NOT NULL,
  `COD_CLN` int(11) NOT NULL,
  `FRM_PGT` varchar(1) DEFAULT NULL,
  `TPO_VND` varchar(1) DEFAULT NULL,
  `DTA_MVM` date NOT NULL,
  `HRA_EMS` time NOT NULL,
  `DTA_EMS` date NOT NULL,
  `COD_USR` int(11) NOT NULL,
  `VLR_DSC` float(9,2) DEFAULT NULL,
  `VLR_TTL` float(9,2) DEFAULT NULL,
  `EXC_UPCSMVM` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_MVM`),
  UNIQUE KEY `COD_MVM` (`COD_MVM`),
  KEY `COD_CLN` (`COD_CLN`),
  KEY `COD_USR` (`COD_USR`),
  KEY `TPO_MVM` (`TPO_MVM`),
  KEY `FRM_PGT` (`FRM_PGT`),
  KEY `TPO_VND` (`TPO_VND`),
  KEY `DTA_MVM` (`DTA_MVM`),
  KEY `VLR_TTL` (`VLR_TTL`),
  CONSTRAINT `upcsmvm_tb_fk` FOREIGN KEY (`COD_CLN`) REFERENCES `upcscln_tb` (`COD_CLN`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsmvm_tb_fk1` FOREIGN KEY (`COD_USR`) REFERENCES `upcsusr_tb` (`COD_USR`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=862 COMMENT='MOVIMENTA��O' ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsmvm_tb VALUES ( '1', '1', '2', '1', '', '2014-02-24', '15:43:11', '2014-02-24', '1', '0.00', '450.00', '');

#
# Criação da Tabela : upcspcs_tb
#

CREATE TABLE `upcspcs_tb` (
  `COD_PRD` int(11) NOT NULL,
  `COD_CLN` int(11) NOT NULL,
  `PRC_CST` decimal(11,2) DEFAULT NULL,
  UNIQUE KEY `COD_CLN_COD_PRD` (`COD_CLN`,`COD_PRD`),
  KEY `COD_CLN` (`COD_CLN`),
  KEY `COD_PRD` (`COD_PRD`),
  CONSTRAINT `upcspcs_tb_fk1` FOREIGN KEY (`COD_PRD`) REFERENCES `upcsprd_tb` (`COD_PRD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

#
# Dados a serem incluídos na tabela
#


#
# Criação da Tabela : upcsprd_tb
#

CREATE TABLE `upcsprd_tb` (
  `COD_PRD` int(11) NOT NULL AUTO_INCREMENT,
  `NMR_PRD` varchar(50) DEFAULT NULL,
  `NME_PRD` varchar(80) NOT NULL,
  `DSC_PRD` blob,
  `STS_PRD` varchar(1) NOT NULL,
  `PRC_VND` decimal(11,2) DEFAULT NULL,
  `QTD_PRD` decimal(11,4) DEFAULT NULL,
  `COD_GPP` int(11) NOT NULL,
  `OBS_PRD` blob,
  `COD_UMD` int(11) NOT NULL,
  `COD_BRR` varchar(80) DEFAULT NULL,
  `TPO_PRD` varchar(1) DEFAULT NULL,
  `COD_CRD` int(11) DEFAULT NULL,
  `COD_DBT` int(11) DEFAULT NULL,
  `EXC_UPCSPRD` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_PRD`),
  UNIQUE KEY `COD_PRD` (`COD_PRD`),
  UNIQUE KEY `COD_CRD` (`COD_CRD`),
  UNIQUE KEY `COD_DBT` (`COD_DBT`),
  KEY `NME_PRD` (`NME_PRD`),
  KEY `STS_PRD` (`STS_PRD`),
  KEY `PRC_VND` (`PRC_VND`),
  KEY `QTD_PDT` (`QTD_PRD`),
  KEY `COD_GPP` (`COD_GPP`),
  KEY `COD_UMD` (`COD_UMD`),
  CONSTRAINT `upcsprd_tb_fk` FOREIGN KEY (`COD_GPP`) REFERENCES `upcsgpp_tb` (`COD_GPP`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsprd_tb_fk1` FOREIGN KEY (`COD_UMD`) REFERENCES `upcsumd_tb` (`COD_UMD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsprd_tb VALUES ( '1', '', 'Massa Aliment�cia de Milho Tivva', 'Massa alimentícia de milho com quinua tipo fusilli. Sem glúten, colesterol e gordura trans. 0% de Sódio.', '1', '0.00', '399.0000', '1', '', '1', '123456', '1', '7', '8', '');
INSERT INTO upcsprd_tb VALUES ( '2', '', 'PRODUTO TESTE', '', '1', '0.00', '0.0000', '1', '', '2', '42342342', '1', '9', '10', '');
INSERT INTO upcsprd_tb VALUES ( '3', '', 'PRODUTO AGRUPADO', '', '1', '0.00', '0.0000', '1', '', '2', '123456', '2', '0', '0', '');

#
# Criação da Tabela : upcsprm_tb
#

CREATE TABLE `upcsprm_tb` (
  `COD_GUS` int(11) NOT NULL,
  `COD_MNU` int(11) DEFAULT NULL,
  UNIQUE KEY `COD_GUS_COD_MNU` (`COD_GUS`,`COD_MNU`),
  KEY `COD_GUS` (`COD_GUS`),
  KEY `COD_MNU` (`COD_MNU`),
  CONSTRAINT `upcsprm_tb_fk` FOREIGN KEY (`COD_GUS`) REFERENCES `upcsgus_tb` (`COD_GUS`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsprm_tb_fk1` FOREIGN KEY (`COD_MNU`) REFERENCES `upcsmnu_tb` (`COD_MNU`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsprm_tb VALUES ( '1', '1');
INSERT INTO upcsprm_tb VALUES ( '1', '2');
INSERT INTO upcsprm_tb VALUES ( '1', '3');
INSERT INTO upcsprm_tb VALUES ( '1', '4');
INSERT INTO upcsprm_tb VALUES ( '1', '5');
INSERT INTO upcsprm_tb VALUES ( '1', '6');
INSERT INTO upcsprm_tb VALUES ( '1', '7');
INSERT INTO upcsprm_tb VALUES ( '1', '8');
INSERT INTO upcsprm_tb VALUES ( '1', '9');
INSERT INTO upcsprm_tb VALUES ( '1', '10');
INSERT INTO upcsprm_tb VALUES ( '1', '11');
INSERT INTO upcsprm_tb VALUES ( '1', '12');
INSERT INTO upcsprm_tb VALUES ( '1', '13');
INSERT INTO upcsprm_tb VALUES ( '1', '14');
INSERT INTO upcsprm_tb VALUES ( '1', '15');
INSERT INTO upcsprm_tb VALUES ( '1', '16');
INSERT INTO upcsprm_tb VALUES ( '1', '17');
INSERT INTO upcsprm_tb VALUES ( '1', '18');
INSERT INTO upcsprm_tb VALUES ( '1', '19');
INSERT INTO upcsprm_tb VALUES ( '1', '20');
INSERT INTO upcsprm_tb VALUES ( '1', '21');
INSERT INTO upcsprm_tb VALUES ( '1', '22');
INSERT INTO upcsprm_tb VALUES ( '1', '23');
INSERT INTO upcsprm_tb VALUES ( '1', '24');
INSERT INTO upcsprm_tb VALUES ( '1', '25');
INSERT INTO upcsprm_tb VALUES ( '1', '26');
INSERT INTO upcsprm_tb VALUES ( '1', '27');

#
# Criação da Tabela : upcspvn_tb
#

CREATE TABLE `upcspvn_tb` (
  `COD_PRD` int(11) NOT NULL,
  `COD_GCL` int(11) NOT NULL,
  `PRC_VEN` decimal(11,2) DEFAULT NULL,
  UNIQUE KEY `COD_PRD_COD_GCL` (`COD_PRD`,`COD_GCL`),
  KEY `COD_PRD` (`COD_PRD`),
  KEY `COD_GCL` (`COD_GCL`),
  CONSTRAINT `upcspvn_tb_fk` FOREIGN KEY (`COD_GCL`) REFERENCES `upcsgcl_tb` (`COD_GCL`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcspvn_tb_fk1` FOREIGN KEY (`COD_PRD`) REFERENCES `upcsprd_tb` (`COD_PRD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcspvn_tb VALUES ( '1', '1', '40.00');

#
# Criação da Tabela : upcsumd_tb
#

CREATE TABLE `upcsumd_tb` (
  `COD_UMD` int(11) NOT NULL AUTO_INCREMENT,
  `DSC_UMD` varchar(80) NOT NULL,
  `DSC_RSM` varchar(3) NOT NULL,
  `EXC_UPCSUMD` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_UMD`),
  UNIQUE KEY `COD_UMD` (`COD_UMD`),
  KEY `DSC_UMD` (`DSC_UMD`),
  KEY `EXC_UPCSUMD` (`EXC_UPCSUMD`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsumd_tb VALUES ( '1', 'UNIDADE', 'UND', '');
INSERT INTO upcsumd_tb VALUES ( '2', 'CAIXA', 'CX', '');

#
# Criação da Tabela : upcsusr_tb
#

CREATE TABLE `upcsusr_tb` (
  `COD_USR` int(11) NOT NULL AUTO_INCREMENT,
  `NME_USR` varchar(80) NOT NULL,
  `EML_USR` varchar(80) NOT NULL,
  `SNH_USR` varchar(100) DEFAULT NULL,
  `ATV_USR` varchar(1) NOT NULL,
  `EXC_UPCSUSR` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_USR`),
  UNIQUE KEY `COD_USR` (`COD_USR`),
  UNIQUE KEY `EML_USR` (`EML_USR`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 ;

#
# Dados a serem incluídos na tabela
#

INSERT INTO upcsusr_tb VALUES ( '1', 'Marcelo Carvalho', 'pablo', 'e10adc3949ba59abbe56e057f20f883e', '1', '');
INSERT INTO upcsusr_tb VALUES ( '2', 'Amanda Tassia', 'amanda', 'e10adc3949ba59abbe56e057f20f883e', '1', '');
