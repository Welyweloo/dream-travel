let app = {

    baseUrl: "https://api.teleport.org/api/urban_areas/slug:san-francisco-bay-area/scores/",

    fetchOptions : {
        "method": "GET",
        "mode": "cors",
        "cache": "no-cache",

    },

    init : function()
    {
        console.log('Coucou');
        //app.handleSummary();
        //app.handleWeather();
        app.handleMaps();
        //app.handlePOI();
    },
/* 
    handleSummary : function(evt) 
    {
        // charger les données pour l'affichage des angry-birds

            //*STEP 1-2 :  Charger des données depuis l'API
            let request = fetch('https://api.teleport.org/api/urban_areas/slug:san-francisco-bay-area/scores/', app.fetchOptions);

            //*STEP 3 : puis on fait ce qu'on a à faire avec la réponse
            request.then(
                //on convertit d'abord en json
                function(response)
                {
                    return response.json();
                }
            )
            .then(
                //réponse qu'on reçoit ici en argument
                function(city)
                {
                    console.log(city)
                    document.querySelector('.example-wrapper').innerHTML = city.summary
                }
            )
            
    },
 */
    handleMaps : function(evt) 
    {
        // charger les données pour l'affichage des angry-birds

            //*STEP 1-2 :  Charger des données depuis l'API
            let request = fetch('https://api.teleport.org/api/cities/geonameid:2988507/', app.fetchOptions);

            //*STEP 3 : puis on fait ce qu'on a à faire avec la réponse
            request.then(
                //on convertit d'abord en json
                function(response)
                {
                    return response.json();
                }
            )
            .then(
                //réponse qu'on reçoit ici en argument
                function(Paris)
                {

    
                    //https://leafletjs.com/examples/quick-start/
                    let mymap = L.map('mapid').setView([Paris.location.latlon.latitude, Paris.location.latlon.longitude], 10);
                    console.log(mymap)


                    // Token here : https://account.mapbox.com/

                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid2VseXdlbG9vIiwiYSI6ImNrYmFmYXdyYjBubGwycW84ZWJxYWk1MXAifQ.0HxMbJuY3rmSYfS2hmhcVw', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox/streets-v11',
                        tileSize: 512,
                        zoomOffset: -1,
                        accessToken: 'pk.eyJ1Ijoid2VseXdlbG9vIiwiYSI6ImNrYmFmYXdyYjBubGwycW84ZWJxYWk1MXAifQ.0HxMbJuY3rmSYfS2hmhcVw'
                    }).addTo(mymap);

                    let marker = L.marker([Paris.location.latlon.latitude, Paris.location.latlon.longitude]).addTo(mymap);

                    var circle = L.circle([Paris.location.latlon.latitude, Paris.location.latlon.longitude], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: 500
                    }).addTo(mymap);

                    marker.bindPopup("<b>Cette destination vous plaît ?</b>").openPopup();
                }
            )
            
    },




   /*  handlePOI : function(evt) 
    {
        // charger les données pour l'affichage des angry-birds

            //*STEP 1-2 :  Charger des données depuis l'API
            let request = fetch("https://test.api.amadeus.com/v1/reference-data/locations/pois?latitude=41.397158&longitude=2.160873&radius=2", {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer DKkFYD27kW8tTVFWUW93kVrzB1jQ',
                }

            });

            //*STEP 3 : puis on fait ce qu'on a à faire avec la réponse
            request.then(
                //on convertit d'abord en json
                function(response)
                {
                    return response.json();
                }
            )
            .then(
                //réponse qu'on reçoit ici en argument
                function(POIs)
                {
                    console.log(POIs.data)
            
                    newDiv = document.createElement('div');
                    list = document.createElement('ul');
                    newDiv.append(list);

                    POIs.data.forEach(
                        function(POI)
                        {
                           el = document.createElement('li');
                           el.textContent = POI.name;
                           list.append(el);

                        }
                    )
                    document.querySelector('.example-wrapper').append(newDiv); 
                }
            )
            
    },

    handleWeather : function(evt)
    {
        let _PremiumApiKey = '96384413baa44ebc914222637201106 ';

        //*STEP 1-2 :  Charger des données depuis l'API
        let request = fetch("http://api.worldweatheronline.com/premium/v1/past-weather.ashx?q=Paris&format=json&extra=isDayTime&date=2019-03-20&enddate=2020-03-30&includelocation=no&key="+ _PremiumApiKey, {
            method: 'GET',
            headers: {
                'content-type': 'application/json',
            }

        });

        //*STEP 3 : puis on fait ce qu'on a à faire avec la réponse
        request.then(
            //on convertit d'abord en json
            function(response)
            {
                return response.json();
            }
        )
        .then(
            //réponse qu'on reçoit ici en argument
            function(datas)
            {
                console.log(datas)
        
            }
        )
    } */
}

app.init();