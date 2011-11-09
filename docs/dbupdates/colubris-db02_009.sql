alter table quote add `attachment_id` INT(11) NULL after html, add  CONSTRAINT `fk_quote_filestore_file1`
FOREIGN KEY (`attachment_id` )
REFERENCES `filestore_file` (`id` )
ON DELETE NO ACTION
ON UPDATE NO ACTION;
