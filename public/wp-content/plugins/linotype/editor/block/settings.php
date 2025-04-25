<div class="linoadmin-container-fluid">

    <div class="linoadmin-row">

        <div class="linoadmin-col">

            <ul>
                <li class="linotype-field">
                    <span class="customize-control-title">Title</span>
                    <input name="title" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['title']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Description</span>
                    <textarea name="desc" type="text" style="width:270px;" autocomplete="off"><?php echo $LINOTYPE_EDITOR['data']['desc']; ?></textarea>
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">category</span>
                    <input name="category" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['category']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">tags</span>
                    <input name="tags" type="text" style="width:270px;" value="<?php echo implode( ',', $LINOTYPE_EDITOR['data']['tags'] ); ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">icon</span>
                    <input name="icon" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['icon']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">color</span>
                    <input name="color" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['color']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <?php
                    $checked = '';
                    if ( $LINOTYPE_EDITOR['data']['collapse'] == 'collapse' ) $checked = 'checked="checked"'; 
                    ?>
                    <input name="collapse" type="checkbox" value="true" autocomplete="off" <?php echo $checked; ?>> Collapse
                </li>
            </ul>  
                
        </div>

        <div class="linoadmin-col">

            <ul>
                <li class="linotype-field">
                    <span class="customize-control-title">target</span>
                    <select name="target[]" size="8" multiple="" style="width: 100%;">
                        
                        <?php 
                        $types = LINOTYPE_helpers::get_theme_types();
                        foreach( $types as $type_key => $type ) {

                            $selected = '';
                            if ( in_array( $type['value'], array_values( $LINOTYPE_EDITOR['data']['target'] ) ) ) $selected = 'selected';
                            
                            echo '<option value="' . $type['value'] . '" '.$selected.'>' . $type['title'] . '</option>';

                        } 
                        ?>

                    </select>
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">parent</span>
                    <select name="parent[]" size="8" multiple="" style="width: 100%;">
                        <?php 
                        $parents = LINOTYPE::$BLOCKS->get();

                        foreach( $parents as $parent_id => $parent ) {

                            $selected = '';
                            if ( in_array( $parent_id, array_values( $LINOTYPE_EDITOR['data']['parent'] ) ) ) $selected = 'selected';
                            
                            echo '<option value="' . $parent_id . '" '.$selected.'>' . $parent['title']  . ' (' . $parent_id  . ')</option>';

                        } 
                        ?>
                    </select>
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">accept</span>
                    <select name="accept[]" size="8" multiple="" style="width: 100%;">
                        <?php 
                        $accepts = LINOTYPE::$BLOCKS->get();

                        foreach( $accepts as $accept_id => $accept ) {

                            $selected = '';
                            if ( in_array( $accept_id, array_values( $LINOTYPE_EDITOR['data']['accept'] ) ) ) $selected = 'selected';
                            
                            echo '<option value="' . $accept_id . '" '.$selected.'>' . $accept['title'] . ' (' . $accept_id . ')</option>';

                        } 
                        ?>
                    </select>
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">libraries</span>
                    <select name="libraries[]" size="8" multiple="" style="width: 100%;">
                        <?php 
                        $libraries = LINOTYPE::$LIBRARIES->get();

                        foreach( $libraries as $library_id => $library ) {

                            $selected = '';
                            if ( in_array( $library_id, array_values( $LINOTYPE_EDITOR['data']['libraries'] ) ) ) $selected = 'selected';
                            
                            echo '<option value="' . $library_id . '" '.$selected.'>' . $library['title'] . ' (' . $library_id . ')</option>';

                        } 
                        ?>
                    </select>
                </li>
            </ul>  
                
        </div>

        <div class="linoadmin-col">

            <ul>
                <li class="linotype-field">
                    <span class="customize-control-title">Update</span>
                    <input readonly="readonly" name="update" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['update']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Version</span>
                    <input name="version" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['version']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">ID</span>
                    <input readonly="readonly" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['id']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Author</span>
                    <input readonly="readonly" name="author" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['author']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Directory</span>
                    <input readonly="readonly" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['dir']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Link</span>
                    <input readonly="readonly" type="text" style="width:270px;" value="<?php echo $LINOTYPE_EDITOR['data']['url']; ?>" autocomplete="off">
                </li>
                <li class="linotype-field">
                    <a id="screenshot-take" class="button" href="#screenshot" >Generate screenshot</a>
                    <textarea name="screenshot" id="screenshot" style="display:none;" autocomplete="off"></textarea>
                </li>
                <li class="linotype-field">
                    <span class="customize-control-title">Delete ? type the id and save</span>
                    <input name="delete" id="delete" type="text" style="width:270px;" value="" placeholder="<?php echo $LINOTYPE_EDITOR['data']['id']; ?>" autocomplete="off">
                </li>
            </ul>

        </div>

    </div>

</div>