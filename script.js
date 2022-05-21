(function ($) {
    $(document).ready(function () {
        $(".cvr").keypress(function () {
            if ($(this).val().length == $(this).attr("maxlength")) {
                return false;
            }
        });

        $("#request").on("submit", function (e) {
            e.preventDefault();
            var cvr = $('input[type="number"]').val();
            var data = { search: cvr };

            $.ajax({
                url: "getWeather.php",
                async: false,
                type: "GET",
                cache: false,
                timeout: 5000,
                dataType: "json",
                data: data,
                success: function (response) {
                    $.each(response, function (key, value) {
                        if (key == 'LocationName') {
                            $('#data').append("<td>" + value + "</td>");
                        } else {
                            $('#data').append("<td>" + value.temperature + "</td>\
                                    <td>"+ value.skyText + "</td>\
                                    <td>"+ value.humidity + "</td>\
                                    <td>"+ value.windText + "</td>");
                            $('#data').wrapInner("<tr>");
                        }

                    })
                },
                error: function (error) {
                    $("#data").html(error.responseText);
                }
            });
        });

    });
})(jQuery);