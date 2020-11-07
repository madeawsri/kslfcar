<?php
include_once("../../app.php");
$arr = array();
$arr['send'] = $_REQUEST;
switch (trim($_REQUEST['mode'])) {
  case "data-q":
    $sql = "SELECT 
                [X].[FQBY],
                SUM([X].[QALL]) AS Q_ALL,
                SUM([X].[QIN]) AS Q_IN,
                SUM([X].[QOUT]) AS Q_OUT,
                SUM([X].[QNO]) AS Q_NO
            FROM (
              SELECT 
                CASE WHEN [FQBY] LIKE 'A0%' OR [FQBY] LIKE 'B0%' THEN [FQBY] ELSE 'C01' END AS FQBY,
                COUNT([FITEMNO]) AS QALL ,
                sum(CASE WHEN  [FWGOUT1] =0 AND [FWGIN1] > 0   THEN 1 ELSE 0 END) AS QIN,
                sum(CASE WHEN  [FWGOUT1] > 0  THEN 1 ELSE 0 END) AS QOUT,
                sum(CASE WHEN   [FWGOUT1] = 0 AND [FWGIN1] = 0  THEN 1 ELSE 0 END) AS QNO
                FROM [dbo].[DD11RRDT] 
                WHERE [FYEAR] = '{$_dblib->get_year()}'
                GROUP BY [FQBY],[FWGIN1],[FWGOUT1] ) AS X
            GROUP BY [X].[FQBY]
            ORDER BY [X].[FQBY] ASC
";

    $arr['data'] = $_dblib->get_data_softpro($sql);

    break;

  case "data-cane":
    $sql = "SELECT 
                SUM([X].[T01]) AS 'T01',
                SUM([X].[W01]) AS 'W01',
                SUM([X].[T02]) AS 'T02',
                SUM([X].[W02]) AS 'W02',
                
                SUM([X].[ST01]) AS 'ST01',
                SUM([X].[SW01]) AS 'SW01',
                SUM([X].[ST02]) AS 'ST02',
                SUM([X].[SW02]) AS 'SW02'
            FROM (
            SELECT 
              
            CASE WHEN  [FVOUDATE] =CONVERT( date, GETDATE(),111) AND [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61')   
                    THEN COUNT([FITEMNO]) ELSE 0 END AS T01,
                cast(isnull(sum(CASE WHEN   [FVOUDATE] = CONVERT( date, GETDATE(),111)  AND [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61')   
                    THEN [FWEIGHT] ELSE 0 END),0) AS decimal(10,3)) AS W01,
                    
                CASE WHEN  [FVOUDATE] = CONVERT( date, GETDATE(),111) AND  [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61')   
                    THEN COUNT([FITEMNO]) ELSE 0 END AS T02,
                cast(isnull(sum(CASE WHEN   [FVOUDATE] = CONVERT( date, GETDATE(),111)AND [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61')   
                    THEN [FWEIGHT] ELSE 0 END),0) AS decimal(10,3)) AS W02,
              
              
                CASE WHEN  [FVOUDATE] < GETDATE() AND [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61')   
                    THEN COUNT([FITEMNO]) ELSE 0 END AS ST01,
                cast(isnull(sum(CASE WHEN   [FVOUDATE] < GETDATE() AND [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61')   
                    THEN [FWEIGHT] ELSE 0 END),0) AS decimal(10,3)) AS SW01,
                    
                CASE WHEN  [FVOUDATE] < GETDATE() AND  [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61')   
                    THEN COUNT([FITEMNO]) ELSE 0 END AS ST02,
                cast(isnull(sum(CASE WHEN   [FVOUDATE] < GETDATE() AND [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61')   
                    THEN [FWEIGHT] ELSE 0 END),0) AS decimal(10,3)) AS SW02,
                    
                COUNT([FITEMNO]) AS TAll,
                cast(isnull(SUM([FWEIGHT]),0) as decimal(10,3)) AS WALL
                
              FROM [dbo].[DD11RRDT] 
              WHERE [FYEAR] = '{$_dblib->get_year()}' AND [FWGOUT1] > 0 
              GROUP BY  FVOUDATE,[FSUBTYPE] ) AS X
 ";
    $arr['data'] =  $_dblib->get_data_softpro($sql);
    break;

    case "data-today":

      $sql  = "SELECT 
                  isnull(AVG([xx].[T01]),0) AS AT1,
                  CAST( ISNULL( AVG([xx].[W1]) ,0) AS decimal(10,3)) AS AW1,
                  CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
                      THEN 0 
                      ELSE CAST((ISNULL(AVG([xx].[W1]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P1,
                  isnull(AVG([xx].[T02]),0) AS AT2,
                    CAST(isnull(AVG([xx].[W2]),0) AS decimal(10,3)) AS AW2,
                    CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
                      THEN 0 
                      ELSE CAST((ISNULL(AVG([xx].[W2]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P2
                FROM (
                    SELECT 
                    [x].[FVEHICLETY],
                    isnull(SUM([x].[T01]),0) AS T01,
                    isnull(SUM([x].[w01]),0) AS W1,
                    CAST(((SUM([x].[w01]))*100)/(SUM([x].[w01])+SUM([x].[w02])) AS decimal(10,2)) AS P1,
                    isnull(SUM([x].[T02]),0) AS T02,
                    isnull(SUM([x].[w02]),0) AS W2,
                    CAST(((SUM([x].[w02]))*100)/(SUM([x].[w01])+SUM([x].[w02]))  AS decimal(10,2)) AS P2,
                    (SUM([x].[w01])+SUM([x].[w02])) AS WS
                    FROM (
                        SELECT 
                        [FVEHICLETY],
                        CASE WHEN [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61') 
                            THEN COUNT([FITEMNO]) ELSE 0 END T01 , 
                            CASE WHEN [FSUBTYPE] IN ('01','03','04','06','11','13','16','22','61') 
                            THEN sum([FWEIGHT]) ELSE 0 END w01 , 
                            CASE WHEN [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61') 
                            THEN COUNT([FITEMNO]) ELSE 0 END T02 ,
                            CASE WHEN [FSUBTYPE] NOT IN ('01','03','04','06','11','13','16','22','61') 
                            THEN sum([FWEIGHT]) ELSE 0 END w02 
                          FROM [dbo].[DD11RRDT] 
                        WHERE [FYEAR] = '{$_dblib->get_year()}'
                        AND [FVOUDATE] = CONVERT( date, GETDATE(),111) 
                        AND [FWGOUT1] > 0 
                        GROUP BY [FVEHICLETY],[FSUBTYPE] ) AS x 
                    GROUP BY [x].[FVEHICLETY] ) AS xx";

      $arr['data'] =  $_dblib->get_data_softpro($sql);

    break;

    case "data-dump" :
      $sql  = "SELECT 
      isnull(AVG([xx].[T01]),0) AS AT1,
      CAST( ISNULL( AVG([xx].[W1]) ,0) AS decimal(10,3)) AS AW1,
      CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W1]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P1,
      isnull(AVG([xx].[T02]),0) AS AT2,
        CAST(isnull(AVG([xx].[W2]),0) AS decimal(10,3)) AS AW2,
        CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W2]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P2
    FROM (
        SELECT 
        isnull(SUM([x].[T01]),0) AS T01,
        isnull(SUM([x].[w01]),0) AS W1,
        CAST(((SUM([x].[w01]))*100)/(SUM([x].[w01])+SUM([x].[w02])) AS decimal(10,2)) AS P1,
        isnull(SUM([x].[T02]),0) AS T02,
        isnull(SUM([x].[w02]),0) AS W2,
        CAST(((SUM([x].[w02]))*100)/(SUM([x].[w01])+SUM([x].[w02]))  AS decimal(10,2)) AS P2,
        (SUM([x].[w01])+SUM([x].[w02])) AS WS
        FROM (
              SELECT 
           sum(CASE WHEN LEFT([FPDCODE],1) = 0 THEN 1 ELSE 0 END ) AS T01,
           CASE WHEN LEFT([FPDCODE],1) = 0 THEN SUM([FWEIGHT]) ELSE 0 END  AS w01,
           sum(CASE WHEN LEFT([FPDCODE],1) = 1 THEN 1 ELSE 0 END ) AS T02,
           CASE WHEN LEFT([FPDCODE],1) = 1 THEN SUM([FWEIGHT]) ELSE 0 END  AS w02
              FROM [dbo].[DD11RRDT] 
            WHERE [FYEAR] = '{$_dblib->get_year()}'
            AND [FVOUDATE] <= CONVERT( date, GETDATE(),111) 
            AND [FWGIN1] > 0
            AND [FCCSCAL] > 0
            AND [FWGOUT1] = 0 
            GROUP BY LEFT([FPDCODE],1) ) AS x 
     ) AS xx";

    $arr['data'] =  $_dblib->get_data_softpro($sql);
    break;

    case "data-in" :
      $sql  = "SELECT 
      isnull(AVG([xx].[T01]),0) AS AT1,
      CAST( ISNULL( AVG([xx].[W1]) ,0) AS decimal(10,3)) AS AW1,
      CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W1]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P1,
      isnull(AVG([xx].[T02]),0) AS AT2,
        CAST(isnull(AVG([xx].[W2]),0) AS decimal(10,3)) AS AW2,
        CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W2]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P2
    FROM (
        SELECT 
        isnull(SUM([x].[T01]),0) AS T01,
        isnull(SUM([x].[w01]),0) AS W1,
        CAST(((SUM([x].[w01]))*100)/(SUM([x].[w01])+SUM([x].[w02])) AS decimal(10,2)) AS P1,
        isnull(SUM([x].[T02]),0) AS T02,
        isnull(SUM([x].[w02]),0) AS W2,
        CAST(((SUM([x].[w02]))*100)/(SUM([x].[w01])+SUM([x].[w02]))  AS decimal(10,2)) AS P2,
        (SUM([x].[w01])+SUM([x].[w02])) AS WS
        FROM (
              SELECT 
           sum(CASE WHEN LEFT([FPDCODE],1) = 0 THEN 1 ELSE 0 END ) AS T01,
           CASE WHEN LEFT([FPDCODE],1) = 0 THEN SUM([FWEIGHT]) ELSE 0 END  AS w01,
           sum(CASE WHEN LEFT([FPDCODE],1) = 1 THEN 1 ELSE 0 END ) AS T02,
           CASE WHEN LEFT([FPDCODE],1) = 1 THEN SUM([FWEIGHT]) ELSE 0 END  AS w02
              FROM [dbo].[DD11RRDT] 
            WHERE [FYEAR] = '{$_dblib->get_year()}'
            AND [FVOUDATE] <= CONVERT( date, GETDATE(),111) 
            AND [FWGIN1] > 0 
            AND [FWGOUT1] = 0 
            GROUP BY LEFT([FPDCODE],1) ) AS x 
     ) AS xx
        ";

    $arr['data'] =  $_dblib->get_data_softpro($sql);
    break;

    case "data-out" :
      $sql  = "SELECT 
      isnull(AVG([xx].[T01]),0) AS AT1,
      CAST( ISNULL( AVG([xx].[W1]) ,0) AS decimal(10,3)) AS AW1,
      CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W1]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P1,
      isnull(AVG([xx].[T02]),0) AS AT2,
        CAST(isnull(AVG([xx].[W2]),0) AS decimal(10,3)) AS AW2,
        CASE WHEN (isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0)) = 0 
          THEN 0 
          ELSE CAST((ISNULL(AVG([xx].[W2]),0)*100)/(isnull(AVG([xx].[W1]),0)+isnull(AVG([xx].[W2]),0))  AS decimal(10,2)) end AS P2
    FROM (
        SELECT 
        isnull(SUM([x].[T01]),0) AS T01,
        isnull(SUM([x].[w01]),0) AS W1,
        CAST(((SUM([x].[w01]))*100)/(SUM([x].[w01])+SUM([x].[w02])) AS decimal(10,2)) AS P1,
        isnull(SUM([x].[T02]),0) AS T02,
        isnull(SUM([x].[w02]),0) AS W2,
        CAST(((SUM([x].[w02]))*100)/(SUM([x].[w01])+SUM([x].[w02]))  AS decimal(10,2)) AS P2,
        (SUM([x].[w01])+SUM([x].[w02])) AS WS
        FROM (
              SELECT 
           sum(CASE WHEN LEFT([FPDCODE],1) = 0 THEN 1 ELSE 0 END ) AS T01,
           CASE WHEN LEFT([FPDCODE],1) = 0 THEN SUM([FWEIGHT]) ELSE 0 END  AS w01,
           sum(CASE WHEN LEFT([FPDCODE],1) = 1 THEN 1 ELSE 0 END ) AS T02,
           CASE WHEN LEFT([FPDCODE],1) = 1 THEN SUM([FWEIGHT]) ELSE 0 END  AS w02
              FROM [dbo].[DD11RRDT] 
            WHERE [FYEAR] = '{$_dblib->get_year()}'
            AND [FVOUDATE] <= CONVERT( date, GETDATE(),111) 
            AND [FWGIN1] = 0
            AND [FWGOUT1] = 0 
            GROUP BY LEFT([FPDCODE],1) ) AS x 
     ) AS xx ";

    $arr['data'] =  $_dblib->get_data_softpro($sql);
    break;
}

echo json_encode($arr);
