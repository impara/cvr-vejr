(function ($) {
    $(document).ready(function () {
        $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
            // handle errors
            console.log("Error Occured", thrownError);
            alert("An error occurred, please try again later.");
        });
        $("#request").on("submit", function (e) {
            // Prevent the form from being submitted
            e.preventDefault();
            console.time("ajax-weather-call");
            // Serialize the form data and send it via an AJAX request
            var data = $(this).serialize();
            $.ajax({
                url: "getWeather.php?" + data,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    // Extract the location name and current data from the response object
                    var locationName = response.LocationName;
                    var currentData = response.CurrentData;

                    // Create a new card using a template engine
                    var newCard = Handlebars.compile(
                        document.querySelector("#template").innerHTML
                    )({ locationName, currentData });

                    // Append the new card to the card-container element
                    $(".card-container").append(newCard);
                    console.timeEnd("ajax-weather-call");
                },
            });
        });
    });
})(jQuery);
