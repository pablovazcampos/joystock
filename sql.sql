
SETyyyyyy FOREIGN_KEY_CHECKS=0;

USE `divinopolispla1`;

#
# Structure for the `upcscln_tb` table : 
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
  `EXC_UPCSCLN` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_CLN`),
  UNIQUE KEY `COD_CLN` (`COD_CLN`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#
# Structure for the `upcsfnc_tb` table : 
#

CREATE TABLE `upcsfnc_tb` (
  `COD_FNC` int(11) NOT NULL AUTO_INCREMENT,
  `CPF_FNC` varchar(11) NOT NULL,
  `NME_FNC` varchar(80) NOT NULL COMMENT 'Nome do funcionário',
  `END_FNC` varchar(80) DEFAULT NULL,
  `BRR_FNC` varchar(80) DEFAULT NULL,
  `CDD_FNC` varchar(80) DEFAULT NULL,
  `CEP_FNC` varchar(8) DEFAULT NULL,
  `EML_FNC` varchar(90) DEFAULT NULL,
  `UFD_FNC` varchar(2) DEFAULT NULL,
  `TLF_FNC` varchar(20) DEFAULT NULL,
  `CLL_FNC` varchar(20) DEFAULT NULL,
  `OBS_FNC` varchar(120) DEFAULT NULL,
  `EXC_UPCSFNC` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_FNC`),
  KEY `CPF_FNC` (`CPF_FNC`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#
# Structure for the `upcsgpp_tb` table : 
#

CREATE TABLE `upcsgpp_tb` (
  `COD_GPP` int(11) NOT NULL AUTO_INCREMENT,
  `DSC_GPP` varchar(80) NOT NULL,
  `EXC_UPCSGPP` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_GPP`),
  UNIQUE KEY `COD_GPP` (`COD_GPP`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#
# Structure for the `upcsgus_tb` table : 
#

CREATE TABLE `upcsgus_tb` (
  `COD_GUS` int(11) NOT NULL AUTO_INCREMENT,
  `NME_GUS` varchar(80) DEFAULT NULL,
  `EXC_UPCSGUS` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_GUS`),
  UNIQUE KEY `COD_GUS` (`COD_GUS`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;

#
# Structure for the `upcsusr_tb` table : 
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;

#
# Structure for the `upcsguu_tb` table : 
#

CREATE TABLE `upcsguu_tb` (
  `COD_GUS` int(11) NOT NULL,
  `COD_USR` int(11) NOT NULL,
  KEY `COD_GUS` (`COD_GUS`),
  KEY `COD_USR` (`COD_USR`),
  CONSTRAINT `upcsguu_tb_fk` FOREIGN KEY (`COD_GUS`) REFERENCES `upcsgus_tb` (`COD_GUS`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsguu_tb_fk1` FOREIGN KEY (`COD_USR`) REFERENCES `upcsusr_tb` (`COD_USR`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `upcsmnu_tb` table : 
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=910;

#
# Structure for the `upcsumd_tb` table : 
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

#
# Structure for the `upcsprd_tb` table : 
#

CREATE TABLE `upcsprd_tb` (
  `COD_PRD` int(11) NOT NULL AUTO_INCREMENT,
  `NME_PRD` varchar(80) NOT NULL,
  `DSC_PRD` blob,
  `STS_PRD` varchar(1) NOT NULL,
  `PRC_VND` decimal(11,2) DEFAULT NULL,
  `QTD_PRD` decimal(11,4) DEFAULT NULL,
  `COD_GPP` int(11) NOT NULL,
  `OBS_PRD` blob,
  `COD_UMD` int(11) NOT NULL,
  `EXC_UPCSPRD` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`COD_PRD`),
  UNIQUE KEY `COD_PRD` (`COD_PRD`),
  KEY `NME_PRD` (`NME_PRD`),
  KEY `STS_PRD` (`STS_PRD`),
  KEY `PRC_VND` (`PRC_VND`),
  KEY `QTD_PDT` (`QTD_PRD`),
  KEY `COD_GPP` (`COD_GPP`),
  KEY `COD_UMD` (`COD_UMD`),
  CONSTRAINT `upcsprd_tb_fk` FOREIGN KEY (`COD_GPP`) REFERENCES `upcsgpp_tb` (`COD_GPP`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsprd_tb_fk1` FOREIGN KEY (`COD_UMD`) REFERENCES `upcsumd_tb` (`COD_UMD`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#
# Structure for the `upcsprm_tb` table : 
#

CREATE TABLE `upcsprm_tb` (
  `COD_GUS` int(11) NOT NULL,
  `COD_MNU` int(11) DEFAULT NULL,
  UNIQUE KEY `COD_GUS_COD_MNU` (`COD_GUS`,`COD_MNU`),
  KEY `COD_GUS` (`COD_GUS`),
  KEY `COD_MNU` (`COD_MNU`),
  CONSTRAINT `upcsprm_tb_fk` FOREIGN KEY (`COD_GUS`) REFERENCES `upcsgus_tb` (`COD_GUS`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `upcsprm_tb_fk1` FOREIGN KEY (`COD_MNU`) REFERENCES `upcsmnu_tb` (`COD_MNU`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for the `upcscln_tb` table  (LIMIT 0,500)
#





#
# Data for the `upcsgus_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsgus_tb` (`COD_GUS`, `NME_GUS`, `EXC_UPCSGUS`) VALUES 
  (1,'Administrador',NULL);
COMMIT;

#
# Data for the `upcsusr_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsusr_tb` (`COD_USR`, `NME_USR`, `EML_USR`, `SNH_USR`, `ATV_USR`, `EXC_UPCSUSR`) VALUES 
  (1,'Mauro Henrique','mauro@viaeuro.com.br',NULL,'1',NULL),
  (2,'Pablo Campos','pablo@gmx.com.br','e10adc3949ba59abbe56e057f20f883e','1',NULL);
COMMIT;

#
# Data for the `upcsguu_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsguu_tb` (`COD_GUS`, `COD_USR`) VALUES 
  (1,1),
  (1,2);
COMMIT;

#
# Data for the `upcsmnu_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsmnu_tb` (`COD_MNU`, `NME_MNU`, `PAI_MNU`, `IDN_MNU`, `ACT_MNU`, `IMG_MNU`, `IFR_MNU`, `ORD_MNU`) VALUES 
  (1,'Sair','root','sair','javascript:location.href=\"../login.php?new=1\"','sair.png',NULL,'99'),
  (2,'Cadastros','root','cadastros','javascript:return false;','oDrafts.gif',NULL,'1'),
  (3,'Configuração','root','configuracao','javascript:return false;','config.png',NULL,'4'),
  (4,'Produtos','cadastros','produtos',NULL,'produtos.png','3','4'),
  (5,'Clientes','cadastros','clientes',NULL,'clientes.png','2','2'),
  (6,'Usuários','cadastros','usuarios',NULL,'kdmconfig.png','5','8'),
  (7,'Permissões','configuracao','permissoes',NULL,'cad.png','4','6'),
  (8,'Fornecedores','cadastros','fornecedores',NULL,'personal.png','1','3'),
  (9,'Grupo de Produtos','cadastros','grupoProdutos',NULL,'Pixadex.png','6','5'),
  (10,'Unidade de Medida','cadastros','unidadeMedida',NULL,'unidadeMedida.png','7','7');
COMMIT;

#
# Data for the `upcsumd_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsumd_tb` (`COD_UMD`, `DSC_UMD`, `DSC_RSM`, `EXC_UPCSUMD`) VALUES 
  (1,'UNIDADE','UND',NULL);
COMMIT;



#
# Data for the `upcsprm_tb` table  (LIMIT 0,500)
#

INSERT INTO `upcsprm_tb` (`COD_GUS`, `COD_MNU`) VALUES 
  (1,1),
  (1,2),
  (1,3),
  (1,4),
  (1,5),
  (1,6),
  (1,7),
  (1,8),
  (1,9),
  (1,10);
COMMIT;



teste