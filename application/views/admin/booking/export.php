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
<body style="color: #555;font-size: 15px;font-family: Arial,Tahoma,sans-serif;background: #eee;padding: 10px;">
    <div style="width: 21cm;margin: 15px auto;">
        <div style="color: #F4C855;display: table;width: 100%;background: #FFF;">
            <div style="display:table-cell;width:30%;padding: 15px;">
            <a href="<?=BASE_URL?>" target="_blank"><img style="width: 130px;" src="<?=IMG_URL?>/logo.jpg" alt=""></a>
            </div>
            <div style="display:table-cell;width:70%;padding: 15px;color:#F4C855;vertical-align:top;text-align:right">
                <p style="margin:0;"><a href="tel:<?=$setting->company_hotline?>" style="color: #F4C855;text-decoration: none;font-size: 12px;"><?=$setting->company_hotline?></a></p>
                <p style="margin:0;"><a href="mailto:<?=$setting->company_email?>" style="color: #F4C855;text-decoration: none;font-size: 12px;"><?=$setting->company_email?></a></p>
                <p style="margin:0;"><a href="<?=GOOGLE_MAPS_LINK?>" target="_blank" style="color: #F4C855;text-decoration: none;font-size: 12px;"><?=$setting->company_address?></a></p>
            </div>
        </div>
        <div style="padding:0 15px 15px;background: #fff;">
            <div style="display:table;width:100%; font-size:14px;border-top: 2px solid #F4C855;padding-top: 20px;">
                <div style="display:table-cell;width:35%">
                    <div style="color: #F4C855;margin-bottom: 7px;font-size:12px;">Người nhận</div>
                    <strong style="font-size:15px;"><?=$booking->fullname?></strong>
                    <p style="margin-bottom:0"><?=$booking->address?></p>
                </div>
                <div style="display:table-cell;width:25%;padding-left: 5%;">
                    <div style="color: #999;margin-bottom: 7px;font-size:12px;">Mã đơn hàng</div>
                    <p style="margin-bottom:0"><?=BOOKING_PREFIX.$booking->id?></p>
                    <br>
                    <div style="color: #999;margin-bottom: 7px;font-size:12px;">Ngày đặt hàng</div>
                    <p style="margin-bottom:0"><?=date('d/m/Y',strtotime($booking->created_date))?></p>
                </div>
                <div style="display:table-cell;width:40%;text-align:right;">
                    <div style="color: #999;margin-bottom: 7px;font-size:12px;">Tổng tiền</div>
                    <div style="font-size: 25px;color: #2e7731;font-weight: bold;"><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></div>
                    <div style="color: #999;margin-bottom: 7px;font-size: 12px;margin-top: 11px;">Hình thức thanh toán</div>
                    <p style="margin-bottom: 0;margin-top: 7px;"><?=$this->util->note_payment($booking->payment)?></p>
                </div>
            </div>
            <table style="width: 100%;border-top: 1px solid #F4C855;margin-top: 40px;">
                <tr>
                    <td style="width:10%;padding: 20px 0;color: #F4C855;font-weight: bold;">
                        Hình
                    </td>
                    <td style="width:35%;padding: 20px 10px;color: #F4C855;font-weight: bold;">
                        Tên sản phẩm
                    </td>
                    <td style="width:23%;padding: 20px 10px;color: #F4C855;font-weight: bold;">
                        Giá
                    </td>
                    <td style="width:7%;padding: 20px 10px;color: #F4C855;font-weight: bold;">
                        SL
                    </td>
                    <td style="width:25%;padding: 20px 10px;color: #F4C855;font-weight: bold;text-align:right;">
                        Thành tiền
                    </td>
                </tr>
                <?  
                $total = 0;
                foreach ($booking->details as $pax) {
                    $price_sale = $pax->price_sale;
                    if (!empty($booking->code_promotion)) {
                        $price_sale = $pax->price;
                    }
                    $total += $price_sale*$pax->qty; 
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="width:10%;padding: 20px 0;">
                        <img style="width: 55px;height: 55px;object-fit: contain;" src="<?=str_replace('./',BASE_URL.'/',$pax->thumbnail)?>" alt="">
                    </td>
                    <td style="width:35%;padding: 20px 10px;">
                        <div style="font-size: 14px;"><a href="" target="_blank" style="text-decoration: none;color: #555;display: block;"><?=$pax->title?> </div></a>
                        <div style="font-size: 12px;color: #8bc34a"><?=$pax->typename?> <?=$pax->subtypename?></div>
                    </td>
                    <td style="width:23%;padding: 20px 10px;vertical-align: top;">
                        <div style="font-size: 14px;"><?=number_format($price_sale,0,',','.')?><sup>₫</sup></div>
                    </td>
                    <td style="width:7%;padding: 20px 10px;vertical-align: top;">
                        <span style="margin: 8px 0;">x<?=$pax->qty?></span>
                    </td>
                    <td style="width:25%;padding: 20px 10px;vertical-align: top;text-align:right;">
                        <div style="font-size: 14px;"><?=number_format($price_sale*$pax->qty,0,',','.')?><sup>₫</sup></div>
                    </td>
                </tr>
                <? } $total = $this->util->round_number($total,1000); ?>
            </table>
            <table style="width:100%;margin-top:30px;border-top: 1px solid #fb5723;padding-top: 15px;">
                <tr>
                    <td style="width:50%;vertical-align: top;">
                        <div style="font-size: 18px;font-weight: bold;color: #333;margin-left: 25px;margin-bottom: 10px;">QR Code</div>
                        <div><img src="<?=site_url("call-service/qrcode/{$code}")?>"/></div>
                    </td>
                    <td style="width:50%">
                        <table style="width: 100%;">
                            <? if (!empty($booking->code_promotion)) { ?>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;font-size: 14px;color: #F4C855;text-align:right;">
                                    Giảm giá:
                                </td>
                                <td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
                                    <div style="font-size: 18px;">- <?=number_format($total-$booking->total,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                            <? } ?>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;font-size: 14px;color: #F4C855;text-align:right;">
                                    Phí vận chuyển
                                </td>
                                <td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
                                    <div style="font-size: 18px;"><?=number_format($booking->ship_money,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%;padding: 3px 10px;font-size: 14px;color: #F4C855;text-align:right;">
                                    Tổng tiền:
                                </td>
                                <td style="width:50%;font-size: 14px;padding: 3px 10px;text-align:right;">
                                    <div style="font-size: 18px;"><?=number_format($booking->total+$booking->ship_money,0,',','.')?><sup>₫</sup></div>
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%;margin-top:30px;">
                            <tr>
                                <td style="width:20%;padding: 3px 10px;font-size: 14px;color: #F4C855;text-align:right;"></td>
                                <td style="width:80%;font-size: 14px;padding: 3px 10px;">
                                    <div style="font-size: 13px;font-style: italic;color: #808080;">....................,Ngày ....,Tháng ....,Năm.......</div>
                                    <div style="margin: 20px 0 0 60px;font-size: 15px;font-family: inherit;">Người bán ký tên</div>
                                </td>
                            </tr>
                        </table>
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