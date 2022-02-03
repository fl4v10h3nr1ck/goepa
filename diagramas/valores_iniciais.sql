
INSERT INTO `enderecos` (id_endereco,logradouro,numero,cidade,uf,bairro,cep,complemento) 
VALUES 
(1, 'Av. Senador Lemos', '3761', 'Belém', 'PA', 'Sacramenta', "66120-000", "");

INSERT INTO `empresas` (id_empresa, fk_endereco, razao_social, nome_fantasia, status, fone_1, fone_2, email, site, cnpj, data_cadastro) 
VALUES (1, 1, 'MSC Solucoes MEI', 'MSC Solucoes', 1, '(91) 99293-4270', null, "contato@mscsolucoes.com.br", "www.mscsolucoes.com.br", "123.456.789/0000-01", "2018-04-13");

INSERT INTO `modulos` (`nome`, `codigo`, status) VALUES('Geral', '1', 1);

INSERT INTO `modulos_permitidos` (fk_empresa, fk_modulo) VALUES(1, 1);

INSERT INTO `empresas_modulos` (fk_empresa, fk_modulo) VALUES(1, 1);

INSERT INTO `usuarios` (senha,status,nome_completo,cpf,tel,email,data_cadastro,token) 
VALUES
('8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, "Administrador", "992.143.342-34", '(91) 99293-4270', "contato@mscsolucoes.com.br", "2018-04-13", null);

INSERT INTO grupos_de_usuarios (id_grupo_de_usuario,nome,codigo,descricao,status) VALUES (1, 'ADMINS', 'ADMINS', "Grupo de administradores (superusuários do sistema)", 1);

INSERT INTO usuarios_grupos (fk_usuario, fk_grupo)  VALUES (1,1);

INSERT INTO usuarios_modulos (fk_usuario, fk_modulo)  VALUES (1,1);


INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Realizar login no sistema', 				2, 	'LGNUSR', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Gestão de sua conta de usuário pessoal', 	1, 	'GSSUSR', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Gestão geral de contas de usuários', 		1, 	'GEGUSR', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Gestão de grupos de usuários', 			1, 	'GEGGRP', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Vincular usuários a grupos', 				2, 	'VINUSR', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Adicionar permissão a grupos', 			2, 	'ADDPMS', 1);
INSERT INTO acessos (fk_modulo, nome, tipo, codigo, ordem) VALUES (NULL, 'Configurações do sistema', 				1, 	'CFGSIS', 1);










