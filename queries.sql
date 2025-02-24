ALTER TABLE responses CHANGE COLUMN next_response_id parent_response_id INT;

UPDATE responses SET parent_response_id = NULL;

ALTER TABLE responses ADD COLUMN get_document INT NOT NULL DEFAULT 0;
