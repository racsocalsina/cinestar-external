DROP TRIGGER IF EXISTS trigger_qmaesoc_update;
DROP TRIGGER IF EXISTS trigger_qmaesoc_insert;
DROP TRIGGER IF EXISTS trigger_qmaesoc_delete;

CREATE TRIGGER trigger_qmaesoc_update after update ON `qmaesoc`
    FOR EACH ROW
begin
  INSERT INTO cinestar_external.job_triggers (origin, origin_id, type, status, created_at, updated_at )
  values ('qmaesoc', NEW.soccod, 'UPDATE', 'PENDENT', now(), now());
end ;

CREATE TRIGGER trigger_qmaesoc_insert AFTER INSERT
 ON `qmaesoc`
    FOR EACH ROW
begin
  INSERT INTO cinestar_external.job_triggers (origin, origin_id, type, status, created_at, updated_at )
  values ('qmaesoc', NEW.soccod, 'INSERT','PENDENT', now(), now() );
end ;

CREATE TRIGGER trigger_qmaesoc_delete   AFTER DELETE
 ON `qmaesoc`
    FOR EACH ROW
begin
  INSERT INTO cinestar_external.job_triggers (origin, origin_id, type, status, created_at, updated_at )
  values ('qmaesoc', OLD.soccod, 'DELETE','PENDENT', now(), now() );
end ;
