jQuery(document).ready(function ($) {
    $('#coupon_image_button').click(function (e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Select Image',
            multiple: false
        }).open()
            .on('select', function (e) {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#coupon_image').val(image_url);
                $('.image-preview img').attr('src', image_url);
            });
    });
});