<?PHP
 include 'D:/dataSource/MJBHRD/main/koneksi.php'; //Koneksi ke database

// $bulan = date('F');
 
$bulan = date('m');
$tahun = date('Y');
 
 //echo $bulan.'--'.$tahun;exit;



// digunakan sesuai tahun dan bulan berjalan


/* DIPAKAI */

$query = "
(
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN, MTA.CREATED_DATE
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
AND MTP.START_POTONGAN_TAHUN || LPAD( START_POTONGAN_BULAN, 2, '0' ) <= $tahun || LPAD( $bulan, 2, '0' )
AND NOT EXISTS
    (   SELECT  1
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
		AND     TAHUN = $tahun
        AND     BULAN = $bulan
		AND		SOURCE = 'SCHEDULER'
    )
AND MTP.JUMLAH_PINJAMAN > 
    (   
        SELECT  NVL( SUM( NOMINAL ), 0 )
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
    )
)
UNION
(
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN, MTA.CREATED_DATE
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
AND MTP.START_POTONGAN_TAHUN || LPAD( START_POTONGAN_BULAN, 2, '0' ) <= $tahun || LPAD( $bulan, 2, '0' )
AND NOT EXISTS
    (   SELECT  1
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
		AND     TAHUN = $tahun
        AND     BULAN = $bulan
		AND		SOURCE = 'SCHEDULER'
    )
AND MTP.JUMLAH_PINJAMAN > 
    (   
        SELECT  NVL( SUM( NOMINAL ), 0 )
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
    )
)
";


 
// echo $query; exit;

$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$idHeader = $row[0];
	$nominal = $row[1];
	$jumlah_cicilan = $row[2];
	$bulan = $row[3];
	$tahun = $row[4];
	
	//Pinjaman
	if($jumlah_cicilan > 0){
		$jumlah_cicilan = $jumlah_cicilan - 1;
		
		$resultUpd = oci_parse($con,"UPDATE MJ.MJ_T_PINJAMAN SET JUMLAH_CICILAN = $jumlah_cicilan WHERE ID=$idHeader");
		oci_execute($resultUpd);
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_DETAIL_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idSeq = $row[0];
		
		$sqlQuery = "
		INSERT INTO MJ.MJ_T_PINJAMAN_DETAIL (
			ID, 
			MJ_T_PINJAMAN_ID, 
			TAHUN, 
			BULAN,
			NOMINAL,
			STATUS, 
			CREATED_BY, 
			CREATED_DATE,
			SOURCE
		) 
		VALUES (
			$idSeq, 
			$idHeader, 
			'$tahun', 
			'$bulan', 			
			$nominal, 
			1, 
			1, 
			SYSDATE,
			'SCHEDULER'
		)";
		
		$resultQuery = oci_parse($con,$sqlQuery);
		oci_execute($resultQuery);
	} 
	
} 

// digunakan sesuai tahun dan bulan berjalan 

/* DIPAKAI */





// digunakan sesuai tahun dan bulan yang diisikan

/* DIPAKAI


$query = "
(
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN, MTA.CREATED_DATE
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
AND MTP.START_POTONGAN_TAHUN || LPAD( START_POTONGAN_BULAN, 2, '0' ) <= '201901'   -- '201811'  -- tahun || $bulan
AND NOT EXISTS
    (   SELECT  1
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
		AND     TAHUN = 2019
        AND     BULAN = 1
		AND		SOURCE = 'SCHEDULER'
    )
AND MTP.JUMLAH_PINJAMAN > 
    (   
        SELECT  NVL( SUM( NOMINAL ), 0 )
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
    )
)
UNION
(
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN, MTA.CREATED_DATE
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
AND MTP.START_POTONGAN_TAHUN || LPAD( START_POTONGAN_BULAN, 2, '0' ) <= '201901'   -- '201901'   -- $tahun || $bulan
AND NOT EXISTS
    (   SELECT  1
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
		AND     TAHUN = 2019
        AND     BULAN = 1
		AND		SOURCE = 'SCHEDULER'
    )
AND MTP.JUMLAH_PINJAMAN > 
    (   
        SELECT  NVL( SUM( NOMINAL ), 0 )
        FROM    MJ_T_PINJAMAN_DETAIL
        WHERE   MJ_T_PINJAMAN_ID = MTP.ID
    )
)
";

 
// echo $query; exit;

$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$idHeader = $row[0];
	$nominal = $row[1];
	$jumlah_cicilan = $row[2];
	$bulan = $row[3];
	$tahun = $row[4];
	
	//Pinjaman
	if($jumlah_cicilan > 0){
		$jumlah_cicilan = $jumlah_cicilan - 1;
		
		$resultUpd = oci_parse($con,"UPDATE MJ.MJ_T_PINJAMAN SET JUMLAH_CICILAN = $jumlah_cicilan WHERE ID=$idHeader");
		oci_execute($resultUpd);
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_T_PINJAMAN_DETAIL_SEQ.nextval FROM dual"); 
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$idSeq = $row[0];
		
		$sqlQuery = "
		INSERT INTO MJ.MJ_T_PINJAMAN_DETAIL (
			ID,
			MJ_T_PINJAMAN_ID,
			TAHUN,
			BULAN,
			NOMINAL,
			STATUS,
			CREATED_BY,
			CREATED_DATE,
			SOURCE
		) 
		VALUES (
			$idSeq,
			$idHeader,
			2019,
			1,
			$nominal,
			1,
			1,
			SYSDATE,
			'SCHEDULER'
		)";
		
		$resultQuery = oci_parse($con,$sqlQuery);
		oci_execute($resultQuery);
	} 
	
} 



// digunakan sesuai tahun dan bulan yang diisikan

DIPAKAI */




// Old query:
 
/* $query = "SELECT ID, NOMINAL, JUMLAH_CICILAN FROM MJ.MJ_T_PINJAMAN 
WHERE TIPE = 'PINJAMAN PERSONAL'
AND STATUS_DOKUMEN = 'Validate'
AND JUMLAH_CICILAN > 0
AND STATUS = 1"; */

/*
$query = "
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTA.CREATED_DATE, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN
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
UNION
SELECT MTP.ID, MTP.NOMINAL, MTP.JUMLAH_CICILAN, MTA.CREATED_DATE, MTP.START_POTONGAN_BULAN, MTP.START_POTONGAN_TAHUN
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
";
*/


?>