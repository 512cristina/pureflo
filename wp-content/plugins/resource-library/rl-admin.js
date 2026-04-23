jQuery(document).ready(function($){

    let file_frame;

    $('#rl_upload_button').on('click', function(e){
        e.preventDefault();

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media({
            title: 'Select or Upload Resource File',
            button: {
                text: 'Use this file'
            },
            multiple: false
        });

        file_frame.on('select', function(){
            let attachment = file_frame.state().get('selection').first().toJSON();

            $('#rl_file').val(attachment.id);

            let preview = '';

            if (attachment.type === 'image') {
                preview = '<img src="'+attachment.sizes.thumbnail.url+'" style="max-width:150px;height:auto;">';
            } else {
                preview = '<p>'+attachment.filename+'</p>';
            }

            $('#rl_file_preview').html(preview);
            $('#rl_remove_file').show();
        });

        file_frame.open();
    });

    $('#rl_remove_file').on('click', function(){
        $('#rl_file').val('');
        $('#rl_file_preview').html('');
        $(this).hide();
    });



    $('form#post').on('submit', function(e){

        if ($('input[name="resource_language_radio"]:checked').length === 0) {
            e.preventDefault();
            alert('Please select a Language before saving.');
            return false;
        }

    });


    // IMAGE UPLOADER
    let image_frame;

    $('#rl_image_upload').on('click', function(e){
        e.preventDefault();

        if (image_frame) {
            image_frame.open();
            return;
        }

        image_frame = wp.media({
            title: 'Select Resource Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        image_frame.on('select', function(){
            let attachment = image_frame.state().get('selection').first().toJSON();

            $('#rl_image').val(attachment.id);
            $('#rl_image_preview')
                .attr('src', attachment.url)
                .show();

            $('#rl_image_remove').show();
        });

        image_frame.open();
    });

    $('#rl_image_remove').on('click', function(){
        $('#rl_image').val('');
        $('#rl_image_preview').hide();
        $(this).hide();
    });


});