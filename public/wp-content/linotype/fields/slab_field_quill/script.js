(function($) {

$.fn.slab_field_quill = function(){

    $(this).each(function(){

        var $this = $(this);
        var $editor = $this.find('.editor-container');
        var $content = $this.find('.wp-field-value');

        var options =  $.parseJSON( $this.find('.slab_field_quill-options').val() );
       
        if ( ! options.p ) {

            var Block = Quill.import('blots/block');
            Block.tagName = 'LINE';
            Quill.register(Block, true);
        
        }

        const Clipboard = Quill.import('modules/clipboard')
        const Delta = Quill.import('delta')

        class PlainClipboard extends Clipboard {
        onPaste (e) {
            e.preventDefault()
            const range = this.quill.getSelection()
            const text = e.clipboardData.getData('text/plain')
            const delta = new Delta()
            .retain(range.index)
            .delete(range.length)
            .insert(text)
            const index = text.length + range.index
            const length = 0
            this.quill.updateContents(delta, 'silent')
            this.quill.setSelection(index, length, 'silent')
            this.quill.scrollIntoView()
            update()
        }
        }

        Quill.register('modules/clipboard', PlainClipboard, true)
        
        var quill = new Quill( $editor[0], {
            modules: {
                toolbar:  options.toolbar 
            },
            placeholder: options.placeholder,
            theme: options.theme
        });

        quill.on('text-change', update );

        function update() {

            var content = $editor.find('> .ql-editor').html();

            if ( ! options.p ) {

                content = content.replace(/<\/line>$/, "");
                content = content.replace(/<line>/g, "");
                content = content.replace(/<\/line>/g, "<br/>");
                content = content.replace(/<br>/, "");
                
            }

            if ( content !== '<line><br></line>' && content !== '<p><br></p>' ) {
                $content.val( content ).change();
            } else {
                $content.val( '' ).change();
            }

        }

    });
}

$(document).ready(function(){

    $('body').find('.slab_field_quill').slab_field_quill();

});
    
}(jQuery));
    