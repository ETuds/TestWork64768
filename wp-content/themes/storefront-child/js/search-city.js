jQuery(document).ready(function($) {
    $('#city-search').on('keyup', function() {
        var search = $(this).val();
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'getCitiesTable',
                search: search
            },
            success: function (response) {
                console.log(response);
                $('#cities-table tbody').html(response);
            }
        });
    });
});