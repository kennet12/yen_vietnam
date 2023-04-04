<?$setting = $this->m_setting->load(1);?>
<div class="supported">
    <div class="box">
        <div class="item">
            <div class="left">
                <i class="fas fa-store"></i>
            </div>
            <div class="right">
                <div class="name">Nguyen Anh Store</div>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <i class="far fa-star"></i>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="left">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="right">
                <div class="name">Cam kết chính hãng 100%</div>
            </div>
        </div>
        <div class="item">
            <div class="left">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="right">
                <div class="name">Nguyen Anh hoàn tiền 150% <br> Nếu phát hiện hàng giả</div>
            </div>
        </div>
    </div>
    <div class="box support">
        <div class="item">
            <div class="left">
                <i class="fas fa-phone"></i>
            </div>
            <div class="right">
                <div><strong>Liên hệ</strong></div>
                <div><a href="tel:<?=$setting->company_hotline?>"><?=$setting->company_hotline?></a></div>
            </div>
        </div>
        <div class="item">
            <div class="left">
                <i class="far fa-envelope"></i>
            </div>
            <div class="right">
                <div><a href="mailto:n<?=$setting->company_email?>"><?=$setting->company_email?></a></div>
            </div>
        </div>
    </div>
</div>