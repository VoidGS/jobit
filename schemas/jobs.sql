CREATE TABLE jobs (
    id serial primary key, 
    id_empresa int NOT NULL,
    titulo varchar(255) NOT NULL, 
    descricao varchar(255) NOT NULL, 
    salario float NOT NULL, 
    stacks integer[] NOT NULL, 
    tempo_exp int NOT NULL,
    tipo_contrato int NOT NULL, 
    remoto int NOT NULL, 
    status int NOT NULL DEFAULT 1,
    data_cadastro timestamp NOT NULL DEFAULT NOW()
);