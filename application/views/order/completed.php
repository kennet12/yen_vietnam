<div class="container">
  <h1 class="headingPage" style="color: #4caf50;"><i class="zmdi zmdi-check-circle"></i> <?=$website['order_success']?></h1>
  <p><?=$website['order_note19']?></p>
  <br><br>
  <div class="row">
        <div class="col-md-7">
            <div class="order-summary__section__content">
                <h2 class="section__title" id="main-header" tabindex="-1">
					      <?=$website['product']?>
                </h2>
                <div class="wrap-order-item">
                  <? foreach ($order_detail as $detail) { ?>
                    <div class="order-item order-item-166">
                      <div class="link" st="166"><input type="text" name="url[]" value="<?=$detail->url?>" disabled placeholder="Url đường dẫn sản phẩm."></div>
                      <div class="box">
                          <div class="box-left">
                            <div class="name"><?=$detail->title?></div>
                            <div class="quantity"><span class="ctrl-qty">-</span><input type="number" disabled class="qty" min="1" value="<?=$detail->quantity?>" name="quantity[]"><span class="ctrl-qty">+</span></div>
                          </div>
                          <div class="box-right"><label class="custom-file-upload" style="background:url(<?=$detail->thumbnail?>)"><input type="hidden" name="url_img[]" class="url-img" value="https://www.nguyenanhfruit.vn/files/upload/product/MP01I1166/userfile_0.jpg"></label></div>
                      </div>
                    </div>
                  <? } ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box-info" style="background: #4caf502b;">
                <h2 class="section__title" id="main-header" tabindex="-1">
					        <?=$website['order_note']?>
                </h2>
                <div class="box-infomation-contact">
                  <table width="100%">
                    <tr>
                      <th width="100px"><?=$website['order_note3']?>:</th>
                      <td style="font-size: 14px;"><?=$order->fullname?></td>
                    </tr>
                    <tr>
                      <th width="100px">Email:</th>
                      <td style="font-size: 14px;"><?=$order->email?></td>
                    </tr>
                    <tr>
                      <th width="100px"><?=$website['order_note4']?>:</th>
                      <td style="font-size: 14px;"><?=$order->phone?></td>
                    </tr>
                    <tr>
                      <th width="100px"><?=$website['order_note5']?>:</th>
                      <td style="font-size: 14px;"><?=$order->address?></td>
                    </tr>
                    <tr>
                      <th width="100px"><?=$website['order_note6']?></th>
                      <td style="font-size: 14px;"><?=!empty($order->message)?$order->message:''?></td>
                    </tr>
                  </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <p><?=$website['order_note13']?> <a style="color: #03a9f4;" href="<?=site_url()?>"><?=$website['return_home']?></a></p>
</div>