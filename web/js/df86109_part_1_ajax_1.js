function getForecasts(gameId, firstResult, maxResults, callback) {

    $.ajax({
        type: "POST",
        url: Routing.generate('get_forecasts_by_game', {gameId: gameId, 
                                                        firstResult:firstResult, 
                                                        maxResults:maxResults}),
        data: {
            game: gameId,
        },
        success: function(data)          //on recieve of reply
        {
            var forecasts = jQuery.parseJSON(data);

            callback(gameId, forecasts.forecasts);
        }
    });
}