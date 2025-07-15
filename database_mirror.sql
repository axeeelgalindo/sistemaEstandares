--Obtener la informacion de mi db_estandares:
/*
USE db_estandares;
GO
EXEC sp_helpfile;
GO
*/


--crear la base de datos de db_estandares_espejo
--desde el backup de db_estandares
/*
RESTORE DATABASE db_estandares_Espejo
  FROM DISK = N'C:\Program Files\Microsoft SQL Server\MSSQL16.SQLEXPRESS\MSSQL\Backup\db_estandares.bak'
  WITH 
    MOVE 'db_estandares_Data' TO 
      'C:\Program Files\Microsoft SQL Server\MSSQL16.SQLEXPRESS\MSSQL\DATA\db_estandares_Espejo.mdf',
    MOVE 'db_estandares_Log' TO 
      'C:\Program Files\Microsoft SQL Server\MSSQL16.SQLEXPRESS\MSSQL\DATA\db_estandares_Espejo_log.ldf',
    REPLACE;
GO
*/

--VERIFICAR QUE ESTÁ CREADO
/*
SELECT name, state_desc
FROM sys.databases
WHERE name LIKE 'db_estandares%';
*/

--CREAR EL LOGIN PARA LA BBDD ESPEJO
/*
CREATE LOGIN [sest_mirror] 
  WITH PASSWORD = 'mirror_database',
       CHECK_POLICY = ON;
GO*/

-- 1) Cambia el contexto a la base “Espejo”
--USE db_estandares_Espejo;

/*
-- 2) Crea el usuario de base de datos ligado al login
CREATE USER [sest_mirror] FOR LOGIN [sest_mirror];
GO
*/
/*
-- 3) Asígnale el rol fijo de solo lectura
ALTER ROLE db_datareader ADD MEMBER [sest_mirror];
GO
*/

--4  (Opcional) Refuerza denegando DML
--DENY INSERT, UPDATE, DELETE TO [sest_mirror];


--SELECT * 
--FROM sys.database_principals 
--WHERE name = 'sest_mirror';

--Comprobar pertenencia al rol db_datareader
/*
SELECT 
  r.name AS Rol, 
  m.name AS Miembro
FROM sys.database_role_members drm
JOIN sys.database_principals r  ON drm.role_principal_id   = r.principal_id
JOIN sys.database_principals m  ON drm.member_principal_id = m.principal_id
WHERE m.name = 'sest_mirror';
*/


--ELIMINAR LOS PRIVILEGIOS PARA VER LOS SP
REVOKE EXECUTE ON SCHEMA::dbo FROM [sest_mirror];
REVOKE VIEW DEFINITION ON SCHEMA::dbo FROM [sest_mirror];

--DAR PRIVILEGIOS A UN SP EN CONCRETO PARA QUE EJECUTE
GRANT EXECUTE 
  ON OBJECT::dbo.Listar_Area 
  TO [sest_mirror];