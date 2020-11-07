<!--  START DASHBOARD -->
<div class="contain-inner content-cards">
    <div class="content">

        <style>
            .text-center {
                margin: auto;
                text-align: center;
            }

            .text-right {
                margin: auto;
                text-align: right;
            }
img { 

    width: 15px;}
            

        </style>
        <div class="row ">
            <div class="col-lg-5">
                <div class="table-responsive table-striped">
                    <table class="table" id="dataQ">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center">ลานจอด</th>
                                <th colspan="4" class="text-center">จำนวนคิว</th>
                            </tr>
                            <tr>
                                <th class="text-center">จ่ายคิว</th>
                                <th class="text-center">ชั่งเข้า</th>
                                <th class="text-center">ชั้งออก</th>
                                <th class="text-center">ยังไม่ได้ชั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="dataQ-loader" style="display:none;">
                                <td colspan="5" class="text-center" ><img src="../assets/images/ajax-loader.gif"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="col-lg-7">
                <div class="table-responsive table-striped">
                    <table class="table" id="dataCane">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center">รถชั่งออกแล้ว</th>
                                <th colspan="3" class="text-center">อ้อยสด</th>
                                <th colspan="3" class="text-center">อ้อยไฟไหม้</th>
                                <th colspan="2" class="text-center">ยอดรวม</th>
                            </tr>
                            <tr>
                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                                <th class="text-center">%</th>

                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                                <th class="text-center">%</th>

                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr class="dataCane-loader" style="display:none;">
                                <td colspan="10" class="text-center" ><img src="../assets/images/ajax-loader.gif"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class=" container-fluid text-center ">

            <div class=" centered text-center">
                <div class="table-responsive ">
                    <table class="table" id="dataCane">
                        <thead>
                            <tr>
                                <th colspan="10" style="background-color:#ffffff;color:black;"> ตารางประมาณการน้ำหนักอ้อยประจำวัน <?= date("d/m/Y") ?> </th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="text-center">รถชั่งออกแล้ว</th>
                                <th colspan="3" class="text-center">อ้อยสด</th>
                                <th colspan="3" class="text-center">อ้อยไฟไหม้</th>
                                <th colspan="2" class="text-center">ยอดรวม</th>
                            </tr>
                            <tr>
                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                                <th class="text-center">%</th>

                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                                <th class="text-center">%</th>

                                <th class="text-center">เที่ยว</th>
                                <th class="text-center">อ้อย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td > น้ำหนักชั่งออก </td>

                                <td class="t11 loader1"></td>
                                <td class="w11 loader1"></td>
                                <td class="p11 loader1"></td>
                                <td class="t21 loader1"></td>
                                <td class="w21 loader1"></td>
                                <td class="p21 loader1"></td>
                                <td class="st1 loader1"></td>
                                <td class="sw1 loader1"></td>

                            </tr>
                            <tr>
                                <td > อ้อยดัมแล้ว </td>

                                <td class="t12 loader1"></td>
                                <td class="w12 loader1"></td>
                                <td class="p12 loader1"></td>
                                <td class="t22 loader1"></td>
                                <td class="w22 loader1"></td>
                                <td class="p22 loader1"></td>
                                <td class="st2 loader1"></td>
                                <td class="sw2 loader1"></td>

                            </tr>

                            <tr>
                                <td class="bb"> รวมประมาณการ </td>

                                <td class="t13 loader1"></td>
                                <td class="w13 loader1"></td>
                                <td class="p13 loader1"></td>
                                <td class="t23 loader1"></td>
                                <td class="w23 loader1"></td>
                                <td class="p23 loader1"></td>
                                <td class="st3 loader1"></td>
                                <td class="sw3 loader1"></td>

                            </tr>

                            <tr>
                                <td class="bb"> ลานจอดใน </td>

                                <td class="t14 loader1"></td>
                                <td class="w14 loader1"></td>
                                <td class="p14 loader1"></td>
                                <td class="t24 loader1"></td>
                                <td class="w24 loader1"></td>
                                <td class="p24 loader1"></td>
                                <td class="st4 loader1"></td>
                                <td class="sw4 loader1"></td>

                            </tr>

                            <tr>
                                <td class="bb"> ลานจอดนอก </td>

                                <td class="t15 loader1"></td>
                                <td class="w15 loader1"></td>
                                <td class="p15 loader1"></td>
                                <td class="t25 loader1"></td>
                                <td class="w25 loader1"></td>
                                <td class="p25 loader1"></td>
                                <td class="st5 loader1"></td>
                                <td class="sw5 loader1"></td>

                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="divider20"></div>
    </div>
</div>
<!--  END DASHBOARD -->