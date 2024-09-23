-- Active: 1713811686843@@10.228.60.14@1521@cofina@COFINATG
SELECT
	(
		CASE
			WHEN P.CODE_BUREAU LIKE '26%' THEN
				'CFN BF'
			WHEN P.CODE_BUREAU LIKE '25%' THEN
				'CFN CI'
			WHEN P.CODE_BUREAU LIKE '11%' THEN
				'CFN SN'
			WHEN P.CODE_BUREAU LIKE '24%' THEN
				'CFN GN'
			WHEN P.CODE_BUREAU LIKE '23%' THEN
				'CFN ML'
			WHEN P.CODE_BUREAU LIKE '20%' THEN
				'CFN TG'
			WHEN P.CODE_BUREAU LIKE '32%' THEN
				'CFN GB'
			WHEN P.CODE_BUREAU LIKE '42%' THEN
				'CFN CG'
		END )                                                                                                                                                                                                                                                                                                        AS "ENTITE",
	REF_COMITE                                                                                                                                                                                                                                                                                                    "Reference Comite",
	P.NO_CPTREC,
	NO_PRET                                                                                                                                                                                                                                                                                                       "N° PRET",
	CL.MATRICULE_CLIENT                                                                                                                                                                                                                                                                                           "MATRICULE CLIENT",
	T.INT_TYPE_PRET                                                                                                                                                                                                                                                                                               "TYPE DE PRET",
	(
		SELECT
			MIN(D_TOMBEE_ECHI)
		FROM
			COFINATG.ECHEANCE
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
	)"DATE PREM ECHEANCE",
	(
		SELECT
			MAX(D_TOMBEE_ECHI)
		FROM
			COFINATG.ECHEANCE
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
	)"DATE FIN ECHEANCE",
	D_MEP_PRET "DATE MISE EN PLACE",
	LIB_NAT_JURID "NATURE JURIDIQUE",
	G.CODE_GESTIONNAIRE "CODE CHARGE DE PRET",
	G.NOM_GESTIONNAIRE "CHARGE DU PRET",
	SS.LIB_SSECT "SECTEUR ACTIVITE",
	CL.TEL_PORT,
	(
		SELECT
			COUNT(NO_ECHEANCE)
		FROM
			COFINATG.ECHEANCE
		WHERE
			NO_PRET=P.NO_PRET
	)"NBRE ECHEANCE",
	'CHAQUE '||P.PERIOD_ECH||' MOIS' "PERIODICITE",
	(
		SELECT
			SUM(E1.PART_CAP_ECH)
		FROM
			COFINATG.ECH_TRANCHE E1
		WHERE
			E1.NO_PRET=P.NO_PRET
	) "CAPITAL DEPART",
	(
		SELECT
			SUM(E1.PART_INT_ECH)
		FROM
			COFINATG.ECH_TRANCHE E1
		WHERE
			E1.NO_PRET=P.NO_PRET
	) "INTERET SIMPLE DEPART",
	(
		SELECT
			SUM(E1.MT_INT_CAP)
		FROM
			COFINATG.ECH_TRANCHE E1
		WHERE
			E1.NO_PRET=P.NO_PRET
	) "INTERET CAPITALISE DEPART",
	(
		SELECT
			SUM(E.CUM_CAP_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
			AND D_TOMBEE_ECHI<='05/04/2024'
	) "Capital Payé",
	(
		SELECT
			SUM(E.CUM_INT_CAP_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
			AND D_TOMBEE_ECHI<='05/04/2024'
	) "Interet Capitalisé Payé",
	(
		SELECT
			SUM(E.CUM_INT_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
			AND D_TOMBEE_ECHI<='05/04/2024'
	) "Interet Simple Payé",
	(
		SELECT
			SUM(E.PART_CAP_ECH) - SUM(E.CUM_CAP_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
	) "Capital restant du",
	(
		SELECT
			SUM(E.PART_INT_ECH) - SUM(E.CUM_INT_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
	) "Interet Simple restant Du",
	(
		SELECT
			SUM(E.MT_INT_CAP) - SUM(E.CUM_INT_CAP_PAY)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			E.NO_PRET=P.NO_PRET
	) "Interet Capitalisé restant du",
	(
		SELECT
			SUM(E1.PART_INT_ECH)
		FROM
			COFINATG.ECH_TRANCHE E1
		WHERE
			E1.NO_PRET=P.NO_PRET
			AND D_TOMBEE_ECHI<='05/04/2024'
	) "Interet Simple Couru",
	(
		SELECT
			SUM(E1.MT_INT_CAP)
		FROM
			COFINATG.ECH_TRANCHE E1
		WHERE
			E1.NO_PRET=P.NO_PRET
			AND D_TOMBEE_ECHI<='05/04/2024'
	) "Interet Capitalisé Couru",
	(
		SELECT
			DISTINCT(MT_ECHEANCE)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
			AND NO_ECHEANCE=(
				SELECT
					MAX(E1.NO_ECHEANCE)
				FROM
					COFINATG.ECHEANCE E1
				WHERE
					E1.NO_PRET=P.NO_PRET
					AND E1.NO_RALLONGE=E.NO_RALLONGE
			)
	)"MONTANT ECHEANCE",
	D_PRO_ECH "ECHEANCE PROCHAINE",
	(
		SELECT
			DISTINCT(PART_CAP_ECH)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
			AND NO_ECHEANCE=(
				SELECT
					MAX(E1.NO_ECHEANCE)
				FROM
					COFINATG.ECHEANCE E1
				WHERE
					E1.NO_PRET=P.NO_PRET
					AND E1.NO_RALLONGE=E.NO_RALLONGE
			)
	)"Part en Capital",
	(
		SELECT
			DISTINCT(PART_INT_ECH)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
			AND NO_ECHEANCE=(
				SELECT
					MAX(E1.NO_ECHEANCE)
				FROM
					COFINATG.ECHEANCE E1
				WHERE
					E1.NO_PRET=P.NO_PRET
					AND E1.NO_RALLONGE=E.NO_RALLONGE
			)
	)"Part en Interet simple",
	(
		SELECT
			DISTINCT(MT_INT_CAP)
		FROM
			COFINATG.ECHEANCE E
		WHERE
			NO_PRET=P.NO_PRET
			AND NO_RALLONGE=P.NO_RALLONGE
			AND NO_ECHEANCE=(
				SELECT
					MAX(E1.NO_ECHEANCE)
				FROM
					COFINATG.ECHEANCE E1
				WHERE
					E1.NO_PRET=P.NO_PRET
					AND E1.NO_RALLONGE=E.NO_RALLONGE
			)
	)"Part en Interet Capitalisé",
	(NVL((ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTREC,
	'05/04/2024'))),
	0)) "SOLDE CPTE COURANT",
	ROUND ((D_DER_ECH -P.D_MEP_PRET)/30) "DUREE INITIALE",
	(
		CASE
			WHEN ROUND ((D_DER_ECH -SYSDATE)/30)>0 THEN
				ROUND ((D_DER_ECH -SYSDATE)/30)
			ELSE
				0
		END) "DUREE RESTANTE",
	STATUS_PRET "STATUT PRET",
	P.TX_INT_PRET "TAUX DE CREDIT",
	B.LIBELLE_BUREAU "AGENCE",
	CL.RAISON_SOCIALE_CLIENT||' '||CL.PRENOM_CLIENT "NOM CLIENT",
	NVL(CL.SEXE,
	'C') "SEXE",
	CL.TEL_BUR "NUMERO TELEPHONE",
	CL.ADRESSE_1 "ADRESSE CLIENT",
	CL.NOM_REPLEGAL "NOM REP LEGAL",
	P.MT_FRAIDOS,
	P.MT_ASSURANCE "ASSURANCE",
	P.MT_PRET_CAP "PRODUCTION EN VOLUME",
	ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTAMO,
	'05/04/2024')) "ENCOURS SAIN",
	ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTIMP,
	'05/04/2024')) "ENCOURS IMPAYE",
	ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTINTIMP,
	'05/04/2024')) "Dont Interet Impayé",
	(NVL((ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTAMO,
	'05/04/2024'))),
	0) + NVL((ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTIMP,
	'05/04/2024'))),
	0)) "ENCOURS TOTAL",
	ABS(COFINATG.SLDARR(P.CODE_BUREAU,
	P.NO_CPTEPA,
	'05/04/2024')) "SOLDE DEPOT GARANTIE",
	P.MT_EPAOBLI "DEPOT GARANTIE",
	(
		SELECT
			MIN(D_TOMBEE_ECH)
		FROM
			COFINATG.ECHEANCE
		WHERE
			NO_PRET=P.NO_PRET
			AND NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTIMP, '05/04/2024')), 0)<>0
			AND CAP_REST_DU<(NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTAMO, '05/04/2024')), 0) + NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTIMP, '05/04/2024')), 0))
	) "DATE PREMIER IMPAYE",
	(
		SELECT
			MIN(D_TOMBEE_ECH)
		FROM
			COFINATG.ECHEANCE
		WHERE
			NO_PRET=P.NO_PRET
			AND NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTIMP, '05/04/2024')), 0)<>0
			AND CAP_REST_DU<(NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTAMO, '05/04/2024')), 0) + NVL(ABS(COFINATG.SLDARR(P.CODE_BUREAU, P.NO_CPTIMP, '05/04/2024')), 0))
			AND STATUS_ECH='I'
	) "DATE DERNIER IMPAYE",
	(
		SELECT
			TRUNC(SYSDATE - MIN(D_TOMBEE_ECH) -1)
		FROM
			COFINATG.ECHEANCE
		WHERE
			STATUS_ECH='I'
			AND NO_PRET=P.NO_PRET
		GROUP BY
			NO_PRET
	) "DUREE IMPAYE AU 05/04/2024",
	P.CODE_STATUT,
	DECODE(P.CODE_STATUT,
	'23',
	'Financé',
	'023',
	'Financé',
	'09',
	'Rejeté Agence',
	'009',
	'Rejeté Agence',
	'00',
	'Etude',
	'99',
	'Dossier rejeté',
	'099',
	'Dossier rejeté',
	'000',
	'Etude',
	'03',
	' Garanties saisies',
	'003',
	' Garanties saisies',
	'96',
	'Annulation déblocage',
	'096',
	'Annulation déblocage') "Libellé Statut",
	P.NO_RALLONGE
 /*(select min(d_tombee_echi) from COFINATG.echeance where no_rallonge=0 and status_ech in ('I','E') and no_pret=p.no_pret) ""DATE PREMIER IMPAYE"",
(to_date('05/04/2024')- (select min(d_tombee_echi) from COFINATG.echeance where no_rallonge=0 and status_ech in ('I','E') and no_pret=p.no_pret)) ""DUREE IMPAYE AU 05/04/2024""*/
FROM
	COFINATG.PRET             P,
	COFINATG.COMPTE           CPTE,
	COFINATG.TYPE_PRET        T,
	COFINATG.CLIENT           CL,
	COFINATG.NATURE_JURIDIQUE NAT,
	COFINATG.SOUS_SECTEUR     SS,
	COFINATG.CATEGORIE        CAT,
	COFINATG.BUREAU           B,
	COFINATG.GESTIONNAIRE     G,
	COFINATG.STATUT           ST
WHERE
	B.CODE_BUREAU=CPTE.CODE_BUREAU
	AND ST.CODE_STATUT(+) = P.CODE_STATUT
	AND CAT.CODE_CATEGORIE(+) = CL.CODE_CATEGORIE
	AND NAT.CODE_NAT_JURID(+)=CL.CODE_NAT_JURID
	AND SS.CODE_SSECT(+) = P.CODE_SSECT
	AND CPTE.NO_COMPTE=P.NO_CPTAMO
	AND P.MATRICULE_CLIENT=CL.MATRICULE_CLIENT
	AND T.CODE_TYPE_PRET(+)=P.CODE_TYPE_PRET
	AND G.CODE_GESTIONNAIRE(+)=P.CODE_CHARGE
	AND P.CODE_TYPE_PRET!='351';