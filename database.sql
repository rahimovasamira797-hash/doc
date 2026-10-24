
CREATE DATABASE IF NOT EXISTS document_system
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE document_system;

CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    author VARCHAR(150) NOT NULL,
    approver VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM(
        'На согласовании',
        'Согласовано',
        'Отклонено'
    ) NOT NULL DEFAULT 'На согласовании',
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO documents
(title, document_type, author, approver, description, status, comment)
VALUES
('Договор поставки №15', 'Договор', 'Иванов И.И.',
 'Петров П.П.', 'Договор на поставку оборудования',
 'На согласовании', ''),

('Приказ о командировке', 'Приказ', 'Сидорова А.А.',
 'Ким Д.Д.', 'Командировка сотрудника в Бишкек',
 'Согласовано', 'Документ проверен'),

('Служебная записка', 'Записка', 'Асанов Б.Б.',
 'Иванов И.И.', 'Запрос на закупку оборудования',
 'Отклонено', 'Необходимо уточнить бюджет');
