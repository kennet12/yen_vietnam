<div class="canvas-menu drawer-left">
    <div class="menu-m-lang clearfix">
        <ul class="sigin-list">
            <? if (empty($user_online)) { ?>
            <li class="sigin-item">
                <a href="<?=site_url('tai-khoan/dang-nhap')?>">
                <?=$website['login']?>
                </a>
            </li>
            <li class="sigin-item">
                /
            </li>
            <li class="sigin-item">
                <a href="<?=site_url('tai-khoan/dang-ky-tai-khoan')?>">
                <?=$website['register_account']?>
                </a>
            </li>
            <? } else { ?>
            <li class="sigin-item">
                <a href="<?=site_url('tai-khoan/lich-su-don-hang')?>" style="font-size: 14px;font-weight: 500;color: #a87031;">
                <i class="zmdi zmdi-account-circle"></i> <?=$user_online->fullname;?>
                </a>
            </li>
            <? } ?>
        </ul>
        <!-- <ul class="lang-list">
            <? if(!isset($_COOKIE['nguyenanh_lang'])) { ?>
            <li class="list-inline-item" style="margin-right: 5px;">
                <a href="#">
                    <span class="<?=!isset($_COOKIE['nguyenanh_lang'])?'selected':''?>">VI <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:16px;height: 14px;"></span>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="<?=site_url("call-service/set-lang/en.html{$actual_link}")?>">
                    <span class="<?=isset($_COOKIE['nguyenanh_lang'])?'selected':''?>">EN <img src="<?=IMG_URL?>english.png" alt="" style="width:16px;height: 17px;"></span>
                </a>
            </li>
            <? } else { ?>
            <li class="list-inline-item" style="margin-right: 5px;">
                <a href="<?=site_url("call-service/set-lang/vi.html{$actual_link}")?>">
                    <span class="<?=!isset($_COOKIE['nguyenanh_lang'])?'selected':''?>">VI <img src="<?=IMG_URL?>vietnamese.png" alt="" style="width:16px;height: 14px;"></span>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="#">
                    <span class="<?=isset($_COOKIE['nguyenanh_lang'])?'selected':''?>">EN <img src="<?=IMG_URL?>english.png" alt="" style="width:16px;height: 17px;"></span>
                </a>
            </li>
            <? } ?>
        </ul> -->
    </div>
    <div class="canvas-header-box d-flex justify-content-center align-items-center">
        <div class="close-box"><i class="zmdi zmdi-close"></i></div>
    </div>
</div>
<div class="canvas-overlay"></div>