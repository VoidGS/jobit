CREATE TABLE users (
    id serial primary key, 
    nome varchar(255) NOT NULL, 
    cpf int NOT NULL, 
    email varchar(255) NOT NULL, 
    senha varchar(255) NOT NULL, 
    data_nasc date NOT NULL, 
    area_atuacao int NOT NULL, 
    stacks int[] NOT NULL, 
    pretencao_salarial float NOT NULL, 
    tempo_exp int NOT NULL,
    descricao varchar(255),
    data_cadastro timestamp NOT NULL DEFAULT NOW()
);