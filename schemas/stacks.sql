CREATE TABLE stacks (
    id serial primary key,
    stack varchar(255) NOT NULL,
    status int NOT NULL DEFAULT 1,
    data_cadastro timestamp NOT NULL DEFAULT NOW()
);

INSERT INTO "public"."stacks" (stack) VALUES 
('PHP'),
('Javascript'),
('Typescript'),
('Python'),
('Java'),
('Go'),
('React'),
('Vue');