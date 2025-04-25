<?php 

if ( isset( $_GET['target'] ) && $_GET['target'] ) {

    $target = $_GET['target'];

?>

    <input name="target" type="hidden" style="width:270px;" value="<?php echo $target; ?>" autocomplete="off">

    <div class="linoadmin-container-fluid">

        <div class="linoadmin-row">
            
            <div class="linoadmin-col-11">

                <ul>
                    <li class="linotype-field">
                        
                        <?php

                            $duplicate = array();

                            switch( $target ){
                                case "block":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'blocks' );
                                    $target_dir = 'blocks' . '/';
                                    $currents = LINOTYPE::$BLOCKS->get();
                                break;
                                case "module":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'modules' );
                                    $target_dir = 'modules' . '/';
                                    $currents = LINOTYPE::$MODULES->get();
                                break;
                                case "template":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'templates' );
                                    $target_dir = 'templates' . '/';
                                    $currents = LINOTYPE::$TEMPLATES->get();
                                break;
                                case "theme":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'themes' );
                                    $target_dir = 'themes' . '/';
                                    $currents = LINOTYPE::$THEMES->get();
                                break;
                                case "library":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'libraries' );
                                    $target_dir = 'libraries' . '/';
                                    $currents = LINOTYPE::$LIBRARIES->get();
                                break;
                                case "field":
                                    $duplicate = LINOTYPE::$SYNC->list_repo( 'fields' );
                                    $target_dir = 'fields' . '/';
                                    $currents = LINOTYPE::$FIELDS->get();
                                break;
                            }

                            if ( $duplicate ) {
                                foreach ( $duplicate as $item_key => $item ) {

                                    $item['path'] = str_replace( $target_dir, '', $item['path'] );

                                    if ( ! isset( $currents[ $item['path'] ] ) ) {
                                        
                                        echo '<label style="display: block; margin: 10px 0px 10px 0px; font-size: 16px;"><input type="radio" name="import" value="' . $item['path'] . '">' . $item['path'] . '</label>';
                                        
                                    }

                                }
                            }

                        ?>

                    </li>

                </ul>

            </div>

        </div>

    </div>

<?php 

}
