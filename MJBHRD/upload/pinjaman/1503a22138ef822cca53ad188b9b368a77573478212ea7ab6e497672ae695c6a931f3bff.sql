

SELECT  *
FROM    APPS.OE_ORDER_HEADERS_V


SELECT  *
FROM    MJ_M_USER
WHERE   EMP_ID = 11879




apps.merakjaya.co.id/MJBHRD/transaksi/potonganacchrd/isi_pdf_potongan.php?hdid=163



apps.merakjaya.co.id/MJBHRD/transaksi/potonganaccbyhrd/isi_pdf_potongan.php?hdid=163




        SELECT  NOMOR_PINJAMAN,
                TRIM( TO_CHAR( NOMINAL * JUMLAH_CICILAN, '999,999,999.99' ) ) TOTAL_PINJAMAN,
                JUMLAH_CICILAN
        FROM    MJ_T_PINJAMAN
        WHERE   PERSON_ID = 
                (   SELECT  PERSON_ID
                    FROM    MJ_T_PINJAMAN
                    WHERE   ID =  163 )
                    
        AND     TANGGAL_PINJAMAN < 
                (   SELECT  TANGGAL_PINJAMAN
                    FROM    MJ_T_PINJAMAN
                    WHERE   ID =  163 )


        (
        SELECT  MTP.TANGGAL_PINJAMAN, MTP.NOMOR_PINJAMAN,
                TRIM( TO_CHAR( MTP.NOMINAL * MTP.JUMLAH_CICILAN, '999,999,999.99' ) ) TOTAL_PINJAMAN,
                MTP.JUMLAH_CICILAN
        FROM MJ.MJ_T_PINJAMAN MTP
        LEFT JOIN (
                SELECT MAX(ID) ID, TRANSAKSI_ID FROM
                MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN' AND STATUS = 'Approved' AND TINGKAT = 4
                GROUP BY TRANSAKSI_ID
            ) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
        LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'    
        WHERE 1=1
        AND MTP.TIPE = 'PINJAMAN PERSONAL'
        AND MTP.STATUS_DOKUMEN = 'Validate'
        AND MTP.TINGKAT = 5
        AND MTP.JUMLAH_CICILAN > 0
        AND MTP.STATUS = 1
        AND MTP.ID <> 163
        AND MTP.PERSON_ID = 
            (   SELECT  PERSON_ID
                FROM    MJ_T_PINJAMAN
                WHERE   ID =  163
            )
        )
        
        UNION
        
        (
        SELECT  MTP.TANGGAL_PINJAMAN, MTP.NOMOR_PINJAMAN,
                -- TRIM( TO_CHAR( MTP.NOMINAL * MTP.JUMLAH_CICILAN, '999,999,999.99' ) ) TOTAL_PINJAMAN,
                TRIM( TO_CHAR( MTP.NOMINAL, '999,999,999.99' ) ) NOMINAL,
                MTP.JUMLAH_CICILAN
        FROM MJ.MJ_T_PINJAMAN MTP
        LEFT JOIN (
                SELECT MAX(ID) ID, TRANSAKSI_ID FROM
                MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN' AND STATUS = 'Approved' AND TINGKAT = 3
                GROUP BY TRANSAKSI_ID
            ) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
        LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'    
        WHERE 1=1
        AND MTP.TIPE = 'PINJAMAN PENGGANTI INVENTARIS'
        AND MTP.STATUS_DOKUMEN = 'Approved'
        AND MTP.JUMLAH_CICILAN > 0
        AND MTP.TINGKAT = 4
        AND MTP.STATUS = 1
        AND MTP.ID <> 163
        AND MTP.PERSON_ID = 
            (   SELECT  PERSON_ID
                FROM    MJ_T_PINJAMAN
                WHERE   ID = 163
            )
        )
        
        
        7500000
        
        7,500,000
        


SELECT  DISTINCT TIPE
FROM    MJ_T_PINJAMAN


SELECT  PPF.FULL_NAME, PPF.EMAIL_ADDRESS
FROM    APPS.PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
WHERE   PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND     PPF.EFFECTIVE_END_DATE > SYSDATE
AND     PPF.PERSON_ID = MTP.MANAGER
AND     MTP.ID = 162


SELECT  *
FROM    MJ_M_USER
WHERE   EMP_ID = 1975


http://192.168.0.58/MJBHRD/transaksi/potonganaccacc/transaksipotongan.php



