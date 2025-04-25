<div class="list-item" list-item-type="">

  <div class="list-item-move list-item-handlebar ui-sortable-handle"></div>

  <div class="list-item-header">
      <span class="list-item-icon list-bt dashicons dashicons-format-image"></span>
      <span class="list-item-title"></span>
  </div>

  <div class="list-item-tools">
    <?php if( in_array( 'edit', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-edit list-bt fa fa-pencil"></div>'; ?>
    <?php if( in_array( 'clone', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-clone list-bt fa fa-clone"></div>'; ?>
    <?php if( in_array( 'delete', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-delete list-bt fa fa-trash-o"></div>'; ?>
    <?php if( in_array( 'sort', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-move list-bt fa fa-arrows ui-sortable-handle"></div>'; ?>
  </div>

  <textarea style="display:none" class="list-item-value"></textarea>

</div>
