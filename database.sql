CREATE TABLE public.employee(
   id SERIAL PRIMARY KEY,
   name VARCHAR (150) NOT NULL,
   email VARCHAR (150) NOT NULL,
   designation TEXT,
   age INTEGER,
   created DATE
);