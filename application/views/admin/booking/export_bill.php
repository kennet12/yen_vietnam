<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?=SITE_NAME?></title>
    <link rel="SHORTCUT ICON" href="<?=BASE_URL?>/favicon.ico"/>
    <script type="text/javascript" src="<?=JS_URL?>jquery.min.js?cr=<?=CACHE_RAND?>"></script>
    <style type="text/css" media="print">
        @media print
        {
            @page {
            margin-top: 0;
            margin-bottom: 0;
            }
            body  {
            padding-top: 72px;
            padding-bottom: 72px ;
            }
        } 
    </style>
</head>
<body style="color: #000000;font-size: 13px;font-family: Arial,Tahoma,sans-serif;background: #eee;padding: 10px;">
    <div style="width: 80mm;margin: 15px auto;">
        <div style="color: #000000;display: table;width: 100%;background: #FFF;">
            <div style="display:table-cell;width:30%;text-align:center;">
            <a href="<?=BASE_URL?>" target="_blank"><img style="width: 100px;" src="<?=BASE_URL?>/images/logo/logo.png" alt=""></a>
            </div>
        </div>
        <div style="padding:0 15px 15px;background: #fff;">
            <div style="display:table;width:100%; border-top: 2px solid #000000;">
                <div style="display:table-cell;width:100%">
                    <p>Mã đơn hàng: <strong style=""><?=BOOKING_PREFIX.$booking->id?></strong></p>
                    <p>Ngày đặt hàng: <strong style=""><?=date('d/m/Y',strtotime($booking->created_date))?></strong></p>
                    <p>Người nhận: <strong style=""><?=$booking->fullname?></strong></p>
                    <p>Điện thoại: <strong style=""><?=$booking->phone?></strong></p>
                    <p><?=$booking->address?></p>
                </div>
            </div>
            <table style="width: 100%;border-top: 1px solid #000000;">
                <tr>
                    <td style="width:5%;padding: 5px 0px;color: #000000;font-weight: bold;">
                        Stt
                    </td>
                    <td style="width:24%;padding: 5px 3px;color: #000000;font-weight: bold;">
                        Tên SP
                    </td>
                    <td style="width:33%;padding: 5px 3px;color: #000000;font-weight: bold;">
                        Giá
                    </td>
                    <td style="width:5%;padding: 5px 3px;color: #000000;font-weight: bold;">
                        SL
                    </td>
                    <td style="width:33%;padding: 5px 3px;color: #000000;font-weight: bold;text-align:right;">
                        Thành tiền
                    </td>
                </tr>
            </table>
            <?  
            $total = 0;
            $i=1;
            foreach ($booking->details as $pax) {
                $price_sale = $pax->price_sale;
                if (!empty($booking->code_promotion)) {
                    $price_sale = $pax->price;
                }
                $total += $price_sale*$pax->qty; 
            ?>
            <table style="width: 100%;">
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="width:5%;padding: 5px 0px;vertical-align: top;"><?=$i?></td>
                    <td style="padding: 5px 0px;">
                        <div style=""><a href="" target="_blank" style="text-decoration: none;color: #000000;display: block;"><?=$pax->title?> </div></a>
                    </td>
                </tr>
            </table>
            <table style="width: 100%;">
            <tr>
                <td style="width:5%;padding: 5px 0px;"></td>
                <td style="width:24%;padding: 5px 0px;"></td>
                <td style="width:33%;padding: 5px 3px;vertical-align: top;">
                    <div style=""><?=number_format($price_sale,0,',','.')?><sup>₫</sup></div>
                </td>
                <td style="width:5%;padding: 5px 3px;vertical-align: top;">
                    <span style="margin: 8px 0;">x<?=$pax->qty?></span>
                </td>
                <td style="width:33%;padding: 5px 3px;vertical-align: top;text-align:right;">
                    <div style=""><?=number_format($price_sale*$pax->qty,0,',','.')?><sup>₫</sup></div>
                </td>
            </tr>
            </table>
            <? $i++; } $total = $this->util->round_number($total,1000); ?>
            <table style="width:100%;border-top: 1px solid #000000;padding-top: 5px;">
                <tr>
                    <td style="width:100%">
                        <table style="width: 100%;">
                            <? if (!empty($booking->code_promotion)) { ?>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;color: #000000;text-align:right;">
                                    Giảm giá:
                                </td>
                                <td style="width:50%;padding: 3px 10px;text-align:right;">
                                    <div style="">- <?=number_format($total-$booking->total,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                            <? } ?>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;color: #000000;text-align:right;">
                                    Phí vận chuyển
                                </td>
                                <td style="width:50%;padding: 3px 10px;text-align:right;">
                                    <div style=""><?=number_format($booking->ship_money,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;color: #000000;text-align:right;">
                                    Tổng tiền:
                                </td>
                                <td style="width:50%;padding: 3px 10px;text-align:right;">
                                    <div style=""><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; vertical-align: top;">
                        <div><img style="width: 100px;" src="<?=site_url("call-service/qrcode/{$code}")?>"/></div>
                        <div style="margin-bottom: 50px;font-family: inherit;">Người bán ký tên</div>
                        <div style="font-family: inherit;">Xin chân thành cảm ơn quý khách.</div>
                    </td>
                </tr>
            </table>
            
        </div>
    </div>
    <div style="text-align:center">
        <button style="color: #fff;border: none;background: #4caf50;padding: 7px 18px;border-radius: 2px;cursor: pointer;" id="print" onclick="pdf()">Xuất file</button>
    </div>
    <script>
        function pdf(){
            $('#print').remove();
            window.print();
        }
    </script>
</body>
</html>