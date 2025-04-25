<?php

global $WP_Bucket;


?>

<li class="wp-field bucketpress-add-repo <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">
  
  <div style="padding-bottom:10px">

    <div class="field-info" >Repository Owner</div>
  
    <div class="field-input">
      <input style="" name="" id="bucketpress-public-owner"  class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" value="" />
    </div>

  </div>

  <div style="padding-bottom:10px">

    <div class="field-info" >Repository slug:</div>
  
    <div class="field-input">
      <input style="" name="" id="bucketpress-public-repo"  class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" value="" />
    </div>

  </div>

  <div style="padding-bottom:10px">

    <div class="field-info" >Repository branch:</div>
    
    <div class="field-input">
      <input style="" name="" id="bucketpress-public-branch"  class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="master" value="" />
    </div>

  </div>

  <div style="padding-bottom:20px">

    <div class="field-info" >Repository type:</div>

    <select id="bucketpress-public-type">
      <option value="plugin">Plugin</option>
      <option value="theme">Theme</option>
    </select>

  </div>

  <a class="bucketpress-public-install button button-primary" data-baseurl="<?php echo admin_url('admin.php?page=bucketpress_setting' ); ?>" href="#" aria-label="" data-name=""><i class="fa fa-download"></i> Install</a>

</li>