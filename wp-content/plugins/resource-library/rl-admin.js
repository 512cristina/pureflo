jQuery(document).ready(function ($) {

    // =====================================================
    // LANGUAGE SHOW / HIDE
    // =====================================================

    function rlToggleLanguageRows() {

        let isVideo = false;

        $('input[name="tax_input[resource_type][]"]:checked').each(function () {

            const label = $(this)
                .closest('label')
                .text()
                .trim()
                .toLowerCase();

            if (label === 'video') {
                isVideo = true;
            }
        });

        const sourceType =
            $('#rl_source_type').val();

        if (isVideo) {

            $('#rl-language-files-wrapper').hide();
            $('#rl-external-url-wrapper').hide();

            

            return;
        }

        if (sourceType === 'external') {

            $('#rl-language-files-wrapper').hide();
            $('#rl-external-url-wrapper').show();

            return;
        }

        $('#rl-external-url-wrapper').hide();
        $('#rl-language-files-wrapper').show();
        $('.rl-language-file-row').hide();

        $('#rl-language-selector input[type="checkbox"]:checked')
            .each(function () {

                const slug = $(this).data('slug');

                $('.rl-language-file-row[data-language="' + slug + '"]')
                    .show();

            });

    }


    rlToggleLanguageRows();

    $(document).on(
        'change',
        '#rl-language-selector input[type="checkbox"]',
        rlToggleLanguageRows
    );

    $(document).on(
        'change',
        'input[name="tax_input[resource_type][]"]',
        rlToggleLanguageRows
    );

    $(document).on(
        'change',
        '#rl_source_type',
        rlToggleLanguageRows
    );


    // =====================================================
    // FILE UPLOADERS
    // =====================================================

    $(document).on('click', '.rl-upload-file', function (e) {

        e.preventDefault();

        const target = $(this).data('target');

        const frame = wp.media({

            title: 'Select Resource File',

            button: {
                text: 'Use this file'
            },

            multiple: false

        });

        frame.on('select', function () {

            const attachment =
                frame.state()
                    .get('selection')
                    .first()
                    .toJSON();

            $('#rl_file_' + target)
                .val(attachment.id);

            $('#rl_file_preview_' + target)
                .html(
                    '<p>' +
                    attachment.filename +
                    '</p>'
                );

        });

        frame.open();

    });



    // =====================================================
    // FILE REMOVE
    // =====================================================

    $(document).on('click', '.rl-remove-file', function (e) {

        e.preventDefault();

        const target = $(this).data('target');

        $('#rl_file_' + target).val('');

        $('#rl_file_preview_' + target).html('');

    });



    // =====================================================
    // IMAGE UPLOADER
    // =====================================================

    let imageFrame;

    $('#rl_image_upload').on('click', function (e) {

        e.preventDefault();

        if (imageFrame) {

            imageFrame.open();

            return;

        }

        imageFrame = wp.media({

            title: 'Select Resource Image',

            button: {

                text: 'Use this image'

            },

            multiple: false

        });

        imageFrame.on('select', function () {

            const attachment =
                imageFrame.state()
                    .get('selection')
                    .first()
                    .toJSON();

            $('#rl_image')
                .val(attachment.id);

            $('#rl_image_preview')
                .attr('src', attachment.url)
                .show();

            $('#rl_image_remove')
                .show();

        });

        imageFrame.open();

    });



    // =====================================================
    // IMAGE REMOVE
    // =====================================================

    $('#rl_image_remove').on('click', function (e) {

        e.preventDefault();

        $('#rl_image').val('');

        $('#rl_image_preview')
            .hide();

        $(this).hide();

    });



    // =====================================================
    // CLIENT VALIDATION
    // =====================================================

    $('form#post').on('submit', function (e) {

        const checkedLanguages =
            $('#rl-language-selector input[type="checkbox"]:checked');

        if (checkedLanguages.length === 0) {

            e.preventDefault();

            alert(
                'Please select at least one Language.'
            );

            return false;

        }

        // =====================================================
        // TOPIC REQUIRED
        // =====================================================

        const checkedTopics =
            jQuery('input[name="tax_input[resource_topic][]"]:checked');

        if (checkedTopics.length === 0) {

            e.preventDefault();

            alert(
                'Please select at least one Topic.'
            );

            return false;

        }

        let isVideo = false;

        $('input[name="tax_input[resource_type][]"]:checked').each(function () {

            const label = $(this)
                .closest('label')
                .text()
                .trim()
                .toLowerCase();

            if (label === 'video') {
                isVideo = true;
            }

        });

        const sourceType = $('#rl_source_type').val();

        if (isVideo) {

            if (!$('#rl_video').val()) {

                e.preventDefault();

                alert(
                    'Video resources require a Video URL.'
                );

                return false;

            }

        }
        
        let missingFile = false;

        if ( !isVideo && sourceType === 'upload') {

            checkedLanguages.each(function () {

                const slug = $(this).data('slug');

                const value =
                    $('#rl_file_' + slug).val();

                if (!value) {

                    alert(
                        'You selected '
                        + slug.toUpperCase()
                        + ' but did not upload a file.'
                    );

                    missingFile = true;

                    return false;

                }

            });
        }

        if ( !isVideo && sourceType === 'external' ) {

            if (!$('#rl_external_url').val()) {

                e.preventDefault();

                alert(
                    'External resources require a URL.'
                );

                return false;
            }
        }

        if (missingFile) {

            e.preventDefault();

            return false;

        }

        if (!$('#rl_image').val()) {

            e.preventDefault();

            alert(
                'Please upload a Resource Image.'
            );

            return false;

        }

    });

});