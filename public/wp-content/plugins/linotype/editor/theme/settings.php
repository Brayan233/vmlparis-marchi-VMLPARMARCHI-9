<div class="scroll">
    
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
                </ul>  
                    
            </div>

            <div class="linoadmin-col">

                <ul>
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

            <div class="linoadmin-col">
   
            </div>

        </div>

    </div>

</div>
