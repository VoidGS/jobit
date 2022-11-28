-- status 1 = pendente, 2 = rejeitado, 3 = aceito

CREATE TABLE applications (
    id serial primary key, 
    id_job int NOT NULL, 
    id_user int NOT NULL, 
    status int NOT NULL DEFAULT 1,
    data_cadastro timestamp NOT NULL DEFAULT NOW()
);