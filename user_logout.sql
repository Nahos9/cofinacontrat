CREATE OR REPLACE PROCEDURE DISCONNECT_USER(
    USERNAME_IN IN VARCHAR2
) IS
    CURSOR SESSION_CUR IS
    SELECT
        SID,
        SERIAL#
    FROM
        V$SESSION
    WHERE
        USERNAME = USERNAME_IN;
    V_SID    V$SESSION.SID%TYPE;
    V_SERIAL V$SESSION.SERIAL#%TYPE;
BEGIN
    FOR SESSION_REC IN SESSION_CUR LOOP
        V_SID := SESSION_REC.SID;
        V_SERIAL := SESSION_REC.SERIAL#;
 -- Déconnecter l'utilisateur en utilisant le SID et le SERIAL#
        EXECUTE IMMEDIATE 'ALTER SYSTEM KILL SESSION '''
                          || V_SID
                          || ','
                          || V_SERIAL
                          || ''' IMMEDIATE';
    END LOOP;
 -- Afficher un message de confirmation
    DBMS_OUTPUT.PUT_LINE('Toutes les sessions de l''utilisateur '
                         || USERNAME_IN
                         || ' ont été déconnectées avec succès.');
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Aucune session trouvée pour l''utilisateur spécifié.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Une erreur est survenue : '
                             || SQLERRM);
END DISCONNECT_USER;
/

EXEC disconnect_user('COFINA_CREDIT');

exit;
