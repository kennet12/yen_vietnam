<div class="page-width pb-30 ">
    <div class="container">
        <div style="background: #fff;padding: 20px 0;">
            <div class="row justify-content-center">
                <div class="col-11 col-xs-12">
                    <div class="form-vertical">
                        <form method="post" action="" id="create_customer" accept-charset="UTF-8">
                            <div class="title_block"><span>Affiliate link</span></div>
                            <div class="box-affiliate-link">
                                <input type="text" id="affiliate-copy" value="<?=BASE_URL.'?af='.$this->session->userdata("user")->affiliate_code?>">
                                <div class="copy">Copy</div>
                            </div>
                        </form>
                    </div>
                    <div class="affiliate">
                        <div class="affiliate-line"></div>
                        <div class="affiliate-report">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="total-approach">
                                        <div class="approach">
                                            <?=!empty($affiliate_analytic->approach)?$affiliate_analytic->approach:0?>
                                        </div>
                                        <h5>Lượt tiếp cận</h5>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="info-affiliate">
                                        <table class="text-center" style="margin-bottom: 20px;">
                                            <tr>
                                                <td>
                                                    <label><i style="color: #ff5722;" class="zmdi zmdi-circle"></i> Tổng đơn hàng</label>
                                                    <div class="text-center point-affiliate"><?=$total_affiliates?></div>
                                                </td>
                                                <td>
                                                    <label><i style="color: #ffc107;" class="zmdi zmdi-circle"></i> Đơn hoàn thành</label>
                                                    <div class="text-center point-affiliate"><?=count($paymented)?></div>
                                                </td>
                                                <td>
                                                    <label><i style="color: #13cc66;" class="zmdi zmdi-circle"></i> Đơn huê hồng</label>
                                                    <div class="text-center point-affiliate"><?=count($payment)?></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <p class="note-affiliate"><i style="color: #ff5722;" class="zmdi zmdi-circle"> :</i> Tổng đơn khách đã đặt qua việc giới thiệu.</p>
                                        <p class="note-affiliate"><i style="color: #ffc107;" class="zmdi zmdi-circle"> :</i> Tổng đơn thành công hệ thống đã thanh toán.</p>
                                        <p class="note-affiliate"><i style="color: #13cc66;" class="zmdi zmdi-circle"> :</i> Tổng đơn thành công hệ thống chưa thanh toán.</p>
                                    </div>
                                </div>
                            </div>
                            <? $str = ''; $offset; foreach($affiliates as $affiliate) {
                                $info = new stdClass();
                                $info->booking_id = $affiliate->id;
                                $booking_detail = $this->m_booking_detail->jion_product_items($info);

                                $total_cost_affiliate=0;
                                $j=0;
                                $str_detail = '';
                                $total_affiliate = 0;
                                foreach($booking_detail as $value) {
                                    $sttj = $j+1;
                                    $cost_affiliate = (($value->price_sale*$value->qty)*$value->p_affiliate)/100;
                                    $total_affiliate += $cost_affiliate;
                                    $total_cost_affiliate += $cost_affiliate;
                                    $str_detail .= '<tr>';
                                        $str_detail .= '<td width="40px" class="text-center">'.$sttj.'</td>';
                                        $str_detail .= '<td><img src="'.BASE_URL.str_replace('./','/',$value->thumbnail).'" alt=""> <sup>x'.$value->qty.'<sup></td>';
                                        $str_detail .= '<td>'.number_format($value->price_sale,0,',','.').'<sup>đ</sup></td>';
                                        $str_detail .= '<td>'.number_format($value->price_sale*$value->qty,0,',','.').'<sup>đ</sup></td>';
                                        $str_detail .= '<td>'.number_format($cost_affiliate,0,',','.').' <sup>đ</sup> <sup style="color:green">(+'.$value->p_affiliate.'%)<sup></td>';
                                    $str_detail .= '</tr>';
                                    $j++;
                                }
                                $str .= '<tr class="more-detail" data="'.$affiliate->id.'" stt="0">';
                                    $str .= '<td width="40px" class="text-center">'.($offset+1).'</td>';
                                    $str .= '<td>'.number_format($affiliate->total,0,',','.').' <sup>đ</sup></td>';
                                    $str .= '<td>'.number_format($total_affiliate,0,',','.').' <sup>đ</sup></td>';
                                    $str_payment_status = ($affiliate->payment_status == 1)?'<span style="color: #f44336;">Chưa thanh</span>':'<span style="color:green">Hoàn thành</span>';
                                    $str .= '<td>'.$str_payment_status.'</td>';
                                    $str_a_payment_status = ($affiliate->a_payment_status == 1)?'<span style="color: #f44336;">Chưa thanh</span>':'<span style="color:green">Đã thanh</span>';
                                    $str .= '<td>'.$str_a_payment_status.'</td>';
                                $str .= '</tr>';
                                $str .= '<tr>';
                                    $str .= '<td colspan ="5" style="padding:5px 0;display:none;" class="wrap-detail-'.$affiliate->id.'">';
                                        $str .= '<table class="booking-detail">';
                                            $str .= '<tr>';
                                                $str .= '<th width="8%" class="text-center">Stt</th>';
                                                $str .= '<th width="23%">Hình</th>';
                                                $str .= '<th width="23%">Giá</th>';
                                                $str .= '<th width="23%">Tổng tiền</th>';
                                                $str .= '<th width="23%">Huê hồng</th>';
                                            $str .= '</tr>';
                                            //////
                                            $str .= $str_detail;
                                        $str .= '</table>';
                                    $str .= '</td>';
                                $str .= '<tr>';
                                $offset++;
                            }
                            ?>
                            <div class="booking-list">
                                <table>
                                    <tr>
                                        <th width="8%" class="text-center">Stt</th>
                                        <th width="23%">Tổng tiền</th>
                                        <th width="23%">Huê hồng</th>
                                        <th width="23%">Trạng thái đơn</th>
                                        <th width="23%">Thanh toán</th>
                                    </tr>
                                    <?=$str?>
                                </table>
                                <script>
                                    $('.more-detail').click(function(e){
                                        var data =  $(this).attr('data');
                                        var stt =  parseInt($(this).attr('stt'));
                                        if (stt == 0) {
                                            $('.wrap-detail-'+data).css('display','table-cell');
                                            $(this).attr('stt',1);
                                        } else {
                                            $('.wrap-detail-'+data).css('display','none');
                                            $(this).attr('stt',0);
                                        }
                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding: 20px;">
                <?=$pagination?>
            </div>
        </div>
	</div>
</div>
<script>
	$('.copy').click(function (e){
		var copyText = document.getElementById("affiliate-copy");
		copyText.select();
		copyText.setSelectionRange(0, 99999)
		document.execCommand("copy");
        $(this).html('Copied');
        $.ajax({
            url: '<?=site_url('call-service/create-affiliate')?>',
            type: 'POST',
            dataType: 'html',
        })
	})
</script>

