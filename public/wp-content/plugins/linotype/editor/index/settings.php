<div class="linoadmin-container-fluid">
   <div class="linoadmin-row">
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-select " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Blocks Engine Type</div>
            <div class="field-content">
               <div class="field-input">
                  <select style="" name="engine" id="engine" class="wp-field-value meta-field">
                     <option autocorrect="off" autocomplete="off" spellcheck="false" value="full" selected="selected">Full blocks system</option>
                     <option autocorrect="off" autocomplete="off" spellcheck="false" value="templates">Only blocks and templates system</option>
                     <option autocorrect="off" autocomplete="off" spellcheck="false" value="blocks">Only blocks system</option>
                  </select>
               </div>
            </div>
         </li>
      </div>
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-text " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Enable Blocks in Content</div>
            <div class="field-content">
               <div class="field-input">
                  <input style="" name="content_post_types" id="linotype_content_post_types" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="Page" value="<?php //echo $LINOTYPE_EDITOR['data']['xxxxx']; ?>">
               </div>
            </div>
         </li>
      </div>
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-checkbox " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Blocks in Content by default</div>
            <div class="field-content">
               <div class="field-input">
                  <input type="checkbox" name="content_by_default" id="linotype_content_by_default" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php //echo $LINOTYPE_EDITOR['data']['xxxxx']; ?>">
                  <label style="" for="linotype_content_by_default">Yes</label>
               </div>
            </div>
         </li>
      </div>
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-checkbox " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Enable Production mode</div>
            <div class="field-content">
               <div class="field-input">
                  <input type="checkbox" name="production" id="linotype_theme_production" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" value="true">
                  <label style="" for="linotype_theme_production">Yes, Merge and minify style and script</label>
               </div>
            </div>
         </li>
      </div>
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-checkbox " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Helper Panel</div>
            <div class="field-content">
               <div class="field-input">
                  <input type="checkbox" name="helper" id="linotype_helper" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" value="true" checked="checked">
                  <label style="" for="linotype_helper">Display the frontend helper panel</label>
               </div>
            </div>
         </li>
      </div>
      <div class="linoadmin-col-12">
         <li class="wp-field wp-field-checkbox " style="display: block;padding:20px 0px 0px 0px">
            <div class="field-title">Hide Welcome</div>
            <div class="field-content">
               <div class="field-input">
                  <input type="checkbox" name="welcome" id="linotype_welcome" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" value="true" checked="checked">
                  <label style="" for="linotype_welcome">Hide Welcome panel</label>
               </div>
            </div>
         </li>
      </div>
   </div>
</div>