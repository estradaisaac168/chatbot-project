SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- Crear el esquema
CREATE SCHEMA IF NOT EXISTS `chatbot_db` DEFAULT CHARACTER SET utf8mb4;
USE `chatbot_db`;

-- Crear tabla `questions`
CREATE TABLE IF NOT EXISTS `questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_text` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `response_types`
CREATE TABLE IF NOT EXISTS `response_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `document_types`
CREATE TABLE IF NOT EXISTS `document_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `responses` sin claves foráneas
CREATE TABLE IF NOT EXISTS `responses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `response_text` TEXT NOT NULL,
  `has_next_question` INT NOT NULL DEFAULT 0,
  `has_next_response` INT NOT NULL DEFAULT 0,
  `has_document` INT NOT NULL DEFAULT 0,
  `questions_id` INT NULL DEFAULT NULL,
  `next_response_id` INT NULL DEFAULT NULL,
  `next_question_id` INT NULL DEFAULT NULL,
  `response_types_id` INT NOT NULL,
  `document_types_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_questions_id` (`questions_id`),
  INDEX `idx_response_types_id` (`response_types_id`),
  INDEX `idx_document_types_id` (`document_types_id`),
  INDEX `idx_next_question_id` (`next_question_id`),
  INDEX `idx_next_response_id` (`next_response_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(250) NOT NULL,
  `carne` VARCHAR(20) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `documents`
CREATE TABLE IF NOT EXISTS `documents` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `absolute_path` TINYTEXT NOT NULL,
  `relative_path` TINYTEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `responses_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_responses_id` (`responses_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Crear tabla `users_has_documents`
CREATE TABLE IF NOT EXISTS `users_has_documents` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `documents_id` INT NOT NULL,
  `accesed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_documents_id` (`documents_id`),
  INDEX `idx_users_id` (`users_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Activar nuevamente las claves foráneas y agregarlas con ALTER TABLE
SET FOREIGN_KEY_CHECKS=1;

-- Agregar claves foráneas a `responses`
ALTER TABLE `responses`
ADD CONSTRAINT `fk_responses_questions`
  FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`)
  ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_responses_response_types`
  FOREIGN KEY (`response_types_id`) REFERENCES `response_types` (`id`)
  ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_responses_document_types`
  FOREIGN KEY (`document_types_id`) REFERENCES `document_types` (`id`)
  ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_responses_questions2`
  FOREIGN KEY (`next_question_id`) REFERENCES `questions` (`id`)
  ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_responses_responses`
  FOREIGN KEY (`next_response_id`) REFERENCES `responses` (`id`)
  ON DELETE SET NULL ON UPDATE CASCADE;

-- Agregar claves foráneas a `documents`
ALTER TABLE `documents`
ADD CONSTRAINT `fk_documents_responses`
  FOREIGN KEY (`responses_id`) REFERENCES `responses` (`id`)
  ON DELETE NO ACTION ON UPDATE NO ACTION;

-- Agregar claves foráneas a `users_has_documents`
ALTER TABLE `users_has_documents`
ADD CONSTRAINT `fk_users_has_documents_users`
  FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
  ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_users_has_documents_documents`
  FOREIGN KEY (`documents_id`) REFERENCES `documents` (`id`)
  ON DELETE NO ACTION ON UPDATE NO ACTION;

-- Restaurar configuraciones previas
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
