<? $slides = $this->m_slide->items(null,1); ?>
<div class="slider container" qty="<?=count($slides)?>">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-slide col-md-9">
      <div class="shopify-section index-section section-slideshow distance" style="padding-top: 30px;">
        <div data-section-type="slideshow-section" class="slideshow position-relative special">
          <div class="main-slider" data-autoplay="true" data-speed="5000" data-arrows="false" data-dots="true">  
            <? $i=1; foreach($slides as $slide) { ?>
            <div class="item image slide-image slide-item-<?=$i?>">
              <img width="100%" height="auto" src=".<?=$slide->thumbnail?>" class="image-entity" alt="slidershow" />
            </div> 
            <? $i++;} ?>
          </div>
        </div>
      </div>
    </div> 
  </div> 
</div>
<script>
  $('.vertical_dropdown').click(function(e){
    let stt = $(this).attr('stt');
    let qty = $('.slider').attr('qty');
    let w = 0;
    if (stt == '2') {
      $('.slider').find('.col-slide').addClass('col-md-12');
      $('.slider').find('.col-slide').removeClass('col-md-9');
      $('.slider .main-slider .item').css('width','1170px');
      $(this).attr('stt',1);
      w = 1170;
    } else {
      $('.slider').find('.col-slide').addClass('col-md-9');
      $('.slider').find('.col-slide').removeClass('col-md-12');
      $('.slider .main-slider .item').css('width','870px');
      $(this).attr('stt',2);
      w = 870;
    }
    let wid = 0;
    let wid_track = 0;
    for(let i = 1;i <= qty;i++){
      $('.slider .main-slider .slide-item-'+i).css('left',wid+'px');
      wid -= w;
      wid_track += w;
    }
    $('.slider .main-slider .slick-track').css('width',wid_track+'px');
  })
</script>