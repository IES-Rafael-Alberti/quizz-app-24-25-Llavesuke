DROP DATABASE IF EXISTS quiz_app;

CREATE DATABASE quiz_app;

USE quiz_app;

CREATE TABLE Usuarios (
                          user_id INT AUTO_INCREMENT PRIMARY KEY,
                          username VARCHAR(50) NOT NULL UNIQUE,
                          password VARCHAR(255) NOT NULL,
                          role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                          remember_token VARCHAR(255) NULL
);

CREATE TABLE Cuestionarios (
                               quiz_id INT AUTO_INCREMENT PRIMARY KEY,
                               title VARCHAR(100) NOT NULL,
                               description TEXT
);

CREATE TABLE Preguntas (
                           question_id INT AUTO_INCREMENT PRIMARY KEY,
                           quiz_id INT,
                           question_text TEXT NOT NULL,
                           option_a VARCHAR(255) NOT NULL,
                           option_b VARCHAR(255) NOT NULL,
                           option_c VARCHAR(255) NOT NULL,
                           option_d VARCHAR(255) NOT NULL,
                           correct_option CHAR(1) NOT NULL,
                           FOREIGN KEY (quiz_id) REFERENCES Cuestionarios(quiz_id)
);

CREATE TABLE Respuestas (
                            answer_id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT,
                            question_id INT,
                            selected_option CHAR(1) NOT NULL,
                            FOREIGN KEY (user_id) REFERENCES Usuarios(user_id),
                            FOREIGN KEY (question_id) REFERENCES Preguntas(question_id)
);

CREATE TABLE Resultados (
                            result_id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT,
                            quiz_id INT,
                            score INT,
                            total_questions INT,
                            percentage FLOAT,
                            attempt_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (user_id) REFERENCES Usuarios(user_id),
                            FOREIGN KEY (quiz_id) REFERENCES Cuestionarios(quiz_id)
);

-- Insert example data

-- Usuarios
INSERT INTO Usuarios (username, password, role) VALUES ('admin', '$2y$10$pLdTD./4ccfqTHhSPLx1vOKcQ86du.MzznPGSjHqtBibgZS1uh4Cm', 'admin');
INSERT INTO Usuarios (username, password, role) VALUES ('user1', 'hashed_password', 'user');

-- Cuestionarios
INSERT INTO Cuestionarios (title, description) VALUES ('Examen de PHP Básico', 'Pon a prueba tus conocimientos de los fundamentos de PHP.');
INSERT INTO Cuestionarios (title, description) VALUES ('Examen de Desarrollo Web', 'Un examen que abarca diversos temas de desarrollo web.');

-- Preguntas para el "Examen de PHP Básico"
INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Qué significa PHP?', 'Página de inicio personal', 'PHP: Procesador de hipertexto', 'Procesador de hipervínculos privado', 'Página de enlace de PHP', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Cuál de los siguientes NO es un tipo de dato de PHP?', 'Entero', 'Booleano', 'Caracter', 'Flotante', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Cuál es el resultado de `echo "Hola" . " " . "Mundo";`?', 'HelloWorld', 'Hola Mundo', 'HelloWorld', '"Hola Mundo"', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, 'En PHP, ¿qué bucle se utiliza para ejecutar un bloque de código un número especificado de veces?', 'Bucle for', 'Bucle while', 'Bucle do...while', 'Bucle foreach', 'A');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Qué función de PHP se utiliza para abrir un archivo para escritura?', 'fopen', 'file_open', 'open_file', 'Ninguna de las anteriores', 'D');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Cuál es el propósito de la superglobal `$_GET` en PHP?', 'Recuperar datos de un formulario con el método POST', 'Almacenar variables de sesión', 'Recuperar datos de la cadena de consulta URL', 'Definir constantes globales', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Cuál de los siguientes es un ejemplo de constante mágica de PHP?', '$this', '__LINE__', '$var', 'functionName()', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Qué hace la función `include` en PHP?', 'Ejecuta un bloque de código solo si una condición es verdadera', 'Incluye y evalúa un archivo especificado', 'Define una nueva función', 'Genera un número aleatorio', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, 'En PHP, ¿qué comprueba el operador `===`?', 'Igualdad', 'Asignación', 'Desigualdad', 'Comparación', 'A');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (1, '¿Cuál de los siguientes se utiliza para crear un objeto en PHP?', 'new', 'objeto', 'crear', 'instancia', 'A');

-- Preguntas para el "Examen de Desarrollo Web"
INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué significa HTML?', 'Lenguaje de marcado de hipertexto', 'Aprendizaje automático de alta tecnología', 'Lenguaje de transferencia de hipertexto', 'Lenguaje de mensajería de texto para el hogar', 'A');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué etiqueta HTML se utiliza para crear un enlace?', '<link>', '<href>', '<a>', '<hyperlink>', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Cuál es el propósito de CSS en el desarrollo web?', 'Definir la estructura de una página web', 'Crear contenido web dinámico', 'Dar formato a la apariencia de los elementos web', 'Añadir interactividad a una página web', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Cuál de las siguientes es una variable JavaScript válida?', '123var', '_myVariable', 'my-variable', 'var 123', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Cuál es la función principal de un servidor web en el contexto del desarrollo web?', 'Mostrar anuncios en un sitio web', 'Procesar la entrada del usuario', 'Hospedar y entregar contenido web a los clientes', 'Encriptar el tráfico web', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué etiqueta HTML se utiliza para crear una lista numerada (ordenada)?', '<list>', '<ol>', '<ul>', '<li>', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Cuál es el propósito de la propiedad CSS `font-family`?', 'Establecer el tamaño de la fuente del texto', 'Especificar el tipo de letra o estilo de fuente para el texto', 'Definir el color del texto', 'Controlar la alineación del texto', 'B');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué lenguaje de programación se usa a menudo para el scripting del lado del servidor en el desarrollo web?', 'Java', 'Python', 'PHP', 'HTML', 'C');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué significa el acrónimo "URL" en español?', 'Localizador uniforme de recursos', 'Lenguaje de representación universal', 'Lenguaje de referencia único', 'Enlace de registro de usuario', 'A');

INSERT INTO Preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES (2, '¿Qué código HTTP indica una solicitud exitosa en el desarrollo web?', '200 OK', '404 No encontrado', '500 Error interno del servidor', '302 Encontrado (Redirección)', 'A');