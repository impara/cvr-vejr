// This code is for an AJAX call that is made when a user clicks a submit button on a form.
// The form will be used to search for a city and country.
// The AJAX call will then be used to return the weather data for that city and country.
// The data returned will be used to populate a card that will be displayed on the page.
// The user will be able to search for multiple cities and countries, and each search will return a new card.
// The code will also handle any errors that may occur, such as a bad city or country name.
// The code will also time the AJAX call, and display the time in the console.

(function ($) {
    $(document).ready(function () {
        // When an AJAX call fails, this function will be called.
        // It will display an alert to the user to let them know an error occurred.
        $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
            alert("An error occurred, please try again later.");
        });
        // When the user clicks the submit button on the form, this event will be called.
        $("#request").on("submit", function (e) {
            e.preventDefault();
            // Start a timer to log the execution time of the AJAX call.
            console.time("ajax-weather-call");
            // Serialize the form data into a query string.
            var data = $(this).serialize();
            // Make the AJAX call to get the weather data.
            $.ajax({
                url: "getWeather.php?" + data,
                type: "GET",
                dataType: "json",
                // When the AJAX call is complete, check the status code.
                // If it is 200, then the AJAX call was successful.
                // If it is not 200, then the AJAX call failed.
                success: function (response) {
                    // Get the location name from the response.
                    var locationName = response.LocationName;
                    // Get the current data from the response.
                    var currentData = response.CurrentData;
                    // Compile the Handlebars template.
                    var newCard = Handlebars.compile(
                        document.querySelector("#template").innerHTML
                    )({ locationName, currentData });
                    // Add the new card to the card container.
                    $(".card-container").append(newCard);
                    // Stop the timer and log the execution time of the AJAX call.
                    console.timeEnd("ajax-weather-call");
                },
            });
        });
    });
})(jQuery);
