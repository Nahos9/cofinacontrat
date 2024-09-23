SELECT
	P.NO_PRET,
	P.MATRICULE_CLIENT,
	G.NOM_GESTIONNAIRE                                                                                                                                    "CHARGE DU PRET",
	T.INT_TYPE_PRET                                                                                                                                       "TYPE DE PRET",
	(NVL((ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTAMO, '05/04/2024'))), 0) + NVL((ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTIMP, '05/04/2024'))), 0)) "ENCOURS TOTAL"
FROM
	COFINATG.PRET         P,
	COFINATG.COMPTE       C,
	COFINATG.GESTIONNAIRE G,
	COFINATG.TYPE_PRET    T
WHERE
	C.MATRICULE_CLIENT = P.MATRICULE_CLIENT
	AND T.CODE_TYPE_PRET(+)=P.CODE_TYPE_PRET
	AND C.CODE_TYPE_CPT='25101';