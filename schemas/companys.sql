CREATE TABLE companys (
    id serial primary key, 
    razao_social varchar(255) NOT NULL, 
    cnpj varchar(255) NOT NULL, 
    qnt_funcionarios integer NOT NULL, 
    email varchar(255) NOT NULL, 
    senha varchar(255) NOT NULL, 
    descricao varchar(255),
    data_cadastro timestamp NOT NULL DEFAULT NOW()
);