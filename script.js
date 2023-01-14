(function ($) {
    document.addEventListener("DOMContentLoaded", () => {
        // Other code here

        document.querySelector("#data").addEventListener("submit", (e) => {
            // Prevent the form from being submitted
            e.preventDefault();
            console.time("ajax-weather-call");
            // Serialize the form data and send it via an AJAX request
            const data = new FormData(e.target);
            fetch("getWeather.php", { method: "GET", body: data })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .then((response) => {
                    // Extract the location name and current data from the response object
                    const locationName = response.LocationName;
                    const currentData = response.CurrentData;

                    // Create a new table row using a template engine
                    const newRow = Handlebars.compile(
                        document.querySelector("#template").innerHTML
                    )({ locationName, currentData });

                    // Append the new table row to the tbody element
                    document.querySelector("#data").innerHTML += newRow;
                    console.timeEnd("ajax-weather-call");
                })
                .catch((error) => {
                    console.log(error);
                });
        });
    });
})(jQuery);
