<?php 

if ( isset( $_GET['target'] ) && $_GET['target'] ) {

    $target = $_GET['target'];

?>

    <input name="target" type="hidden" style="width:270px;" value="<?php echo $target; ?>" autocomplete="off">

    <div class="linoadmin-container-fluid">

        <div class="linoadmin-row">
            
            <div class="linoadmin-col-6">

                <ul>

                    <li class="linotype-field">
                        <span class="customize-control-title">Author</span>
                        <input name="author" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">ID</span>
                        <input name="id" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">Title</span>
                        <input name="title" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>

                    <li class="linotype-field">
                        <span class="customize-control-title">Duplicate</span>
                        <select name="duplicate">

                            <option value="">- Select -</option>

                            <?php

                            $duplicate = array();

                            switch( $target ){
                                case "block":
                                    $duplicate = LINOTYPE::$BLOCKS->get();
                                    $target_dir = 'blocks' . '/';
                                break;
                                case "module":
                                    $duplicate = LINOTYPE::$TEMPLATES->get();
                                    $target_dir = 'modules' . '/';
                                break;
                                case "template":
                                    $duplicate = LINOTYPE::$TEMPLATES->get();
                                    $target_dir = 'templates' . '/';
                                break;
                                case "theme":
                                    $duplicate = LINOTYPE::$THEMES->get();
                                    $target_dir = 'themes' . '/';
                                break;
                                case "library":
                                    $duplicate = LINOTYPE::$LIBRARIES->get();
                                    $target_dir = 'libraries' . '/';
                                break;
                                case "field":
                                    $duplicate = LINOTYPE::$FIELDS->get();
                                    $target_dir = 'fields' . '/';
                                break;
                            }
                            
                            if ( $duplicate ) {
                                foreach ( $duplicate as $item_key => $item ) {

                                    $item['path'] = str_replace( $target_dir, '', $item['path'] );

                                    echo '<option value="' . $item['path'] . '">';

                                        echo $item['path'];
                                    
                                    echo '</option>';

                                }
                            }

                            ?>

                        </select>
                    </li>
                    
                    <li class="linotype-field">
                        <span class="customize-control-title">Description</span>
                        <textarea name="desc" type="text" style="width:270px;" autocomplete="off"></textarea>
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">category</span>
                        <input name="category" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">tags</span>
                        <input name="tags" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">icon</span>
                        <input name="icon" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                    <li class="linotype-field">
                        <span class="customize-control-title">color</span>
                        <input name="color" type="text" style="width:270px;" value="" autocomplete="off">
                    </li>
                </ul>

            </div>

        </div>

    </div>

<?php 

}

?>